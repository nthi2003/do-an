<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['delete'])) {
    $category_id = $_POST['category_id'];
    $delete_category = $conn->prepare("DELETE FROM `category` WHERE category_id = ?");
    $delete_category->execute([$category_id]);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Mục</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">


    <link rel="stylesheet" href="../css/admin_ac.css">

</head>

<body>

    <?php include '../components/manager_header.php' ?>
    <?php
    $select_category = $conn->prepare("SELECT * FROM `category`");
    $select_category->execute();
    ?>
    <h1 class="title_acc">Danh Sách Danh Mục</h1>
    <div style="display: flex;">
        <h2 style="margin-left: 100px; margin-top: 20px; padding:20px 20px">Tạo Danh Mục</h2>
        <div style=" margin-left: 20px; margin-top: 20px; background-color: #D2E9E9; padding:20px 20px; border-radius: 25px">
            <a href="manager_create_catagory.php">
                <h2>Thêm mới</h2>
            </a>
        </div>
    </div>
    <table>
        <thead>
            <tr class=" table100-head">
                <th>Doanh Mục</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($select_category as $select_categorys) { ?>
                <tr>
                    <td><?php echo $select_categorys['category']; ?></td>

                    <td>
                        <a href="category_update.php" class="option-btn" style="margin-bottom: .5rem;">update</a>
                        <form action="" method="POST">
                            <input type="hidden" name="category_id" value="<?= $select_categorys['category_id']; ?>">
                            <button type="submit" name="delete" onclick="return confirm('Bạn muốn xóa danh mục?');" class="delete-btn" style="margin-bottom: .5rem;">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
        </tbody>

    </table>

    <script src=" ../js/admin_script.js"></script>

</body>

</html>