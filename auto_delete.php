<?php
include_once 'resource/database.php';
include_once 'resource/utilities.php';

try{
    $sqlQuery = $db->query("SELECT user_id FROM trash WHERE deleted_at <= CURRENT_DATE - INTERVAL 14 DAY");

    while($rs = $sqlQuery->fetch()){
        //get record from user table
        $user_id = $rs['user_id'];

        $user_record = $db->prepare("SELECT * FROM users WHERE id=:id");
        $user_record->execute(array(':id' => $user_id));

        if($row = $user_record->fetch()){
            $username = $row['username'];
            $id = $row['id'];
            $user_pic = 'uploads/'.$username.'.jpg';

            if(file_exists($user_pic)){
                unlink($user_pic);
            }
            $db->exec("DELETE FROM trash WHERE user_id=$user_id LIMIT 1");
            $result = $db->exec("DELETE FROM users WHERE id=$id AND activated = '0' LIMIT 1");
            //email admin or write to your log file
            echo 'account deleted';
        }
    }
}catch(PDOexception $ex){
    //display error message
    echo $ex->getMessage();
}

//Auto delete non activated accounts after 3days
try{
    $sqlQuery = $db->query("SELECT id, username FROM users WHERE join_date <= CURRENT_DATE - INTERVAL 3 DAY AND                                activated = '0'");

    while($rs = $sqlQuery->fetch()){
        //get record from user table
        $user_id = $rs['id'];
        $username = $rs['username'];

        //check if row exists in user table
        if(!checkDuplicateEntries('trash','user_id', $user_id, $db)){
            /* Uncomment if users were allowed to upload profile picture prior
                Account activation  
            $user_pic = 'uploads/'.$username.'.jpg';

            if(file_exists($user_pic)){
                unlink($user_pic);
            }*/
            $result = $db->exec("DELETE FROM users WHERE id=$user_id AND activated = '0' LIMIT 1");
            //email admin or write to your log file
            echo 'account deleted';
        }
    }
}catch(PDOexception $ex){

}