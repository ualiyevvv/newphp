<?
$route = $_GET['route'];
if ($route == '' || $route == '/') {
    require_once "main.php";
}
else if ($route == 'admin') {
    require_once "admin.php";
}
elseif ($route == 'create') {
    require_once "ex.php";
}
elseif ($route == 'login') {
    require_once "login.php";
}
else {
    $route = explode("/",$route);
    if ($route[0] == 'update') {
        $_GET['id'] = $route[1];
        require_once "update.php";
    }
    if ($route[0] == 'cat') {
        $_GET['id'] = $route[1];
        require_once "category.php";
    }
    if ($route[0] == 'article') {
        $_GET['id'] = $route[1];
        require_once "article.php";
    }
}



?>