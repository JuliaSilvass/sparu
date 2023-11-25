 <?php
$title = 'Sobre';
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
  <p class="msg">Site desenvolvido como Trabalho de Conclusão de Curso apresentado 
ao Curso Técnico em Desenvolvimento de Sistemas – modalidade EaD, orientado pela
Prof. Katia Cilene Passos, como requisito parcial para obtenção do título de Técnico em 
Desenvolvimento de Sistemas - 2023.</p>
</div>

<?php require_once('footer.php') ?>
