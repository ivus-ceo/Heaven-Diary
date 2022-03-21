<?php
  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  $article_id = $_POST['article_id'];
  $check_article_id = $_POST['ch_article_id'];

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
      $articles = mysqli_stmt_get_result($stmt);
      $articles_output = mysqli_fetch_assoc($articles);
    }
  }
?>

<div class="large-article-form" dt-article-control="<?php echo $check_article_id ?>" dt-article-id="<?php echo $check_article_id ?>">
  <div class="title-close">
    <input type="text" name="article_title" placeholder="Заголовок . . ." value="<?php echo $articles_output['article_title'] ?>">
    <i onclick="remove_large_article_creation()" class="fas fa-times"></i>
  </div>

  <div class="file-switch">
    <input type="text" name="article-image" placeholder="URL изображения" value="<?php echo $articles_output['article_image'] ?>" <?php if (substr($articles_output['article_image'], 0, 4) == 'http') {} else { echo 'disabled'; } ?>>
    <input type="file" name="custom-article-image" onchange="large_article_image(this)">
    <i title="Загрузить свое изображение" class="fas fa-image"></i>
  </div>

  <div class="options-buttons">
    <button title="Bold" type="button" onclick="doRichEditCommand('bold')"><i class="fas fa-bold"></i></button>
    <button title="Italic" type="button" onclick="doRichEditCommand('italic')"><i class="fas fa-italic"></i></button>
    <button title="Underline" type="button" onclick="doRichEditCommand('underline')"><i class="fas fa-underline"></i></button>
    <button title="Strikethrough" type="button" onclick="doRichEditCommand('strikeThrough')"><i class="fas fa-strikethrough"></i></button>
    <!--<button title="Subscript" type="button" onclick="doRichEditCommand('subscript')"><i class="fas fa-subscript"></i></button>
    <button title="Superscript" type="button" onclick="doRichEditCommand('superscript')"><i class="fas fa-superscript"></i></button>-->

    <button title="Left" type="button" onclick="doRichEditCommand('justifyLeft')"><i class="fas fa-align-left"></i></button>
    <button title="Center" type="button" onclick="doRichEditCommand('justifyCenter')"><i class="fas fa-align-center"></i></button>
    <button title="Right" type="button" onclick="doRichEditCommand('justifyRight')"><i class="fas fa-align-right"></i></button>
    <button title="Justify" type="button" onclick="doRichEditCommand('justifyFull')"><i class="fas fa-align-justify"></i></button>
    <!--<button title="Outdent" type="button" onclick="doRichEditCommand('outdent')"><i class="fas fa-outdent"></i></button>
    <button title="Indent" type="button" onclick="doRichEditCommand('indent')"><i class="fas fa-indent"></i></button>-->

    <button title="Unordered List" type="button" onclick="doRichEditCommand('insertunorderedlist')"><i class="fas fa-list-ul"></i></button>
    <button title="Ordered List" type="button" onclick="doRichEditCommand('insertorderedlist')"><i class="fas fa-list-ol"></i></button>
    <button title="Blockquote" type="button" onclick="doRichEditCommandWithArg('formatblock','blockquote')"><i class="fas fa-quote-right"></i></button>

    <!--<select title="Header" onchange="doRichEditCommandWithArg('formatBlock', this.value)">
      <option value="H1">H1</option>
      <option value="H2">H2</option>
      <option value="H3">H3</option>
      <option value="H4">H4</option>
      <option value="H5">H5</option>
      <option value="H6">H6</option>
    </select>-->

    <button title="Header" type="button" onclick="doRichEditCommandWithArg('formatBlock', 'H3')"><i class="fas fa-heading"></i></button>

    <button title="Horizontal Line" type="button" onclick="doRichEditCommand('insertHorizontalRule')"><i class="fas fa-ruler-horizontal"></i></button>
    <button title="Link" type="button" onclick="doRichEditCommandWithArg('createLink', prompt('Enter an URL', 'http://'))"><i class="fas fa-link"></i></button>
    <button title="Unlink" type="button" onclick="doRichEditCommand('unlink')"><i class="fas fa-unlink"></i></button>
    <button title="Insert image" type="button" onclick="doRichEditCommandWithArg('insertImage', prompt('Enter an URL'))"><i class="fas fa-image"></i></button>

    <!--<input title="Change text color" type="color" onchange="doRichEditCommandWithArg('foreColor', this.value)">
    <input title="Change background color" type="color" onchange="doRichEditCommandWithArg('hiliteColor', this.value)">-->

    <!--<select title="Font" onchange="doRichEditCommandWithArg('fontName', this.value)">
      <option value="Arial">Arial</option>
      <option value="Courier">Courier</option>
      <option value="Times New Roman">Times New Roman</option>
      <option value="Verdana">Verdana</option>
      <option value="Agency FB">Agency FB</option>
    </select>-->

    <!--<select title="Font Size" onchange="doRichEditCommandWithArg('fontSize', this.value)">
      <option value="1">1</option>
      <option value="2">2</option>
      <option value="3">3</option>
      <option value="4">4</option>
      <option value="5">5</option>
      <option value="6">6</option>
      <option value="7">7</option>
    </select>-->

    <!--<button title="Cut" type="button" onclick="doRichEditCommand('cut')"><i class="fas fa-cut"></i></button>
    <button title="Copy" type="button" onclick="doRichEditCommand('copy')"><i class="fas fa-copy"></i></button>-->
    <button title="Remove Format" type="button" onclick="doRichEditCommand('removeFormat')"><i class="fas fa-eraser"></i></button>
  </div>

  <div class="editor" contenteditable="true">
    <?php echo $articles_output['article_content'] ?>
  </div>
</div>
