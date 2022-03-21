<?php
  require 'php/db.php';
  session_start();

  if (!empty($_POST['search-name'])) {
    $search_name = mysqli_real_escape_string($link, $_POST['search-name']);

    $sql = "SELECT * FROM users WHERE user_name LIKE '%$search_name%' OR user_last LIKE '%$search_name%'";
    $search_user = mysqli_query($link, $sql);

    $sql = "SELECT * FROM articles WHERE article_content LIKE '%$search_name%' AND article_type = 1 AND article_visible = 1 OR article_author LIKE '%$search_name%' AND article_type = 1 AND article_visible = 1 OR article_date LIKE '%$search_name%' AND article_type = 1 AND article_visible = 1 ORDER BY article_id DESC";
    $article_small = mysqli_query($link, $sql);

    $sql = "SELECT * FROM articles WHERE article_title LIKE '%$search_name%' AND article_type = 2 AND article_visible = 1 OR article_content LIKE '%$search_name%' AND article_type = 2 AND article_visible = 1 OR article_author LIKE '%$search_name%' AND article_type = 2 AND article_visible = 1 OR article_date LIKE '%$search_name%' AND article_type = 2 AND article_visible = 1 ORDER BY article_id DESC";
    $article_large = mysqli_query($link, $sql);
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <?php include 'includes/head.php'; ?>
    <title>Поиск</title>
  </head>
  <body>
    <?php include 'includes/navbar.php' ?>

    <header>
      <div class="header-search-container">
        <?php if (mysqli_num_rows($search_user) > 0) { ?>
          <p>Пользователей по запросу найдено: <?php echo mysqli_num_rows($search_user); ?></p>
        <?php } else { ?>
          <p>Пользователей по запросу не найдено</p>
        <?php } ?>

        <div class="search-users">
          <?php while($search_user_output = mysqli_fetch_assoc($search_user)): ?>
            <div class="search-user" style="background-image: url(media/uploads/backgrounds/<?php echo $search_user_output['user_background'] ?>)">
              <a href="<?php if ($_SESSION['id'] == $search_user_output['user_id']) { echo 'profile'; } else { echo 'profile?id='.$search_user_output['user_id']; } ?>">
                <div class="background">
                  <div class="search-user-avatar">
                    <img src="media/uploads/<?php echo $search_user_output['user_image'] ?>" alt="User-Avatar">
                    <p><?php echo $search_user_output['user_name']." ".$search_user_output['user_last'] ?></p>
                  </div>
                </div>
              </a>
            </div>
          <?php endwhile; ?>
        </div>

        <?php if (mysqli_num_rows($article_small) > 0) { ?>
          <p>Записей по запросу найдено: <?php echo mysqli_num_rows($article_small); ?></p>
        <?php } else { ?>
          <p>Записей по запросу не найдено</p>
        <?php } ?>

        <div class="search-articles">
          <?php while($article_small_output = mysqli_fetch_assoc($article_small)): ?>
            <div class="search-article" style="padding: .4em .8em;" onclick="header_to(<?php echo $article_small_output['article_id'] ?>, <?php echo $article_small_output['article_uid'] ?>, <?php echo $_SESSION['id'] ?>, this)">
              <small>Автор: <img src="media/uploads/<?php echo $article_small_output['article_author_image'] ?>" alt="Author_Avatar"><?php echo $article_small_output['article_author'] ?></small>
              <small><?php echo $article_small_output['article_content']; ?></small>
            </div>
          <?php endwhile; ?>
        </div>

        <?php if (mysqli_num_rows($article_large) > 0) { ?>
          <p>Статей по запросу найдено: <?php echo mysqli_num_rows($article_large); ?></p>
        <?php } else { ?>
          <p>Статей по запросу не найдено</p>
        <?php } ?>

        <div class="search-articles">
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

          <?php while($article_large_output = mysqli_fetch_assoc($article_large)): ?>

            <div class="search-article">
              <a href="article?id=<?php echo $article_large_output['article_id'] ?>&title=<?php echo ru2lat(strtolower($article_large_output['article_title'])) ?>&time=<?php echo $article_large_output['article_time'] ?>">
                <div class="large-article" style="background-image: url(<?php if (substr($article_large_output['article_image'], 0, 4) == 'http') { echo $article_large_output['article_image']; } else { echo 'media/uploads/backgrounds/'.$article_large_output['article_image']; } ?>)">
                  <div class="background">
                    <h3><?php echo $article_large_output['article_title'] ?></h3>
                  </div>
                </div>
              </a>
            </div>

          <?php endwhile; ?>
        </div>
      </div>
    </header>

    <?php include 'includes/footer.php' ?>

    <script src="js/main.js"></script>
  </body>
</html>
