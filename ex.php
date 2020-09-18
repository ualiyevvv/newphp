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
    
    $sql = "INSERT INTO info (title, descr_min, description, image )
    VALUES ('".$title."','".$descr_min."','".$description."','".$image."')";

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
<form action="" method="POSt" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="title">
    <input type="text" name="description" placeholder="description">
    <input type="file" name="image"><br>
    <input type="text" name="tags" placeholder="tag">
    <input type="submit" name="add">
</form>