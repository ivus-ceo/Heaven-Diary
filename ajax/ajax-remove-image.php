<?php

  require '../php/db.php';
  session_start();
  date_default_timezone_set('Asia/Omsk');

  $path = '../media/uploads/backgrounds/'.$_POST['image'];

  if (!unlink($path)) {
    echo "Ошибка";
  }

?>
