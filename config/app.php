<?php
//online connection strings
//$username = 'id4271661_powerstove';
//$dsn = 'mysql:host=localhost; dbname=id4271661_powerstove_db';
//$password = 'powerstove';

//base directory

return[
    'database' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'dbname' => 'learningphp',
        'username' => 'root',
        'password' => '123456'
    ],
    'mail' => [
        'transport' => 'smtp',
        'encryption' => 'tls',
        'port' => 587,
        'host' => 'smtp.gmail.com',
        'username' => 'basttyysignal@gmail.com',
        'password' => 'atmega16micro',
        'from' => 'no-reply@anatelsystems.com',
        'sender_name' => 'User Authentication'
    ]
];