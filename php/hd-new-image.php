<?php

  //move_uploaded_file($_FILES['file']['tmp_name'], "../uploads/".$_FILES['file']['name']);

  //var_dump($_FILES['file']);
  // or
  //print_r($_FILES['file']);

  include 'db.php';

  session_start();

  if (empty($_FILES['image-avatar'])) {
    $file_name = $_FILES['image-background']['name'];
    $file_TMPname = $_FILES['image-background']['tmp_name'];
    $file_size = $_FILES['image-background']['size'];
    $file_error = $_FILES['image-background']['error'];
    $file_type = $_FILES['image-background']['type'];
    $user_id = $_POST['user_id'];

    $file_ext = explode('.', $file_name);
    $fileActualExt = strtolower(end($file_ext));

    $allowed = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($fileActualExt, $allowed)) {
      if ($file_error == 0) {
        if ($file_size < 100000000) {
          $fileNameNew = $user_id.".".$fileActualExt;
          $fileDestination = '../media/uploads/backgrounds/'.$fileNameNew;
          move_uploaded_file($file_TMPname, $fileDestination);

          $first = "UPDATE users SET user_background='$fileNameNew' WHERE user_id=".$user_id;
          $first_result = mysqli_query($link, $first);

          $second = "UPDATE articles SET article_author_image='$fileNameNew' WHERE article_uid=".$user_id;
          $second_result = mysqli_query($link, $second);

          $third = "UPDATE comments SET comment_avatar='$fileNameNew' WHERE comment_uid=".$user_id;
          $second_result = mysqli_query($link, $third);

          header('Location: ../profile?nocache='.uniqid().'&upload='.uniqid());
          exit();
        } else {
          header('Location: ../profile?error=large-file-size');
        }
      } else {
        header('Location: ../profile?error=file-load');
      }
    } else {
      header('Location: ../profile?error=wrong-fily-format');
    }
  } else {
    $file_name = $_FILES['image-avatar']['name'];
    $file_TMPname = $_FILES['image-avatar']['tmp_name'];
    $file_size = $_FILES['image-avatar']['size'];
    $file_error = $_FILES['image-avatar']['error'];
    $file_type = $_FILES['image-avatar']['type'];
    $user_id = $_POST['user_id'];

    $file_ext = explode('.', $file_name);
    $fileActualExt = strtolower(end($file_ext));

    $allowed = array('jpg', 'jpeg', 'png');

    if (in_array($fileActualExt, $allowed)) {
      if ($file_error == 0) {
        if ($file_size < 100000000) {
          $fileNameNew = $user_id.".".$fileActualExt;
          $fileDestination = '../media/uploads/'.$fileNameNew;
          move_uploaded_file($file_TMPname, $fileDestination);

          $first = "UPDATE users SET user_image='$fileNameNew' WHERE user_id=".$user_id;
          $first_result = mysqli_query($link, $first);

          $_SESSION['image'] = $fileNameNew;

          header('Location: ../profile?nocache='.uniqid().'&upload='.uniqid());
          exit();
        } else {
          header('Location: ../profile?error=large-file-size');
        }
      } else {
        header('Location: ../profile?error=file-load');
      }
    } else {
      header('Location: ../profile?error=wrong-fily-format');
    }
  }

?>
