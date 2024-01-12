<?php
$mapping=[
  'name'=>["First Name","Last Name"],
  'dob'=>["DOB","Calculated Age"],
  'educations'=>["university","location","degree"],
  'residence'=>["city","address"]
];
$data_type_mapping = [
  "F" => "float",
  "I" => "int",
  "S" => "varchar(255)"
];
define('MAPPING',$mapping);
define('DATA_TYPE_MAPPING', $data_type_mapping);