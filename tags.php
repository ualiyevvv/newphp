<?php
require_once("config.php");
require_once("function.php");
$success = '';

if (isset($_COOKIE["bd_create_success"]) && trim($_COOKIE["bd_create_success"])!='') {
    setcookie("bd_create_success",1, time()-10);
    $success = '<div class="alert alert-success" role="alert">New record created successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}

if (isset($_GET['tag']) && trim($_GET['tag'])!='') {
    $tag = $_GET['tag'];
    $conn = connect();
    //$arr = selectPage($conn);
    $tags = selectAllTags($conn);
    $arr = selectTag($conn, $tag);
    close($conn);
}
else {
    header("Location: /");
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

<div class="container">
    <?=$success?>
    <a href="ex.php">add post</a><br>
    post from <b><?=$_GET['tag']?></b> tag
    <div class="row col-6">
    <table class="table table-bordered">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">id</th>
            <th scope="col">title</th>
            <th scope="col">descr_min</th>
            <th scope="col">image</th>
            <th scope="col">tools</th>
        </tr>
    </thead>
    <tbody>
    <? foreach ($arr as $key => $value) { 
        $key++;
        $image = $value['image'];
        if (!isset($image) || trim($image)=='') {
            $image = 'default.jpg';
        }
        
        ?>
        <tr>
            <th><?=$key?></th>
            <th><?=$value['id']?></th>
            <td><?=$value['title']?></td>
            <td><?=$value['descr_min']?>...<a href="article.php?id=<?=$value['id']?>">Read more</a></td>
            <td><img width="70" src="images/<?=$image?>" alt="<?=$image?>"></td>
            <td><a href="delete.php?id=<?=$value['id']?>">del</a> | <a href="update.php?id=<?=$value['id']?>">update</a></td>
        </tr>
        <?  } ?>
    </tbody>
    </table>
        
    </div>
    <div class="row col-6">
        <a href="index.php">all</a> | 
        <?php
            for ($i = 0; $i <= count($tags); $i++){
                echo '<a style="padding:5px;" href="tags.php?tag='.$tags[$i]['tag'].'">'.$tags[$i]['tag'].'</a>';
            }
        ?>
    </div>
</div>
    
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>