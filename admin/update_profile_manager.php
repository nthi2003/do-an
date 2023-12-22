<?php

include '../components/connect.php';

session_start();

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];
    $select_profile = $conn->prepare("SELECT name FROM `admin` WHERE admin_id = ?");
    $select_profile->execute([$admin_id]);
    $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
} else {
    $admin_id = '';
    header('location:home.php');
}

if (isset($_POST['submit'])) {

    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);

    $update_image = '';
    $update_image_size = 0;
    $update_image_tmp_name = '';
    $update_image_folder = '';

    if (!empty($_FILES['update_image']['name'])) {
        $update_image = $_FILES['update_image']['name'];
        $update_image_size = $_FILES['update_image']['size'];
        $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
        $update_image_folder = '../uploaded_img/' . $update_image;
    }

    if (!empty($name)) {
        $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE admin_id = ?");
        $update_name->execute([$name, $admin_id]);
    }

    $empty_pass = 'da39a3ee5e6b4b0d3255bfef95601890afd80709';
    $select_prev_pass = $conn->prepare("SELECT password FROM `admin` WHERE admin_id = ?");
    $select_prev_pass->execute([$admin_id]);
    $fetch_prev_pass = $select_prev_pass->fetch(PDO::FETCH_ASSOC);
    $prev_pass = $fetch_prev_pass['password'];
    $old_pass = sha1($_POST['old_pass']);
    $old_pass = filter_var($old_pass, FILTER_SANITIZE_STRING);
    $new_pass = sha1($_POST['new_pass']);
    $new_pass = filter_var($new_pass, FILTER_SANITIZE_STRING);
    $confirm_pass = sha1($_POST['confirm_pass']);
    $confirm_pass = filter_var($confirm_pass, FILTER_SANITIZE_STRING);

    if (!empty($update_image)) {
        if ($update_image_size > 2000000) {
            $message[] = 'Ảnh vượt quá 2MB';
        } else {
            $update_image_query = $conn->prepare("UPDATE `admin` SET image = ? WHERE admin_id = ?");
            $update_image_query->execute([$update_image, $admin_id]);

            if ($update_image_query) {
                move_uploaded_file($update_image_tmp_name, $update_image_folder);
            }

            $message[] = 'Avatar đã được cập nhập!';
        }
    }
    if ($old_pass != $empty_pass) {
        if ($old_pass != $prev_pass) {
            $message[] = 'Mật khẩu cũ không khớp!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = ' ';
        } else {
            if ($new_pass != $empty_pass) {
                $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE admin_id = ?");
                $update_pass->execute([$confirm_pass, $admin_id]);
                $message[] = 'Mật khẩu đã được cập nhật!';
            } else {
                $message[] = 'Vui lòng nhập lại mật khẩu mới!';
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cập Nhật Hồ Sơ</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="../css/admin_ac.css">
</head>

<body style="padding-left: 0 !important;">
    <?php include '../components/admin_header.php' ?>
    <?php
    if (isset($message) && is_array($message)) {
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

        <form action="" method="post" enctype="multipart/form-data">
            <h3>Cập Nhật Hồ Sơ</h3>
            <input type="text" name="name" placeholder="<?= $fetch_profile['name'] ?? ''; ?>" class="box" maxlength="50">
            <input type="password" name="old_pass" placeholder="nhập mật khẩu" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="new_pass" placeholder="nhập mật khẩu mới" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <input type="password" name="confirm_pass" placeholder="nhập lại mật khẩu mới" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
            <div class="drop_box">
                <input type="file" name="update_image" accept="image/jpg, image/jpeg, image/png">
            </div>
            <input type="submit" value="Cập Nhật" name="submit" class="btn">
        </form>
    </section>

    <?php include '../components/footer.php'; ?>
    <script src="js/script.js"></script>
</body>

</html>