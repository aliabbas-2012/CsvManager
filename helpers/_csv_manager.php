<?php
class CsvManager
{
  private $data;

  public function __construct($file)
  {
    $file_name = $file;
    $file_handle = fopen($file_name, "r");

    while (!feof($file_handle)) {
      $csvArray[] = fgetcsv($file_handle, 1000, ",");
    }

    fclose($file_handle);
    $header = array_shift($csvArray);
    $header_format = array_map([$this, "snakeFormat"], $header);

    $this->data = $this->convertArray($header_format, $csvArray);
  }

  private function snakeFormat($label)
  {
    $label = strtolower(str_replace(" ", "_", trim($label)));
    return preg_replace("/[^\w\s]/", "", $label);
  }

  private function convertArray($header, $data)
  {
    $result = [];

    foreach ($data as $row) {
      if (is_array($row)) {
        $combined_array = array_combine($header, $row);
        unset($value);
        $result[] = $combined_array;
      }
    }

    return $result;
  }

  public function getData()
  {
    return $this->data;
  }
}
?>
