<?php
session_start();
include("db.php");

// if the user is not logged in, redirect them to the login page
if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

// after the form is submitted, the comment should be stored in the database
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    $post_id = mysqli_real_escape_string($conn, $_POST['post_id']);
    $user_id = $_SESSION['user_id'];
    $comment_text = mysqli_real_escape_string($conn, $_POST['comment_text']);

    if(!empty($comment_text)) {
        // SQL Query
        $query = "INSERT INTO comments (post_id, user_id, comment_text) VALUES ('$post_id', '$user_id', '$comment_text')";
        mysqli_query($conn, $query);
    }
    
    // After posting the comment refresh the home page (home.php)
    header("Location: home.php");
    exit();
}
?>