<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}


if (isset($_POST['update_category'])) {
    $category_id = $_POST['category_id'];
    $category_id = filter_var($category_id, FILTER_SANITIZE_STRING);
    $updated_category = $_POST['updated_category'];
    $updated_category = filter_var($updated_category, FILTER_SANITIZE_STRING);


    $verify_category = $conn->prepare("SELECT * FROM `category` WHERE category = ?");
    $verify_category->execute([$updated_category]);

    if ($verify_category->rowCount() > 0) {
        $message[] = 'Nội dung danh mục đã tồn tại!';
    } else {

        $update_category = $conn->prepare("UPDATE `category` SET category = ? WHERE category_id = ?");
        $update_category->execute([$updated_category, $category_id]);
        $message[] = 'Đã cập nhật danh mục!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Danh Mục</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_ac.css">
</head>

<body>
    <?php include '../components/admin_header.php' ?>

    <section class="post-editor">
        <h1 class="heading">Cập Nhật Danh Mục</h1>

        <?php

        $select_categories = $conn->prepare("SELECT * FROM `category`");
        $select_categories->execute();
        $categories = $select_categories->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <form action="" method="post">
            <label for="category_id">Chọn danh mục cần cập nhật:</label>
            <select name="category_id" required>
                <?php foreach ($categories as $category) : ?>
                    <option value="<?= $category['category_id']; ?>"><?= $category['category']; ?></option>
                <?php endforeach; ?>
            </select>

            <p>Nhập tên danh mục mới <span>*</span></p>
            <input type="text" name="updated_category" maxlength="100" required placeholder="Nhập Tên Danh Mục Mới ...." class="box">

            <div class="flex-btn">
                <input type="submit" value="Cập Nhật" class="inline-btn" name="update_category">
            </div>
        </form>
    </section>


    <script src="../js/admin_script.js"></script>
</body>

</html>