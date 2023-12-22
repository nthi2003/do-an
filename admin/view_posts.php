<?php
include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
}

if (isset($_POST['delete'])) {

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
   $delete_comments = $conn->prepare("DELETE FROM `comments` WHERE post_id = ?");
   $delete_comments->execute([$post_id]);
   $message[] = 'post deleted successfully!';
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>posts</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_ac.css">

</head>

<body>

   <?php include '../components/admin_header.php' ?>

   <section class="show-posts">

      <h1 class="heading">Bài viết của Bạn</h1>

      <table>
         <thead>
            <tr class="table100-head">
               <th>Title</th>
               <th>Image</th>
               <th>status</th>
               <th>Like</th>
               <th>Comment</th>
               <th>Action</th>
            </tr>
         </thead>
         <tbody>
            <?php
            $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ?");
            $select_posts->execute([$admin_id]);

            foreach ($select_posts as $fetch_posts) {
               $post_id = $fetch_posts['post_id'];

               $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
               $count_post_comments->execute([$post_id]);
               $total_post_comments = $count_post_comments->rowCount();

               $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
               $count_post_likes->execute([$post_id]);
               $total_post_likes = $count_post_likes->rowCount();
            ?>
               <tr>
                  <form method="post" class="box">
                     <input type="hidden" name="post_id" value="<?= $post_id; ?>">
                     <td>
                        <div class="title"><?= $fetch_posts['title']; ?></div>
                     </td>

                     <?php if ($fetch_posts['image'] != '') { ?>
                        <td><img src="../uploaded_img/<?= $fetch_posts['image']; ?>" class="image" alt=""></td>
                     <?php } ?>



                     <td>
                        <div class="likes"><i class="fas fa-heart"></i><span><?= $total_post_likes; ?></span></div>
                     </td>

                     <td>
                        <div class="comments"><i class="fas fa-comment"></i><span><?= $total_post_comments; ?></span></div>
                     </td>
                     <td>
                        <div class="status" style="padding:20px 20px; border-radius:20px; color:aliceblue; background-color:<?php echo ($fetch_posts['status'] == 'active') ? 'limegreen' : 'coral'; ?>;">
                           <?= $fetch_posts['status']; ?>
                        </div>
                     </td>
                     <td>
                        <div class="flex-btn">
                           <a href="edit_post.php?post_id=<?= $post_id; ?>" class="option-btn">edit</a>
                           <button type="submit" name="delete" class="delete-btn" onclick="return confirm('delete this post?');">delete</button>
                        </div>
                     </td>
                  </form>
               </tr>
            <?php } ?>
         </tbody>

      </table>

      <?php


      ?>
   </section>

   <!-- custom js file link  -->
   <script src="../js/admin_script.js"></script>

</body>

</html>