<?php

  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  $check_article_id = mysqli_real_escape_string($link, $_POST['ch_article_id']);
  $article_id = mysqli_real_escape_string($link, $_POST['article_id']);

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
      mysqli_stmt_bind_param($stmt, "i", $check_article_id);
      mysqli_stmt_execute($stmt);
      $articles = mysqli_stmt_get_result($stmt);
      $article_output = mysqli_fetch_assoc($articles);

      if (substr($article_output['article_image'], 0, 4) == 'http') {} elseif ($article_output['article_type'] == 2) {
        $path = '../media/uploads/backgrounds/'.$article_output['article_image'];
        if (!unlink($path)) {
          echo "Ошибка";
        }
      } elseif ($article_output['article_type'] == 1) {
        $doc = new DOMDocument();
        $doc->loadHTML($article_output['article_content']);
        $xpath = new DOMXPath($doc);
        $images = $xpath->evaluate("//img");

        foreach ($images as $image) {
          $path = "../".$image->getAttribute('src');
          if (!unlink($path)) {
            echo "Ошибка";
          }
        }
      }
    }

    $sql = "DELETE FROM articles WHERE article_id = ?";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "i", $check_article_id);
      mysqli_stmt_execute($stmt);
    }

    $sql = "DELETE FROM likes WHERE like_aid = ?";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "i", $check_article_id);
      mysqli_stmt_execute($stmt);
    }

    $sql = "DELETE FROM comments WHERE comment_aid = ?";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "i", $check_article_id);
      mysqli_stmt_execute($stmt);
    }

    $sql = "DELETE FROM views WHERE view_aid = ?";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "i", $check_article_id);
      mysqli_stmt_execute($stmt);
    }
  }

  $id = $_SESSION['id'];

  $sql = "SELECT * FROM articles WHERE article_uid = $id AND article_visible = 1 ORDER BY article_id DESC";
  $articles = mysqli_query($link, $sql);
?>

<?php if (mysqli_num_rows($articles) > 0) { ?>

<?php while($article_output = mysqli_fetch_assoc($articles)): ?>

<?php if ($article_output['article_type'] == 1) { ?>

  <article>
    <div class="small-article" dt-article-control="<?php echo $article_output['article_id'] ?>" dt-article-id="<?php echo $article_output['article_id'] ?>">
      <nav>
        <div class="article-author">
          <a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>"><img src="media/uploads/<?php echo $article_output['article_author_image'] ?>" alt="Author"></a>
          <div class="name-time">
            <p><a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>"><?php echo $article_output['article_author'] ?></a></p>
            <small>
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
            </small>
          </div>
        </div>

        <div class="article-options">
          <i class="fad fa-ellipsis-h" onmouseover="openDropdown(this)" onmouseout="hideDropdown(this)"></i>
          <div class="article-dropdown">
            <?php if ($id == $_SESSION['id']) { ?>
              <a onclick="ajax_remove_article(<?php echo $article_output['article_id'] ?>, this)">Удалить</a>
              <a onclick="#">Пожаловаться</a>
              <a onclick="ajax_edit_article(<?php echo $article_output['article_id'] ?>, this)">Редактировать</a>
              <a onclick="ajax_toggle_comments(<?php echo $article_output['article_id'] ?>, this)"><?php if ($article_output['article_comments'] == 0) { echo 'Отключить комментарии'; } else { echo 'Включить комментарии'; } ?></a>
            <?php } else { ?>
              <a onclick="#">Пожаловаться</a>
            <?php } ?>
          </div>
        </div>
      </nav>

      <main>
        <div class="small-article-content">
          <?php echo $article_output['article_content'] ?>
        </div>
      </main>

      <?php $article_id = $article_output['article_id']; ?>

      <footer>
        <?php
          $sql = "SELECT * FROM likes WHERE like_aid = $article_id";
          $like = mysqli_query($link, $sql);
          $like_output = mysqli_fetch_assoc($like);

          $sql = "SELECT * FROM views WHERE view_aid = $article_id";
          $view = mysqli_query($link, $sql);

          $sql = "SELECT * FROM comments WHERE comment_aid = $article_id";
          $comments = mysqli_query($link, $sql);
        ?>

        <div class="article-options">
          <div onclick="ajax_toggle_like(<?php echo $article_output['article_id'] ?>, <?php echo $_SESSION['id'] ?>, this)" class="<?php while ($like_output = mysqli_fetch_assoc($like)) { if ($like_output['like_uid'] == $_SESSION['id']) { echo "like-total"; } } ?>" title="Понравилось"><i class="fad fa-heart"></i><span><?php echo mysqli_num_rows($like); ?></span></div>
          <div class="comments-total" title="Количество комментариев"><i class="fad fa-comments-alt"></i><span><?php echo mysqli_num_rows($comments); ?></span></div>
          <?php if ($article_output['article_visible'] == 0) { ?>
          <div class="views-total" title="Закрыто"><i class="fad fa-lock-alt"></i><span></span></div>
          <?php } else { ?>
          <div class="views-total" title="Количество просмотров"><i class="fad fa-eye"></i><span><?php echo mysqli_num_rows($view); ?></span></div>
          <?php } ?>
          <div class="words-total" title="Количество слов"><i class="fad fa-font"></i><span><?php echo str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя"); ?></span></div>
          <?php if (floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) > 0) { ?>
          <div class="read-total" title="Время чтения"><i class="fad fa-book-open"></i><span>
            <?php
              if (floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 1) {
                echo floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60)." минуту";
              } elseif (floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 4 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 2 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 24 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 22 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 34 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 32 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 44 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 42 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 54 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 52) {
                echo floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60)." минуты";
              } elseif (floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) == 21 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) == 31 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) == 41 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) == 51) {
                echo floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60)." минуту";
              } else {
                echo floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60)." минут";
              }
            ?>
          </span></div>
          <?php } ?>
        </div>

        <div class="article-comments">
          <?php if ($article_output['article_comments'] == 0): ?>
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
          <?php endif; ?>
        </div>
      </footer>
    </div>
  </article>

