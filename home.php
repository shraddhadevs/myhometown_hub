<?php
session_start();
include("db.php");

// if hte user in not logged in, redirect them to the login page 
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
    <title>Hometown Hub - Digital Platform</title>
    
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="navbar" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 30px; background: #1e3a8a; color: white;">
    
    <div class="logo-container" style="display: flex; flex-direction: column; gap: 2px; text-align: left;">
        <div class="logo" style="font-size: 22px; font-weight: 600; display: flex; align-items: center; gap: 8px; color: #ffffff; line-height: 1.2;">
            🏛️ Hometown Hub
        </div>
        <div class="sub-title" style="font-size: 12px; color: #cbd5e1; font-weight: 400; opacity: 0.95; line-height: 1.2;">
            ✨ A Digital Platform for Village Communication and Community Engagement
        </div>
    </div>
   
    <div class="nav-links" style="display: flex; gap: 18px; align-items: center;">
        <a href="home.php" style="color: white; text-decoration: none; font-weight: 600; font-size: 14px;">Home</a>
        <a href="about.php" style="color: white; text-decoration: none; font-weight: 600; font-size: 14px;">About</a>
        <a href="announcement.php" style="color: white; text-decoration: none; font-weight: 600; font-size: 14px;">Announcements</a>
        <a href="communication.php" style="color: white; text-decoration: none; font-weight: 600; font-size: 14px;">Communication</a>
        <a href="logout.php" style="color: white; text-decoration: none; font-weight: 600; font-size: 14px;">Logout</a>
    </div>
</div>

