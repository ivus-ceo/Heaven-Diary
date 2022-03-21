<?php
  require 'php/db.php';
  session_start();

  if (empty($_GET['id']) || empty($_GET['title']) || empty($_GET['time'])) {
    header('Location: profile');
  } else {
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

    $article_id = $_GET['id'];
    $sql = "SELECT * FROM articles WHERE article_id = $article_id";
    $article = mysqli_query($link, $sql);
    $article_output = mysqli_fetch_assoc($article);

    if ($article_output['article_time'] !== $_GET['time'] || ru2lat(strtolower($article_output['article_title'])) !== $_GET['title'] || $article_output['article_visible'] == 0 && $_SESSION['id'] != $article_output['article_uid']) {
      header('Location: profile');
    }
  }
?>

<!DOCTYPE html>
<html>
  <head>
    <?php include 'includes/head.php'; ?>
    <title><?php echo $article_output['article_title'] ?></title>
    <style>
      body {
        background-color: #ffffff;
      }
      div.main-navbar-container {
        border: none;
      }
    </style>
  </head>
  <body>
    <?php include 'includes/navbar.php'; ?>

    <header>
      <div class="container-article-author">
        <div class="author-background">
          <div class="author-border">
            <div class="left-options">
              <i class="fas fa-chevron-circle-left"></i>
            </div>
            <div class="author-name-image">
              <img src="media/uploads/<?php echo $article_output['article_author_image'] ?>" alt="Author_avatar">
              <p><?php echo $article_output['article_author'] ?></p>
            </div>
            <div class="right-options">
              <i class="fas fa-adjust" onclick="toggle_night_mode()"></i>
            </div>
          </div>
        </div>
      </div>
    </header>

    <main>
      <div class="article-container">
        <div class="article-title"><?php echo $article_output['article_title'] ?></div>

        <div class="article-content">
          <?php echo $article_output['article_content'] ?>
        </div>
      </div>
    </main>

    <section>
      <div class="section-header">
        <p>У нас еще много интересного!</p>
      </div>
      <div class="section-slider-container">

        <?php
          $sql = "SELECT * FROM articles WHERE article_type = 2 AND article_visible = 1 ORDER BY RAND() LIMIT 5";
          $articles = mysqli_query($link, $sql);

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

          while ($articles_output = mysqli_fetch_assoc($articles)):
        ?>

        <div class="slide">
          <a href="article?id=<?php echo $articles_output['article_id'] ?>&title=<?php echo ru2lat(strtolower($articles_output['article_title'])) ?>&time=<?php echo $articles_output['article_time'] ?>">
            <div class="random-large-article">
              <div class="article-image" style="background-image: url(<?php if (substr($articles_output['article_image'], 0, 4) == 'http') { echo $articles_output['article_image']; } else { echo 'media/uploads/backgrounds/'.$articles_output['article_image']; } ?>)">
                <div class="article-background"></div>
              </div>

              <div class="article-title">
                <p>
                  <?php
                    if (strlen($articles_output['article_title']) > 20) {
                      echo mb_substr($articles_output['article_title'], 0, 20, 'utf-8')."...";
                    } else {
                      echo $articles_output['article_title'];
                    }
                  ?>
                </p>
              </div>
            </div>
          </a>
        </div>

        <?php endwhile; ?>

      </div>
    </section>

    <section>
      <div class="modal-container">
        <img id="image" onclick="resize(this)" src="">
      </div>
    </section>

    <script src="js/main.js"></script>
  </body>
</html>
