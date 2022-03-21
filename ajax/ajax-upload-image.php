<?php

  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
    die("Upload failed with error code ".$_FILES['file']['error']);
  } else {
    move_uploaded_file($_FILES['file']['tmp_name'], "../media/uploads/backgrounds/".$_FILES['file']['name']);
    echo $_FILES['file']['name'];
  }

?>
