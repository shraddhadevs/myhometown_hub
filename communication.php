<?php
// File: communication.php
session_start();

// 🔴 change 1 : Prevent the browser from catching old data(to avoide displaying outdated names)
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

date_default_timezone_set('Asia/Kolkata');

// 🔴 change 2 : security check (if the user is not logged in, stop the code execution at that point)
if(!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
    header("Location: ../index.html");
    exit();
}

include("db.php");
$my_id = $_SESSION['user_id'];

// Check chat type
$chat_type = (isset($_GET['user']) && $_GET['user'] !== 'group') ? 'private' : 'group';
$receiver_id = ($chat_type === 'private') ? mysqli_real_escape_string($conn, $_GET['user']) : null;

// Handle New Message
if($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = mysqli_real_escape_string($conn, $_POST['message']);
    if(!empty(trim($message))) {
        if($chat_type === 'private') {
            $insert_query = "INSERT INTO communication (sender_id, receiver_id, message) VALUES ('$my_id', '$receiver_id', '$message')";
        } else {
            $insert_query = "INSERT INTO communication (sender_id, receiver_id, message) VALUES ('$my_id', NULL, '$message')";
        }
        mysqli_query($conn, $insert_query);
        
        $redirect_url = "communication.php" . ($chat_type === 'private' ? "?user=$receiver_id" : "");
        header("Location: " . $redirect_url);
        exit();
    }
}

// Try to find the correct user table name automatically
$user_table = "users";
$table_check = mysqli_query($conn, "SHOW TABLES LIKE 'users'");
if(mysqli_num_rows($table_check) == 0) {
    $table_check_reg = mysqli_query($conn, "SHOW TABLES LIKE 'registration'");
    if(mysqli_num_rows($table_check_reg) > 0) {
        $user_table = "registration";
    }
}

// Get Chat Partner Name
$chat_partner_name = "👥 Group Discussion";
if($chat_type === 'private') {
    $user_check = mysqli_query($conn, "SELECT fullname FROM $user_table WHERE id = '$receiver_id'");
    if($row = mysqli_fetch_assoc($user_check)) {
        $chat_partner_name = "👤 " . $row['fullname'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Communication - Hometown Hub</title>
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

<div class="container" style="max-width: 950px; margin: 30px auto; padding: 0 20px; display: flex; gap: 20px; font-family: 'Poppins', sans-serif;">

    <div style="width: 30%; background: #fff; padding: 15px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); height: 500px; overflow-y: auto; box-sizing: border-box;">
        <h4 style="color: #1e293b; margin: 0 0 15px 0; border-bottom: 2px solid #f1f5f9; padding-bottom: 10px;">Conversations</h4>
        
        <a href="communication.php?user=group" style="display: block; padding: 12px; margin-bottom: 12px; border-radius: 8px; text-decoration: none; background: <?php echo ($chat_type === 'group') ? '#1a2a6c' : '#f8fafc'; ?>; color: <?php echo ($chat_type === 'group') ? '#fff' : '#1a2a6c'; ?>; font-weight: 600; font-size: 14px;">
            👥 Group Discussion
        </a>

        <h5 style="color: #64748b; margin: 15px 0 8px 0; font-size: 12px; text-transform: uppercase; font-weight: 600;">Direct Messages</h5>
        <div style="display: flex; flex-direction: column; gap: 4px;">
            <?php
            $users_list = mysqli_query($conn, "SELECT id, fullname FROM $user_table WHERE id != '$my_id'");
            if(mysqli_num_rows($users_list) > 0) {
                while($u_row = mysqli_fetch_assoc($users_list)) {
                    $is_active = ($chat_type === 'private' && $receiver_id == $u_row['id']);
                    $bg_color = $is_active ? '#e2e8f0' : '#fff';
                    $font_weight = $is_active ? '600' : '400';
                    echo "<a href='communication.php?user=".$u_row['id']."' style='display: block; padding: 10px; border-radius: 8px; text-decoration: none; color: #334155; background: $bg_color; font-size: 14px; font-weight: $font_weight; border: 1px solid #f1f5f9;'>👤 ".$u_row['fullname']."</a>";
                }
            } else {
                echo "<p style='font-size:12px; color:#94a3b8; text-align:center;'>No other users found.</p>";
            }
            ?>
        </div>
    </div>

    <div style="width: 70%; display: flex; flex-direction: column; height: 500px; background: #fff; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); overflow: hidden; box-sizing: border-box;">
        
        <div style="background: #1a2a6c; color: white; padding: 15px; font-weight: 600; font-size: 16px;">
            <?php echo htmlspecialchars($chat_partner_name); ?>
        </div>

        <div style="padding: 12px 15px; background: #fff; border-bottom: 1px solid #e2e8f0;">
            <form action="communication.php<?php echo $chat_type==='private'?'?user='.$receiver_id:''; ?>" method="POST" style="display: flex; gap: 10px;">
                <input type="text" name="message" placeholder="Type your message here..." required autocomplete="off" style="flex: 1; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; font-family: inherit; font-size: 14px;">
                <button type="submit" style="background: #d4af37; color: #000; border: none; padding: 0 20px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">
                    Send
                </button>
            </form>
        </div>

        <div style="flex: 1; padding: 15px; background: #f8fafc; overflow-y: auto; display: flex; flex-direction: column;">
            <?php
            if($chat_type === 'private') {
                $chat_query = "SELECT communication.*, $user_table.fullname FROM communication JOIN $user_table ON communication.sender_id = $user_table.id WHERE (sender_id = '$my_id' AND receiver_id = '$receiver_id') OR (sender_id = '$receiver_id' AND receiver_id = '$my_id') ORDER BY communication.id ASC";
            } else {
                $chat_query = "SELECT communication.*, $user_table.fullname FROM communication JOIN $user_table ON communication.sender_id = $user_table.id WHERE receiver_id IS NULL ORDER BY communication.id ASC";
            }
            $chat_res = mysqli_query($conn, $chat_query);
            if(mysqli_num_rows($chat_res) > 0) {
                while($c_row = mysqli_fetch_assoc($chat_res)) {
                    $is_me = ($c_row['sender_id'] == $my_id);
                    // the colour of the massege bubble has been changed 
                    $bubble_bg = $is_me ? '#e0e7ff' : '#fff'; 
                    echo "<div style='margin-bottom: 10px; text-align: ".($is_me ? 'right' : 'left').";'>
                            <div style='display: inline-block; max-width: 75%; text-align: left; background: ".$bubble_bg."; padding: 8px 12px; border-radius: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;'>
                                ".(!$is_me ? "<strong style='font-size: 11px; color: #1a2a6c; display: block; margin-bottom: 2px;'>".htmlspecialchars($c_row['fullname'])."</strong>" : "")."
                                <p style='margin: 0; color: #1e293b; font-size: 14px;'>".nl2br(htmlspecialchars($c_row['message']))."</p>
                                <div style='text-align: right; margin-top: 3px;'>
                                    <span style='font-size: 9px; color: #94a3b8;'>".date('h:i A', strtotime($c_row['created_at']))."</span>
                                    ".($is_me ? "<a href='delete_chat.php?id=".$c_row['id'].($chat_type==='private'?'&user='.$receiver_id:'')."' onclick='return confirm(\"Delete?\");' style='color:#ef4444; text-decoration:none; margin-left:8px; font-size:10px;'>🗑️</a>" : "")."
                                </div>
                            </div>
                          </div>";
                }
            } else {
                echo "<p style='text-align:center; color:#94a3b8; font-size: 13px; margin-top: 20px;'>No messages here yet.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>