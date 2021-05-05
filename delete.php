<?php
require_once("config.php");
require_once("function.php");
if (isset($_GET['id']) && trim($_GET['id'])!='') {
    $conn = connect();
    $id = $_GET['id'];
    $delete = delete($conn, $id);
    if ($delete) {
        setcookie("delete_success",1, time()+10);
        header("Location: /");
    }
    close($conn);
}
else {
    header("Location: /");
}