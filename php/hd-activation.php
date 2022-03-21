<?php

  if (isset($_GET['code']) && !empty($_GET['code'])) {

    require "db.php";

    $user_code = $_GET['code'];
    $sql = "SELECT * FROM users WHERE user_status=?";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: .././?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "s", $user_code);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);

      $user_id = $row['user_id'];
      $user_status = 'active';
      $sql = "UPDATE users SET user_status=? WHERE user_id=?";
      $stmt = mysqli_stmt_init($link);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: .././?error=sql-error');
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "si", $user_status, $user_id);
        mysqli_stmt_execute($stmt);
        header('Location: .././?activation=success');
      }
    }
  }
?>
