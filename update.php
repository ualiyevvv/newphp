<?php
require_once("config.php");
require_once("function.php");


$id = $_GET['id'];
if (isset($id ) && trim($id )!='' ) {
    $conn = connect();
    $article = select($conn,$id);
    close($conn);
        
    if (isset($_POST['update']) && trim($_POST['title']) !='' ) {
        $conn = connect();
        $title = $_POST['title'];
        $description = $_POST['description'];
        $descr_min = substr($description, 0, 45);
        
        if (isset($_FILES["image"]["name"]) && trim($_FILES["image"]["name"])!='') {
            $uploads_dir = 'images';
            $image = $_FILES["image"]["name"];
            $tmp_name =  $_FILES["image"]["tmp_name"];
            if (is_dir($uploads_dir)) {
                move_uploaded_file($tmp_name, "$uploads_dir/$image");
            }
            else {
                mkdir($uploads_dir);
                move_uploaded_file($tmp_name, "$uploads_dir/$image");
            }
        } else {
            $image = $article["image"];
        }
        
        $tag = trim($_POST['tags']);
        $tags = explode(',', $tag);
        for ($i=0;$i < count($tags); $i++) {
            $newtags[] = trim($tags[$i]); 
        }
        
        
        $sql = "UPDATE info SET title = '".$title."', descr_min = '".$descr_min."', description = '".$description."', image = '".$image."' WHERE id = $id";
        

        if ($conn->query($sql) === TRUE) {
            /* $last_id = mysqli_insert_id ($conn);
            for($i=0; $i < count($newtags);$i++) {
                $sql = "INSERT INTO tags (tag, post) VALUES ('".$newtags[$i]."','".$last_id."')";
                $conn->query($sql);
            } */
            
            setcookie("bd_update_success",1, time()+10);
            header("Location: /"); 
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        close($conn);

    }
}
else {
    header("Location: /");
}


?>
<form action="" method="POSt" enctype="multipart/form-data">
    <input type="text" name="title" placeholder="title" value="<?=$article[0]['title']?>">
    <input type="text" name="description" placeholder="description" value="<?=$article[0]['description']?>">
    <input type="file" name="image"><br>
    <!-- <input type="text" name="tags" placeholder="tag" value="<?=$article[0]['title']?>"> -->
    <input type="submit" name="update">
</form>