<?php } elseif ($article_output['article_type'] == 2) { ?>

  <article>
    <div class="large-article" dt-article-control="<?php echo $article_output['article_id'] ?>" dt-article-id="<?php echo $article_output['article_id'] ?>">
      <nav>
        <div class="article-author">
          <a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>"><img src="media/uploads/<?php echo $article_output['article_author_image'] ?>" alt="Author"></a>
          <div class="name-time">
            <p><a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>"><?php echo $article_output['article_author'] ?></a></p>
            <small>
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
            </small>
          </div>
        </div>

        <div class="article-options">
          <i class="fad fa-ellipsis-h" onmouseover="openDropdown(this)" onmouseout="hideDropdown(this)"></i>
          <div class="article-dropdown">
            <?php if ($id == $_SESSION['id']) { ?>
              <a onclick="ajax_remove_article(<?php echo $article_output['article_id'] ?>, this)">Удалить</a>
              <a onclick="#">Пожаловаться</a>
              <a onclick="ajax_edit_large_article(<?php echo $article_output['article_id'] ?>, this)">Редактировать</a>
              <a onclick="ajax_toggle_comments(<?php echo $article_output['article_id'] ?>, this)"><?php if ($article_output['article_comments'] == 0) { echo 'Отключить комментарии'; } else { echo 'Включить комментарии'; } ?></a>
            <?php } else { ?>
              <a onclick="#">Пожаловаться</a>
            <?php } ?>
          </div>
        </div>
      </nav>

      <main>
        <div class="large-article-content" style="background-image: url(<?php if (substr($article_output['article_image'], 0, 4) == 'http') { echo $article_output['article_image']; } else { echo 'media/uploads/backgrounds/'.$article_output['article_image']; } ?>)">
          <?php
            if (!function_exists('ru2lat')) {
              function ru2lat($str) {
                $tr = array(
                "А"=>"a", "Б"=>"b", "В"=>"v", "Г"=>"g", "Д"=>"d",
                "Е"=>"e", "Ё"=>"yo", "Ж"=>"zh", "З"=>"z", "И"=>"i",
                "Й"=>"j", "К"=>"k", "Л"=>"l", "М"=>"m", "Н"=>"n",
                "О"=>"o", "П"=>"p", "Р"=>"r", "С"=>"s", "Т"=>"t",
                "У"=>"u", "Ф"=>"f", "Х"=>"kh", "Ц"=>"ts", "Ч"=>"ch",
                "Ш"=>"sh", "Щ"=>"sch", "Ъ"=>"", "Ы"=>"y", "Ь"=>"",
                "Э"=>"e", "Ю"=>"yu", "Я"=>"ya", "а"=>"a", "б"=>"b",
                "в"=>"v", "г"=>"g", "д"=>"d", "е"=>"e", "ё"=>"yo",
                "ж"=>"zh", "з"=>"z", "и"=>"i", "й"=>"j", "к"=>"k",
                "л"=>"l", "м"=>"m", "н"=>"n", "о"=>"o", "п"=>"p",
                "р"=>"r", "с"=>"s", "т"=>"t", "у"=>"u", "ф"=>"f",
                "х"=>"kh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"sch",
                "ъ"=>"", "ы"=>"y", "ь"=>"", "э"=>"e", "ю"=>"yu",
                "я"=>"ya", " "=>"-", "."=>"", ","=>"", "/"=>"-",
                ":"=>"", ";"=>"","—"=>"", "–"=>"-"
                );
                return strtr($str,$tr);
              }
            }
          ?>
          <a href="article?id=<?php echo $article_output['article_id'] ?>&title=<?php echo ru2lat(strtolower($article_output['article_title'])) ?>&time=<?php echo $article_output['article_time'] ?>">
            <div class="large-article-background">
              <h3><?php echo $article_output['article_title'] ?></h3>
            </div>
          </a>
        </div>
      </main>

      <?php $article_id = $article_output['article_id']; ?>

      <footer>
        <?php
          $sql = "SELECT * FROM likes WHERE like_aid = $article_id";
          $like = mysqli_query($link, $sql);
          $like_output = mysqli_fetch_assoc($like);

          $sql = "SELECT * FROM views WHERE view_aid = $article_id";
          $view = mysqli_query($link, $sql);

          $sql = "SELECT * FROM comments WHERE comment_aid = $article_id";
          $comments = mysqli_query($link, $sql);
        ?>

        <div class="article-options">
          <div onclick="ajax_toggle_like(<?php echo $article_output['article_id'] ?>, <?php echo $_SESSION['id'] ?>, this)" class="<?php while ($like_output = mysqli_fetch_assoc($like)) { if ($like_output['like_uid'] == $_SESSION['id']) { echo "like-total"; } } ?>" title="Понравилось"><i class="fad fa-heart"></i><span><?php echo mysqli_num_rows($like); ?></span></div>
          <div class="comments-total" title="Количество комментариев"><i class="fad fa-comments-alt"></i><span><?php echo mysqli_num_rows($comments); ?></span></div>
          <?php if ($article_output['article_visible'] == 0) { ?>
          <div class="views-total" title="Закрыто"><i class="fad fa-lock-alt"></i><span></span></div>
          <?php } else { ?>
          <div class="views-total" title="Количество просмотров"><i class="fad fa-eye"></i><span><?php echo mysqli_num_rows($view); ?></span></div>
          <?php } ?>
          <div class="words-total" title="Количество слов"><i class="fad fa-font"></i><span><?php echo str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя"); ?></span></div>
          <?php if (floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) > 0) { ?>
          <div class="read-total" title="Время чтения"><i class="fad fa-book-open"></i><span>
            <?php
              if (floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 1) {
                echo floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60)." минуту";
              } elseif (floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 4 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 2 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 24 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 22 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 34 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 32 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 44 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 42 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) <= 54 && floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) >= 52) {
                echo floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60)." минуты";
              } elseif (floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) == 21 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) == 31 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) == 41 || floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60) == 51) {
                echo floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60)." минуту";
              } else {
                echo floor(str_word_count(strip_tags($article_output['article_content']), 0, "АаБбВвГгДдЕеЁёЖжЗзИиЙйКкЛлМмНнОоПпРрСсТтУуФфХхЦцЧчШшЩщЪъЫыЬьЭэЮюЯя") / 60)." минут";
              }
            ?>
          </span></div>
          <?php } ?>
        </div>

        <div class="article-comments">
          <?php if ($article_output['article_comments'] == 0): ?>
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
                    <a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>">
                      <img src="media/uploads/<?php echo $comments_output['comment_avatar'] ?>" alt="Avatar">
                    </a>
                  </div>

                  <div class="comment-content">
                    <div class="comment-name-time">
                      <p><a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>"><?php echo $comments_output['comment_author'] ?></a></p>
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
                    <i class="fal fa-times"></i>
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
          <?php endif; ?>
        </div>
      </footer>
    </div>
  </article>

