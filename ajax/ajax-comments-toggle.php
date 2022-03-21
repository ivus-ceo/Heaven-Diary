<?php
  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  $user_id = $_SESSION['id'];
  $article_id = mysqli_real_escape_string($link, $_POST['article_id']);
  $check_article_id = mysqli_real_escape_string($link, $_POST['ch_article_id']);

  if ($check_article_id != $article_id) {
    header('Location: profile');
    exit();
  } else {

    $sql = "SELECT * FROM articles WHERE article_id = ?";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "i", $article_id);
      mysqli_stmt_execute($stmt);
      $article = mysqli_stmt_get_result($stmt);
      $article_output = mysqli_fetch_assoc($article);
    }

    if ($article_output['article_comments'] == 1) {
      $article_comments = 0;
      $sql = "UPDATE articles SET article_comments = ? WHERE article_id = ?";
      $stmt = mysqli_stmt_init($link);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../profile.php?error=sql-error');
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "ii", $article_comments, $check_article_id);
        mysqli_stmt_execute($stmt);
      }
    } else {
      $article_comments = 1;
      $sql = "UPDATE articles SET article_comments = ? WHERE article_id = ?";
      $stmt = mysqli_stmt_init($link);
      if (!mysqli_stmt_prepare($stmt, $sql)) {
        header('Location: ../profile.php?error=sql-error');
        exit();
      } else {
        mysqli_stmt_bind_param($stmt, "ii", $article_comments, $check_article_id);
        mysqli_stmt_execute($stmt);
      }
    }
  }

  if ($article_output['article_comments'] == 1) { ?>

    <div class="article-comments-container">
    <small>Комментарии:</small>

    <div class="comments-container">
      <div class="comments-start">
        <?php
          $sql = "SELECT * FROM comments WHERE comment_aid = $article_id AND comment_ruid = 0";
          $comments = mysqli_query($link, $sql);
          if (mysqli_num_rows($comments) > 0) {
          while ($comments_output = mysqli_fetch_assoc($comments)):
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
                    echo str_replace("January", "Января", $comment_date);
                  } elseif (strpos($comment_date, "February")) {
                    echo str_replace("February", "Февраля", $comment_date);
                  } elseif (strpos($comment_date, "March")) {
                    echo str_replace("March", "Марта", $comment_date);
                  } elseif (strpos($comment_date, "April")) {
                    echo str_replace("April", "Апреля", $comment_date);
                  } elseif (strpos($comment_date, "May")) {
                    echo str_replace("May", "Мая", $comment_date);
                  } elseif (strpos($comment_date, "June")) {
                    echo str_replace("June", "Июня", $comment_date);
                  } elseif (strpos($comment_date, "July")) {
                    echo str_replace("July", "Июля", $comment_date);
                  } elseif (strpos($comment_date, "August")) {
                    echo str_replace("August", "Августа", $comment_date);
                  } elseif (strpos($comment_date, "September")) {
                    echo str_replace("September", "Сентября", $comment_date);
                  } elseif (strpos($comment_date, "October")) {
                    echo str_replace("October", "Октября", $comment_date);
                  } elseif (strpos($comment_date, "November")) {
                    echo str_replace("November", "Ноября", $comment_date);
                  } else {
                    echo str_replace("December", "Декабря", $comment_date);
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
          endwhile; } else { echo "<small>Комментарии отсутствуют</small>"; }
        ?>
      </div>

      <form onsubmit="ajax_submit_comment_f(event, this)" method="POST">
        <input type="hidden" name="article_id" value="<?php echo $article_output['article_id'] ?>">
        <input type="hidden" name="user_id" value="<?php echo $_SESSION['id'] ?>">
        <input type="hidden" name="user_name" value="<?php echo $_SESSION['name'].' '.$_SESSION['last'] ?>">
        <input type="hidden" name="user_avatar" value="<?php echo $_SESSION['image'] ?>">
        <input type="hidden" name="comment_ruid" value="0">
        <input type="hidden" name="comment_rcid" value="0">

        <textarea name="comment_content" required></textarea>
        <button type="submit" name="button">Оставить комментарий</button>
      </form>
    </div>
  </div>

  <?php } else { } ?>
