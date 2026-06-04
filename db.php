<?php

$host = "localhost";
$user = "root";
$password = "root123";
$database = "myhometown";

$conn = mysqli_connect(
    $host,
    $user,
    $password,
    $database
);

if (!$conn) {
    die("Database Connection Failed");
}

?>