<?php
     require __DIR__ . '/../resource/session.php';
     require __DIR__ . '/../resource/database.php';
     require __DIR__ . '/../resource/utilities.php';

     $myArray = array(
         'biomass_consumed' => 'consumed_biomass',
         'daily_usage' => 'daily usage',
         'battery_health' => 'battery health'
     );

     $jsonData = json_encode($myArray, JSON_PRETTY_PRINT);
     $plainText = "This is My text this";

     $encrypted = encrypt_decrypt('encrypt', $plainText);

     echo 'plaintext is:'.$plainText;
     echo 'encrypted form is:'.$encrypted;
     echo 'decrypted form is:'.encrypt_decrypt('decrypt', $encrypted);
?> 