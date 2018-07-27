<?php
    $page_tittle = "Registeration Page";
    require "partials/headers.php";
    require "partials/parse_signup.php";
?>
<div class="container center z-depth-3">

<div class="flag">
    <h1 class="grey lighten-2 teal-text text-darken-3">User Registeration Form</h1>
    
    <?php 
        //TODO: remove if statement and just display logged in username
        if(isset($_SESSION['username'])){
            echo '<p class="lead">You are logged in as ' .$_SESSION['username'] .'<a href="logout.php"> Logout</a></p>';
        }else{
            echo '<script type="text/javascript">window.location.href = "login.php"</script>';
        }

    ?>
</div>
</div><!-- /.container -->
    <div class="container">
        <section class="col col-lg-7">
            <div>
                <?php
                    if(isset($result)) echo $result;
                    if(!empty($form_errors)) echo show_errors($form_errors);
                ?>
            </div>
            <div class="clearfix"></div>
            <br>
            <?php
            $token = _token();
            $adminForm = '<form action="" method="post" class="z-depth-2">
                <h5 class="center">Personal Details</h5>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix light">account_circle</i>
                        <label class="active" for="firstnameField">Firstname</label>
                        <input type="text" name="firstname" id="firstnameField" class="validate">
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix light">account_circle</i>
                        <label class="active" for="lastnameField">Lastname</label>
                        <input type="text" name="lastname" id="lastnameField" class="validate">
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix light">account_circle</i>
                        <label class="active" for="usernameField">Username</label>
                        <input type="text" name="username" id="usernameField" class="validate">
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock_outline</i>
                        <label class="active" for="passwordField">Password</label>
                        <input type="password" name="password" id="passwordField" class="validate">
                    </div>
                </div>
                <h5 class="center">Contact Details</h5>
                <div class="row">
                <div class="input-field col s3">
                        <i class="material-icons prefix light">contact_phone</i>
                        <label class="active" for="phonenumField">Phone Number</label>
                        <input type="text" name="phone_num" id="phonenumField" class="validate">
                    </div>
                    <span class="col s1"></span>
                    <div class="input-field col s3">
                        <select name="country" id="" class="select">
                            <option value="" disabled selected>Country</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Togo">Togo</option>
                        </select>
                        <label class="active">Country</label>
                    </div><span class="col s1"></span>
                    <div class="input-field col s3">
                        <select name="state" id="" class="select">
                            <option value="" disabled selected>State</option>
                            <option value="Abia">Abia</option>
                            <option value="Adamawa">Adamawa</option>
                            <option value="Akwa Ibom">Akwa Ibom</option>
                            <option value="FCT-Abuja">FCT-Abuja</option>
                            <option value="Lagos">Lagos</option>
                            <option value="Kano">Kano</option>
                        </select>
                        <label>State</label>
                    </div><span class="col s1"></span>
                </div>
                <div class="row">
                    <div class="input-field col s8">
                        <i class="material-icons prefix light">home</i>
                        <label class="active" for="addressField">Office Address</label>
                        <input type="text" name="address" id="addressField" class="validate">
                    </div>
                    <div class="input-field col s4">
                        <i class="material-icons prefix light">place</i>
                        <label class="active" for="postalcodeField">Postal Code</label>
                        <input type="text" name="postal_code" id="postalcodeField" class="validate">
                    </div>
                    
                <div class="input-field col s6">
                <i class="material-icons prefix light">contact_mail</i>
                <label for="emailField" class="active">Email Address</label>
                <input type="email" name="email" id="emailField" class="validate" class="form-control">
            </div>
                    <div class="file-field input-field col s9 center">
                        <div class="orange darken-2 btn">
                            <span><i class="material-icons light">file_upload</i>Select Pic</span>
                            <input type="file">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" placeholder="Select profile picture" type="text">
                        </div>
                    </div>
                </div>
                <button type="submit" name="signupBtn" class="btn btn-primary orange darken-2 right">Create</button>
                <input name="token" value="'.$token.'" type="hidden">
            </form>';
            $agentForm = '<form action="" method="post" class="z-depth-2">
            <h5 class="center">Personal Details</h5>
            <div class="row">
                <div class="input-field col s6">
                    <i class="material-icons prefix light">account_circle</i>
                    <label class="active" for="firstnameField">Firstname</label>
                    <input type="text" name="firstname" id="firstnameField" class="validate">
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix light">account_circle</i>
                    <label class="active" for="lastnameField">Lastname</label>
                    <input type="text" name="lastname" id="lastnameField" class="validate">
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    <i class="material-icons prefix light">account_circle</i>
                    <label class="active" for="usernameField">Username</label>
                    <input type="text" name="username" id="usernameField" class="validate">
                </div>
                <div class="input-field col s6">
                    <i class="material-icons prefix">lock_outline</i>
                    <label class="active" for="passwordField">Password</label>
                    <input type="password" name="password" id="passwordField" class="validate">
                </div>
            </div>
            <!--
            <div class="row">
            <div class="input-field col s3">
                <i class="material-icons prefix light">account_circle</i>
                <label for="birthdateField">Date Of Birth</label>
                <input type="text" name="birthdate" id="birthdateField" class="datepicker">
            </div>
            </div>-->
            <h5 class="center">Contact Details</h5>
            <div class="row">
            <div class="input-field col s3">
                    <i class="material-icons prefix light">contact_phone</i>
                    <label class="active" for="phonenumField">Phone Number</label>
                    <input type="text" name="phone_num" id="phonenumField" class="validate">
                </div>
                <span class="col s1"></span>
                <div class="input-field col s3">
                    <select name="country" id="" class="select">
                        <option value="" disabled selected>Country</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Togo">Togo</option>
                    </select>
                    <label class="active">Country</label>
                </div><span class="col s1"></span>
                <div class="input-field col s3">
                    <select name="state" id="" class="select">
                        <option value="" disabled selected>State</option>
                        <option value="Abia">Abia</option>
                        <option value="Adamawa">Adamawa</option>
                        <option value="Akwa Ibom">Akwa Ibom</option>
                        <option value="FCT-Abuja">FCT-Abuja</option>
                        <option value="Lagos">Lagos</option>
                        <option value="Kano">Kano</option>
                    </select>
                    <label>State</label>
                </div><span class="col s1"></span>
            </div>
            <div class="row">
                <div class="input-field col s8">
                    <i class="material-icons prefix light">home</i>
                    <label class="active" for="addressField">Office Address</label>
                    <input type="text" name="address" id="addressField" class="validate">
                </div>
                <div class="input-field col s4">
                    <i class="material-icons prefix light">place</i>
                    <label class="active" for="postalcodeField">Postal Code</label>
                    <input type="text" name="postal_code" id="postalcodeField" class="validate">
                </div>
                
            <div class="input-field col s6">
            <i class="material-icons prefix light">contact_mail</i>
            <label for="emailField" class="active">Email Address</label>
            <input type="email" name="email" id="emailField" class="validate" class="form-control">
        </div>
                <div class="file-field input-field col s9 center">
                    <div class="orange darken-2 btn">
                        <span><i class="material-icons light">file_upload</i>Select Pic</span>
                        <input type="file">
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" placeholder="Select profile picture" type="text">
                    </div>
                </div>
            </div>
            <button type="submit" name="signupBtn" class="btn btn-primary orange darken-2 right">Create</button>
            <input name="token" value="'.$token.'" type="hidden">
        </form>';
                $userForm = '<form action="" method="post" class="z-depth-2">
                <h5 class="center">Personal Details</h5>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix light">account_circle</i>
                        <label class="active" for="firstnameField">Firstname</label>
                        <input type="text" name="firstname" id="firstnameField" class="validate">
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix light">account_circle</i>
                        <label class="active" for="lastnameField">Lastname</label>
                        <input type="text" name="lastname" id="lastnameField" class="validate">
                    </div>
                </div>
                <div class="row">
                    <div class="input-field col s6">
                        <i class="material-icons prefix light">account_circle</i>
                        <label class="active" for="usernameField">Username</label>
                        <input type="text" name="username" id="usernameField" class="validate">
                    </div>
                    <div class="input-field col s6">
                        <i class="material-icons prefix">lock_outline</i>
                        <label class="active" for="passwordField">Password</label>
                        <input type="password" name="password" id="passwordField" class="validate">
                    </div>
                </div>
                <div class="row">
                <!--<div class="input-field col s3">
                    <i class="material-icons prefix light">account_circle</i>
                    <label for="birthdateField">Date Of Birth</label>
                    <input type="text" name="birthdate" id="birthdateField" class="datepicker">
                </div>-->
                </div>
                <h5 class="center">Contact Details</h5>
                <div class="row">
                <div class="input-field col s3">
                        <i class="material-icons prefix light">contact_phone</i>
                        <label class="active" for="phonenumField">Phone Number</label>
                        <input type="text" name="phone_num" id="phonenumField" class="validate">
                    </div>
                    <span class="col s1"></span>
                    <div class="input-field col s3">
                        <select name="country" id="" class="select">
                            <option value="" disabled selected>Country</option>
                            <option value="Nigeria">Nigeria</option>
                            <option value="Ghana">Ghana</option>
                            <option value="Togo">Togo</option>
                        </select>
                        <label class="active">Country</label>
                    </div><span class="col s1"></span>
                    <div class="input-field col s3">
                        <select name="state" id="" class="select">
                            <option value="" disabled selected>State</option>
                            <option value="Abia">Abia</option>
                            <option value="Adamawa">Adamawa</option>
                            <option value="Akwa Ibom">Akwa Ibom</option>
                            <option value="FCT-Abuja">FCT-Abuja</option>
                            <option value="Lagos">Lagos</option>
                            <option value="Kano">Kano</option>
                        </select>
                        <label>State</label>
                    </div><span class="col s1"></span>
                </div>
                <div class="row">
                    <div class="input-field col s8">
                        <i class="material-icons prefix light">home</i>
                        <label class="active" for="addressField">House Address</label>
                        <input type="text" name="address" id="addressField" class="validate">
                    </div>
                    <div class="input-field col s4">
                        <i class="material-icons prefix light">place</i>
                        <label class="active" for="postalcodeField">Postal Code</label>
                        <input type="text" name="postal_code" id="postalcodeField" class="validate">
                    </div>
                    
                <div class="input-field col s6">
                <i class="material-icons prefix light">contact_mail</i>
                <label for="emailField" class="active">Email Address</label>
                <input type="email" name="email" id="emailField" class="validate" class="form-control">
            </div>
                    <div class="file-field input-field col s9 center">
                        <div class="orange darken-2 btn">
                            <span><i class="material-icons light">file_upload</i>Select Pic</span>
                            <input type="file">
                        </div>
                        <div class="file-path-wrapper">
                            <input class="file-path validate" placeholder="Select profile picture" type="text">
                        </div>
                    </div>
                </div>
                <button type="submit" name="signupBtn" class="btn btn-primary orange darken-2 right">Create</button>
                <input name="token" value="'.$token.'" type="hidden">
            </form>';
            if($_SESSION['usertype'] === 'super_admin'){
                echo $adminForm;
            }elseif($_SESSION['usertype'] === 'admin'){
                echo $agentForm;
            }else{
                echo $userForm;
            }
            ?>
        </section>  
    </div><br><br>
    <div class="container">
        <div class="divider"></div>
    </div>
    
    <?php include_once "partials/footers.php"; ?>
</body>
</html>