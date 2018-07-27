<?php
    $page_title = "User Authentication - Account Activation";
    include_once 'partials/headers.php';
    include_once 'partials/parse_signup.php';
?>

<div class="container center z-depth-3">
    <h1 class="grey lighten-2 teal-text text-darken-3">Account Activation Page</h1>
    <div class="">
        
        <?php if(isset($result)) echo $result; ?>
    </div>
</div>

<?php include_once 'partials/footers.php'; ?>
</body>
</html>