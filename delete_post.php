<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

if(isset($_GET['id'])) {
    $post_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];

    // first delete the photo from folder
    $img_query = "SELECT image FROM posts WHERE id = '$post_id' AND user_id = '$user_id'";
    $img_result = mysqli_query($conn, $img_query);
    
    if($img_row = mysqli_fetch_assoc($img_result)) {
        $image_name = $img_row['image'];
        if(!empty($image_name) && file_exists("uploads/" . $image_name)) {
            unlink("uploads/" . $image_name); 
        }
    }

    //delete the post from database
    $delete_query = "DELETE FROM posts WHERE id = '$post_id' AND user_id = '$user_id'";
    
    if(mysqli_query($conn, $delete_query)) {
        header("Location: home.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
} else {
    header("Location: home.php");
    exit();
}
?>