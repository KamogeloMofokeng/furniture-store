<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/furniture/init.php';
$email = ((isset($_POST['email']))?sanitize($_POST['email']):'');
$email = trim($email);
$password = ((isset($_POST['password']))?sanitize($_POST['password']):'');
$password = trim($password);
$errors = array();

?>
<!DOCTYPE html>
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
        if(empty($_POST['email']) || empty($_POST['password'])){
            $errors[] = 'You must provide email and password';
        }

        if(strlen($password) < 6){
            $errors[] = 'Password must at least be 6 characters';
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
            $errors[] = 'You must enter a valid email';
        }

        $query = $db->query("SELECT * FROM users WHERE email = '$email'");
        $user = mysqli_fetch_assoc($query);
        $userCount = mysqli_num_rows($query);
        if($userCount < 1){
            $errors[] = 'That email does not exist in our database';
        }

        if(!password_verify($password, $user['password'])){
            $errors[] = 'The password does not match. Please try again';
        }

        if(!empty($errors)){
            echo display_errors($errors);
        }else{
            $user_id = $user['id'];
            login($user_id);
        }

    }
    
    ?>
    
    </div>
    <h2 class="text-center">login</h2>
    <form action="login.php" method="POST">
    <div class="form-group">
    <label for="email">E-mail:</label>
    <input type="email" name="email" id="email" class="form-control" value="<?=$email;?>">
    </div><br>
    <div class="form-group">
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" class="form-control" value="<?=$password;?>">
    </div><br>
    <div class="form-group">
    <input type="submit" value="Login" class="btn btn-primary">
    </div>
    </form><br>
    <p class="text-right">
    <a href="/furniture/index.php" alt="home">Visit Site</a></p>
    
</div>
</body>
</html>

