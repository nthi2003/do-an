<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   <section class="flex">

      <a href="home.php" class="logo">LYNN.

      </a>

      <form action="search.php" method="POST" class="search-form">
         <input type="text" name="search_box" class="box" maxlength="100" placeholder="Tìm kiếm...." required>
         <button type="submit" class="fas fa-search" name="search_btn"></button>
      </form>
      <?php
      $select_profile = $conn->prepare("SELECT * FROM `users` WHERE user_id = ?");
      $select_profile->execute([$user_id]);
      if ($select_profile->rowCount() > 0) {
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
         <div id="user-btn" class="">

            <img class="image_logo logo_user_mini" src="uploaded_img/<?= $fetch_profile['image'];  ?>">

         </div>
         <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="search-btn" class="fas fa-search"></div>

         </div>
      <?php
      }
      ?>

      <nav class="navbar">

         <a href="posts.php"> <i class="fas fa-angle-right"></i> Bài viết</a>
         <a href="login.php"> <i class="fas fa-angle-right"></i> Đăng nhập</a>
         <a href="register.php"> <i class="fas fa-angle-right"></i> Đăng Ký</a>
      </nav>

      <div class="profile">
         <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE user_id = ?");
         $select_profile->execute([$user_id]);
         if ($select_profile->rowCount() > 0) {
            $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
         ?>
            <p class="name"><?= $fetch_profile['name']; ?></p>
            <a href="update.php" class="btn"><i class="fa-solid fa-pen-to-square"></i></a>

            <a href="components/user_logout.php" onclick="return confirm('Đăng xuất khỏi trang web?');" class="delete-btn"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
         <?php
         } else {
         ?>
            <p class="name">Vui Lòng Đăng Nhập!</p>
            <a href="login.php" class="option-btn">login</a>
         <?php
         }
         ?>
      </div>

   </section>

</header>