<?php
require_once("config.php");
require_once("function.php");


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

$id = $_GET['id'];
if (isset($id ) && trim($id )!='' ) {
    if (isset($_COOKIE["addComment_success"]) && trim($_COOKIE["addComment_success"])!='') {
        setcookie("addComment_success",1, time()-10);
        $success = '<div class="alert alert-success" role="alert">add comment successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    if (isset($_COOKIE["addComment_error"]) && trim($_COOKIE["addComment_error"])!='') {
        setcookie("addComment_error",1, time()-10);
        $error = '<div class="alert alert-danger" role="alert">please log in or register to leave a comment
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }
    if (isset($_COOKIE["addtoCart_success"]) && trim($_COOKIE["addtoCart_success"])!='') {
        setcookie("addtoCart_success",1, time()-10);
        $success = '<div class="alert alert-success" role="alert">add to cart successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
    }

    $conn = connect();
    $article = select($conn,$id);
    $visits = selectVisits($conn,$id);

    if (isset($article) && empty($visits)) {
        $sql = "INSERT INTO visits (post_id,counter,date) VALUE ($id, 1, CURDATE())";
        $conn->query($sql);
        $visits[0]['counter'] = 0;
    }
    else {
        $sql = "UPDATE visits SET counter = counter+1, date = CURDATE() WHERE post_id = ".$id;
        $conn->query($sql);
        $sql = "UPDATE info SET views = ".$visits[0]['counter']." WHERE id = ".$id;
        $conn->query($sql);

    }
    if (isset($_POST['addComment']) && trim($_POST['text'])!='' && $login_status_error != true ){
        $login = $user['login'];
        $text = $_POST['text'];
        
        $sql = "INSERT INTO comments (post_id, text, login, date)
        VALUES ($id,'".$text."','".$login."', CURDATE())";

        if ($conn->query($sql) === TRUE) {
            setcookie("addComment_success",1, time()+10);
            header("Location: article.php?id=$id");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }


    }
    else if (isset($_POST['addComment']) && $login_status_error == true) {
        setcookie("addComment_error",1, time()+10);
        header("Location: article.php?id=$id");
    }
    $comments = selectComments($conn,$id);
    $sql = "UPDATE info SET comments = ".count($comments)." WHERE id =".$id;
    $conn->query($sql);


    if (isset($_POST['addToCart']) && trim($_POST['orderId'])!='') { 
        $orderId = $_POST['orderId'];
        $orderCount = $_POST['orderCount'];
        $userId = $user['id'];
        if ($login_status_error == true) {

        }
        else {
            $checkCountOrder = checkCountOrder($conn,$orderId);
            if (is_null($checkCountOrder)) {
                $sql = "INSERT INTO cart (post_id,count,user_id,date) VALUE ($orderId, $orderCount,$userId, CURDATE())";
                if ($conn->query($sql) === TRUE) {
                    setcookie("addtoCart_success",1, time()+10);
                    header("Location: article.php?id=$id");
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }

            }
            else {
                $sql = "UPDATE cart SET count = count + $orderCount WHERE post_id = $orderId AND user_id = $userId";
                if ($conn->query($sql) === TRUE) {
                    setcookie("addtoCart_success",1, time()+10);
                    header("Location: article.php?id=$id");
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

        }
    }


    close($conn);

}
else {
    header("Location: /");
}

$image = $article[0]['image'];
if (!isset($image) || trim($image)=='') {
    $image = 'default.jpg';
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
<div class="container d-flex justify-content-center">
    <div class="row col-12">
    <a href="index.php">main</a>
        <div class="card">
            <?=$success?>
            <?=$error?>
            <img class="card-img-top" src="images/<?=$image?>" alt="Card image cap">
            <div class="card-body">
                <h5 class="card-title"><?=$article[0]['title']?></h5>
                <p class="card-text"><?=$article[0]['description']?></p>
                <p class="card-text">views: <?=$visits[0]['counter']?></p>
                <p class="card-text">id; <?=$article[0]['id']?></p>
                <a href="update.php?id=<?=$id?>" class="btn btn-primary">update</a> 
                <form action="" method='POST'>
                    <input type="hidden" value='<?=$id?>' name='orderId'>
                    <input type="number" min='1' value='1' name='orderCount'>
                    <input class='btn btn-success' value='addToCart' type="submit" name='addToCart'><?=$haveorder?>
                </form>

                <?=count($comments)?> комментариев <br>
                <form action="" method="POST">
                    <textarea name="text" id="" cols="110" rows="2"></textarea><br>
                    <input type="submit" name="addComment">
                </form>
                <br>

                <?foreach ($comments as $value) {?>
                <div class="comments">
                    <b><?=$value['login']?></b> <?=$value['date']?>
                    <br><?=$value['text']."<hr>"?>
                </div>
                <? } ?>
            </div>
        </div>
    </div>
</div>
    
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>