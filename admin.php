<?php
require_once("config.php");
require_once("function.php");
$success = '';

if (isset($_COOKIE["hash"]) && isset($_COOKIE["id"])) {
    $conn = connect();
    $sql = "SELECT * FROM users WHERE id = ".$_COOKIE["id"]." LIMIT 1";
    
    $row = mysqli_query($conn,$sql);
    $user = mysqli_fetch_assoc($row);
    if ($user['hash']==$_COOKIE["hash"]) {
        $success = '<div class="alert alert-success" role="alert">login successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';

    }
    else {
        setcookie("id",$user['id'], time()-30, "/");
        setcookie("hash",$hash, time()-30,  "/");
        header("Location: login.php");
    }
    close($conn);

} 
else {
    setcookie("id",$user['id'], time()-30, "/");
    setcookie("hash",$hash, time()-30,  "/");
    header("Location: login.php");
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
    admin hello <b><?=$user['login']?></b> <a href="logout.php">logout</a>





<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>