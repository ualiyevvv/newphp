<?php
require_once("config.php");
require_once("function.php");
$success = '';

if (isset($_COOKIE["bd_create_success"]) && trim($_COOKIE["bd_create_success"])!='') {
    setcookie("bd_create_success",1, time()-10);
    $success = '<div class="alert alert-success" role="alert">New record created successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}

if (isset($_COOKIE["delete_success"]) && trim($_COOKIE["delete_success"])!='') {
    setcookie("delete_success",1, time()-10);
    $success = '<div class="alert alert-success" role="alert">delete a post successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
}
if (isset($_COOKIE["bd_update_success"]) && trim($_COOKIE["bd_update_success"])!='') {
    setcookie("bd_update_success",1, time()-10);
    $success = '<div class="alert alert-success" role="alert">update a post successfully<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>';
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
    
    ini_set('date.timezone', 'UTC'); 
    $time = time(); 
    $offset = 6; // Допустим, у пользователя смещение относительно Гринвича составляет + 6 часа
    $time += $offset * 3600; // Добавляем  6 часа к времени по Гринвичу

    $userTime = date_create(selectUserTime($conn,$user['id']));
    $date = date_create(date("Y-m-d H:i:s", $time));
    $interval = date_diff( $userTime,$date );
    echo "<br>";
    close($conn);

} 
else {
    setcookie("id",$user['id'], time()-30, "/");
    setcookie("hash",$hash, time()-30,  "/");
    $login_status_error = true;
}

$conn = connect();
$arr = selectPage($conn);
$countPage = pagginationCount($conn);
$tags = selectAllTags($conn);
$categories = selectAllCat($conn);
close($conn);

if (isset($_GET['page']) && trim($_GET['page'])!='') {
    $page = $_GET['page'];
}
else {
    $page = 1;
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
<?php require_once("blocks/header.php"); ?>
<?php echo "Вы находитесь на сайте ".$interval->format('%i минут %s секунд'); ?>
<div class="container">
    <?=$success?>
    реализовать защиту от удаления, выволд в флеш какой именно пост добавлен и удален, выбор категории через аякс-селект, вывод названия и описния и фото и слаг категории, открытие фото, вывод постов с !названием категории(для этого нужен комбинированный запрос), сделать пагинацию для категорий, тегов и т.п <br>
    сделать вывод тегов в инпут при редактировании поста(комбинированные запросы), вывод категории при редактировании поста, вывод тегов и категории при карточке поста. 
    сделать авторизацию на все. сделать уникальные просмотры. настроить роутинг. сделать отзывы, сделать древовидные отвзывы, сделать карму, сделать корзину
    .сделать проверку на айди юзерв в куки и переданное айди через гет запросы <br>
    сделать урезку фото чтобы макс разиер по большой строне не превышал 320пс а затем создавать ее миниатюру
    <br>
    <a href="ex.php">add post</a>
    <br>
    <?if ($login_status_error!=true) {?>
        <a href="profile.php?id=<?=$user['id']?>">profile</a> | <a href="logout.php">logout</a> 
    <? } else {?>
        <a href="login.php">sign in</a> or <a href="register.php">sign up</a>
    <? } ?>
    <br>
    <a href="cart.php?id=<?=$user['id']?>">cart</a>
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
                <th scope="col">views</th>
            </tr>
        </thead>
        <tbody>
        <? foreach ($arr as $key => $value) { 
            $key++;
            $image = $value['image'];
            if (!isset($image) || trim($image)=='') {
                $image = 'default.jpg';
            }
            
            if (is_null($value['views'])) {
                $value['views'] = 0;
            }
            ?>
            <tr>
                <th><?=$key?></th>
                <th><?=$value['id']?></th>
                <td><?=$value['title']?></td>
                <td><?=$value['descr_min']?>...<a href="article.php?id=<?=$value['id']?>">Read more</a></td>
                <td><img width="70" src="images/<?=$image?>" alt="<?=$image?>"></td>
                <td><a href="delete.php?id=<?=$value['id']?>">del</a> | <a href="update.php?id=<?=$value['id']?>">update</a></td>
                <td><?=$value['views']?></td>
            </tr>
            <?  } ?>
        </tbody>
        </table>
        <div class="row col-12 d-flex align-items-center">
            <b>Pages:</b>
            <?php
                for ($i = 0; $i <= $countPage; $i++){
                    echo '<a style="padding:5px;" href="index.php?page='.$i.'">'.$i.'</a>';
                }
            ?>
        </div>
    </div>
    <div class="row col-12 d-flex align-items-center"><b>Tags:</b>
        <?php
            for ($i = 0; $i <= count($tags); $i++){
                echo '<a style="padding:5px;" href="tags.php?tag='.$tags[$i]['tag'].'">'.$tags[$i]['tag'].'</a>';
            }
        ?>
    </div>
    <div class="row col-12 d-flex align-items-center"><b>Categories:</b>
        <?php
            for ($i = 0; $i <= count($categories); $i++){
                echo '<a style="padding:5px;" href="category.php?category='.$categories[$i]['id'].'">'.$categories[$i]['category'].'</a>';
            }
        ?>
    </div>
</div>
    
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>