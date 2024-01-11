<?php
function snakeFormat($label){
    return strtolower(str_replace(' ', '_', trim($label)));
}
function convertarray($header, $data) {
    $result = [];

    foreach ($data as $row) {
        if (is_array($row)) {
            $combinedArray = array_combine($header, $row);
            $result[] = $combinedArray;
        }
    }
    return $result;
}

$fileName = "example.csv";
$fileHandle = fopen($fileName, "r");

while (! feof($fileHandle)) {
    
    $csvArray[] = fgetcsv($fileHandle, 1000, ',');
}

fclose($fileHandle);
$header= array_shift($csvArray);
$header_format = array_map('snakeFormat',$header);

$result=convertarray($header_format,$csvArray);
print_r($header_format)
?>

