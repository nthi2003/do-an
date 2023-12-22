<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['delete_comment'])) {

   $comment_id = $_POST['comment_id'];
   $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);
   $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
   $delete_comment->execute([$comment_id]);
   $message[] = 'comment delete!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bình Luận</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_ac.css">


</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <section class="comments">

      <h1 class="heading">Bình Luận</h1>

      <p class="comment-title">Bình Luận</p>
      <div class="box-container">
         <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE admin_id = ?");
         $select_comments->execute([$admin_id]);
         if ($select_comments->rowCount() > 0) {
            while ($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <?php
               $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE post_id = ?");
               $select_posts->execute([$fetch_comments['post_id']]);
               while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
               ?>
                  <div class="post-title"> from : <span><?= $fetch_posts['title']; ?></span> <a href="read_post.php?post_id=<?= $fetch_posts['post_id']; ?>"></a></div>
               <?php
               }
               ?>
               <?php
               $select_user = $conn->prepare("SELECT * FROM `users` WHERE user_id = ?");
               $select_user->execute([$fetch_comments['user_id']]);
               while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)) {
               ?>
                  <div class="box">
                     <div class="user">
                        <?php if ($fetch_user['image'] != '') { ?>
                           <img src="../uploaded_img/<?= $fetch_user['image']; ?>" class="img_user" alt="">
                        <?php }; ?>
                        <div class="user-info">
                           <span><?= $fetch_user['name']; ?></span>
                           <div><?= $fetch_comments['date']; ?></div>
                        </div>
                     </div>
                  <?php }; ?>

                  <div class="text"><?= $fetch_comments['comment']; ?></div>
                  <form action="" method="POST">
                     <input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
                     <button type="submit" class="inline-delete-btn" name="delete_comment" onclick="return confirm('Bạn muốn xóa bình luận?');"><i class="fa-solid fa-trash"></i></button>
                  </form>
                  </div>
            <?php
            }
         } else {
            echo '<p class="empty">no comments added yet!</p>';
         }
            ?>
      </div>

   </section>


















   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>

</html>