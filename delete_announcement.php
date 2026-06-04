<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

if (isset($_GET['id'])) {
    $announcement_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];

    $query = "DELETE FROM announcements WHERE id = '$announcement_id' AND user_id = '$user_id'";
    
    if (mysqli_query($conn, $query)) {
        header("Location: announcement.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: announcement.php");
    exit();
}
?>