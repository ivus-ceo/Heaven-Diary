<?php
  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');


  $check_article_id = mysqli_real_escape_string($link, $_POST['ch_article_id']);
  $article_id = mysqli_real_escape_string($link, $_POST['article_id']);
  $user_id = mysqli_real_escape_string($link, $_POST['user_id']);

  if ($user_id != $_SESSION['id'] || $check_article_id != $article_id) {
    header('Location: profile');
    exit();
  } else {

    $sql = "SELECT * FROM likes WHERE like_uid = $user_id AND like_aid = $article_id";
    $like = mysqli_query($link, $sql);
    $like_output = mysqli_fetch_assoc($like);
    if ($like_output['like_aid'] == $article_id && $like_output['like_uid'] == $user_id) {
      $sql = "DELETE FROM likes WHERE like_aid = $article_id AND like_uid = $user_id";
      $result = mysqli_query($link, $sql);
    } else {
      $sql = "INSERT INTO likes (like_uid, like_aid) VALUES ('$user_id', '$article_id')";
      $result = mysqli_query($link, $sql);
    }

    $sql = "SELECT * FROM likes WHERE like_aid = $article_id";
    $like_amount = mysqli_query($link, $sql);

  }

?>

<i class="fad fa-heart"></i><span><?php echo mysqli_num_rows($like_amount); ?></span>
