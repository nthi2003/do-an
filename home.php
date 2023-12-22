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
   <title>Trang chủ</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="./css/users.css">
   <link rel="stylesheet" href="./css/header_home.css">
   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

   <style>
      html,
      body {
         position: relative;
         height: 100%;
      }


      .swiper {
         width: 100%;
         height: 100%;
      }

      .swiper-slide {
         text-align: center;
         font-size: 18px;
         background: #fff;
         display: flex;
         justify-content: center;
         align-items: center;
      }

      .swiper-slide img {
         display: block;
         width: 100%;
         height: 300px;
         object-fit: cover;
      }
   </style>
</head>

<body>

   <?php include 'components/user_header.php'; ?>

   <section class="home-grid">

      <div class="box-container">



         <div class="text_cate">
            <div class="flex-box">

               <?php
               $select_category = $conn->prepare("SELECT * FROM `category`");
               $select_category->execute();
               ?>
               <?php foreach ($select_category as $select_categorys) { ?>
                  <a href="category.php?category=<?= $select_categorys['category_id'] ?>" class="links" style="text-transform: uppercase"> <?= $select_categorys['category'] ?></option></a>

               <?php } ?>
            </div>
         </div>



      </div>
      <div class="swiper mySwiper">
         <div class="swiper-wrapper">
            <div class="swiper-slide">
               <img src="https://i.pinimg.com/564x/a7/2b/0c/a72b0c9618e42683cab4b6c1b415992d.jpg" style=" opacity: 0.4" alt="">
               <div style="position: absolute; color:#000;  padding:20px 20px; border-radius:20px">
                  <div class="title_category" style="margin-top: 200px ; margin-right: 900px; width:100%;">IT</div>
                  <p style=" width: 100%; margin-right: 600px">Cách ngôn ngữ mới, bài viết bài báo cáo, kĩ thuật lập trình</p>
               </div>
            </div>
            <div class="swiper-slide"><img src="https://i.pinimg.com/564x/88/0c/a2/880ca2e18e2a3e125784ab76dd51f6aa.jpg" style=" opacity: 0.4  " alt="">
               <div style="position: absolute; color:#000;padding:20px 20px;border-radius:20px">
                  <div class="title_category" style="margin-top: 200px ; margin-right: 900px; width:100%;">Anime</div>
                  <p style=" width: 100%; margin-right: 600px">Anime top hàng tuần, danh sách nhân vật yêu thích</p>
               </div>
            </div>
            <div class="swiper-slide"><img src="https://i.pinimg.com/564x/33/6b/91/336b915c15c456d29e1b78d4d7ec78c6.jpg" style=" opacity: 0.4" alt="">
               <div style="position: absolute; color:#000;padding:20px 20px;border-radius:20px">
                  <div class="title_category" style="margin-top: 200px ; margin-right: 900px; width:100%;">PET AND ANIMAL</div>
                  <p style=" width: 100%; margin-right: 600px">Các bài viết liên quan về động vật , thú nuôi , cách chăm sóc hiệu quả</p>
               </div>

            </div>
            <div class="swiper-slide"><img src="https://i.pinimg.com/564x/e6/4d/84/e64d84f2873f0b37c2795f180de437c3.jpg" style=" opacity: 0.4" alt="">
               <div style="position: absolute; color:#000;padding:20px 20px;border-radius:20px ">
                  <div class="title_category" style="margin-top: 200px ; margin-right: 900px; width:100%;">TECHNOLOGY</div>
                  <p style=" width: 100%; margin-right: 600px">Dòng sản phẩm công nghệ mới, cập nhật nhanh chóng</p>
               </div>
            </div>
         </div>
         <div class="swiper-button-next"></div>
         <div class="swiper-button-prev"></div>
      </div>
   </section>

   <section class="posts-container">

      <h1 class="section__title">Bài Viết Mới Nhất</h1>

      <div class="box-container">

         <?php
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE status = ? ORDER BY post_id DESC LIMIT 6");

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

               $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE admin_id = ?");
               $select_admin->execute([$fetch_posts['admin_id']]);
               while ($fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC)) {
         ?>
                  <form class="box" method="post">
                     <input type="hidden" name="post_id" value="<?= $post_id; ?>">

                     <div class="post-admin">


                        <div style="display: flex;">
                           <?php if ($fetch_admin['image'] != '') { ?>
                              <img src="uploaded_img/<?= $fetch_admin['image']; ?>" class="logo_admin" alt="">
                           <?php } ?>


                           <a class="text_name" href="author_posts.php?author=<?= $fetch_admin['name']; ?>"><?= $fetch_posts['name']; ?></a>

                        </div>
                        <div><?= $fetch_posts['date']; ?></div>
                     </div>
                  <?php }; ?>
                  <?php
                  if ($fetch_posts['image'] != '') {
                  ?>
                     <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
                  <?php
                  }
                  ?>

                  <div class="post-title"><?= $fetch_posts['title']; ?></div>
                  <?php
                  $p = strip_tags($fetch_posts['content']);
                  $p = substr($p, 0, 250);
                  ?>
                  <div class="">
                     <?= $p ?>...</div>
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
            echo '<p class="empty">Chưa có bài viết !</p>';
         }
            ?>
      </div>


   </section>
   <section class="ggmap">

      <h2 class="section__title">Liên Hệ</h2>
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3834.088537099107!2d108.15948341301362!3d16.060894750844067!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31421924682e8689%3A0x48eb0bdbeec05215!2zVHLGsOG7nW5nIMSQ4bqhaSBI4buNYyBTxrAgUGjhuqFtIC0gxJDhuqFpIGjhu41jIMSQw6AgTuG6tW5n!5e0!3m2!1svi!2s!4v1703259123280!5m2!1svi!2s" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

   </section>



   <script src="https://unpkg.com/scrollreveal"></script>
   <script src="./js/script.js"></script>
   <script src="./js/scrollreveal.js"></script>
   <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

   <script>
      var swiper = new Swiper(".mySwiper", {
         pagination: {
            el: ".swiper-pagination",
            type: "fraction",
         },
         navigation: {
            nextEl: ".swiper-button-next",
            prevEl: ".swiper-button-prev",
         },
      });
   </script>
</body>

</html>