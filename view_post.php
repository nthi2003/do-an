<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

include 'components/like_post.php';

$get_id = $_GET['post_id'];

if (isset($_POST['add_comment'])) {

   $admin_id = $_POST['admin_id'];
   $admin_id = filter_var($admin_id, FILTER_SANITIZE_STRING);
   $user_name = $_POST['user_name'];
   $user_name = filter_var($user_name, FILTER_SANITIZE_STRING);
   $comment = $_POST['comment'];
   $comment = filter_var($comment, FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ? AND admin_id = ? AND user_id = ? AND comment = ?");
   $verify_comment->execute([$get_id, $admin_id, $user_id, $comment]);

   if ($verify_comment->rowCount() > 0) {
      $message[] = 'nội dung bình luận đã trùng!';
   } else {
      $insert_comment = $conn->prepare("INSERT INTO `comments`(post_id, admin_id, user_id, comment) VALUES(?,?,?,?)");
      $insert_comment->execute([$get_id, $admin_id, $user_id, $comment]);
      $message[] = 'Bình luận đã được thêm!';
   }
}

if (isset($_POST['edit_comment'])) {
   $edit_comment_id = $_POST['edit_comment_id'];
   $edit_comment_id = filter_var($edit_comment_id, FILTER_SANITIZE_STRING);
   $comment_edit_box = $_POST['comment_edit_box'];
   $comment_edit_box = filter_var($comment_edit_box, FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE comment = ? AND id = ?");
   $verify_comment->execute([$comment_edit_box, $edit_comment_id]);

   if ($verify_comment->rowCount() > 0) {
      $message[] = 'comment already added!';
   } else {
      $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
      $update_comment->execute([$comment_edit_box, $edit_comment_id]);
      $message[] = 'Bình luận của bạn đã đc chỉnh sửa!';
   }
}

if (isset($_POST['delete_comment'])) {
   $delete_comment_id = $_POST['comment_id'];
   $delete_comment_id = filter_var($delete_comment_id, FILTER_SANITIZE_STRING);
   $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
   $delete_comment->execute([$delete_comment_id]);
   $message[] = 'Đã xóa bình luận!';
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>view post</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="./css/users.css">
   <link rel="stylesheet" href="./css/header_home.css">

</head>

<body>

   <?php include 'components/user_header.php'; ?>


   <?php
   if (isset($_POST['open_edit_box'])) {
      $comment_id = $_POST['comment_id'];
      $comment_id = filter_var($comment_id, FILTER_SANITIZE_STRING);
   ?>
      <section class="comment-edit-form">

         <?php
         $select_edit_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
         $select_edit_comment->execute([$comment_id]);
         $fetch_edit_comment = $select_edit_comment->fetch(PDO::FETCH_ASSOC);
         ?>
         <form action="" method="POST">
            <input type="hidden" name="edit_comment_id" value="<?= $comment_id; ?>">
            <textarea name="comment_edit_box" required cols="30" rows="10" placeholder="Vui lòng nhập comment"><?= $fetch_edit_comment['comment']; ?></textarea>
            <button type="submit" class="inline-btn" name="edit_comment"><i class="fa-solid fa-pen-to-square"></i></i></button>
            <div class="inline-option-btn" onclick="window.location.href = 'view_post.php?post_id=<?= $get_id; ?>';"><i class="fa-solid fa-ban"></i></div>
         </form>
      </section>
   <?php
   }
   ?>


   <section class="posts-container" style="padding-bottom: 0;">

      <div class="box-container">

         <?php
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE status = ? AND post_id = ?");
         $select_posts->execute(['active', $get_id]);
         if ($select_posts->rowCount() > 0) {
            while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {

               $post_id = $fetch_posts['post_id'];

               $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
               $count_post_comments->execute([$post_id]);
               $total_post_comments = $count_post_comments->rowCount();

               $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
               $count_post_likes->execute([$post_id]);
               $total_post_likes = $count_post_likes->rowCount();

               $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
               $confirm_likes->execute([$user_id, $post_id]);
         ?>
               <form class="box" method="post">
                  <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                  <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
                  <div class="post-admin">
                     <?php
                     $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE admin_id = ?");
                     $select_admin->execute([$fetch_posts['admin_id']]);
                     while ($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)) {
                     ?>


                        <div class="comment-user">
                           <?php if ($fetch_admin['image'] != '') { ?>
                              <img src="uploaded_img/<?= $fetch_admin['image']; ?>" alt="" class="logo_admin">
                           <?php } ?>

                        </div>
                     <?php }; ?>
                     <div>
                        <a style="text-transform: uppercase;" href="author_posts.php?author=<?= $fetch_posts['name']; ?>"><?= $fetch_posts['name']; ?></a>
                        <div><?= $fetch_posts['date']; ?></div>
                     </div>
                  </div>

                  <?php
                  if ($fetch_posts['image'] != '') {
                  ?>
                     <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
                  <?php
                  }
                  ?>
                  <div class="post-title"><?= $fetch_posts['title']; ?></div>
                  <div class="post-content"><?= $fetch_posts['content']; ?></div>
                  <div class="icons">
                     <button type="submit" name="like_post"><i class="fas fa-heart" style="<?php if ($confirm_likes->rowCount() > 0) {
                                                                                                echo 'color:var(--red);';
                                                                                             } ?>  "></i><span><?= $total_post_likes; ?></span></button>
                     <div><i class="fas fa-comment"></i><span><?= $total_post_comments; ?></span></div>

                  </div>

               </form>
         <?php
            }
         } else {
            echo '<p class="empty">no posts found!</p>';
         }
         ?>
      </div>

   </section>

   <section class="comments-container">
      <?php
      if ($user_id != '') {
         $select_admin_id = $conn->prepare("SELECT * FROM `posts` WHERE post_id = ?");
         $select_admin_id->execute([$get_id]);
         $fetch_admin_id = $select_admin_id->fetch(PDO::FETCH_ASSOC);
      ?>
         <form action="" method="post" class="add-comment">
            <input type="hidden" name="admin_id" value="<?= $fetch_admin_id['admin_id']; ?>">
            <input type="hidden" name="user_name" value="<?= $fetch_profile['name']; ?>">

            <div style="display: flex;" class="user"><?php if ($fetch_profile['image'] != '') { ?>
                  <img src="uploaded_img/<?= $fetch_profile['image']; ?>" class="img_user" alt="">
               <?php } ?>
               <div class="text_user">
                  <a href="update.php" class=""><?= $fetch_profile['name']; ?></a>
               </div>
            </div>

            <textarea name="comment" maxlength="1000" class="comment-box" cols="30" rows="10" placeholder="" required></textarea>
            <input type="submit" value="Viết bình luận" class="inline-btn" name="add_comment">
         </form>
      <?php
      } else {
      ?>
         <div class="add-comment">
            <p>Vui lòng đăng nhập!</p>
            <a href="login.php" class="inline-btn">Đăng Nhập</a>
         </div>
      <?php
      }
      ?>
      <div class="user-comments-container">

         <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
         $select_comments->execute([$get_id]);
         if ($select_comments->rowCount() > 0) {
            while ($fetch_comments = $select_comments->fetch(PDO::FETCH_ASSOC)) {
         ?>
               <div class="show-comments" style="<?php if ($fetch_comments['user_id'] == $user_id) {
                                                      echo 'order:-1;';
                                                   } ?>">
                  <?php
                  $select_user = $conn->prepare("SELECT * FROM `users` WHERE user_id = ?");
                  $select_user->execute([$fetch_comments['user_id']]);
                  while ($fetch_user = $select_user->fetch(PDO::FETCH_ASSOC)) {
                  ?>


                     <div class="comment-user">
                        <?php if ($fetch_user['image'] != '') { ?>
                           <img src="uploaded_img/<?= $fetch_user['image']; ?>" class="img_user" alt="">
                        <?php } ?>


                        <div>
                           <p class="name_user"><?= $fetch_user['name']; ?></p>
                           <span class="date_cmt"><?= $fetch_comments['date']; ?></span>
                           <div><?= $fetch_comments['comment']; ?></div>
                        </div>
                     </div>
                  <?php }; ?>
                  <?php
                  if ($fetch_comments['user_id'] == $user_id) {
                  ?>
                     <form action="" method="POST">
                        <input type="hidden" name="comment_id" value="<?= $fetch_comments['id']; ?>">
                        <button type="submit" class="inline-option-btn" name="open_edit_box"><i class="fa-solid fa-pen-to-square"></i></button>
                        <button type="submit" class="inline-delete-btn" name="delete_comment" onclick="return confirm('Bạn chắc chắn muốn xóa bình luận?');"><i class="fa-solid fa-trash"></i></button>
                     </form>
                  <?php
                  }
                  ?>
               </div>
         <?php
            }
         } else {
            echo '<p class="empty">Chưa có Bình luận</p>';
         }
         ?>

      </div>


      </div>

   </section>

   <script src="https://unpkg.com/scrollreveal"></script>
   <script src="./js/script.js"></script>
   <script src="./js/scrollreveal.js"></script>

</body>

</html>