<?php
// File: about.php
session_start();

// Redirect to login page if the user is not authenticated
if(!isset($_SESSION['user_id']))
{
    header("Location: ../index.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - Hometown Hub</title>
    
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="navbar">
    <div class="logo" style="display: flex; flex-direction: column; line-height: 1.2;">
        <span style="font-size: 20px; font-weight: 700; letter-spacing: 0.5px;">🏛️ Hometown Hub</span>
        <span style="font-size: 11px; font-weight: 400; opacity: 0.85; letter-spacing: 0.2px; color: #FDFBF7;">
            ✨ A Digital Platform for Village Communication and Community Engagement
        </span>
    </div>
    <div class="nav-links">
        <a href="home.php">Home</a>
        <a href="about.php">About</a>
        <a href="announcement.php">Announcements</a>
        <a href="communication.php">Communication</a>
        <a href="logout.php">Logout</a>
    </div>
</div>
<div class="container" style="max-width: 650px; margin: 40px auto; padding: 0 20px;">

    <div class="card" style="border-top: 6px solid #C85A32; padding: 35px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff;">
        
        <h2 style="color: #C85A32; text-align: center; margin-bottom: 25px; font-weight: 600; font-size: 26px;">
            🏛️ About Hometown Hub
        </h2>

        <p style="text-align: center; font-size: 16px; color: #333; font-weight: 500; margin-bottom: 25px; line-height: 1.6;">
            Hometown Hub is a small community platform created to keep villagers connected with their hometown and people 🏡✨
        </p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">

        <div style="font-size: 15px; color: #555; line-height: 1.7; text-align: justify;">
            <p style="margin-bottom: 15px;">
                In today’s generation, communication between village people is slowly reducing because everyone is busy in studies, jobs and city life. The attraction, bonding and emotional connection towards villages is becoming less day by day 💭
            </p>

            <p style="margin-bottom: 15px;">
                This platform is a small effort to bring everyone together digitally and help people stay connected with their roots 🌱🤝
            </p>

            <p style="margin-bottom: 15px;">
                Through this website villagers can share posts, announcements, events and important updates with the community.
            </p>

            <p style="margin-bottom: 25px;">
                The main purpose of this project is to improve communication among villagers and create one common digital space where everyone can stay updated about their hometown ❤️
            </p>
        </div>

        <div style="background: #F8EEEA; padding: 15px; border-radius: 10px; text-align: center; border: 1px dashed #C85A32; margin-top: 20px;">
            <h3 style="color: #C85A32; font-size: 15px; margin: 0; font-weight: 600;">
                🏛️ One Digital Space For Our Village Community ✨
            </h3>
        </div>

    </div>
</div>

</body>
</html>