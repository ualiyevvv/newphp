<?php
require_once("config.php");
require_once("function.php");
$success = '';

if (isset($_COOKIE["logout"]) && trim($_COOKIE["logout"])!='') {
    setcookie("logout",1, time()-10);
    $success = '<div class="alert alert-success" role="alert">logout successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}

if (isset($_POST["signin"]) && trim($_POST["password"])!='') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $conn = connect();
    $sql = "SELECT id,password FROM users WHERE email = '".$email."'";
    $row = mysqli_query($conn,$sql);
    $user = mysqli_fetch_assoc($row);
    if (  $user['password'] == md5($password)) {
        $hash = generateHash();
        //$time = date("Y-d-m h:m:s");
        $userId = $user['id'];

        $sql = "UPDATE users SET hash = '".$hash."' WHERE id = " . $user['id'];
        $conn->query($sql);
        $sql = "INSERT INTO users_auth (user_id, hash,time) VALUE ($userId,'$hash',CURRENT_TIMESTAMP())";
        $conn->query($sql);

        setcookie("id",$user['id'], time()+3600*24*30);
        setcookie("hash",$hash, time()+3600*24*30, null, null, null, null);
        header("Location: main.php");
    }
    else {
         echo"error pass";
    } 
    
    close($conn);
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
</head>
<body>
<?=$success?>
    <form action="" method="POSt">
        <input type="email" name="email">
        <input type="password" name="password">
        <input type="submit" name="signin">
    
    </form>
 
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>