<?php
if(!isset($_SESSION)) {
  session_start();
}

if(!isset($_SESSION['id'])) {
  // header('location:../access/access_denied.php');
  header('location:../login.php');
  die();
}
?>