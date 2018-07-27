<?php
    include_once 'resource/session.php';
    include_once 'resource/database.php';
    include_once 'resource/utilities.php';

    $feedbacks = "";
        
    //get list feedbacks from the server
    $sqlQuery = "SELECT * FROM feedback";
    $statement = $db->prepare($sqlQuery);
    $statement->execute();
    //$result = $statement->setFetchMode(PDO::FETCH_ASSOC);
    while($row = $statement->fetch()){
        $sender_name = $row['sender_name'];
        $message = $row['message'];
        $date = $row['send_date'];

        $feedbacks .= "<p> {$message} <br> {$sender_name}<br>{$date} </p>";
    }
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Page</title>
</head>
<body>
    <h2>User Authentication System</h2><hr>

    <h3>List of feedbacks</h3>
    <?php
        if(isset($feedbacks)) echo $feedbacks;
    ?>
</body>
</html>