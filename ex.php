<?php
require_once("config.php");
require_once("function.php");

if (isset($_POST['add']) && trim($_POST['title']) !='' ) {
    $conn = connect();
    $title = $_POST['title'];
    $description = $_POST['description'];
    $descr_min = substr($description, 0, 45);
    
    $uploads_dir = 'images';
    $image = $_FILES["image"]["name"];
    $tmp_name =  $_FILES["image"]["tmp_name"];
    
    $tag = trim($_POST['tags']);
    $tags = explode(',', $tag);
    for ($i=0;$i < count($tags); $i++) {
        $newtags[] = trim($tags[$i]); 
    }

    if (is_dir($uploads_dir)) {
        move_uploaded_file($tmp_name, "$uploads_dir/$image");
    }
    else {
        mkdir($uploads_dir);
        move_uploaded_file($tmp_name, "$uploads_dir/$image");
    }
    
    $sql = "INSERT INTO info (title, descr_min, description, image, category)
    VALUES ('".$title."','".$descr_min."','".$description."','".$image."',0)";

    if ($conn->query($sql) === TRUE) {
        $last_id = mysqli_insert_id ($conn);
        for($i=0; $i < count($newtags);$i++) {
            $sql = "INSERT INTO tags (tag, post) VALUES ('".$newtags[$i]."','".$last_id."')";
            $conn->query($sql);
        }
        
        setcookie("bd_create_success",1, time()+10);
        header("Location: /"); 
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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
<form action="" method="POSt" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="title">
    <input type="text" name="description" placeholder="description">
    <input type="file" name="image"><br>
    <input type="text" name="tags" placeholder="tag">
    <input type="submit" name="add">
</form>


<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>