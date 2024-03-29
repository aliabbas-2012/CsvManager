<?php
class MySQLManager
{
    private static $instance;
    private static $conn;

    private function __construct()
    {
        $db_host = $_ENV["DB_HOST"];
        $db_user = $_ENV["DB_USER"];
        $db_pass = $_ENV["DB_PASS"];
        $db_name = $_ENV["DB_NAME"];

        self::$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

        if (self::$conn->connect_error) {
            die("Connection failed: " . self::$conn->connect_error);
        }
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public static function getConnection()
    {
        return self::$conn;
    }

    public function execute($csv_data_array)
    {
        $conn = self::getConnection();
        $table_name = $_ENV["TABLE_NAME"];

        if (!$this->isTableExist($table_name)) {
            $this->createTable($table_name,$csv_data_array[0]);
        } else {
            $this->modifyTable($csv_data_array, $conn, $table_name);
        }

//        it includes data inserts and updates
        $this->performDmlOperation($csv_data_array, $table_name);
    }

    private function modifyTable($csv_data_array, $conn, $table_name)
    {
        $csv_columns = array_keys($csv_data_array[0]);
        $db_columns = $this->getDbColumns($table_name);

        $added_columns = array_diff($csv_columns, $db_columns);
        $removed_columns = array_diff($db_columns, $csv_columns);

        if (!empty($added_columns)) {
            $this->addColumns($table_name, $added_columns,$csv_data_array[0] ,$conn);
        }

        if (!empty($removed_columns)) {
            $this->removeColumns($table_name, $removed_columns, $conn);
        }
    }

    private function addColumns($table_name, $columns,$csv_columns, $conn)
    {
        foreach ($columns as $column) {
            $data_type = $this->getDataType($csv_columns[$column]);
            $sql = "ALTER TABLE $table_name ADD COLUMN $column $data_type";
            $conn->query($sql);
        }
    }

    private function removeColumns($table_name, $columns, $conn)
    {
        foreach ($columns as $column) {
            $sql = "ALTER TABLE $table_name DROP COLUMN $column";
            $conn->query($sql);
        }
    }

    private function isTableExist($table_name)
    {
        $conn = self::getConnection();
        $sql = "SHOW TABLES LIKE '$table_name';";
        $result = $conn->query($sql);
        return $result->num_rows > 0;
    }

    private function createTable($table_name,$csv_columns)
    {
        $conn = self::getConnection();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (";
        foreach ($csv_columns as $column_name => $column_type) {
            $data_type=$this->getDataType($column_type);
            $sql .= "$column_name $data_type, ";
        }
        $sql = rtrim($sql, ", ") . ")";
        $conn->query($sql);
    }

    private function performDmlOperation($csv_data_array, $table_name)
    {
        $existing_db_data = $this->getDbData($table_name);
        $table_name = $_ENV["TABLE_NAME"];
        $conn = self::getConnection();
        $csv_primary_keys = array_column($csv_data_array, 'id');
        $db_primary_keys = array_column($existing_db_data, 'id');
        $ids_For_Delete = array_diff($db_primary_keys, $csv_primary_keys);

        foreach ($csv_data_array as $row) {
            if ($this->isNewRecord($row, $table_name, $conn)) {
                $this->insertRow($row, $table_name, $conn);
            } else {
                $this->updateRow($row, $table_name, $conn);
            }
        }

        $this->performBulkDeletion($ids_For_Delete, $table_name, $conn);

    }

    private function insertRow($row, $table_name, $conn)
    {
        $columns = implode(", ", array_keys($row));
        $values = "'" . implode("', '", array_values($row)) . "'";
        $sql = "INSERT INTO $table_name ($columns) VALUES ($values)";
        $conn->query($sql);
    }

    private function isNewRecord($row, $table_name, $conn)
    {
        $id = $row['id'];
        $sql = "SELECT 1 FROM $table_name WHERE id = $id";
        $result = $conn->query($sql);

        return $result->num_rows === 0;
    }
    private function updateRow($row, $table_name, $conn)
    {
        $id = $row['id'];
        $setClause = implode(", ", array_map(function ($key, $value) {
            return "$key = '$value'";
        }, array_keys($row), array_values($row)));

        $sql = "UPDATE $table_name SET $setClause WHERE id = $id";
        $conn->query($sql);
    }


    private function performBulkDeletion($ids_for_delete, $table_name, $conn){
        if(!empty($ids_for_delete)){

            $sql = "DELETE FROM $table_name WHERE id IN(".implode(",", $ids_for_delete).")";
            $conn->query($sql);
        }
    }

    private function getDbData($table_name)
    {
        $conn = self::getConnection();
        $query = "SELECT * FROM $table_name";
        $result = $conn->query($query);
        $data = [];

        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $data[$row['id']] = $row;
            }
        } else {
            echo "Error: " . $conn->error;
        }
        return $data;
    }

    private function getDbColumns($table_name) {
        $query = "SHOW columns from $table_name";
        $conn = self::getConnection();
        $data = [];
        if($result = $conn->query($query)){
            while ($row = $result->fetch_assoc()) {
                $data[$row['Field']] = $row['Field'];
            }
        } else {
            echo "Error: " . $conn->error;
        }
        return $data;
    }
    private function getDataType($value) {
        if (preg_match('#[0-9]#',$value)){
            return $this->getNumberType($value);
        }
        else {
            return DATA_TYPE_MAPPING["S"];
        }
    }

    private function getNumberType($value) {
        if (ctype_digit($value)) {
            return DATA_TYPE_MAPPING['I'];
        } else if (preg_match("/^([+-]?(\d+(\.\d*)?|\.\d+)([eE][+-]?\d+)?)$/", $value)) {
            return DATA_TYPE_MAPPING['F'];
        }
    }
}
?>
