<?php
// File: announcement.php
session_start();
date_default_timezone_set('Asia/Kolkata');
include("db.php");

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
    <title>Announcements - Hometown Hub</title>
    
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<!-- 🌐 UNIVERSAL NAVBAR START -->
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
<!-- 🌐 UNIVERSAL NAVBAR END -->

<div class="container" style="max-width: 650px; margin: 40px auto; padding: 0 20px;">

    <!-- Announcement Creation Form Card Component -->
    <div class="card" style="border-top: 5px solid #C85A32; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); background: #fff; margin-bottom: 30px;">
        <h3 style="color: #C85A32; margin-bottom: 15px; font-weight: 600;">📢 Create Village Announcement</h3>
        
        <form action="add_announcement.php" method="POST">
            <input 
                type="text" 
                name="title" 
                placeholder="Announcement Title (e.g. Village Meeting, Health Camp)" 
                required 
                style="width: 100%; padding: 10px; margin-bottom: 12px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box;">
                
            <textarea 
                name="description" 
                placeholder="Write announcement details here..." 
                required 
                style="width: 100%; height: 100px; padding: 10px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; resize: vertical; font-family: inherit;"></textarea>
                
            <button type="submit" style="background: #C85A32; color: white; border: none; padding: 10px 25px; border-radius: 6px; cursor: pointer; font-weight: 600; float: right; margin-top: 12px;">
                Publish
            </button>
            <div style="clear: both;"></div>
        </form>
    </div>

    <h2 style="color: #C85A32; text-align: center; margin-bottom: 20px; font-weight: 600;">
        📢 Recent Village Announcements
    </h2>

    <?php
    // Fetch all village announcements sorted by the latest records
    $query = "SELECT * FROM announcements ORDER BY id DESC";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            ?>
            <!-- Individual Announcement Card Block -->
            <div class="card" style="border-left: 5px solid #C85A32; padding: 20px; margin-bottom: 20px; border-radius: 4px 12px 12px 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.03); background: #fff;">
                <h4 style="color: #C85A32; font-size: 18px; font-weight: 600; margin: 0 0 5px 0;">
                    🎉 <?php echo htmlspecialchars($row['title']); ?>
                </h4>
                <span style="font-size: 11px; color: #888;">
                    Posted on: <?php 
                        $ann_date = isset($row['created_at']) ? $row['created_at'] : 'now';
                        echo date('d M Y, h:i A', strtotime($ann_date)); 
                    ?>
                </span>
       
                <p style="margin-top: 12px; color: #333; line-height: 1.6; font-size: 15px; text-align: justify; margin-bottom: 15px;">
                    <?php echo nl2br(htmlspecialchars($row['description'])); ?>
                </p>

                <!-- Conditional rendering for delete action (Author Only) -->
                <?php if($row['user_id'] == $_SESSION['user_id']): ?>
                    <div style="text-align: right; border-top: 1px dashed #f1f5f9; padding-top: 10px;">
                        <a href="delete_announcement.php?id=<?php echo $row['id']; ?>" 
                           onclick="return confirm('Do you really want to delete this announcement?');" 
                           style="color: #dc2626; text-decoration: none; font-size: 13px; font-weight: 600;">
                            🗑️ Delete Announcement
                        </a>
                    </div>
                <?php endif; ?>
            </div>
              
            <?php
        }
    } else {
        ?>
        <!-- Placeholder Block for Empty Announcement Feed State -->
        <div class='card'>
            <p style='text-align:center; color:#666;'>No announcements available yet.</p>
        </div>
        <?php
    }
    ?>

</div>

</body>
</html>