<?php } elseif ($article_output['article_type'] == 3) { ?>

  <article>
    <div class="survey-article" dt-article-control="<?php echo $article_output['article_id'] ?>" dt-article-id="<?php echo $article_output['article_id'] ?>">
      <nav>
        <div class="article-author">
          <a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>"><img src="media/uploads/<?php echo $article_output['article_author_image'] ?>" alt="Author"></a>
          <div class="name-time">
            <p><a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>"><?php echo $article_output['article_author'] ?></a></p>
            <small>
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
            </small>
          </div>
        </div>

        <div class="article-options">
          <i class="fad fa-ellipsis-h" onmouseover="openDropdown(this)" onmouseout="hideDropdown(this)"></i>
          <div class="article-dropdown">
            <?php if ($id == $_SESSION['id']) { ?>
              <a onclick="ajax_remove_article(<?php echo $article_output['article_id'] ?>, this)">Удалить</a>
              <a onclick="#">Пожаловаться</a>
              <a onclick="ajax_remove_survey_vote(<?php echo $_SESSION['id'] ?>, <?php echo $article_output['article_id'] ?>, this)">Убрать голос</a>
              <a onclick="ajax_toggle_comments(<?php echo $article_output['article_id'] ?>, this)"><?php if ($article_output['article_comments'] == 0) { echo 'Отключить комментарии'; } else { echo 'Включить комментарии'; } ?></a>
            <?php } else { ?>
              <a onclick="#">Пожаловаться</a>
              <a onclick="ajax_remove_survey_vote(<?php echo $_SESSION['id'] ?>, <?php echo $article_output['article_id'] ?>, this)">Убрать голос</a>
            <?php } ?>
          </div>
        </div>
      </nav>

      <main>
        <div class="survey-article-content">
          <div class="survey-header"><?php echo $article_output['article_title'] ?></div>
          <?php
            $data = $article_output['article_content'];
            $trimed_data = rtrim($data, "&1=&2=");

            $first_pos = strrpos($trimed_data, "&") + 1;
            $second_pos = strrpos($trimed_data, "=");
            $index = substr($trimed_data, $first_pos, $second_pos-$first_pos);

            $survey_data = array();
            parse_str($trimed_data, $survey_data);

            $survey_aid = $article_output['article_id'];

            $sql = "SELECT * FROM surveys WHERE survey_aid = $survey_aid";
            $survey = mysqli_query($link, $sql);

            if (mysqli_num_rows($survey) > 0) {
              $survey_amount_votes = mysqli_num_rows($survey);
            } else {
              $survey_amount_votes = 1;
            }

            for ($i = 1; $i <= $index; $i++) {
              $sql = "SELECT * FROM surveys WHERE survey_user_choice = $i AND survey_aid = $survey_aid";
              $survey = mysqli_query($link, $sql);
              $survey_option_amount_votes = mysqli_num_rows($survey);
          ?>

          <div class="survey-option" onclick="ajax_survey_option_decide(<?php echo $article_output['article_id'] ?>, <?php echo $i ?>, this)" style="<?php while ($survey_output = mysqli_fetch_assoc($survey)) { if ($survey_output['survey_uid'] == $_SESSION['id']) { echo 'border: 1px solid #38a9ff'; } } ?>">
            <small><?php echo $survey_data[$i]; ?></small>
            <small><?php echo round($survey_option_amount_votes / $survey_amount_votes * 100).'%' ?></small>
            <div class="progress">
              <div class="progress-bar" style="width: <?php echo ($survey_option_amount_votes / $survey_amount_votes * 100).'%' ?>"></div>
            </div>
          </div>

          <?php
            }
          ?>
        </div>
      </main>

      <?php $article_id = $article_output['article_id']; ?>

      <footer>
        <?php
          $sql = "SELECT * FROM likes WHERE like_aid = $article_id";
          $like = mysqli_query($link, $sql);
          $like_output = mysqli_fetch_assoc($like);

          $sql = "SELECT * FROM views WHERE view_aid = $article_id";
          $view = mysqli_query($link, $sql);

          $sql = "SELECT * FROM comments WHERE comment_aid = $article_id";
          $comments = mysqli_query($link, $sql);

          $sql = "SELECT * FROM surveys WHERE survey_aid = $article_id";
          $survey = mysqli_query($link, $sql);

          $sql = "SELECT * FROM surveys WHERE survey_aid = $article_id AND survey_uid =".$_SESSION['id'];
          $survey_check = mysqli_query($link, $sql);
        ?>

        <div class="article-options">
          <div onclick="ajax_toggle_like(<?php echo $article_output['article_id'] ?>, <?php echo $_SESSION['id'] ?>, this)" class="<?php while ($like_output = mysqli_fetch_assoc($like)) { if ($like_output['like_uid'] == $_SESSION['id']) { echo "like-total"; } } ?>" title="Понравилось"><i class="fad fa-heart"></i><span><?php echo mysqli_num_rows($like); ?></span></div>
          <div class="comments-total" title="Количество комментариев"><i class="fad fa-comments-alt"></i><span><?php echo mysqli_num_rows($comments); ?></span></div>
          <?php if ($article_output['article_visible'] == 0) { ?>
          <div class="views-total" title="Закрыто"><i class="fad fa-lock-alt"></i><span></span></div>
          <?php } else { ?>
          <div class="views-total" title="Количество просмотров"><i class="fad fa-eye"></i><span><?php echo mysqli_num_rows($view); ?></span></div>
          <?php } ?>
          <div class="words-total" title="Количество проголосовавших"><i class="fad fa-users"></i><span><?php echo mysqli_num_rows($survey); ?></span></div>
          <?php if (mysqli_num_rows($survey_check) <= 0) { ?>
          <div class="words-total" title="Вы не проголосовали"><i class="fad fa-user-times"></i><span></span></div>
          <?php } else { ?>
          <div class="words-total" title="Вы не проголосовали"><i class="fad fa-user-check"></i><span></span></div>
          <?php } ?>
        </div>

        <div class="article-comments">
          <?php if ($article_output['article_comments'] == 0): ?>
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
                    <a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>">
                      <img src="media/uploads/<?php echo $comments_output['comment_avatar'] ?>" alt="Avatar">
                    </a>
                  </div>

                  <div class="comment-content">
                    <div class="comment-name-time">
                      <p><a href="<?php if ($_SESSION['id'] == $article_output['article_uid']) { echo 'profile'; } else { echo 'profile?id='.$article_output['article_uid']; } ?>"><?php echo $comments_output['comment_author'] ?></a></p>
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
                    <i class="fal fa-times"></i>
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
          <?php endif; ?>
        </div>
      </footer>
    </div>
  </article>

<?php } ?>

<?php endwhile; ?>

<?php } else { ?>

  <section>
    <div class="there-are-no-articles">
      <p>Нет никаких записей, но это легко исправить! <a href="#editor">Запишите ваши мысли</a> для себя! Или поделитесь ими с другими.</p>
    </div>
  </section>

<?php } ?>
