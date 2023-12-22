<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

include 'components/like_post.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
   <link rel="stylesheet" href="./css/users.css">
   <link rel="stylesheet" href="./css/header_home.css">
</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <?php
   if (isset($_POST['search_box']) or isset($_POST['search_btn'])) {
   ?>
      <section class="posts-container">

         <div class="box-container">

            <?php
            $search_box = $_POST['search_box'];
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE title LIKE '%{$search_box}%'  AND status = ?");
            $select_posts->execute(['active']);
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
                        <?php if ($fetch_posts['image'] != '') { ?>
                           <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="logo_admin" alt="">
                        <?php } ?>

                        <div>
                           <a href="author_posts.php?author=<?= $fetch_posts['name']; ?>"><?= $fetch_posts['name']; ?></a>
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
                     <div class="post-content content-150"><?= $fetch_posts['content']; ?></div>
                     <a href="view_post.php?post_id=<?= $post_id; ?>" class="more">Đọc Thêm <i class="fa-solid fa-arrow-right"></i></a>
                     <div class="icons">
                        <button type="submit" name="like_post"><i class="fas fa-heart" style="<?php if ($confirm_likes->rowCount() > 0) {
                                                                                                   echo 'color:var(--red);';
                                                                                                } ?>  "></i><span><?= $total_post_likes; ?></span></button>
                        <a href="view_post.php?post_id=<?= $post_id; ?>"><i class="fas fa-comment" style="color: #000;"></i><span><?= $total_post_comments; ?></span></a>

                     </div>

                  </form>
            <?php
               }
            } else {
               echo '<p class="empty">no result found!</p>';
            }
            ?>
         </div>

      </section>

   <?php
   } else {
      echo '<section><p class="empty">search something!</p></section>';
   }
   ?>


   <script src="https://unpkg.com/scrollreveal"></script>
   <script src="./js/script.js"></script>
   <script src="./js/scrollreveal.js"></script>
</body>

</html>