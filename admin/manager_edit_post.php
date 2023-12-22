<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
}

if (isset($_POST['save'])) {

    // Sử dụng isset để kiểm tra xem 'post_id' có tồn tại không trước khi sử dụng
    $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;

    $title = $_POST['title'];
    $title = filter_var($title, FILTER_SANITIZE_STRING);
    $content = $_POST['content'];
    $content = filter_var($content, FILTER_SANITIZE_STRING);
    $category_id = $_POST['category'];
    $category_id = filter_var($category_id, FILTER_SANITIZE_STRING);
    $status = $_POST['status'];
    $status = filter_var($status, FILTER_SANITIZE_STRING);

    $update_post = $conn->prepare("UPDATE `posts` SET title = ?, content = ?, category_id = ?, status = ? WHERE post_id = ?");
    $update_post->execute([$title, $content, $category_id, $status, $post_id]);

    $message[] = 'Bài viết đã dược cập nhật !';

    $old_image = $_POST['old_image'];
    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_tmp_name = $_FILES['image']['tmp_name'];
    $image_folder = '../uploaded_img/' . $image;

    $select_image = $conn->prepare("SELECT * FROM `posts` WHERE image = ? AND admin_id = ?");
    $select_image->execute([$image, $admin_id]);

    if (!empty($image)) {
        if ($image_size > 2000000) {
            $message[] = 'Ảnh quá lớn Thay đổi ảnh bé hơn 2MB';
        } elseif ($select_image->rowCount() > 0 and $image != '') {
            $message[] = 'Sửa tên ảnh !';
        } else {
            $update_image = $conn->prepare("UPDATE `posts` SET image = ? WHERE post_id = ?");
            move_uploaded_file($image_tmp_name, $image_folder);
            $update_image->execute([$image, $post_id]);
            if ($old_image != $image and $old_image != '') {
                unlink('../uploaded_img/' . $old_image);
            }
            $message[] = 'Ảnh đã được chỉnh sửa!';
        }
    }
}

if (isset($_POST['delete_post'])) {

    $post_id = $_POST['post_id'];
    $post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
    $delete_image = $conn->prepare("SELECT * FROM `posts` WHERE post_id = ?");
    $delete_image->execute([$post_id]);
    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
    if ($fetch_delete_image['image'] != '') {
        unlink('../uploaded_img/' . $fetch_delete_image['image']);
    }
    $delete_post = $conn->prepare("DELETE FROM `posts` WHERE post_id = ?");
    $delete_post->execute([$post_id]);

    $message[] = 'Đã xóa bài viết!';
}

if (isset($_POST['delete_image'])) {

    $empty_image = '';
    $post_id = $_POST['post_id'];
    $post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
    $delete_image = $conn->prepare("SELECT * FROM `posts` WHERE post_id = ?");
    $delete_image->execute([$post_id]);
    $fetch_delete_image = $delete_image->fetch(PDO::FETCH_ASSOC);
    if ($fetch_delete_image['image'] != '') {
        unlink('../uploaded_img/' . $fetch_delete_image['image']);
    }
    $unset_image = $conn->prepare("UPDATE `posts` SET image = ? WHERE post_id = ?");
    $unset_image->execute([$empty_image, $post_id]);
    $message[] = 'Ảnh đã được xóa!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh sửa Bài Viết</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="stylesheet" href="../css/admin_ac.css">


</head>

<body>

    <?php include '../components/manager_header.php' ?>

    <section class="post-editor">

        <h1 class="heading">Chỉnh sửa Bài Viết</h1>

        <?php
        $post_id = isset($_GET['post_id']) ? $_GET['post_id'] : null;
        $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE post_id = ?");
        $select_posts->execute([$post_id]);
        if ($select_posts->rowCount() > 0) {
            while ($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)) {
        ?>
                <form action="" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="old_image" value="<?= $fetch_posts['image']; ?>">
                    <input type="hidden" name="post_id" value="<?= $fetch_posts['post_id']; ?>">
                    <p>Trạng Thái bài viết <span>*</span></p>
                    <select name="status" class="box" required>
                        <option value="<?= $fetch_posts['status']; ?>" selected><?= $fetch_posts['status']; ?></option>
                        <option value="active">active</option>
                        <option value="deactive">deactive</option>
                    </select>
                    <p>Tiêu đề bài viết <span>*</span></p>
                    <input type="text" name="title" maxlength="100" required placeholder="add post title" class="box" value="<?= $fetch_posts['title']; ?>">
                    <p>Nội dung bài viết <span>*</span></p>
                    <textarea name="content" class="box" required maxlength="10000" placeholder="write your content..." cols="30" rows="10"><?= $fetch_posts['content']; ?></textarea>
                    <p>Chuyên Mục Bài Đăng <span>*</span></p>
                    <select name="category" class="box" required>
                        <?php
                        $select_category = $conn->prepare("SELECT * FROM `category`");
                        $select_category->execute();
                        ?>
                        <option value="" selected disabled>--Chọn Chuyên Mục-- </option>
                        <?php foreach ($select_category as $select_categorys) { ?>
                            <option value="<?= $select_categorys['category_id'] ?>">
                                <?= $select_categorys['category'] ?></option>
                        <?php } ?>
                    </select>
                    <p>Ảnh Bài Viết</p>
                    <div class="drop_box">
                        <input type="file" name="image" accept="image/jpg, image/jpeg, image/png, image/webp">
                    </div>
                    <?php if ($fetch_posts['image'] != '') { ?>
                        <img src="../uploaded_img/<?= $fetch_posts['image']; ?>" class="image" alt="">
                        <input type="submit" value="Xóa Ảnh" class="inline-delete-btn" name="delete_image">
                    <?php } ?>
                    <div class="flex-btn">
                        <input type="submit" value="Lưu" name="save" class="btn">
                        <a href="view_posts.php" class="option-btn"><i class="fa-solid fa-arrow-left"></i></a>
                        <div>
                            <input type="submit" value="Xóa" class="delete-btn" name="delete_post">
                        </div>
                    </div>
                </form>

            <?php
            }
        } else {
            echo '<p class="empty">no posts found!</p>';
            ?>
            <div class="flex-btn">
                <a href="view_posts.php" class="option-btn">view posts</a>
                <a href="add_posts.php" class="option-btn">add posts</a>
            </div>
        <?php
        }
        ?>

    </section>

    <!-- custom js file link  -->
    <script src="../js/admin_script.js"></script>

</body>

</html>