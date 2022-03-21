<?php

  if (isset($_POST['login-button'])) {

    require 'db.php';

    $user_uid = mysqli_real_escape_string($link, $_POST['login-email']);
    $user_pwd = mysqli_real_escape_string($link, $_POST['password']);

    if (empty($user_uid) || empty($user_pwd)) {
      header('Location: .././?error=empty-fields');
      exit();
    } else {

      $sql = "SELECT * FROM users WHERE user_uid=? OR user_email=?";
      $stmt = mysqli_stmt_init($link);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: .././?error=sql-error');
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "ss", $user_uid, $user_uid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) < 1) {
          header('Location: .././?error=username-not-found');
          exit();
        }

        if ($row = mysqli_fetch_assoc($result)) {
          $user_dehashed_password = password_verify($user_pwd, $row['user_pwd']);
          if ($user_dehashed_password == FALSE) {
            header('Location: .././?error=wrong-password');
            exit();
          } elseif ($row['user_status'] !== "active") {
            header('Location: .././?error=account-inactive');
            exit();
          } elseif ($user_dehashed_password == TRUE) {
            session_start();

            $_SESSION['id'] = $row['user_id'];
            $_SESSION['name'] = $row['user_name'];
            $_SESSION['last'] = $row['user_last'];
            $_SESSION['image'] = $row['user_image'];
            $_SESSION['email'] = $row['user_email'];
            $_SESSION['background'] = $row['user_background'];
            $_SESSION['uid'] = $row['user_uid'];

            header('Location: ../profile');
            exit();
          } else {
            header('Location: .././?error=wrong-password');
            exit();
          }
        }
      }

    }

  } else {
    header('Location: .././');
    exit();
  }

?>
