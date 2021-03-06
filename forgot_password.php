<?php
    $page_tittle = "Registeration Page";   
    include_once "partials/headers.php";
    include_once "partials/parse_forgot_password.php"; 
?>
    <div class="container">
        <section class="col col-lg-7">
            <h2>Password Reset Form</h2><hr>
            <div>
                <?php
                    if(isset($result)) echo $result;
                    if(!empty($form_errors)) echo show_errors($form_errors);
                ?>
            </div>
            <div class="clearfix"></div>
            <form action="" method="post">
                <div class="form-group">
                    <label for="newPasswordField">New Password</label>
                    <input type="password" name="new_password" id="newPasswordField" placeholder="Password" class="form-control">
                </div>
                <div class="form-group">
                    <label for="confirmPasswordField">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirmPasswordField" placeholder="Password" class="form-control">
                </div>
                <input type="hidden" name="email" value="<?php echo $_GET['email']; ?>">
                <input type="hidden" name="resetToken" value = "<?php echo $_GET['token']; ?>">
                <input name="token" value="<?php if(function_exists('_token')) echo _token(); ?>" type="hidden">
                <button type="submit" name="passwordResetBtn" class="btn btn-primary btnRight">Reset Password</button>
            </form>
        </section>
    </div>
    
    <?php include_once "partials/footers.php"; ?>
</body>
</html>