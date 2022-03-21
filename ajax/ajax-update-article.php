<?php
  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  $user_id = $_SESSION['id'];
  $article_id = mysqli_real_escape_string($link, $_POST['article_id']);

  $sql = "SELECT * FROM articles WHERE article_id = ?";
  $stmt = mysqli_stmt_init($link);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header('Location: ../profile.php?error=sql-error');
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "i", $article_id);
    mysqli_stmt_execute($stmt);
    $articles = mysqli_stmt_get_result($stmt);
    $article_output = mysqli_fetch_assoc($articles);
  }

  $sql = "SELECT * FROM views WHERE view_aid = ? AND view_uid = ?";
  $stmt = mysqli_stmt_init($link);
  if (!mysqli_stmt_prepare($stmt, $sql)) {
    header('Location: ../profile.php?error=sql-error');
    exit();
  } else {
    mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);
    $views = mysqli_stmt_num_rows($stmt);
  }

  if ($views == 0) {
    $sql = "INSERT INTO views (view_aid, view_uid) VALUES (?, ?)";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "ii", $article_id, $user_id);
      mysqli_stmt_execute($stmt);
    }
  }

?>

<?php
  $time = time() - $article_output['article_time'];

  if ($time <= 60) {
    if ($time <= 1) {
      echo $time." секунда назад";
    } elseif ($time <= 4 && $time > 1 || $time <= 24 && $time >= 22 || $time <= 34 && $time >= 32 || $time <= 44 && $time >= 42 || $time <= 54 && $time >= 52) {
      echo $time." секунды назад";
    } elseif ($time == 21 || $time == 31 || $time == 41 || $time == 51) {
      echo $time." секунду назад";
    } else {
      echo $time." секунд назад";
    }
  } elseif ($time / 60 <= 60) {
    if (floor($time / 60) <= 1) {
      echo floor($time / 60)." минуту назад";
    } elseif (floor($time / 60) <= 4 && floor($time / 60) >= 2 || floor($time / 60) <= 24 && floor($time / 60) >= 22 || floor($time / 60) <= 34 && floor($time / 60) >= 32 || floor($time / 60) <= 44 && floor($time / 60) >= 42 || floor($time / 60) <= 54 && floor($time / 60) >= 52) {
      echo floor($time / 60)." минуты назад";
    } elseif (floor($time / 60) == 21 || floor($time / 60) == 31 || floor($time / 60) == 41 || floor($time / 60) == 51) {
      echo floor($time / 60)." минуту назад";
    } else {
      echo floor($time / 60)." минут назад";
    }
  } elseif ($time / 3600 <= 24) {
    if (floor($time / 3600) <= 1) {
      echo floor($time / 3600)." час назад";
    } elseif (floor($time / 3600) <= 4 && floor($time / 3600) >= 2 || floor($time / 3600) <= 24 && floor($time / 3600) >= 22 || floor($time / 3600) <= 34 && floor($time / 3600) >= 32 || floor($time / 3600) <= 44 && floor($time / 3600) >= 42 || floor($time / 3600) <= 54 && floor($time / 3600) >= 52) {
      echo floor($time / 3600)." часа назад";
    } elseif (floor($time / 3600) == 21 || floor($time / 3600) == 31 || floor($time / 3600) == 41 || floor($time / 3600) == 51) {
      echo floor($time / 3600)." час назад";
    } else {
      echo floor($time / 3600)." часов назад";
    }
  } else {
    $article_date = $article_output['article_date'];
    if (strpos($article_date, "January")) {
      echo str_replace("January", "Января", $article_date);
    } elseif (strpos($article_date, "February")) {
      echo str_replace("February", "Февраля", $article_date);
    } elseif (strpos($article_date, "March")) {
      echo str_replace("March", "Марта", $article_date);
    } elseif (strpos($article_date, "April")) {
      echo str_replace("April", "Апреля", $article_date);
    } elseif (strpos($article_date, "May")) {
      echo str_replace("May", "Мая", $article_date);
    } elseif (strpos($article_date, "June")) {
      echo str_replace("June", "Июня", $article_date);
    } elseif (strpos($article_date, "July")) {
      echo str_replace("July", "Июля", $article_date);
    } elseif (strpos($article_date, "August")) {
      echo str_replace("August", "Августа", $article_date);
    } elseif (strpos($article_date, "September")) {
      echo str_replace("September", "Сентября", $article_date);
    } elseif (strpos($article_date, "October")) {
      echo str_replace("October", "Октября", $article_date);
    } elseif (strpos($article_date, "November")) {
      echo str_replace("November", "Ноября", $article_date);
    } else {
      echo str_replace("December", "Декабря", $article_date);
    }
  }
?>
