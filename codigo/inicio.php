<?php
$title = 'Inicio';
// Initialize the session
session_start();
 
include('./access/protect.php');

// Include db_config file
require_once "./access/db_config.php";

// Prepare a select statement
// $sql = "SELECT num_receitas FROM users WHERE username = ?";
		
// if($stmt = mysqli_prepare($link, $sql)){
  // Bind variables to the prepared statement as parameters
  // mysqli_stmt_bind_param($stmt, "s", $param_username);
  
  // Set parameters
  //$param_username = $_SESSION["username"];
  $param_name = $_SESSION["nome"];
      
  // Attempt to execute the prepared statement
  // if(mysqli_stmt_execute($stmt)){
    // Store result
    // mysqli_stmt_store_result($stmt);
    // If username found in db, then get num_receitas
    // if(mysqli_stmt_num_rows($stmt) == 1){                    
      // Bind result variables
      // mysqli_stmt_bind_result($stmt, $num_receitas);
      // mysqli_stmt_fetch($stmt);
    // }
  // }
// } else{
  // Username doesn't exist, display a generic error message
  // $access_err = "Usuário não cadastrado.";
// }
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

<div class="inicio">
  <center>
  <br>
  <h1 class="my-5">Oi, <b><?php echo htmlspecialchars($_SESSION["nome"]); ?></b>.</h1>
  <br>
  <p>
  Seja bem-vindo ao início da SPARU! Neste espaço, você terá a oportunidade de decidir entre explorar diretamente todas as residências e produtos disponíveis ou realizar uma busca por uma residência e um produto em um estado e cidade específicos. 
  </p>
  <br>
  <p>
  Além disso, oferecemos a opção de cadastrar um novo local e produto para anunciar e fornecemos informações detalhadas sobre nós. 
  </p>
  <br>
  <p>    
  Sinta-se à vontade para navegar e descobrir todas as possibilidades que a SPARU tem a oferecer.
  </p>
  </center>
</div>

<?php require_once('footer.php') ?>
