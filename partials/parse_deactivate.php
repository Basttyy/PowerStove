<?php
include_once 'resource/database.php';
include_once 'resource/utilities.php';
include_once 'resource/send_email.php';

if(isset($_POST['deleteAccountBtn'], $_POST['token'])){
    guard();
    if(validate_token($_POST['token'])){
        //process the form
        $user_id = $_POST['hidden_id'];
        
        try{
            //STEP1 retrieve user information from the database
            $sqlQuery = "SELECT * FROM users WHERE id=:id";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':id' => $user_id));

            if($statement->rowCount()===1){
                //STEP2 deactivate the account of the user
                $deactivateQuery = $db->prepare("UPDATE users SET activated =:activated WHERE id=:id");
                $deactivateQuery->execute(array(':activated' => '0', ':id' => $user_id));
                if($deactivateQuery->rowCount()===1){
                    //STEP3 Insert record into the deleted users table
                    $insertRecord = $db->prepare("INSERT INTO trash(user_id, deleted_at)
                                                VAlUES(:id, now())");
                    $insertRecord->execute(array(':id' => $user_id));
                    if($insertRecord->rowCount()===1){
                        $url = getBaseUrl();
                        //STEP4 notify the user via email and display alert
                        //prepare mail body
                        $mail_body = '<html>
                        <body style="background-color: #CCCCCC; color:$000; font-family: Arial, Helvetica, sans-serif; line-height:1.8em;">
                        <h2>User Authentication: Code A Secured Login System</h2>
                        <p>Dear '.$username.'<br><br>You have requested to deactivate your account, your account information will be kept for 14 days, if you wish to continue using the account login within the next 14 days to reactivate your account or it will be permanently deleted.</p>
                        <p><a href="'.getBaseUrl().'login.php"> Sign In</a><p>
                        <p><srong>$copy;2018 Anatel Systems</strong><p>
                        </body>
                        </html>';
                    
                        //Always set content type when sending html email
                        $headers = "MIME-Version: 1.0" ."\r\n";
                        $headers .= "Content-type:text/html;charset=UTF-8" ."\r\n";
                        $headers .= 'From: <basttyydev@gmail.com>' ."\r\n";
                        $mailTo = $email;
                        $subject = "Notification of Account Deactivation";

                        //Error handling for phpmailer
                        if(mail($email, $subject, $mail_body)){
                            echo "<script type\"text/javascript\">
                                swal({
                                    title: \"Dear $username!\",
                                    text: \"Your account information will be kept for 14 days, if you wish to continue using the system login within 14 days to prevent the account from been deleted permanently.\",
                                    type: \"success\",
                                    confirmButtonText: \"Thank You\"
                                    });
                                </script>";
                    }else{
                        echo "<script type\"text/javascript\">
                                swal({
                                    title: \"Error!\",
                                    text: \"Email sending failed:\",
                                    type: \"error\"
                                    });
                                </script>";
                    }
                    }else{
                        flashMessage("Couldn't complete the operation please try again");
                    }
                }else{
                    flashMessage("Couldn't complete the operation please try again");
                }
            }else{
                //something is fishing
                echo $user_id;
                //signout();
            }

        }catch(PDOexception $ex){
            $result = flashMessage("An error occurred: " .$ex->getMessage());
        }
    }else{
        //display error
        $result = "<script type='text/javascript'>
                    swal('Error', 'Unknown Request Source!', 'error');
                </script>";
    }
}