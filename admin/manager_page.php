<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (isset($_SESSION['admin_type'])) {
    header('location:admin_login.php');
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trang Chủ</title>
    <link rel="stylesheet" href="../css/ss.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_ac.css">
</head>

<body>
    <?php include '../components/manager_header.php' ?>

    <section class="dashboard">
        <h1 class="heading">Trang Chủ</h1>
        <div class="box-container">
            <div class="box">
                <?php
                $select_users = $conn->prepare("SELECT * FROM `users`");
                $select_users->execute();
                $numbers_of_users = $select_users->rowCount();
                ?>
                <h3><?= $numbers_of_users; ?></h3>
                <a href="users_accounts.php" class="btn">Xem danh sách users</a>
            </div>

        </div>
    </section>

    <!-- custom js file link -->
    <script src="../js/admin_script.js"></script>
</body>

</html>