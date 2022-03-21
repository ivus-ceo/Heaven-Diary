<?php

  if (isset($_GET['logout'])) {
    session_start();
    session_unset();
    session_destroy();
    header("Location: ./");
    exit();
  } else {
    header("Location: ./");
    exit();
  }

?>
