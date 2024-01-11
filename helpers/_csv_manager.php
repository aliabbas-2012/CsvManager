<?php
class CsvManager
{
  private $data;

  public function __construct($file)
  {
    global $mapping_casting;
    $fileName = $file;
    $fileHandle = fopen($fileName, "r");

    while (!feof($fileHandle)) {
      $csvArray[] = fgetcsv($fileHandle, 1000, ",");
    }

    fclose($fileHandle);
    $header = array_shift($csvArray);
    $headerFormat = array_map([$this, "snakeFormat"], $header);

    $this->data = $this->convertArray($headerFormat, $csvArray, $mapping_casting);
  }

  private function snakeFormat($label)
  {
    $label = strtolower(str_replace(" ", "_", trim($label)));
    return preg_replace("/[^\w\s]/", "", $label);
  }

  private function convertArray($header, $data, $mapping)
  {
    $result = [];

    foreach ($data as $row) {
      if (is_array($row)) {
        $combinedArray = array_combine($header, $row);
        foreach ($combinedArray as $key => &$value) {
          $value = $this->convertValue($value, $mapping[$key]);
        }
        unset($value); // Unset reference to avoid potential issues
        $result[] = $combinedArray;
      }
    }

    return $result;
  }

  private function convertValue($value, $type)
  {
    switch ($type) {
      case "string":
        return (string)$value;
      case "int":
        return (int)$value;
      case "float":
        return (float)$value;
      default:
        return $value;
    }
  }

  public function getData()
  {
    return $this->data;
  }
}

?>
