<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (isset($_SESSION['admin_type']) && $_SESSION['admin_type'] !== 'manager') {
    header('location:admin_login.php');
}

if (isset($_POST['delete'])) {
    $admin_id_to_delete = $_POST['admin_id'];

    // Ensure you have proper validation and sanitation for the admin_id_to_delete variable.

    $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE admin_type = 'author' AND admin_id = ?");
    $delete_admin->execute([$admin_id_to_delete]);
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

    <?php include '../components/manager_header.php' ?>
    <?php
    $select_account = $conn->prepare("SELECT * FROM `admin` WHERE admin_type = 'author'");
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
            <?php foreach ($select_account as $fetch_accounts) { ?>
                <tr>
                    <td><?php echo $fetch_accounts['name']; ?></td>
                    <td> <img src="../uploaded_img/<?php echo $fetch_accounts['image']; ?>" class="image_admin" alt=""></td>
                    <td>
                        <form action="" method="POST">
                            <input type="hidden" name="admin_id" value="<?= $fetch_accounts['admin_id']; ?>">
                            <button type="submit" name="delete" onclick="return confirm('Bạn muốn xóa tài khoản?');" class="delete-btn" style="margin-bottom: .5rem;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>

    <script src="../js/admin_script.js"></script>

</body>

</html>