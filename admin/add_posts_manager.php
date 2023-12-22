<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

$fetch_profile = [];

// Fetch admin profile information
$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE admin_id = ?");
$select_profile->execute([$admin_id]);
$fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

if (empty($fetch_profile)) {
    // Handle the case where the profile information is not found
    $message[] = 'Error fetching admin profile information.';
}

if (isset($_POST['publish'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $status = 'active';

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
    $select_image->execute([$image, $admin_id]);

    if (isset($image)) {
        if ($select_image->rowCount() > 0 and $image != '') {
            $message[] = 'Tên hình ảnh đã được lặp lại!  ';
        } elseif ($image_size > 2000000) {
            $message[] = 'Kích thước ảnh quá lớn!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $image = '';
    }

    if ($select_image->rowCount() > 0 and $image != '') {
        $message[] = 'Vui lòng đổi tên hình ảnh!';
    } else {
        $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, name, title, content, category_id, image, status) VALUES(?,?,?,?,?,?,?)");
        $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
        $message[] = 'Đã đăng công khai!';
    }
}

if (isset($_POST['draft'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $category = $_POST['category'];
    $category = filter_var($category, FILTER_SANITIZE_STRING);
    $status = 'deactive';

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
    $select_image->execute([$image, $admin_id]);

    if (isset($image)) {
        if ($select_image->rowCount() > 0 and $image != '') {
            $message[] = 'Tên hình ảnh đã được lặp lại!';
        } elseif ($image_size > 2000000) {
            $message[] = 'Kích thước ảnh quá lớn!';
        } else {
            move_uploaded_file($image_tmp_name, $image_folder);
        }
    } else {
        $image = '';
    }

    if ($select_image->rowCount() > 0 and $image != '') {
        $message[] = 'Vui lòng đổi tên hình ảnh!';
    } else {
        $insert_post = $conn->prepare("INSERT INTO `posts`(admin_id, name, title, content, category_id, image, status) VALUES(?,?,?,?,?,?,?)");
        $insert_post->execute([$admin_id, $name, $title, $content, $category, $image, $status]);
        $message[] = 'Đã lưu vào bản nháp';
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

    <?php include '../components/manager_header.php' ?>

    <section class="post-editor">

        <h1 class="heading">Thêm bài đăng mới</h1>

        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="name" value="<?= isset($fetch_profile['name']) ? $fetch_profile['name'] : ''; ?>">
            <p>Tiêu Đề Bài Viết <span>*</span></p>
            <input type="text" name="title" maxlength="100" required placeholder="Nhập Tiêu Đề Bài Viết ...." class="box">
            <p>Nội Dung Bài Viết <span>*</span></p>
            <textarea name="content" class="box" required maxlength="10000" placeholder="Nhập nội dung bài viết..." cols="30" rows="10"></textarea>
            <p>Chuyên Mục Bài Đăng <span>*</span></p>
            <select name="category" class="box" required>
                <option value="" selected disabled>--Chọn Chuyên Mục-- </option>
                <?php
                $select_category = $conn->prepare("SELECT * FROM `category`");
                $select_category->execute();

                while ($fetch_category = $select_category->fetch(PDO::FETCH_ASSOC)) {

                ?>


                    <option value="<?= $fetch_category['category_id'] ?>">
                        <?= $fetch_category['category'] ?></option>
                <?php
                }
                ?>
            </select>
            <p>Hình Ảnh</p>
            <div class="drop_box">
                <input type="file" name="image" class="image" accept="image/jpg, image/jpeg, image/png, image/webp">
            </div>
            <div class="flex-btn">
                <input type="submit" value="Đăng Công Khai" name="publish" class="btn">
                <input type="submit" value="Lưu Vào Bản Nháp" name="draft" class="option-btn">
            </div>
        </form>

    </section>

    <!-- custom js file link  -->
    <script src="../js/admin_script.js"></script>

</body>

</html>