<?php
     require __DIR__ . '/../resource/session.php';
     require __DIR__ . '/../resource/database.php';
     require __DIR__ . '/../resource/utilities.php';
    //prepare variables for database connection
    if(isset($_GET['value'])){
        // $key = create_byte_array("this is  my  key");
        // $iv = create_byte_array("this is my iv oo");
        // //$dehashed = hash_hmac('sha256', $_GET['value'], 'Hash Key', false);
        // $decrypted = openssl_decrypt($_GET['value'], "AES-256-CBC", $key, 1, $iv);
        $sql = "INSERT INTO sensor (value) VALUES (:value)";
        //use pdo to sanitize data
        $statement = $db->prepare($sql);
        //add data into database
        $statement->execute(array(':value' => $_GET['value']));

        echo 'success';
    } 
?>