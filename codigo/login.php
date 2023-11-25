<?php
$title = 'Login';
// Initialize the session
session_start();
 
// Check if the user is already logged in, if yes then redirect him to inicio
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("location: inicio.php");
	exit;
}
 
// Include db_config file
require_once "./access/db_config.php";
 
// Define variables and initialize with empty values
$senha = $login = "";
$senha_err = $login_err = $access_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Check if login is empty
	if(empty(trim($_POST["login"]))){
		$login_err = "Insira o nome de usuário";
	} else{
		$login = trim($_POST["login"]);
	}
	
	// Check if senha is empty
	if(empty(trim($_POST["senha"]))){
		$senha_err = "Insira a senha.";
	} else{
		$senha = trim($_POST["senha"]);
	}


	// Validate credentials
	if(empty($senha_err) && empty($login_err)){
		
		// Prepare a select statement
		$sql = "SELECT cod_usuario, login, nome, email, senha FROM usuario WHERE login = ?";
		
		
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_login);
			
			// Set parameters
			$param_login = $login;
			
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				// Store result
				mysqli_stmt_store_result($stmt);
				
				// Check if login exists, if yes then verify senha
				if(mysqli_stmt_num_rows($stmt) == 1){                    
					// Bind result variables
					mysqli_stmt_bind_result($stmt, $id, $login, $name, $email, $hashed_password);
					if(mysqli_stmt_fetch($stmt)){
						  if(password_verify($senha, $hashed_password)){
							// senha is correct, so start a new session
							session_start();
							
							// Store data in session variables
							$_SESSION["loggedin"] = true;
							$_SESSION["id"] = $id;
							$_SESSION["login"] = $login;  
							$_SESSION["nome"] = $name;                            
							$_SESSION["email"] = $email;                            
							
							// Redirect user to inicio
							header("location: inicio.php");
						} else{
							// senha is not valid, display a generic error message
							$access_err = "Usuário ou senha incorretos.";
						}
					}
				} else{
					// login doesn't exist, display a generic error message
					$access_err = "Usuário não cadastrado.";
				}
			} else{
				echo "Oops! Ocorreu algum erro inesperado! Tente novamente mais tarde.";
			}

			// Close statement
			mysqli_stmt_close($stmt);
		}
	}
	
	// Close connection
	mysqli_close($link);
}
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

<div class="caixa">
	<div>
		<p class="subtitle">Login</p>

		<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
			<div class="form-line">
				<label>Usuário:</label><br>
				<input type="text" name="login" class="<?php echo (!empty($login_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $login; ?>">
			</div>    
			<span class="warning"><?php echo $login_err; ?></span>
				
			<div class="form-line">
				<label>Senha:</label><br>
				<input type="password" name="senha" class="<?php echo (!empty($senha_err)) ? 'is-invalid' : ''; ?>">
			</div>
			<span class="warning"><?php echo $senha_err; ?></span>

			<div>
				<button type="submit" class="button" value="Login">Acessar</button>
			</div>

			<?php 
				if(!empty($access_err)){
					echo '<div class="warning">' . $access_err . '</div>';
				}	        
			?>

			<p class="caixa-msg">Não tem uma conta? <a href="cadastro.php">Cadastre-se</a>.</p>
		</form>
	</div>
</div>

<?php require_once('footer.php') ?>