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
    <section class="home-grid">

        <div class="box-container">

            <div class="profile">

                <?php

                $select_profile = $conn->prepare("SELECT * FROM `admin` WHERE admin_id = ?");
                $select_profile->execute([$admin_id]);
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                ?>

                <div class="proifle_admin">
                    <?php if ($fetch_profile['image'] != '') { ?>
                        <img src="../uploaded_img/<?= $fetch_profile['image']; ?>" class="image_logo" alt="" accept="image/jpg, image/jpeg, image/png, image/webp">
                    <?php } ?>

                    <div class="text_name">
                        <a href="update_profile_manager.php">
                            <h1 class="name_admin"><?= $fetch_profile['name']; ?></h1>
                        </a>
                    </div>
                </div>

            </div>

            <nav class="navbar">

                <a href="manager_category.php"><i class="fas fa-pen"></i> <span>Danh Mục</span></a>
                <a href="add_posts_manager.php"><i class="fas fa-pen"></i> <span>Thêm bài viết</span></a>
                <a href="manager_posts.php"><i class="fas fa-eye"></i> <span>Xem bài viết</span></a>
                <a href="manager_acc_usser.php"><i class="fas fa-user"></i> <span>Tài khoản User</span></a>
                <a href="manager_admin_account.php"><i class="fas fa-user"></i> <span>Tài khoản Author</span></a>
                <a href="../components/admin_logout.php" style="color:var(--red);" onclick="return confirm('Bạn muốn rời khỏi website?');"><i class="fas fa-right-from-bracket"></i><span></span>Đăng xuất</a>
            </nav>

            <div class="flex-btn">
                <a href="admin_login.php" class="option-btn">Đăng nhập</a>
                <a href="register_admin.php" class="option-btn">Đăng Ký</a>
            </div>
            <div class="contact">
                <div>
                    <a href="https://github.com/nthi2003"><i class="fa-brands fa-github icon"></i></a>
                </div>
                <div> <a href="https://www.facebook.com/profile.php?id=100077749772493"><i class="fa-brands fa-facebook icon"></i></a></div>
            </div>
</header>

<div id="menu-btn" class="fas fa-bars"></div>