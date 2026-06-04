<?php
session_start();
include("db.php");

if(isset($_POST['email']) && isset($_POST['password']))
{
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0)
    {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['fullname'] = $row['fullname'];
        $_SESSION['village'] = $row['village'];
        header("Location: home.php");
        exit();
    }
    else
    {
        echo "<script>alert('Invalid Email or Password'); window.location='index.html';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Hometown Hub</title>
    <link rel="stylesheet" href="../css/style.css"> <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="auth-wrapper">
    <div class="auth-card">
        <div style="text-align: center; margin-bottom: 25px;">
            <h2 style="font-size: 26px; color: #1a2a6c; margin-bottom: 10px;">🏛️ Hometown Hub</h2>
            <p style="font-size: 13px; color: #64748b; line-height: 1.4;">
                ✨ A Digital Platform for Village Communication and Community Engagement
            </p>
        </div>

        <form action="login.php" method="POST">
            <label style="font-size: 14px; font-weight: 600;">Email Address</label>
            <input type="email" name="email" required placeholder="Enter your email">
            
            <label style="font-size: 14px; font-weight: 600;">Password</label>
            <input type="password" name="password" required placeholder="Enter your password">
            
            <button type="submit">Login</button>
        </form>

        <div class="switch-form">
            Don't have an account? <a href="register.php">Register here</a>
        </div>
    </div>
</div>

</body>
</html>