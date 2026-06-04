<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

if(isset($_GET['id'])) {
    $chat_id = mysqli_real_escape_string($conn, $_GET['id']);
    $user_id = $_SESSION['user_id'];

    // security: user can delete only their own masseges
    $query = "DELETE FROM communication WHERE id = '$chat_id' AND sender_id = '$user_id'";
    mysqli_query($conn, $query);
}

// return to the same chat from which the user came
$redirect = "communication.php";
if(isset($_GET['user'])) {
    $redirect .= "?user=" . $_GET['user'];
}
header("Location: " . $redirect);
exit();
?>