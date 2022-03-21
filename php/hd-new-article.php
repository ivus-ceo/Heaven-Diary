<?php

  if (isset($_POST['article-button'])) {

    require 'db.php';

    date_default_timezone_set('Asia/Omsk');

    $article_uid = mysqli_real_escape_string($link, $_POST['user_id']);
    $article_author = mysqli_real_escape_string($link, $_POST['author']);
    $article_category = mysqli_real_escape_string($link, $_POST['cat']);
    $article_visible = mysqli_real_escape_string($link, $_POST['visible']);
    $article_type = mysqli_real_escape_string($link, $_POST['type']);
    $article_content = $_POST['content'];
    $article_date = date('d F Y H:i');
    $article_time = time();

    $sql = "INSERT INTO articles (article_uid, article_type, article_visible, article_author, article_category, article_date, article_time, article_content) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../Profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "iiisssss", $article_uid, $article_type, $article_visible, $article_author, $article_category, $article_date, $article_time, $article_content);
      mysqli_stmt_execute($stmt);
      //header('Location: ../Profile');
      exit();
    }

    mysqli_stmt_close($stmt);
    mysqli_close($link);

  } else {
    header('Location: ../Profile');
    exit();
  }

?>
