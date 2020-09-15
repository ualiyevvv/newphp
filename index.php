<?php
require_once("config.php");
require_once("function.php");


if (isset($_COOKIE["bd_create_success"]) && trim($_COOKIE["bd_create_success"])!='') {
    setcookie("bd_create_success",1, time()-10);
    echo "New record created successfully";
}

$conn = connect();
$arr = select($conn);
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

<div class="container">
    <a href="ex.php">add post</a>
    <div class="row col-6">
    <table class="table table-bordered">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">title</th>
      <th scope="col">descr_min</th>
      <th scope="col">image</th>
    </tr>
  </thead>
  <tbody>
   <? foreach ($arr as $key => $value) { 
       $image = $value['image'];
       if (!isset($image) || trim($image)=='') {
        $image = 'default.jpg';
       }
       
    ?>
    <tr>
      <th><?=$value['id']?></th>
      <td><?=$value['title']?></td>
      <td><?=$value['descr_min']?></td>
      <td><img width="70" src="images/<?=$image?>" alt="<?=$image?>"></td>
    </tr>
    <?  } ?>
  </tbody>
</table>
    </div>
</div>
    
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>