<div class="container">

    <div class="card" style="border-top: 5px solid #2F5233; background: linear-gradient(to right, #ffffff, #fefefe);">
        <h2 style="color: #2F5233; margin-bottom: 5px;">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?> 👋</h2>
        <p style="color: #64748b; font-size: 14px;">Stay connected with your village community 🌱</p>
    </div>

    <div class="card">
        <h3 style="color: #2F5233; margin-bottom: 15px; font-weight: 600;">📝 Create Post</h3>
        <form action="post.php" method="POST" enctype="multipart/form-data">
            <textarea 
                name="content" 
                placeholder="Share something with your village..." 
                required 
                style="height: 100px; width: 100%; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; box-sizing: border-box; resize: none; margin-bottom: 15px; font-family: inherit;"></textarea>
            
            <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">
                <input type="file" name="image" style="width: auto; margin: 0; padding: 5px;">
                <button type="submit" style="width: auto; padding: 10px 30px; background: #2F5233; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer;">Post</button>
            </div>
        </form>
    </div>

    <h3 style="color: #2F5233; margin: 30px 0 15px 5px; font-weight: 600;">🌾 Recent Village Updates</h3>

    <?php
    // retrieve all posts along with the users name from the database in reverse chronological order(newest first)
    $query = "SELECT posts.*, users.fullname, users.village 
              FROM posts 
              JOIN users ON posts.user_id = users.id 
              ORDER BY posts.id DESC";

    $posts_result = mysqli_query($conn, $query);

    if(mysqli_num_rows($posts_result) > 0) {
        while($post = mysqli_fetch_assoc($posts_result)) {
            ?>
            <div class="card animate-post" style="background: #fff; padding: 25px; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 20px; box-sizing: border-box;">
                <div style="border-bottom: 1px solid #f1f5f9; padding-bottom: 10px; margin-bottom: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <h4 style="color: #2F5233; font-size: 16px; font-weight: 600; margin: 0;">
                            👤 <?php echo htmlspecialchars($post['fullname']); ?>
                        </h4>
                        <span style="font-size: 11px; color: #94a3b8;">📍 गाव: <?php echo htmlspecialchars($post['village']); ?></span>
                    </div>
                    <span style="font-size: 11px; color: #94a3b8;">
                        <?php echo date('d M Y', strtotime($post['created_at'])); ?>
                    </span>
                </div>
                
                <p style="font-size: 15px; color: #334155; line-height: 1.6; text-align: left; white-space: pre-line; margin-bottom: 15px;">
                    <?php echo htmlspecialchars($post['content']); ?>
                </p>

                <?php if(!empty($post['image']) && file_exists("uploads/" . $post['image'])): ?>
                    <div style="margin-bottom: 15px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0;">
                        <img src="uploads/<?php echo htmlspecialchars($post['image']); ?>" class="post-img" alt="Post Image" style="width: 100%; max-height: 450px; object-fit: contain; display: block; margin: 0 auto;">
                    </div>
                <?php endif; ?>

                <hr style="border: 0; border-top: 1px solid #f1f5f9; margin-bottom: 15px;">

                <div class="post-actions" style="display: flex; gap: 15px; align-items: center; margin-bottom: 15px; position: relative; z-index: 10;">
                    <button class="like-btn" data-id="<?php echo $post['id']; ?>" style="background: #d4af37; color: #000; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">
                        ❤️ <span class="like-count">0</span> Likes
                    </button>
                    
                    <button class="comment-toggle-btn" style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">
                        💬 Comment
                    </button>
                    
                    <button class="save-btn" data-id="<?php echo $post['id']; ?>" style="background: #d4af37; color: #000; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 14px;">
                        🔖 Save Post
                    </button>
                    
                    <?php if($post['user_id'] == $_SESSION['user_id']) { ?>
                        <a href="delete_post.php?id=<?php echo $post['id']; ?>" 
                           onclick="return confirm('तुम्हाला खरंच ही POST डिलीट करायची आहे का?');" 
                           style="color: #dc2626; text-decoration: none; font-size: 14px; font-weight: 600; margin-left: auto; display: flex; align-items: center; gap: 4px;">
                            🗑️ Delete
                        </a>
                    <?php } ?>
                </div>

                <div class="comment-section" style="background: #f8fafc; padding: 15px; border-radius: 8px; width: 100%; box-sizing: border-box; margin-top: 15px; border: 1px solid #e2e8f0;">
                    
                    <form action="comment.php" method="POST" style="display: flex; gap: 10px; margin-bottom: 12px; width: 100%;">
                        <input type="hidden" name="post_id" value="<?php echo $post['id']; ?>">
                        <input type="text" name="comment_text" placeholder="Write a comment..." required 
                               style="flex: 1; padding: 10px 12px; border: 1px solid #cbd5e1; border-radius: 6px; font-size: 13px; margin: 0; box-sizing: border-box; font-family: inherit;">
                        <button type="submit" name="submit_comment" 
                                style="width: auto; padding: 10px 25px; font-size: 13px; background: #2F5233; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; margin: 0;">Reply</button>
                    </form>

                    <div class="comments-list" style="max-height: 200px; overflow-y: auto; width: 100%;">
                        <?php
                        $pid = $post['id'];
                        $c_query = "SELECT comments.*, users.fullname FROM comments 
                                    JOIN users ON comments.user_id = users.id 
                                    WHERE comments.post_id = '$pid' ORDER BY comments.id DESC";
                        $c_result = mysqli_query($conn, $c_query);

                        if(mysqli_num_rows($c_result) > 0) {
                            while($comment = mysqli_fetch_assoc($c_result)) {
                                ?>
                                <div style="font-size: 13px; margin-bottom: 8px; padding-bottom: 6px; border-bottom: 1px dashed #e2e8f0; text-align: left;">
                                    <strong style="color: #2F5233;">👤 <?php echo htmlspecialchars($comment['fullname']); ?>:</strong> 
                                    <span style="color: #334155;"><?php echo htmlspecialchars($comment['comment_text']); ?></span>
                                </div>
                                <?php
                            }
                        } else {
                            echo "<p style='font-size: 12px; color: #94a3b8; margin: 5px 0 0 0; text-align: left;'>No comments yet.</p>";
                        }
                        ?>
                    </div>
                </div>

            </div> 
            <?php
        }
    } else {
        ?>
        <div class="card" style="text-align: center; padding: 40px 20px;">
            <p style="color: #64748b; font-size: 15px;">No posts available yet. Be the first one to share something! 🚀</p>
        </div>
        <?php
    }
    ?>

</div>

<script src="../script.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    
    // 1. Like Functionality
    document.querySelectorAll('.like-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let btn = this;
            let postId = btn.getAttribute('data-id');
            
            fetch('like_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'post_id=' + postId
            })
            .then(res => res.text())
            .then(data => {
                if(data.trim() !== "error") {
                    btn.querySelector('.like-count').innerText = data;
                    btn.style.background = '#ef4444';
                    btn.style.color = '#fff';
                }
            }).catch(err => console.error(err));
        });
    });

    // 2. Save Functionality
    document.querySelectorAll('.save-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            let btn = this;
            let postId = btn.getAttribute('data-id');
            
            fetch('save_post.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: 'post_id=' + postId
            })
            .then(res => res.text())
            .then(data => {
                if(data.trim() === "saved") {
                    btn.innerHTML = '🔖 Saved';
                    btn.style.background = '#1e293b';
                    btn.style.color = '#fff';
                }
            }).catch(err => console.error(err));
        });
    });
});
</script>
</body>
</html>