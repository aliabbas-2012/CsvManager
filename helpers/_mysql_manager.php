<?php
class MySQLManager
{
    private static $instance;
    private static $conn;

    private function __construct()
    {
        $dbHost = $_ENV["DB_HOST"];
        $dbUser = $_ENV["DB_USER"];
        $dbPass = $_ENV["DB_PASS"];
        $dbName = $_ENV["DB_NAME"];

        self::$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

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

            $this->createTable($table_name);
        } else {
            $this->modifyTable($csv_data_array, $conn, $table_name);
        }

//        it includes data inserts and updates
        $existing_db_data = $this->getDbData($table_name);
        $this->performDmlOperation($csv_data_array, $existing_db_data);
    }

    private function modifyTable($csv_data_array, $conn, $table_name)
    {
        $csvColumns = array_keys($csv_data_array[0]);
        $dbColumns = $this->getDbColumns($table_name);

        $addedColumns = array_diff($csvColumns, $dbColumns);
        $removedColumns = array_diff($dbColumns, $csvColumns);

        if (!empty($addedColumns)) {
            $this->addColumns($table_name, $addedColumns, $conn);
        }

        if (!empty($removedColumns)) {
            $this->removeColumns($table_name, $removedColumns, $conn);
        }
    }

    private function addColumns($table_name, $columns, $conn)
    {
        foreach ($columns as $column) {
            $sql = "ALTER TABLE $table_name ADD COLUMN $column VARCHAR(255)";
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

    private function createTable($table_name)
    {
        $conn = self::getConnection();
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (";
        foreach (MAPPING_COLUMN as $columnName => $columnType) {
            $sql .= "$columnName $columnType, ";
        }
        $sql = rtrim($sql, ", ") . ")";
        $conn->query($sql);
    }

    private function performDmlOperation($csv_data_array, $existing_db_data)
    {
        $table_name = $_ENV["TABLE_NAME"];
        $conn = self::getConnection();
        $csvPrimaryKeys = array_column($csv_data_array, 'id');
        $dbPrimaryKeys = array_column($existing_db_data, 'id');
        $idsForDelete = array_diff($dbPrimaryKeys, $csvPrimaryKeys);

        foreach ($csv_data_array as $row) {
            if ($this->isNewRecord($row, $table_name, $conn)) {
                $this->insertRow($row, $table_name, $conn);
            } else {
                $this->updateRow($row, $table_name, $conn);
            }
        }

        $this->performBulkDeletion($idsForDelete, $table_name, $conn);

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


    private function performBulkDeletion($idsForDelete, $table_name, $conn){
        if(!empty($idsForDelete)){

            $sql = "DELETE FROM $table_name WHERE id IN(".implode(",", $idsForDelete).")";
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
}
?>
