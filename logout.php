<?php
require_once("config.php");
require_once("function.php");
$success = '';

$conn = connect();
$sql = "DELETE FROM users_auth WHERE user_id = ".$_COOKIE['id'];
$conn->query($sql);
close($conn);
setcookie("id",$user['id'], time()-30, "/");
setcookie("hash",$hash, time()-30,  "/");
setcookie("logout",1, time()+10,  "/");
header("Location: login.php");