<?php
include_once 'resource/database.php';
include_once 'resource/utilities.php';

if(isset($_GET['u'])){
    $username = $_GET['u'];

    $sqlQuery = "SELECT * FROM users WHERE username = :username";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array(':username' => $username));

    while($rs = $statement->fetch()){
        $username = $rs['username'];        
        $profile_pic = $rs['avatar'];
        $date_joined = strftime("%b %d %Y", strtotime($rs["join_date"]));

        $rs['activated'] = 1 ? $status = 'Activated' : $status = 'Not Activated';
    }
}
//check wether to show profile
if((isset($_SESSION['id']) || isset($_GET['user_identity'])) && (!isset($_POST['updateProfileBtn']))){
    //check if user_identity is set
    if(isset($_GET['user_identity'])){
        $url_encoded_id = $_GET['user_identity'];
        $decode_id = base64_decode($url_encoded_id);
        $user_id_array = explode("encodeuserid", $decode_id);
        $id = $user_id_array[1];
    }else{
        $id = $_SESSION['id'];
    }
    

    $sqlQuery = "SELECT * FROM users WHERE id = :id";
    $statement = $db->prepare($sqlQuery);
    $statement->execute(array(':id' => $id));

    while($rs = $statement->fetch()){
        $username = $rs['username'];
        $email = $rs['email'];
        $profile_pic = $rs['avatar'];
        $date_joined = strftime("%b %d %Y", strtotime($rs["join_date"]));
    }

    //encrypt user id and store
    $encode_id = base64_encode("encodeuserid{$id}");
}//or to update profile
else if(isset($_POST['updateProfileBtn'], $_POST['token'])){
    if(validate_token($_POST['token'])){
        //process the form
        //initialize an array to store any error message
    $form_errors = array();
    //form validation
    $required_fields = array('email', 'username');
    //call the function to check empty fields and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
    //fields that require checking for minimum length
    $fields_to_check_length = array('username' => 4);
    //call the function to check the minimum required length and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));
    //email validation / merge the return data into form error_array
    $form_errors = array_merge($form_errors, check_email($_POST));
    //validate if file has a valid extension
    isset($_FILES['avatar']['name']) ? $avatar = $_FILES['avatar']['name'] : $avatar = null;
    if($avatar != null){
        $form_errors = array_merge($form_errors, isValidImage($avatar));
    }
    //collect form data and store in variables
    $email = $_POST['email'];
    $username = $_POST['username'];
    $hidden_id = $_POST['hidden_id'];

    if(empty($form_errors)){
        try{
            if($avatar != null){
                $query = "SELECT avatar FROM users WHERE id=:id";
                $oldAvatarStatement = $db->prepare($query);
                $oldAvatarStatement->execute(array(':id' => $hidden_id));
                
                if($rs = $oldAvatarStatement->fetch()){
                    $oldAvatar = $rs['avatar'];
                }
                //create sql update statement
                $sqlUpdate = "UPDATE users SET  username =:username, email =:email, avatar =:avatar WHERE  id =:id";
                
                $avatar_path = uploadAvatar($username);
                if(!$avatar_path){
                    $avatar_path = 'uploads/default.jpg';
                }
                //use PDO prepared to sanitize data
                $statement = $db->prepare($sqlUpdate);
                //update the record in the database
                $statement->execute(array(':username' => $username, ':email' => $email, ':avatar' => $avatar_path, ':id' => $hidden_id));
                
                //delete old avatar/profile_pic
                if(isset($oldAvatar)){
                    unset($oldAvatar);
                }
            }else{                
                //create sql update statement
                $sqlUpdate = "UPDATE users SET  username =:username, email = :email WHERE  id =:id";

                //use PDO prepared to sanitize data
                $statement = $db->prepare($sqlUpdate);
                //update the record in the database
                $statement->execute(array(':username' => $username, ':email' => $email, ':id' => $hidden_id));
            }

            //check if one new row was created
            if($statement->rowCount()==1){
                $result = "<script type=\"text/javascript\">
            swal({
                title: \"Updated\",
                text: \"Profile Updated Successfully!\",
                type: \"success\",
                confirmButtonText: \"Thank You!\"
             }, function(){
                 window.location.replace(window.location.href);
             });
        </script>";
            }else{
                $result = "<script type=\"text/javascript\">
            swal({
                title: \"Nothing Happened\",
                text: \"You have not made any changes!\"
             }, function(){
                window.location.replace(window.location.href);
            });
        </script>";
            }
        }catch(PDOexception $ex){
            $result = flashMessage("An error occurred: ".$ex->getMessage());
        }
    }else{
        if(count($form_errors)==1){
            $result = flashMessage("There was 1 error in the form<br>");
        }else{
            $result = flashMessage("There were " .count($form_errors) ."errors in the form<br>");
        }
    }
    }else{
        //throw an error message
        $result = "<script type='text/javascript'>
            swal('Error', 'Sorry! Unknown request', 'error');
            </script>";
    }
    
}