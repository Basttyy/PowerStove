<?php
     require __DIR__ . '/../resource/session.php';
     require __DIR__ . '/../resource/database.php';
     require __DIR__ . '/../resource/utilities.php';
    //prepare variables for database connection
    if(isset($_GET['value'])){
        $sql = "INSERT INTO sensor (value) VALUES (:value)";
        //use pdo to sanitize data
        $statement = $db->prepare($sql);
        //add data into database
        $statement->execute(array('value' => $_GET['value']));
    }
?>