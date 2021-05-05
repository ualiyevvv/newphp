<?php
require_once("config.php");
require_once("function.php");
$success = '';

if (isset($_COOKIE["bd_create_success"]) && trim($_COOKIE["bd_create_success"])!='') {
    setcookie("bd_create_success",1, time()-10);
    $success = '<div class="alert alert-success" role="alert">New record created successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}

if (isset($_COOKIE["hash"]) && isset($_COOKIE["id"])) {
    $conn = connect();
    $sql = "SELECT * FROM users WHERE id = ".$_COOKIE["id"]." LIMIT 1";
    
    $row = mysqli_query($conn,$sql);
    $user = mysqli_fetch_assoc($row);
    if ($user['hash']!=$_COOKIE["hash"]) {
        setcookie("id",$user['id'], time()-30, "/");
        setcookie("hash",$hash, time()-30,  "/");
        header("Location: login.php");
    }
    close($conn);

} 
else {
    setcookie("id",$user['id'], time()-30, "/");
    setcookie("hash",$hash, time()-30,  "/");
    $login_status_error = true;
}


if (isset($_GET["id"]) && trim($_GET["id"])!='' ) {
    $conn = connect();  
    $arr = selectProfile($conn,$_GET["id"]);

}
else {
    header("Location: main.php");
}




close($conn);

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

<div class='row col-6'>
    <div class="container">
        <?=$success?>
        <br>
        <a href="main.php">main</a>
        <br>
        <table class="table table-bordered">
            <tbody>
                <tr>
                    <th>id</th>
                    <td><?=$arr['id']?></td>
                </tr>
                <tr>
                    <th>login</th>
                    <td><?=$arr['login']?></td>
                </tr>
            </tbody>
            </table>
    </div>
</div>
    
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>