<?php
  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  $article_id = mysqli_real_escape_string($link, $_POST['article_id']);
  $check_article_id = mysqli_real_escape_string($link, $_POST['ch_article_id']);

  if ($check_article_id != $article_id) {
    echo "Неизвестная ошибка";
  } else {
    echo '<div class="save-button-container"><button class="save-button" onclick="ajax_save_edited_article('.$check_article_id.', this)">Сохранить изменения</button></div>';
  }
?>
