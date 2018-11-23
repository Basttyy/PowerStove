<?php
    require __DIR__ . '/../resource/session.php';
    require __DIR__ . '/../resource/database.php';
    require __DIR__ . '/../resource/utilities.php';
    //prepare variables for database connection
    if(isset($_GET['key']) & isset($_GET['imei'])){
        $encryptedKey = $_GET['key'];
        $imei = $_GET['imei'];
        $payStatus = 'debted';
        // $decryptedKey = base64_decode($encryptedKey);
        // $decryptedKeyArray = explode("decryptstoveapikey", $decryptedKey);
        // $decryptedKey = $decryptedKeyArray[1];

        $sql = "SELECT * FROM stoves WHERE key =:key AND username =:iemi";
        //use pdo to sanitize data
        $statement = $db->prepare($sql);
        //add data into database
        $statement->execute(array(':key' =>$encryptedKey , 'imei' => $imei));

        if($statement->rowCount()==1){
            $payStatus = $row['paid'];
        }
        return $payStatus;
    }
?>