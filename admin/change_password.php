<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/furniture/init.php';
if(!is_logged_in()){
    login_error_redirect();
}



$hashed = $user_data['password'];
$old_password = ((isset($_POST['old_password']))?sanitize($_POST['old_password']):'');
$old_password = trim($old_password);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$confirm = ((isset($_POST['confirm']))?sanitize($_POST['confirm']):'');
$confirm = trim($conform);
$new_hashed = password_hash($password, PASSWORD_DEFAULT);
$user_id = $user_data['id'];
$errors = array();

?>
<!doctype html>
<html>
    <head>
    <title>Simplistic Threads</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/bootstrap.min.css" />
        <link href="../style.css" rel="stylesheet">
        <script src="../js/bootstrap.min.js"></script>
        
        </head>
        <body>
<div id="login-form">
    <div>
    <?php

    if($_POST){
        if(empty($_POST['old_password']) || empty($_POST['password']) || empty($_POST['confirm'])){
            $errors[] = 'You must fill out all fields';
        }

        if(strlen($password) < 6){
            $errors[] = 'Password must at least be 6 characters';
        }

        if($password != $confirm){
            $errors[] = 'The new password and confirm nre password does not match';
        }


        if(!password_verify($old_password, $hashed)){
            $errors[] = 'Your old password does not match our records';
        }

        if(!empty($errors)){
            echo display_errors($errors);
        }else{
            $db->query("UPDATE users SET password = '$new_hashed' WHERE id = '$user_id'");
            $_SESSION['success_flash'] = 'Your password has been updated';
            header("Location: index.php");
        }

    }
    
    ?>
    
    </div>
    <h2 class="text-center">Change Password</h2>
    <form action="change_password.php" method="POST">
    <div class="form-group">
    <label for="old_password">Old Paasword:</label>
    <input type="old_password" name="old_password" id="old_pasdword" class="form-control" value="<?=$old_password;?>">
    </div><br>
    <div class="form-group">
    <label for="password">New Password:</label>
    <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div><br>
    <div class="form-group">
    <label for="confirm">Confirm Password:</label>
    <input type="password" name="conform" id="confirm" class="form-control" value="<?=$confirm;?>">
    </div><br>
    <div class="form-group">
    <a href="index.php" class="btn btn-default">Cancel</a>
    <input type="submit" value="Login" class="btn btn-primary">
    </div>
    </form><br>
    <p class="text-right">
    <a href="/website/index.php" alt="home">Visit Site</a></p>
    
</div>
</body>
</html>
