<?php
    //add our database connection script
    include_once 'resource/database.php';
    include_once 'resource/utilities.php';
    include_once 'resource/send_email.php';

    //process the form if the reset password button is clicked
    if(isset($_POST['passwordResetBtn'], $_POST['token'])){
        if(isset($_POST['token'])){
            //process the form
            //initialize an array to store any error message
        $form_errors = array();
        //form validation
        $required_fields = array('email', 'resetToken','new_password', 'confirm_password');
        //call the function to check empty fields and merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
        //fields that require checking for minimum length
        $fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);
        //call the function to check the minimum required length and merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

        //check if error array is empty, if yes process form data and insert record
        if(empty($form_errors)){
            //collect form data and store in variables
            $email = $_POST['email'];
            $reset_token = $_POST['resetToken'];
            $password1 = $_POST['new_password'];
            $password2 = $_POST['confirm_password'];
            //check if new password and confirm password are the same
            if($password1 != $password2){
                $result = flashMessage("New password and confirm password does not match");
            }else{
                try{
                    $email = explode("EncodeUserEmail", base64_decode($email))[1];
                    //validate email and token
                    $query = "SELECT * FROM password_resets WHERE email=:email";
                    $query_statement = $db->prepare($query);
                    $query_statement->execute(array(':email' =>$email));
                    $isValid = true;

                    if($rows = $query_statement->fetch()){
                        //email found
                        $stored_token = $rows['token'];
                        $expire_time = $rows['expire_time'];

                        if($stored_token != $reset_token){
                            $isValid = false;
                            $result = flashMessage("Your token is corrupt, please request a new one...");
                        }
                        if($expire_time < date('Y-m-d H-i-s')){
                            $isValid = false;
                            $result = flashMessage("Sorry! this reset token has expired, please request a new one");
                            //delete token
                            $db->exec("DELETE FROM password_resets WHERE email=$email LIMIT 1");
                        }
                    }else{
                        $isValid = false;
                        goto invalid_email;
                    }
                    //if token verification passed
                    if($isValid){
                        //create sql select statement to verify if id input exists in the database
                    $sqlQuery = "SELECT id FROM  users WHERE email = :email";
                    //use PDO prepared to sanitize data
                    $statement = $db->prepare($sqlQuery);
                    //execute the query
                    $statement->execute(array(':email' => $email));
                    //check if record exists
                    if($rs = $statement->fetch()){
                        //hash the password
                        $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
                        $id = $rs['id'];
                        
                        //SQL statement to update password
                        $sqlUpdate = "UPDATE users SET password =:password WHERE id =:id";
                        //use PDO prepared to sanitize 
                        $statement = $db->prepare($sqlUpdate);
                        //execute the statement
                        $statement->execute(array(':password' => $hashed_password, ':id' => $id));
                        
                        if($statement->rowCount()==1){
                            //delete token
                            $deleteqry = $db->prepare("DELETE FROM password_resets WHERE email =:email AND token =:token LIMIT 1"); 
                            $deleteqry->execute(array(':email' => $email, ':token' => $reset_token));
                        }
                        $result = "<script type=\"text/javascript\">
                    swal({
                        title: \"Congratulations\",
                        text: \"Password Reset Successfully!\",
                        type: \"success\",
                        showCancelButton: false,
                        confirmButtonText: \"Thank You!\"
                     });
                </script>";
                    }else{
                        invalid_email:
                        $result = "<script type=\"text/javascript\">
                    swal({
                        title: \"Ouch...\",
                        text: \"The email address provided {$email} does not exist in our database, please try again!\",
                        type: \"error\",
                        showCancelButton: false,
                        confirmButtonText: \"Retry!\"
                     });
                </script>";
                    }
                    }
                    
                }catch(PDOexception $ex){
                    $result = flashMessage("An error occurred: ".$ex->getMessage());
                }
            }
        }else{
            if(count($form_errors)==1){
                $result = flashMessage("There was one error in the form");
            }else{
                $result = flashMessage("There were " .count($form_errors). " errors in the form");
            }
        }
        }else{
            //display error
            $result = "<script type='text/javascript'>
            swal('Error', 'Sorry! Unknown request', 'error');
            </script>";
        }
        
    }else if(isset($_POST['passwordRecoveryBtn'], $_POST['token'])){
        if(isset($_POST['token'])){
            //process the form
            //initialize an array to store any error message in the form
        $form_errors = array();
        //form validation
        $required_fields = array('email');
        //call the function to check empty field and merge the return data into form error array
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
        //email validation / merge the return data into form error array
        $form_errors = array_merge($form_errors, check_email($_POST));
        //check if error array is empty if yes proccess form data
        if(empty($form_errors)){
            //collect form data and store in variables
            $email = $_POST['email'];

            try{
                //create sql select statement to verify is email address input exist in the database
                $sqlQuery = "SELECT * FROM users WHERE email =:email";
                //use PDO prepared to sanitize data
                $statement = $db->prepare($sqlQuery);
                //execute the query
                $statement->execute(array(':email' => $email));
                //check if record exists
                if($rs = $statement->fetch()){
                    $username = $rs['username'];
                    $email = $rs['email'];
                    //for Option1
                    //$user_id = $rs['id'];
                    $encode_email = base64_encode("EncodeUserEmail{$email}");

                    //create and store token
                    $expire_time = date('Y-m-d H:i:s', strtotime('1 hour'));
                    $random_string = base64_encode(openssl_random_pseudo_bytes(10));
                    $reset_token = strtoupper(preg_replace('/[^A-Za-z0-9\-]/', '', $random_string));

                    $insertToken = "INSERT INTO password_resets (email, token, expire_time)
                                    VALUES (:email, :token, :expire_time)";
                    $token_statement = $db->prepare($insertToken);
                    $token_statement->execute([
                        ':email' => $email,
                        ':token' => $reset_token,
                        ':expire_time' => $expire_time
                    ]);
                    
                    //prepare email body content Option 1 by encrypting user id
                    /*$mail_body = '<html>
                        <body style="background-color: #CCCCCC; color:$000; font-family: Arial, Helvetica, sans-serif; line-height:1.8em;">
                        <h2>User Authentication: Code A Secured Login System</h2>
                        <p>Dear '.$username.'<br><br>To reset your login password, Please click the link below:</p>
                        <p><a href="https://embeddedideaz.000webhostapp.com/powerstove/forgot_password.php?id='.$encode_id.'"> Reset Password</a><p>
                        <p><srong>$copy;2018 Anatel Systems</strong><p>
                        </body>
                        </html>';*/

                    //Prepare email content Option2 by sending encrypted random token
                    $mail_body = '<html>
                        <body style="background-color: #CCCCCC; color:$000; font-family: Arial, Helvetica, sans-serif; line-height:1.8em;">
                        <h2>Password Reset: Code A Secured Login System</h2>
                        <p>Dear '.$username.'<br><br>To reset your login password, Copy the token below and click on the reset password link then paste the token in the token field of the form:
                        <br><br>
                        This token will expire after 1 hour
                        </p>
                        <p><a href="'.getBaseUrl().'forgot_password.php?email='.$encode_email.'&token='.$reset_token.'"> Reset Password</a><p>
                        <p><srong>$copy;'.date('Y').' Peachwater Consults</strong><p>
                        </body>
                        </html>';
                    //Always set content type when sending html email
                    $headers = "MIME-Version: 1.0" ."\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" ."\r\n";
                    $headers .= 'From: <basttyydev@gmail.com>' ."\r\n";
                    $mailTo = $email;
                    $subject = "Password Recovery";
    
                        //Error handling for phpmailer
                        if(mail($mailTo, $subject, $mail_body)){
                            echo "<script type\"text/javascript\">
                                    swal({
                                        title: \"Congratulations $username!\",
                                        text: \"Password reset link sent successfully. Please check you email for reset link\",
                                        type: \"success\",
                                        confirmButtonText: \"Thank You\"
                                        });
                                    </script>";
                        }else{
                            echo "<script type\"text/javascript\">
                                    swal({
                                        title: \"Error!\",
                                        text: \"Email sending failed: $mail->ErrorInfo \",
                                        type: \"error\"
                                        });
                                    </script>";
                        }
                }else{
                    $result = "<script type\"text/javascript\">
                    swal({
                        title: \"OOPS!!\",
                        text: \"The email address provided does not exist in our database, please try again. \",
                        type: \"error\",
                        confirmButtonText: \"OK!\"
                        });
                    </script>";
                }
            }catch(PDOexception $ex){
                $result = flashMessage("An error occured: ".$ex->getMessage());
            }
        }else{
            if(count($form_errors)==1){
                $result = flashMessage("There was 1 error in the form<br>");
            }else{
                $result = flashMessage("There were " .count($form_errors). " errors in the form<br>");
            }
        }
        }else{
            //throw an error
            $result = "<script type='text/javascript'>
            swal('Error', 'Sorry! Unknown request', 'error');
            </script>";
        }
        
    }
?>