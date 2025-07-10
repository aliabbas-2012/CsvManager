<?php
include "helpers/_function.php";
include "config/_mapping.php";
include "config/_data.php";
$csv_header = createHeaders($data[0]);
$csv_rows = createRows($data);

$array_2d = array_merge([$csv_header],$csv_rows);
$fp = fopen("data.csv", "w");
foreach ($array_2d as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);
?>





