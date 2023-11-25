<?php
// remember to add this code in .htaccess
// php_flag output_buffering on

$title = 'Index';
// Initialize the session
session_start();
?>

<?php require_once('base.php') ?>
<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true)
{
	require_once('nav-logged.php');
}
else
{
	require_once('nav.php');
}
?>
<?php require_once('header.php') ?>

<div>
  <p class="msg">Bem vindo ao site SPARU, um site voltado ao anúncio e aluguel de residências universitárias, para estudantes de todo o Brasil.</p>
</div>
<div class="image">
  <img src="assets/close-up-students-with-books.jpg" alt="">
</div>

<?php require_once('footer.php') ?>
