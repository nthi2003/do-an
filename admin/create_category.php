<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}
if (isset($_POST['add_category'])) {


    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);

    $verify_category = $conn->prepare("SELECT * FROM `category` WHERE  category = ?");
    $verify_category->execute([$category]);

    if ($verify_category->rowCount() > 0) {
        $message[] = 'nội dung danh mục đã trùng!';
    } else {
        $insert_category = $conn->prepare("INSERT INTO `category`(category) VALUES(?)");
        $insert_category->execute([$category]);
        $message[] = 'Đã thêm danh mục!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bài Viết</title>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_ac.css">

</head>

<body>


    <?php include '../components/admin_header.php' ?>

    <section class="post-editor">

        <h1 class="heading">Thêm danh mục mới</h1>

        <form action="" method="post" enctype="multipart/form-data">

            <p>Nhập tên Danh mục <span>*</span></p>

            <input type="text" name="category" maxlength="100" required placeholder="Nhập Tên Danh Mục ...." class="box">

            <div class="flex-btn">
                <input type="submit" value="Thêm" class="inline-btn" name="add_category">
            </div>
        </form>

    </section>








    <!-- custom js file link  -->
    <script src="../js/admin_script.js"></script>

</body>

</html>