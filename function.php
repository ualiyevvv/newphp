<?php

function connect()
{
    $conn = mysqli_connect(SERVERNAME, USERNAME, PASSWORD, DBNAME);
    mysqli_set_charset($conn, "utf8");

    if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

function select($conn,$id)
{
    $sql = "SELECT * FROM info WHERE id = $id";
    $result = mysqli_query($conn, $sql);

    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function update($conn)
{
    $sql = "UPDATE info SET name='baaa' WHERE id=2";
    if (mysqli_query($conn, $sql)) {
    echo "Record updated successfully";
    } else {
    echo "Error updating record: " . mysqli_error($conn);
    }
} 

function insert($conn)
{
    $sql = "INSERT INTO info (name, description, cost, amount, image )
    VALUES ('John', 'Doe', '111', '111', 'john@example.com')";

    if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

function selectPage($conn)
{   
    $offset = 0;
    if (isset($_GET['page']) && trim($_GET['page'])!='') {
        $offset = trim($_GET['page']);
    }
    
    $sql = "SELECT * FROM info LIMIT 5 OFFSET " . $offset*5;
    $result = mysqli_query($conn, $sql);
    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function pagginationCount($conn) 
{   
    $sql = "SELECT * FROM info";
    $total = mysqli_num_rows(mysqli_query($conn, $sql)); // всего записей  
    return ($total/5);
}

function selectAllTags($conn)
{
    $sql = "SELECT DISTINCT(tag) FROM tags";
    $result = mysqli_query($conn, $sql);

    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function selectTag($conn,$tag)
{
    $sql = "SELECT post FROM tags wHERE tag = '" .$tag. "'";
    $result = mysqli_query($conn, $sql);

    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row['post'];
        }
    }
    $sql = "SELECT * FROM info wHERE id in(".join(",",$arr).")";
    $result = mysqli_query($conn, $sql);
    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function selectAllCat($conn)
{
    $sql = "SELECT * FROM category";
    $result = mysqli_query($conn, $sql);

    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function selectCat($conn,$category)
{
    $sql = "SELECT * FROM info wHERE category = " . $category;
    $result = mysqli_query($conn, $sql);

    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function delete($conn,$id)
{
    $sql = "DELETE FROM info wHERE id = " . $id;
    if ($conn->query($sql) === TRUE) {
        $success = 'success delete';
        return $success;
    } 
    else {
        $ere = 'err delete';
        return $ere;
    }
    
}

function generateHash($length = 10) 
{
    $symbol = "qwertyuiopasdfghjklzxcvbnmQWERTYIUGAFHJKLZVCBMN";
    for ($i=0;$i<$length;$i++){
        $hash .= $symbol[rand(0, strlen($symbol)-1)];
    }
    return $hash;
}

function selectVisits($conn,$id)
{
    $sql = "SELECT * FROM visits wHERE post_id = " . $id;
    $result = mysqli_query($conn, $sql);

    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}


function selectComments($conn,$id)
{
    $sql = "SELECT * FROM comments wHERE post_id = " . $id;
    $result = mysqli_query($conn, $sql);

    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}


function selectProfile($conn,$id)
{
    $sql = "SELECT * FROM users wHERE id = " . $id;
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr = $row;
        }
    }
    return $arr;
}

function selectCartOrders($conn,$id)
{
    $sql = "SELECT * FROM `cart` INNER JOIN `info` ON `cart`.`post_id`=`info`.`id` WHERE user_id = $id";
    $result = mysqli_query($conn, $sql);
    
    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr[] = $row;
        }
    }
    return $arr;
}

function checkCountOrder($conn,$id)
{
    $sql = "SELECT * FROM cart wHERE post_id = " . $id;
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row;
}

function selectUserTime($conn,$id)
{
    $sql = "SELECT time FROM users_auth WHERE user_id = " . $id;
    $result = mysqli_query($conn, $sql);

    $arr = [];
    if (mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $arr = $row['time'];
        }
    }
    return $arr;
}

function close($conn)
{
    mysqli_close($conn);
}