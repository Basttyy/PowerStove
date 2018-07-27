<?php
include_once 'resource/database.php';
include_once 'resource/utilities.php';

if(isset($_POST['changePasswordBtn'], $_POST['passToken'])){
    if(validate_token($_POST['passToken'])){
        //process the form
        //initialize an array to store any error message
    $form_errors = array();
    //form validation
    $required_fields = array('current_password', 'new_password', 'confirm_password');
    //call the function to check empty fields and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_empty_fields($required_fields));
    //fields that require checking for minimum length
    $fields_to_check_length = array('new_password' => 6, 'confirm_password' => 6);
    //call the function to check the minimum required length and merge the return data into form_error array
    $form_errors = array_merge($form_errors, check_min_length($fields_to_check_length));
    //check if error array is empty
    if(empty($form_errors)){
        $password1 = $_POST['new_password'];
        $password2 = $_POST['confirm_password'];
        $id = $_POST['pass_hidden_id'];
        $current_password = $_POST['current_password'];

        //check if new password and confirm password are the same
        if($password1 != $password2){
            $result = flashMessage("New password and confirm password does not match");
        }else{
            try{
                //process request check if old passwodd exists
                $sqlQuery = "SELECT password FROM users WHERE id =:id";
                $statement = $db->prepare($sqlQuery);
                $statement->execute(array(':id' => $id));
                //check if record found
                if($row = $statement->fetch()){
                    $password_from_db = $row['password'];
                    if(password_verify($current_password, $password_from_db)){
                        //hash the new password
                        $hashed_password = password_hash($password1, PASSWORD_DEFAULT);
                        //sql statement to update password
                        $sqlUpdate = "UPDATE users SET password =:password WHERE id =:id";
                        $statement = $db->prepare($sqlUpdate);
                        $statement->execute(array(':password' => $hashed_password, ':id' => $id));
                        //check if record was created
                        if($statement->rowCount()===1){
                            $result = "<script type=\"text/javascript\">
                swal({
                    title: \"Operation Successful\",
                    text: \"Password Updated Successfully!\",
                    type: \"success\",
                    confirmButtonText: \"Thank You!\"
                 });
            </script>";
                        }else{
                            $result = flashMessage("No changes saved");
                        }
                    }else{
                        $result = "<script type=\"text/javascript\">
                swal({
                    title: \"OOps!!\",
                    text: \"Old password not correct, please try again!\",
                    type: \"error\",
                    confirmButtonText: \"Ok!\"
                 });
            </script>";
                    }
                }else{
                    signout();
                }
                
            }catch(PDOexception $ex){
                $result = flashMessage("An error occurred: ".$ex->getMessage());
            }
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