<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
};

if (isset($_POST['submit'])) {

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if ($select_user->rowCount() > 0) {
      $_SESSION['user_id'] = $row['user_id'];
      header('location:home.php');
   } else {
      $message[] = 'Mật khẩu hoặc tài khoản không chính xác!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>


   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="./css/users.css">
   <link rel="stylesheet" href="./css/header_home.css">

</head>

<body>


   <?php include 'components/user_header.php'; ?>


   <section class="form-container">

      <form action="" method="post">
         <h3>Đăng Nhập</h3>
         <input type="email" name="email" required placeholder="nhập email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <input type="password" name="pass" required placeholder="nhập mật khẩu" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
         <p>OR</p>
         <div class="flex_login">
            <div class="ac_login lo_gg">
               <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 16 16">
                  <path fill="#2dadfc" d="M8,0C3.582,0,0,3.582,0,8s3.582,8,8,8s8-3.582,8-8S12.418,0,8,0z"></path>
                  <path fill="#fff" d="M9.082,10.12h2.071l0.326-2.104H9.082V6.868c0-0.875,0.286-1.65,1.104-1.65h1.312V3.383	c-0.23-0.03-0.719-0.099-1.641-0.099c-1.924,0-3.054,1.016-3.054,3.334v1.398H4.824v2.104h1.979v5.781C7.196,15.961,7.592,16,8,16	c0.368,0,0.729-0.033,1.082-0.082V10.12z"></path>
               </svg>
               <div class="text_lo ">Facebook</div>
            </div>
            <div class="ac_login lo_gg">
               <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="20" height="20" viewBox="0 0 48 48">
                  <path fill="#fbc02d" d="M43.611,20.083H42V20H24v8h11.303c-1.649,4.657-6.08,8-11.303,8c-6.627,0-12-5.373-12-12	s5.373-12,12-12c3.059,0,5.842,1.154,7.961,3.039l5.657-5.657C34.046,6.053,29.268,4,24,4C12.955,4,4,12.955,4,24s8.955,20,20,20	s20-8.955,20-20C44,22.659,43.862,21.35,43.611,20.083z"></path>
                  <path fill="#e53935" d="M6.306,14.691l6.571,4.819C14.655,15.108,18.961,12,24,12c3.059,0,5.842,1.154,7.961,3.039	l5.657-5.657C34.046,6.053,29.268,4,24,4C16.318,4,9.656,8.337,6.306,14.691z"></path>
                  <path fill="#4caf50" d="M24,44c5.166,0,9.86-1.977,13.409-5.192l-6.19-5.238C29.211,35.091,26.715,36,24,36	c-5.202,0-9.619-3.317-11.283-7.946l-6.522,5.025C9.505,39.556,16.227,44,24,44z"></path>
                  <path fill="#1565c0" d="M43.611,20.083L43.595,20L42,20H24v8h11.303c-0.792,2.237-2.231,4.166-4.087,5.571	c0.001-0.001,0.002-0.001,0.003-0.002l6.19,5.238C36.971,39.205,44,34,44,24C44,22.659,43.862,21.35,43.611,20.083z"></path>
               </svg>
               <div class="text_lo">Google</div>
            </div>
         </div>
         <input type="submit" value="Đăng Nhập" name="submit" class="btn">
         <p>Bạn chưa có tài khoản ? <a href="register.php">Đăng Ký</a></p>
      </form>

   </section>












   <script src="https://unpkg.com/scrollreveal"></script>
   <script src="./js/script.js"></script>
   <script src="./js/scrollreveal.js"></script>
</body>

</html>