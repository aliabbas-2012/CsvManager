<?php
require "vendor/autoload.php";
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once('config/_mapping.php');
require_once('helpers/_function.php');
require_once('helpers/_csv_manager.php');
require_once('helpers/_mysql_manager.php');

$csv_manager= new CsvManager(getcwd().'/storage/csv/example.csv');
$csv_data_array = $csv_manager->getData();
$mysql_manager = MySQLManager::getInstance();
$mysql_manager->execute($csv_data_array);
?>