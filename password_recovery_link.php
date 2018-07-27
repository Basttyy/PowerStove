<?php
    $page_title = "User Authentication - Password Recovery";
    include_once 'partials/headers.php';
    include_once 'partials/parse_forgot_password.php';
?>

<div class="container">
    <section class="col col-lg-7">
        <div>
            <?php if(isset($result)) echo $result; ?>
            <?php if(!empty($form_errors)) echo show_errors($form_errors); ?>
        </div>
        <div class="clearfix"></div>

        To request password reset link please enter your email address in the form below <br><br>
        <form action="" method="post">
            <div class="form-group">
                <label for="emailField">Email Address</label>
                <input type="text" class="form-control" name="email" id="emailField" placeholder="email">
            </div>
                <input name="token" value="<?php if(function_exists('_token')) echo _token(); ?>" type="hidden">
            <button class="btn btn-primary btnRight" type="submit" name="passwordRecoveryBtn">Recover Password</button>
        </form>
    </section>
    <p>Go back to homepage <a href="index.php">Back</a></p>
</div>
<?php include_once 'partials/footers.php'; ?>
</body>
</html>