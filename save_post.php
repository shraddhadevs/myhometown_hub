<?php
session_start();
include("db.php");

if (!isset($_SESSION['user_id'])) {
    echo "error";
    exit();
}

if (isset($_POST['post_id'])) {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $user_id = $_SESSION['user_id'];

    // 1. check weather the user has already saved this post
    $check_query = "SELECT * FROM saves WHERE post_id = '$post_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // if the post is already saved remove it
        $delete_query = "DELETE FROM saves WHERE post_id = '$post_id' AND user_id = '$user_id'";
        mysqli_query($conn, $delete_query);
        echo "removed";
    } else {
        //if the post is not saved create a new entry
        $insert_query = "INSERT INTO saves (post_id, user_id) VALUES ('$post_id', '$user_id')";
        mysqli_query($conn, $insert_query);
        echo "saved";
    }
} else {
    echo "error";
}
?>