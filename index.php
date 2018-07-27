<?php
    $page_tittle = "User Authentication - Hompage";
    require "partials/headers.php";

?>
<div class="container center z-depth-3">

    <div class="flag">
        <h1 class="grey lighten-2 teal-text text-darken-3">Welcome To Admin Page</h1>
        
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
        <div class="divider"></div>
    </div>
        <!--Work Section-->
        <div class="container z-depth-2 myBorder">
        <h4 class="section-header teal-text text-darken-3">Manage your dashboard</h4>
        <?php
        $superAdmin = '<div class="row">
            <div class="col s12 m6">
                <div class="card grey lighten-2">
                    <div class="card-content orange-text text-darken-2">
                        <span class="card-title">Add an Admin</span>
                        <p>Create a new branch admin into the platform</p>
                    </div>
                    <div class="card-action z-depth-3">
                        <a href="signup.php" class="btn-large waves-effect orange darken-2 waves-light"><b>Register Users</b><i class="material-icons medium right light">person_add</i></a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card grey lighten-2">
                    <div class="card-content orange-text text-lighten-1">
                        <span class="card-title">View Admins</span>
                        <p>View and perform operations on a registered Admin account</p>
                    </div>
                    <div class="card-action z-depth-3">
                        <a href="members.php" class="btn-large waves-effect orange darken-2 waves-light"><b>View Users</b><i class="material-icons medium right light">people_outline</i></a>
                    </div>
                </div>
            </div>
            </div>
            <div class="row">
            <div class="col s12 m6">
                <div class="card grey lighten-2">
                    <div class="card-content orange-text text-lighten-1">
                        <span class="card-title">Admin Feedbacks</span>
                        <p>Read respond and treat admin feedback</p>
                    </div>
                    <div class="card-action z-depth-3">
                        <a href="feedback.php" class="btn-large waves-effect orange darken-2 waves-light"><b>User Feedbacks</b><i class="material-icons medium right light">comment</i></a>
                    </div>
                </div>
            </div>
            <div class="col s12 m6">
                <div class="card grey lighten-2">
                    <div class="card-content orange-text text-lighten-1">
                        <span class="card-title">Freeze an Account</span>
                        <p>Deactivate an Agent or Admin Account</p>
                    </div>
                    <div class="card-action z-depth-3">
                        <a href="processpay.php" class="btn-large waves-effect orange darken-2 waves-light"><b>Process Payments</b><i class="material-icons medium right light">credit_card</i></a>
                    </div>
                </div>
            </div>
        </div>';
        $admin = '<div class="row">
        <div class="col s12 m6">
            <div class="card grey lighten-2">
                <div class="card-content orange-text text-darken-2">
                    <span class="card-title">Register Agent</span>
                    <p>Add an agent to the platform using the details of the customer</p>
                </div>
                <div class="card-action z-depth-3">
                    <a href="signup.php" class="btn-large waves-effect orange darken-2 waves-light"><b>Register Users</b><i class="material-icons medium right light">person_add</i></a>
                </div>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="card grey lighten-2">
                <div class="card-content orange-text text-lighten-1">
                    <span class="card-title">View Agents</span>
                    <p>View and perform operations on a registered agent\'s account</p>
                </div>
                <div class="card-action z-depth-3">
                    <a href="members.php" class="btn-large waves-effect orange darken-2 waves-light"><b>View Users</b><i class="material-icons medium right light">people_outline</i></a>
                </div>
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col s12 m6">
            <div class="card grey lighten-2">
                <div class="card-content orange-text text-lighten-1">
                    <span class="card-title">Agents Feedbacks</span>
                    <p>Read respond and treat agent\'s feedback</p>
                </div>
                <div class="card-action z-depth-3">
                    <a href="feedback.php" class="btn-large waves-effect orange darken-2 waves-light"><b>User Feedbacks</b><i class="material-icons medium right light">comment</i></a>
                </div>
            </div>
        </div>
        <div class="col s12 m6">
            <div class="card grey lighten-2">
                <div class="card-content orange-text text-lighten-1">
                    <span class="card-title">Freeze Account</span>
                    <p>Deactivate an Agent\'s account temporarilly</p>
                </div>
                <div class="card-action z-depth-3">
                    <a href="processpay.php" class="btn-large waves-effect orange darken-2 waves-light"><b>Process Payments</b><i class="material-icons medium right light">credit_card</i></a>
                </div>
            </div>
        </div>
    </div>';
    $agent = '<div class="row">
    <div class="col s12 m6">
        <div class="card grey lighten-2">
            <div class="card-content orange-text text-darken-2">
                <span class="card-title">Register User</span>
                <p>Add a user to the platform using the details of the customer</p>
            </div>
            <div class="card-action z-depth-3">
                <a href="signup.php" class="btn-large waves-effect orange darken-2 waves-light"><b>Register Users</b><i class="material-icons medium right light">person_add</i></a>
            </div>
        </div>
    </div>
    <div class="col s12 m6">
        <div class="card grey lighten-2">
            <div class="card-content orange-text text-lighten-1">
                <span class="card-title">View Users</span>
                <p>View and perform operations on a registered user account</p>
            </div>
            <div class="card-action z-depth-3">
                <a href="members.php" class="btn-large waves-effect orange darken-2 waves-light"><b>View Users</b><i class="material-icons medium right light">people_outline</i></a>
            </div>
        </div>
    </div>
    </div>
    <div class="row">
    <div class="col s12 m6">
        <div class="card grey lighten-2">
            <div class="card-content orange-text text-lighten-1">
                <span class="card-title">User Feedbacks</span>
                <p>Read respond and treat users feedback</p>
            </div>
            <div class="card-action z-depth-3">
                <a href="feedback.php" class="btn-large waves-effect orange darken-2 waves-light"><b>User Feedbacks</b><i class="material-icons medium right light">comment</i></a>
            </div>
        </div>
    </div>
    <div class="col s12 m6">
        <div class="card grey lighten-2">
            <div class="card-content orange-text text-lighten-1">
                <span class="card-title">Process Payments</span>
                <p>Process bank payment and mobile transfers teller</p>
            </div>
            <div class="card-action z-depth-3">
                <a href="processpay.php" class="btn-large waves-effect orange darken-2 waves-light"><b>Process Payments</b><i class="material-icons medium right light">credit_card</i></a>
            </div>
        </div>
    </div>
</div>';
            if($_SESSION['usertype'] === 'superadmin'){
                echo $superAdmin;
            }elseif($_SESSION['usertype'] === 'admin'){
                echo $admin;
            }else{
                echo $agent;
            }
        ?>
    </div>
    
    <div class="container">
        <div class="divider"></div>
    </div>

</main>
<?php include_once "partials/footers.php"; ?>
</body>
</html>