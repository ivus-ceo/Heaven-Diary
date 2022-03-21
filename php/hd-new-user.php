<?php

  if (isset($_POST['signup-button'])) {

    require "db.php";

    $user_name = mysqli_real_escape_string($link, $_POST['name']);
    $user_last = mysqli_real_escape_string($link, $_POST['last']);
    $user_email = mysqli_real_escape_string($link, $_POST['email']);
    $user_uid = mysqli_real_escape_string($link, $_POST['login']);
    $user_pwd = mysqli_real_escape_string($link, $_POST['password']);
    $user_sex = mysqli_real_escape_string($link, $_POST['sex']);

    $user_status = uniqid();
    $activation_url = "http://localhost/Heaven-Diary/php/hd-activation?code=".$user_status;

    $user_background = "default-background.png";

    if ($user_sex == "male") {
      $user_image = "default-male.jpg";
    } else {
      $user_image = "default-female.jpg";
    }

    if (empty($user_name) || empty($user_last) || empty($user_email) || empty($user_uid) || empty($user_pwd)) {
      header('Location: .././?error=empty-fields');
      exit();
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL) && !preg_match('/^[a-zA-Z\p{Cyrillic}\d\s\-]+$/u', $user_name)) {
      header('Location: .././?error=invalid-login-email');
      exit();
    } elseif (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
      header('Location: .././?error=invalid-email');
      exit();
    } elseif (!preg_match('/^[a-zA-Z\p{Cyrillic}\d\s\-]+$/u', $user_name)) {
      header('Location: .././?error=invalid-login');
      exit();
    } else {

      $sql = "SELECT * FROM users WHERE user_uid=? OR user_email=?";
      $stmt = mysqli_stmt_init($link);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: .././?error=sql-error');
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "ss", $user_name, $user_email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        $result = mysqli_stmt_num_rows($stmt);
        if ($result > 0) {
          header('Location: .././?error=username-or-email-taken');
          exit();
        } else {

          $sql = "INSERT INTO users (user_status, user_name, user_last, user_sex, user_image, user_background, user_email, user_uid, user_pwd) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
          $stmt = mysqli_stmt_init($link);
          if (!mysqli_stmt_prepare($stmt, $sql)) {
            header('Location: .././?error=sql-error');
            exit();
          } else {
            $user_hashed_pwd = password_hash($user_pwd, PASSWORD_DEFAULT);
            mysqli_stmt_bind_param($stmt, "sssssssss", $user_status, $user_name, $user_last, $user_sex, $user_image, $user_background, $user_email, $user_uid, $user_hashed_pwd);
            mysqli_stmt_execute($stmt);

            require_once 'hd-send-email.php';

            header('Location: .././?signup=success');
            exit();
          }
        }
      }
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);

  } else {
    header('Location: ../Signup');
    exit();
  }

?>
