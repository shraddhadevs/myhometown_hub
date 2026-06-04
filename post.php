<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $content = mysqli_real_escape_string($conn, $_POST['content']);
    $image_name = "";

    // 📸image uploading logic
    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "uploads/";
        
        // if folde not exist , create it 
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // set unique name of file
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $target_file = $target_dir . $image_name;

        // transfer the file in folder
        move_uploaded_file($_FILES['image']['tmp_name'], $target_file);
    }

    // insert the post in database 
    $query = "INSERT INTO posts (user_id, content, image) VALUES ('$user_id', '$content', '$image_name')";
    
    if(mysqli_query($conn, $query)) {
        header("Location: home.php");
        exit();
    } else {
        die("database error: " . mysqli_error($conn));
    }
}
?>