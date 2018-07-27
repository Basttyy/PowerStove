<?php
    $page_tittle = "User Authentication - LoginPage";
    include_once "partials/headers.php";
    include_once "partials/parse_login.php"
?>
    <div class="container center-align">
        <section>
            <h2 class="grey-text text-darken-2">Admin Access</h2>
            <div class="z-depth-1">
            <hr>
            <h5 class="grey-text text-darken-2">You are required to login</h5>
            <div>
                <?php
                    if(isset($result)) echo $result;
                    if(!empty($form_errors)) echo show_errors($form_errors);
                ?>
            </div>
            <div class="clearfix"></div>
            <div class="row">
            <form action="" method="post" class="z-depth-1 col s7 offset-s3">
                
                <div class="input-field col s9 offset-s1">
                    <i class="material-icons prefix light">account_circle</i>
                    <label class="active" for="usernameField">Username</label>
                    <input type="text" name="username" id="usernameField" placeholder="Username" class="validate">
                </div>
                <div class="input-field col s9 offset-s1">
                    <i class="material-icons prefix">lock_outline</i>
                    <label class="active" for="passwordField">Password</label>
                    <input type="password" name="password" id="passwordField" placeholder="Password" class="validate">
                </div>
                </div>
                <div class="input-field s6 offset-s3">
                    <p>
                        <input id="rememberMe" name="remember" value="yes" type="checkbox">
                        <label for="rememberMe">Remember Me</label>
                    </p>
                </div>
                <label><a href="password_recovery_link.php">Forgot Password?</a></label>                
                <input name="token" value="<?php if(function_exists('_token')) echo _token(); ?>" type="hidden">
                <button type="submit" name="loginBtn" class="btn btn-primary orange darken-2">Sign In</button>
                <br><br>
            </form></div>
        </section>
    </div><br><br>
    <div class="container">
        <div class="divider"></div>
    </div>
</div>
    
    <?php include_once "partials/footers.php"; ?>
</body>
</html>