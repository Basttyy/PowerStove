<?php
    $page_title = "User Authentication - Edit Profile";
    include_once 'partials/headers.php';
    include_once 'partials/parse_profile.php';
    include_once 'partials/parse_change_password.php';
    include_once 'partials/parse_deactivate.php';
?>

<div class="container">
        <section class="col col-lg-7">
            <h2>Edit Profile</h2><hr>
            <div>
                <?php
                    if(isset($result)) echo $result;
                    if(!empty($form_errors)) echo show_errors($form_errors);
                ?>
            </div>
            <div class="clearfix"></div>
            <?php if(!isset($_SESSION['username'])): ?>
                <p class="lead">You are not authorized  to view this page <a href="login.php">Login</a> Not yet a member? <a href="signup.php">Signup</a></p>
            <?php else: ?>
                <?php if(function_exists('_token')): ?>
                    <?php $token = _token(); ?>
                <?php endif ?>
            <h5>Change Username Email or Picture</h5>           
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="emailField">Email</label>
                    <input type="email" name="email" id="emailField" value="<?php if(isset($email)) echo $email; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="usernameField">Username</label>
                    <input type="text" name="username" id="usernameField" value="<?php if(isset($username)) echo $username; ?>" class="form-control">
                </div>
                <div class="form-group">
                    <label for="fileField">Avatar</label>
                    <input type="file" name="avatar" id="fileField">
                </div> 
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <input name="hidden_id" value="<?php if(isset($id)) echo $id; ?>" type="hidden">
                <button type="submit" name="updateProfileBtn" class="btn btn-primary right">Update Profile</button><br>
            </form><br><hr>
            <!--Change passwor area-->
            <h5>Password Management</h5>
            <hr>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="currentPasswordField">Current Password</label>
                    <input type="password" name="current_password" id="currentPasswordField" placeholder="Current Password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="newPasswordField">New Password</label>
                    <input type="password" name="new_password" id="newPasswordField" placeholder="New Password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="confirmPasswordField">Confirm Password</label>
                    <input type="password" name="confirm_password" placeholder="Confirm Password" class="form-control" id="confirmPasswordField">
                </div> 
                <input type="hidden" name="passToken" value="<?php echo $token ?>">
                <input name="pass_hidden_id" value="<?php if(isset($id)) echo $id; ?>" type="hidden">
                <button type="submit" name="changePasswordBtn" class="btn btn-primary right">Change Password</button><br>
            </form><br><hr>
            <!--Deactivate Account area-->
            <h5>Deactivate Account</h5>
            <hr>
            <form action="" method="post">
                <input type="hidden" name="token" value="<?php echo $token ?>">
                <input name="hidden_id" value="<?php if(isset($id)) echo $id; ?>" type="hidden">
                <button onClick="return confirm('Do you really want to deactivate your account?')" type="submit" name="deleteAccountBtn" class="btn btn-danger btn-block right">Deactivate Your Account</button><br>
            </form><br><br>
            <div class="container">
        <div class="divider"></div>
    </div>
            <?php endif ?>
        </section>
    </div>
    <?php include_once 'partials/footers.php' ?><br><br><br>
</body>
</html>