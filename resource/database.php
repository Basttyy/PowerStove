<?php
$config = require __DIR__ . '/../config/app.php';

$driver = $config['database']['driver'];
$dbname = $config['database']['dbname'];
$host = $config['database']['host'];
$db_username = $config['database']['username'];
$db_password = $config['database']['password'];

$dsn = "{$driver}:host={$host}; dbname={$dbname}";

try{
    //create and instance of pdo class with the required parameter
    $db = new PDO($dsn, $db_username, $db_password);

    //set pdo error mode to exception
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //display success message
    //echo 'connected to the learningPHP database';
}catch(PDOExeption $ex){
    //display error message
    echo 'connection failed '.$ex->getMessage();
}

?>