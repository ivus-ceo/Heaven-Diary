<?php
  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  $survey_uid = $_SESSION['id'];
  $survey_user_choice = $_POST['id'];
  $survey_aid = $_POST['article_id'];

  $sql = "SELECT * FROM surveys WHERE survey_aid = $survey_aid AND survey_uid = $survey_uid";
  $result = mysqli_query($link, $sql);

  if (mysqli_num_rows($result) < 1) {
    $sql = "INSERT INTO surveys (survey_uid, survey_user_choice, survey_aid) VALUES (?, ?, ?)";
    $stmt = mysqli_stmt_init($link);
    if (!mysqli_stmt_prepare($stmt, $sql)) {
      header('Location: ../profile.php?error=sql-error');
      exit();
    } else {
      mysqli_stmt_bind_param($stmt, "iii", $survey_uid, $survey_user_choice, $survey_aid);
      mysqli_stmt_execute($stmt);
    }
  }

  $sql = "SELECT * FROM articles WHERE article_id = $survey_aid";
  $article = mysqli_query($link, $sql);
  $article_output = mysqli_fetch_assoc($article);
?>

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
