<?php
     require __DIR__ . '/../resource/session.php';
     require __DIR__ . '/../resource/database.php';
     require __DIR__ . '/../resource/utilities.php';
    //prepare variables for database connection
    if(isset($_GET['key']) & isset($_GET['imei'])){
        
        $encryptedKey = $_GET['key'];
        $imei = $_GET['imei'];
        $decryptedKey = base64_decode($encryptedKey);
        $decryptedKeyArray = explode("decryptstoveapikey", $decryptedKey);
        $decryptedKey = $decryptedKeyArray[1];

        $sql = "SELECT * FROM stoves WHERE key =:key AND imei =:iemi";
        //use pdo to sanitize data
        $statement = $db->prepare($sql);
        //add data into database
        $statement->execute(array(':key' => $_decryptedKey, 'imei' => $imei));
 
        if($statement->rowCount()==1){
            //Get statistical info from stove's request
            $biomassConsumed = $_GET['biomass_consumed'];
            $dailyUsage = $_GET['daily_usage'];
            $batteryHealth = $_GET['battery_health'];

            //store info in a file
            
        }
    }
?>