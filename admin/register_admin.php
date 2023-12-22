<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);
   $cpass = sha1($_POST['cpass']);
   $cpass = filter_var($cpass, FILTER_SANITIZE_STRING);

   // Fix: Use $_POST['admin_type'] instead of $_POST['user_type']
   $admin_type = $_POST['admin_type'];

   if (isset($_FILES['image'])) {
      $image = $_FILES['image']['name'];
      $image_size = $_FILES['image']['size'];
      $image_tmp_name = $_FILES['image']['tmp_name'];
      $image_folder = 'uploaded_img/' . $image;
   } else {
      $image = null;
   }
   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
   $select_admin->execute([$name]);
   $row = $select_admin->fetch(PDO::FETCH_ASSOC);

   if ($select_admin->rowCount() > 0) {
      $message[] = 'Tên đã tồn tại';
   } else {
      if ($pass != $cpass) {
         $message[] = 'Mật khẩu xác nhận không khớp!';
      } else {
         $insert_admin = $conn->prepare("INSERT INTO `admin` (name, password, image, admin_type) VALUES (?, ?, ?, ?)");

         if (isset($image)) {
            $insert_admin->execute([$name, $cpass, $image, $admin_type]);

            if ($image_size > 2000000) {
               $message[] = 'Ảnh quá lớn. Thay đổi ảnh bé hơn 2MB!';
            } else {
               move_uploaded_file($image_tmp_name, $image_folder);
            }
         } else {
            // Fix: Provide a default value for admin_type if not set
            $insert_admin->execute([$name, $cpass, null, $admin_type]);
         }

         $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
         $select_admin->execute([$name, $pass]);
         $row = $select_admin->fetch(PDO::FETCH_ASSOC);

         if ($select_admin->rowCount() > 0) {
            $_SESSION['admin_id'] = $row['admin_id'];
            header('location:admin_login.php');
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng Ký</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


   <link rel="stylesheet" href="../css/admin_ac.css">


</head>

<body style="padding-left: 0 !important;">

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
   <section class="form-container">

      <form action="" method="post" enctype="multipart/form-data">
         <img src="../Img/Screenshot 2023-12-04 230038.png" alt="">
         <input type="text" name="name" required placeholder="Nhập tên" class="box" maxlength="50">

         <input type="password" name="pass" required placeholder="Nhập mật khẩu" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="cpass" required placeholder="Nhập lại mật khẩu" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <select name="admin_type" class="box">
            <option value="author">author</option>
            <option value="manager">manager</option>
         </select>
         <div class="drop_box">
            <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp">
         </div>

         <input type="submit" value="Đăng Ký" name="submit" class="btn">

         <p>Bạn đã có tài khoản? <a href="admin_login.php">Đăng Nhập</a></p>
      </form>

   </section>


   <script src="js/script.js"></script>

</body>

</html>