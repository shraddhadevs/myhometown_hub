<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id']))
{
    header("Location: ../index.html");
    exit();
}

if(isset($_POST['title']) && isset($_POST['description'])) {
    
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $user_id = $_SESSION['user_id'];

    // 'announcements' insert data into the table 
    $sql = "INSERT INTO announcements (user_id, title, description) VALUES ('$user_id', '$title', '$description')";

    if(mysqli_query($conn, $sql)) {
        //After successfully saving return to the Announcement page 
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