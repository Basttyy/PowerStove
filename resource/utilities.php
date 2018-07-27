<?php
    /**
     * @param $required_fields_array, n array containing the list of all required fields
     * @return array, containing all errors
     */

    function check_empty_fields($required_fields_array){
        //initailize an array to store all error messages
        $form_errors = array();

        //loop through the required fields array
        foreach($required_fields_array as $name_of_field){
            if(!isset($_POST[$name_of_field]) || $_POST[$name_of_field] == null){
                $form_errors[] = $name_of_field ." is required!";  
            }
        }
        return $form_errors;
    }

    /**
     * @param $fields_to_check_length, an array containing the name of fields for which we want to check min
     * required length e.g array('username' => 4, 'email' => 12)
     * @return array, containing all errors
     */
    function check_min_length($fields_to_check_length){
        //initialize an array to store any error message from the form
        $form_errors = array(); 

        foreach($fields_to_check_length as $name_of_field => $minimum_length_required){
            if(strlen(trim($_POST[$name_of_field])) < $minimum_length_required){
                $form_errors[] = $name_of_field ." is too short, must be {$minimum_length_required} characters long";
            }
        }
        return $form_errors;
    }
    /**
     * @param $data, store a key/value pair array where a key is the name of the form control in this case 'email'
     * and value is the input entered by the user
     * @return array, containing email error
     */
    function check_email($data){
        //initialize an array to store error messages
        $form_errors = array();
        $key = 'email';
        //check if the key 'email' exists in data array
        if(array_key_exists($key, $data)){
            //check if email field has a value
            if($_POST[$key] != null){
                //remove all illegal characters from email
                filter_var($key, FILTER_SANITIZE_EMAIL);

                //check if input is a valid email address
                if(filter_var($_POST[$key], FILTER_VALIDATE_EMAIL) === false){
                    $form_errors[] = $key ." is not a valid email address";
                }
            }
        }
        return $form_errors;
    }
    /**
     * @param $form_errors_array, the array holding all errors which we want to loop through
     * @return string, list containing all error messages
     */
    function show_errors($form_errors_array){
        $errors = "<p><ul style='color: red;'>";
        //loop through error array and display all items in a list
        foreach($form_errors_array as $the_error){
            $errors .= "<li> {$the_error} </li>";
        }
        $errors .= "</ul></p>";
        return $errors;
    }

    function flashMessage($message, $passOrFail = "fail"){
        if($passOrFail === "pass"){
            $data = "<div class='alert alert-success'>{$message}</p>";
        }else{
            $data = "<div class='alert alert-danger'>{$message}</p>";
        }
        return $data;
    }
    function redirectTo($page){
        header("location: {$page}.php");
    }
    function checkDuplicateEntries($table, $column_name, $value, $db){
        try{
            $sqlQuery = "SELECT * FROM " .$table. " WHERE " .$column_name. "=:$column_name";
            $statement = $db->prepare($sqlQuery);
            $statement->execute(array(':column_name' => $value));

            if($row = $statement->fetch()){
                return true;
            }
            return false;
        }catch(PDOexception $ex){
            //handle exception
            //echo $ex->getMessage();
            return false;
        }
    }
    /**
     * @param $user_id
     */
    function rememberMe($user_id){
        $encryptCookieData = base64_encode("abcdefABCDEF1234567890{$user_id}");
        // cookie set to expire in about 30 days
        setcookie("rememberUserCookie", $encryptCookieData, time()+60*60*24*100, "/");
    }
    /**
     * checked if the cookie used is the same with the encrypted cookie
     * @param $db, database connection link
     * @return bool, true if the user cookie is valid
     */
    function isCookieValid($db){
        $isValid = false;

        if(isset($_COOKIE['rememberUserCookie'])){
            /**
             * Decode cookies and extract user ID
             */
            $decryptCookieData = base64_decode($_COOKIE['rememberUserCookie']);
            $user_id = explode("abcdefABCDEF1234567890", $decryptCookieData);
            $userID = $user_id[1];
            /**
             * check if id retrieved from the cookie exists in the database
             */
            $sqlQuery = "SELECT * FROM users WHERE id = :id";
            $statement = $db->prepare($sqlQuery);
            $statement->exectue(array(':id' => $userID));
            
            if($row = $statement->fetch()){
                $id = $row['id'];
                $username = $row['username'];
                //create the user session variable
                $_SESSION['id']=$id;
                $_SESSION['username']=$username;
                $isValid = true;
            }else{
                $isValid = false;
                signout();
            }
        }
        return $isValid;
    }
    function signout(){
        unset($_SESSION['username']);
        unset($_SESSION['id']);

        if(isset($_COOKIE['rememberUserCookie'])){
            unset($_COOKIE['rememberUserCookie']);
            setcookie('remeberUserCookie', null, -1, '/');
        }
        session_destroy();
        session_regenerate_id(true);
        redirectTo('index');
    }
    function guard(){
        $isValid = true;
        $inactive = 60*10; //10mins
        $fingerprint = md5($_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']);

        if(isset($_SESSION['fingerprint']) && $_SESSION['fingerprint'] != $fingerprint){
            $isValid = false;
            signout();
        }else if((isset($_SESSION['last_active'])&&(time() - $_SESSION['last_active'] > $inactive)&& $_SESSION['username'])){
            $isValid = false;
            signout();
        }else{
            $_SESSION['last_active'] = time();
        }
    }
    function isValidImage($file){
        $form_errors = array();

        //split file name into an array using dot(.)
        $part = explode(".", $file);

        //target the last element in the array
        $extension = end($part);
        switch(strtolower($extension)){
            case 'jpg':
            case 'gif':
            case 'bmp':
            case 'png':

            return $form_errors;
        }
        $form_errors[] = $extension. " is not a valid image file";
        return $form_errors;
    }
    function uploadAvatar($username){
        if($_FILES['avatar']['tmp_name']){
            //files in the temporary location
            $temp_file = $_FILES['avatar']['tmp_name'];
            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION); //get uploaded image extension
            $filename = $username.md5(microtime()).".{$ext}";       //concatenate a random no and file ext to username

            $path = "uploads/{$filename}";     // uploads/username.jpg
            move_uploaded_file($temp_file, $path);
        }
        return $path;
    }
    function _token(){
        $randomToken = base64_encode(openssl_random_pseudo_bytes(32));
        //$randomToken = md5(uniqid(rand(), true));

        return $_SESSION['token'] = $randomToken;
    }
    function validate_token($requestToken){
        if(isset($_SESSION['token']) && $requestToken === $_SESSION['token']){
            unset($_SESSION['token']);
            return true;
        }
        return false;
    }
    function prepLogin($id, $username, $remember, $usertype){
        $_SESSION['id'] = $id;
        $_SESSION['username'] = $username;
        $_SESSION['usertype'] = $usertype;
    
        //hash and store user IP and login time in the session object
        $fingerprint = md5($_SERVER['REMOTE_ADDR'] .$_SERVER['HTTP_USER_AGENT']);
        $_SESSION['last_active'] = time();
        $_SESSION['fingerprint'] = $fingerprint;
    
        if($remember === "yes"){
            rememberMe($id);
        }
                        
        echo "<script type=\"text/javascript\">
            swal({
                title: \"Welcome back $username\",
                text: \"You're being logged in!\",
                type: \"success\",
                showCancelButton: false,
                showConfirmButton: false,
            }, setTimeout(function(){
                    window.location.href = \"index.php\";
                }, 5000));
            </script>";
                        //redirectTo('index');
    }
?>