<?php
include_once 'resource/database.php';
include_once 'resource/utilities.php';

try{
    $statement = $db->query("SELECT * FROM users");
    $members = $statement->fetchAll(PDO::FETCH_ASSOC);
}catch(PDOexception $ex){
    $result = flashMessage("An error occured: " .$ex->getMessage());
}