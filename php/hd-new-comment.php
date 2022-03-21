<?php

  if (isset($_POST['comment-button-1']) || isset($_POST['comment-button-2']) || isset($_POST['comment-button-3'])) {

    require 'db.php';

    date_default_timezone_set('Asia/Omsk');

    $comment_aid = mysqli_real_escape_string($link, $_POST['article_id']);
    $comment_uid = mysqli_real_escape_string($link, $_POST['user_id']);
    $comment_ruid = mysqli_real_escape_string($link, $_POST['reply_uid']);
    $comment_rcid = mysqli_real_escape_string($link, $_POST['reply_cid']);
    $comment_author = mysqli_real_escape_string($link, $_POST['author']);
    $comment_avatar = mysqli_real_escape_string($link, $_POST['user_avatar']);
    $comment_content = nl2br($_POST['content']);
    $comment_date = date('d F Y H:i');

    $sql = "INSERT INTO comments (comment_aid, comment_uid, comment_ruid, comment_rcid, comment_author, comment_avatar, comment_date, comment_content) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../Profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "iiiissss", $comment_aid, $comment_uid, $comment_ruid, $comment_rcid, $comment_author, $comment_avatar, $comment_date, $comment_content);
      mysqli_stmt_execute($stmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);

  } else {
    header('Location: ../Profile');
    exit();
  }

?>
