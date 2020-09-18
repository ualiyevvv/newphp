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

function close($conn)
{
    mysqli_close($conn);
}