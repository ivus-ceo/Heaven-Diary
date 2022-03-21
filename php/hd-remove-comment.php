<?php

  if (isset($_POST['remove-comment'])) {

    require 'db.php';

    $comment_id = mysqli_real_escape_string($link, $_POST['comment_id']);

    $sql = "DELETE FROM comments WHERE comment_id = ?";

    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../Profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "i", $comment_id);
      mysqli_stmt_execute($stmt);
      header('Location: ../Profile');
      exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);

  } else {
    header('Location: ../Profile');
    exit();
  }

?>
