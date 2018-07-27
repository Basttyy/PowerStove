<?php
    require 'resource/session.php';
    require 'resource/database.php';
    require 'resource/utilities.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0" />
    <title><?php if(isset($page_tittle)) echo $page_tittle?></title>

    <!-- Bootstrap -->    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="css/materialize.min.css" type="text/css" rel="stylesheet" media="screen,projection" />
    <link href="css/style.css" type="tex/css" rel="stylesheet">
    <script src="js/sweetalert.js"></script>
    <link rel="stylesheet" href="css/sweetalert.css">
</head>
<body>
    <div id="nav-div">
        <nav>
            <div class="nav-wrapper orange darken-2" >
                <a href="index.php" class="brand-logo"><span class="logo grey-text text-lighten-2">PowerStove</span>     <img src="uploads/company_img/powerstove-logo.png" class="left" alt="" style="height: 45px; width: 45px;"></a>
                <a href="#mobile-demo" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>
                <ul class="right navi hide-on-med-and-down"><?php guard(); ?>
                    <?php if(isset($_SESSION['username'])): ?>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="members.php">Members</a></li>
                        <li><a href="logout.php">Logout</a></li>
                        <li><a href="feedback.php">Feedbacks</a></li>
                    <?php endif ?>
                </ul>
                <ul class="side-nav" nav id="mobile-demo"><?php guard(); ?>
                    <?php if(isset($_SESSION['username'])): ?>
                        <li><a href="profile.php">Profile</a></li>
                        <li><a href="members.php">Members</a></li>
                        <li><a href="logout.php">Logout</a></li>
                        <li><a href="feedback.php">Feedbacks</a></li>
                    <?php endif ?>
                </ul>
            </div><!--/.nav-collapse -->
        </nav>
    </div> 
    <main>
    