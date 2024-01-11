<?php

$mapping = [
    "name" => ["First Name", "Last Name"],
    "dob" => ["DOB", "Calculated Age"],
    "educations" => ["university","location","degree"],
    "residence" => ["city","address"]
];

$manipulation = [
    "name" => 'S',
    "dob" => 'C',
    "subjects" => 'V',
    "educations" => 'D',
    "residence" => 'R',
];


$mapping_casting =[
    'id'=> 'string',
    'affiliate' => 'string',
    'promo__deal' => 'string',
    'offer' => 'string',
    'banner' => 'string',
    'click' => 'int',
    'impression' => 'int',
    'total_conversion' => 'int',
    'pending_conversion' => 'int',
    'approved_conversion' => 'int',
    'rejected_conversion' => 'int',
    'conversion_' => 'float',
    'total_revenue' => 'float',
    'total_payout' => 'float',
    'approved_payout' => 'float',
    'pending_payout' => 'float',
    'rejected_payout' => 'float',
    'avg_cpc' => 'float',

];


$mapping_column =[
    'id' => 'varchar(2500)',
    'affiliate' => 'varchar(255)',
    'promo__deal' => 'varchar(255)',
    'offer' => 'varchar(255)',
    'banner' => 'varchar(255)',
    'click' => 'int',
    'impression' => 'int',
    'total_conversion' => 'int',
    'pending_conversion' => 'int',
    'approved_conversion' => 'int',
    'rejected_conversion' => 'int',
    'conversion_' => 'float',
    'total_revenue' => 'float',
    'total_payout' => 'float',
    'approved_payout' => 'float',
    'pending_payout' => 'float',
    'rejected_payout' => 'float',
    'avg_cpc' => 'float',

];

define('MAPPING', $mapping);
define('MANIPULATION', $manipulation);
define('MAPPING_CASTING', $mapping_casting);
define('MAPPING_COLUMN', $mapping_column);