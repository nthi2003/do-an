<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['delete'])) {

   $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE admin_id = ?");
   $delete_admin->execute([$admin_id]);
   header('location:../components/admin_logout.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Tài Khoản</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

   <link rel="stylesheet" href="../css/admin_ac.css">



</head>

<body>
   <?php include '../components/admin_header.php' ?>
   <?php
   $select_account = $conn->prepare("SELECT * FROM `admin`");
   $select_account->execute();
   ?>
   <h1 class="title_acc">ADMIN LIST</h1>
   <table>
      <thead>
         <tr class="table100-head">
            <th>Name</th>
            <th>Image</th>
            <th>Action</th>
         </tr>
      </thead>
      <tbody>
         <tr>
            <?php foreach ($select_account as $fetch_accounts) {
            ?>
               <td><?php echo $fetch_accounts['name']; ?></td>
               <td> <img src="../uploaded_img/<?php echo $fetch_accounts['image']; ?>" class="image_admin" alt=""></td>
               <?php
               if ($fetch_accounts['admin_id'] == $admin_id) {
               ?>

                  <td>
                     <a href="update_profile.php" class="option-btn" style="margin-bottom: .5rem;">update</a>
                     <form action="" method="POST">

                        <input type="hidden" name="post_id" value="<?= $fetch_accounts['admin_id']; ?>" on>
                        <button type="submit" name="delete" onclick="return confirm('bạn muốn xóa tài khoản?');" class="delete-btn" style="margin-bottom: .5rem;">delete</button>
                     </form>
                  <?php
               }
                  ?>
                  </td>
         </tr>
      </tbody>
   <?php
            }
   ?>

   </table>

   <script src=" ../js/admin_script.js"></script>

</body>

</html>