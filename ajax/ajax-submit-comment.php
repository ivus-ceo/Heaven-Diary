<?php
  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  $data = $_POST['data'];
  parse_str($data, $comment_data);

  $comment_aid = $comment_data['article_id'];
  $comment_uid = $comment_data['user_id'];
  $comment_ruid = $comment_data['comment_ruid'];
  $comment_rcid = $comment_data['comment_rcid'];
  $comment_author = $comment_data['user_name'];
  $comment_avatar = $comment_data['user_avatar'];
  $comment_date = date('d F Y H:i');
  $comment_content = nl2br($comment_data['comment_content']);
  $check_article_id = mysqli_real_escape_string($link, $_POST['ch_article_id']);

  if ($check_article_id != $comment_aid) {
    header('Location: profile');
  } else {
    $sql = "INSERT INTO comments (comment_aid, comment_uid, comment_ruid, comment_rcid, comment_author, comment_avatar, comment_date, comment_content) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "iiiissss", $comment_aid, $comment_uid, $comment_ruid, $comment_rcid, $comment_author, $comment_avatar, $comment_date, $comment_content);
      mysqli_stmt_execute($stmt);
    }
  }
?>

<?php
  $article_id = $comment_aid;
  $sql = "SELECT * FROM comments WHERE comment_aid = $article_id";
  $comments = mysqli_query($link, $sql);
  if (mysqli_num_rows($comments) > 0) {
  while ($comments_output = mysqli_fetch_assoc($comments)):

    $article_uid = $_SESSION['id'];

    $sql = "SELECT * FROM articles WHERE article_uid = ? AND article_id = ?";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "ii", $article_uid, $check_article_id);
      mysqli_stmt_execute($stmt);
      $article = mysqli_stmt_get_result($stmt);
      $article_output = mysqli_fetch_assoc($article);
    }
?>

<div class="user-comment">
  <div class="comment-author">
    <a href="<?php if ($_SESSION['id'] == $comments_output['comment_uid']) { echo 'profile'; } else { echo 'profile?id='.$comments_output['comment_uid']; } ?>">
      <img src="media/uploads/<?php echo $comments_output['comment_avatar'] ?>" alt="Avatar">
    </a>
  </div>

  <div class="comment-content">
    <div class="comment-name-time">
      <p><a href="<?php if ($_SESSION['id'] == $comments_output['comment_uid']) { echo 'profile'; } else { echo 'profile?id='.$comments_output['comment_uid']; } ?>"><?php echo $comments_output['comment_author'] ?></a></p>
      <small>
        <?php
          $comment_date = $comments_output['comment_date'];
          if (strpos($comment_date, "January")) {
            echo str_replace("January", "????????????", $comment_date);
          } elseif (strpos($comment_date, "February")) {
            echo str_replace("February", "??????????????", $comment_date);
          } elseif (strpos($comment_date, "March")) {
            echo str_replace("March", "??????????", $comment_date);
          } elseif (strpos($comment_date, "April")) {
            echo str_replace("April", "????????????", $comment_date);
          } elseif (strpos($comment_date, "May")) {
            echo str_replace("May", "??????", $comment_date);
          } elseif (strpos($comment_date, "June")) {
            echo str_replace("June", "????????", $comment_date);
          } elseif (strpos($comment_date, "July")) {
            echo str_replace("July", "????????", $comment_date);
          } elseif (strpos($comment_date, "August")) {
            echo str_replace("August", "??????????????", $comment_date);
          } elseif (strpos($comment_date, "September")) {
            echo str_replace("September", "????????????????", $comment_date);
          } elseif (strpos($comment_date, "October")) {
            echo str_replace("October", "??????????????", $comment_date);
          } elseif (strpos($comment_date, "November")) {
            echo str_replace("November", "????????????", $comment_date);
          } else {
            echo str_replace("December", "??????????????", $comment_date);
          }
        ?>
      </small>
    </div>
    <p><?php echo $comments_output['comment_content'] ?></p>
  </div>


  <div class="comment-times">
    <?php if ($comments_output['comment_uid'] == $_SESSION['id'] || $article_output['article_uid'] == $_SESSION['id']) { ?>
      <i class="fal fa-times" onclick="ajax_remove_comment_f(<?php echo $comments_output['comment_id'] ?>, <?php echo $comments_output['comment_aid'] ?>, this)"></i>
    <?php } else { ?>
      <i class="fad fa-reply-all" onclick="reply_to_comment('<?php echo $comments_output['comment_author'] ?>', <?php echo $comments_output['comment_uid'] ?>, this)"></i>
    <?php } ?>
  </div>
</div>

<?php
  endwhile; } else { echo "<small>?????????????????????? ??????????????????????</small>"; }
?>
