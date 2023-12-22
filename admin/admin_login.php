<?php

include '../components/connect.php';

session_start();

if (isset($_POST['submit'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   //loại bỏ các ký tự đặc biệt không an toàn như dấu nháy kép, nháy đơn, dấu chấm phẩy
   // ví dụ nhập quoc thi `` thì sau khi lọc sẽ là quocthi
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND password = ?");
   $select_admin->execute([$name, $pass]);

   if ($select_admin->rowCount() > 0) {
      $fetch_admin_id = $select_admin->fetch(PDO::FETCH_ASSOC);
      if ($fetch_admin_id['admin_type'] == 'author') {
         $_SESSION['admin_id'] = $fetch_admin_id['admin_id'];
         header('location:add_posts.php');
      } elseif ($fetch_admin_id['admin_type'] == 'manager') {
         $_SESSION['admin_id'] = $fetch_admin_id['admin_id'];
         header('location:manager_admin_account.php');
      }
   } else {
      $message[] = 'Tên đăng nhập hoặc mật khẩu không chính xác!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Đăng Nhập</title>

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

      <form action="" method="POST">
         <img src="../Img/Screenshot 2023-12-04 230038.png" alt="">
         <input type="text" name="name" maxlength="20" required placeholder="Nhập tên" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" maxlength="20" required placeholder="Nhập mật khẩu" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="submit" value="Đăng nhập" name="submit" class="btn">
      </form>

   </section>

</body>

</html>