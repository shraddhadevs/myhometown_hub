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

    // १.check weather the user has already liked this post
    $check_query = "SELECT * FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        // if a like already exists, remove it 
        $delete_query = "DELETE FROM likes WHERE post_id = '$post_id' AND user_id = '$user_id'";
        mysqli_query($conn, $delete_query);
    } else {
        // if no like exists, create a new entry
        $insert_query = "INSERT INTO likes (post_id, user_id) VALUES ('$post_id', '$user_id')";
        mysqli_query($conn, $insert_query);
    }

    // २. count all likes for this posts 
    $count_query = "SELECT COUNT(*) as total_likes FROM likes WHERE post_id = '$post_id'";
    $count_result = mysqli_query($conn, $count_query);
    $count_row = mysqli_fetch_assoc($count_result);

    // send only the total count to the frontend 
    echo $count_row['total_likes'];
} else {
    echo "error";
}
?>