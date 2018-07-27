<?php
    require_once 'resource/database.php';
    require_once 'resource/utilities.php';
    require_once 'resource/send_email.php';

    if(isset($_POST['signupBtn'], $_POST['token'])){
        if(validate_token($_POST['token'])){
            //process form request
            //initailize an array to store all error messages
        if($_SESSION['username']==='superadmin'){
            $user_type = 'admin';
        }
        elseif($_SESSION['username']==='admin'){
            $user_type = 'agent';
        }
        elseif($_SESSION['username']==='agent'){
            $user_type = 'user';
        }
        $form_errors = array();        

        //form validation
        $required_fields = array('firstname', 'lastname', 'country', 'state', 'address', 'postal_code', 'phone_num', 'email', 'username', 'password');

        //call the function to check empty field and merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_empty_fields($required_fields));

        //fields that require checking for min length
        $fields_to_check_length = array('username' => 4, 'password' => 6, 'phone_num' => 11, 'postal_code' => 6);

        //call the function to check minimum required length and merge the returned data into form_error array
        $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));

        //email validation/merge the return data into form_error array
        $form_errors = array_merge($form_errors, check_email($_POST));

        //collect form data and store in variables
        $email = $_POST['email'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $country = $_POST['country'];
        $state = $_POST['state'];
        $address = $_POST['address'];
        $postalcode = $_POST['postal_code'];
        $phone = $_POST['phone_num'];

        //check against duplicate username and email
        if(checkDuplicateEntries('users', 'username', $username, $db)){
            $result = flashMessage("Username is already taken, please try another one");
        }
        if(checkDuplicateEntries('users', 'email', $email, $db)){
            $result = flashMessage("Email is already taken, please try another one");
        }
        //*******************************TODO: Check for valid postalcode********************************************/
        //check if error array is empty, if yes proces form data and insert record
        else if(empty($form_errors)){

            //hashing the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try{
                //create sql statements
                $sqlInsert = "INSERT INTO users (username, email, password, firstname, lastname, country, state, address, postal_code, phone_num, join_date, user_type )
                                Values (:username, :email, :password, :firstname, :lastname, :country, :state, :address, :postal_code, :phone_num, now(), :user_type)";

                //use PDO to sanitize data                    
                $statement = $db->prepare($sqlInsert);

                //add data into the database
                $statement->execute(array('username' => $username, 'email' => $email, 'password' => $hashed_password, 'firstname' => $firstname, 'lastname' => $lastname, 'country' => $country, 'state' => $state, 'address' => $address, 'postal_code' => $postalcode, 'phone_num' => $phone, 'user_type' => $user_type));

                //check if one row was created
                if($statement->rowCount() == 1){
                    //get the last inserted id
                    $user_id = $db->LastInsertId();
                    //encode the id
                    $encode_id = base64_encode("encodeuserid{$user_id}");
                    //prepare email body content
                    $mail_body = '<html>
                        <body style="background-color: #CCCCCC; color:$000; font-family: Arial, Helvetica, sans-serif; line-height:1.8em;">
                        <h2>User Authentication: Code A Secured Login System</h2>
                        <p>Dear '.$username.'<br><br>Thank you for registering, Please click the following link below to confirm your email address</p>
                        <p><a href="https://embeddedideaz.000webhostapp.com/powerstove/activate.php?id='.$encode_id.'"> Confirm Email</a><p>
                        <p><srong>$copy; '.Date("y").'</strong><p>
                        </body>
                        </html>';
                    
                    //Always set content type when sending html email
                    $headers = "MIME-Version: 1.0" ."\r\n";
                    $headers .= "Content-type:text/html;charset=UTF-8" ."\r\n";
                    $headers .= 'From: <basttyydev@gmail.com>' ."\r\n";
                    $mailTo = $email;
                    $subject = "Account Activation";

                    //Error handling for phpmailer
                    if(mail($email, $subject, $mail_body)){
                        echo "<script type\"text/javascript\">
                                swal({
                                    title: \"Congratulations $username!\",
                                    text: \"Registeration Completed Successfully. Please check you email for confirmation link\",
                                    type: \"success\",
                                    confirmButtonText: \"Thank You\"
                                    });
                                </script>";
                    }else{
                        echo "<script type\"text/javascript\">
                                swal({
                                    title: \"Error!\",
                                    text: \"Email sending failed: mail->ErrorInfo \",
                                    type: \"error\"
                                    });
                                </script>";
                    }
                    //$result = flashMessage("Registeration Successful", "pass");    
                }
            }
            catch(PDOexception $ex){
                $error = $ex->getMessage();
                $result = "<script type=\"text/javascript\">
                    swal({
                        title: \"Ooop's!!!\",
                        text: \"An error occured! $error\",
                        type: \"error\",
                        showCancelButton: false,
                        confirmButtonText: \"Retry!\"
                     });
                </script>";
                $result = flashMessage("An error occured".$ex->getMessage());
            }
        }
        else{
            if(count($form_errors) == 1){
                $result = flashMessage("There was 1 error in the form<br>");
            }
            else{
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
    //Account activation
    else if(isset($_GET['id'])){
        $encode_id = $_GET['id'];
        $decode_id = base64_decode($encode_id);
        $user_id_array = explode("encodeuserid", $decode_id);
        $id = $user_id_array[1];

        $sql = "UPDATE users SET activated =:activated WHERE id=:id AND activated='0'";
        $statement = $db->prepare($sql);
        $statement->execute(array(':activated' => "1", ':id' =>$id));        

        if($statement->rowCount()==1){
            if($_SESSION['usertype']==='admin' or $_SESSION['usertype']==='agent'){
                $sql2 = "SELECT * FROM users WHERE id=:id AND activated='1'";
                $statement = $db->prepare($sql2);
                $statement->execute(array(':id' => $id, ':activated' =>"1"));
                if($statement->rowCount()==1){
                    $username = $row['username'];
                    $email = $row['email'];
                    $usertype = $row['usertype'];
                    
                    $result = '<h2>Email Confirmed</h2>
                        <p>Your email address has been verified successfully, you can now <a href="confirm_order.php?usr='.$username.'&usrid='.$id.'&mail='.$email.'&usrtyp='.$usertype.'&paycat=purchase">Make Payment</a> to complete your registeration then <a href="login.php">Login</a> with your email and password.</p>';
                }
            }
        }else{
            $result = '<p class="lead">No changes made please contact site admin, if you have not confirmed your email before</p>';
        }
    }
?>