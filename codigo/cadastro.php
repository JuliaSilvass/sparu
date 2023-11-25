<?php
$title = 'Cadastrar';
// Initialize the session
session_start();

// Check if the user is already logged in, if yes then redirect him to inicio
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	header("location: inicio.php");
	exit;
}

// Include db_config file
require_once "./access/db_config.php";
require "./access/valida_cpf.php";

// Define variables and initialize with empty values
$login = $nome = $cpf = $senha = $confirm_senha = $email = $telefone = $tipo = "";
$login_err = $cpf_err = $nome_err = $senha_err = $confirm_senha_err = $email_err = $telefone_err = $tipo_err = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
	// Validate login
	if(empty(trim($_POST["login"]))){
		$login_err = "Preencha o nome de usuário.";
	} elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["login"]))){
		$login_err = "Nome de usuário pode conter somente letras, números e underscore.";
	} else{
		// Prepare a select statement
		$sql = "SELECT cod_usuario FROM usuario WHERE login = ?";
		
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_login);
			
			// Set parameters
			$param_login = trim($_POST["login"]);
			
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				/* store result */
				mysqli_stmt_store_result($stmt);
					
				if(mysqli_stmt_num_rows($stmt) == 1){
					$login_err = "Nome de usuário já cadastrado.";
				} else{
					$login = trim($_POST["login"]);
				}
			} else{
				echo "Oops! Ocorreu algum erro inesperado! Tente novamente mais tarde.";
			}

			// Close statement
			mysqli_stmt_close($stmt);
		}
	}

	// Validate nome
	if(empty(trim($_POST["nome"]))){
		$nome_err = "Preencha seu nome.";
	} else{
		$nome = trim($_POST["nome"]);
	}

	// Validate CPF
	if(empty(trim($_POST["cpf"]))){
		$cpf_err = "Preencha seu CPF.";
	} elseif(validaCPF(trim($_POST["cpf"]))){
		// Prepare a select statement
		$sql = "SELECT cod_usuario FROM usuario WHERE cpf = ?";
		
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_cpf);
			
			// Set parameters
			$param_cpf = trim($_POST["cpf"]);
			
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				/* store result */
				mysqli_stmt_store_result($stmt);
					
				if(mysqli_stmt_num_rows($stmt) == 1){
					$cpf_err = "CPF já cadastrado.";
				} else{
					$cpf = trim($_POST["cpf"]);
				}
			} else{
				echo "Oops! Ocorreu algum erro inesperado! Tente novamente mais tarde.";
			}
			// Close statement
			mysqli_stmt_close($stmt);
		}
	} else{
		$cpf_err = "CPF inválido";
	}

	// Validate email
	if(empty(trim($_POST["email"]))) {
		$email_err = "Preencha o email.";
	} 
	elseif(!filter_var(trim($_POST["email"]), FILTER_VALIDATE_EMAIL)){
	 	$email_err = "Formato de email incorreto.";
	} 
	else {
		// Prepare a select statement
		$sql = "SELECT cod_usuario FROM usuario WHERE email = ?";
		
		if($stmt = mysqli_prepare($link, $sql)){
			// Bind variables to the prepared statement as parameters
			mysqli_stmt_bind_param($stmt, "s", $param_email);
			
			// Set parameters
			$param_email = trim($_POST["email"]);
			
			// Attempt to execute the prepared statement
			if(mysqli_stmt_execute($stmt)){
				/* store result */
				mysqli_stmt_store_result($stmt);
					
					if(mysqli_stmt_num_rows($stmt) == 1){
						$email_err = "Email já cadastrado.";
					} else{
						$email = trim($_POST["email"]);
					}
				} else{
					echo "Oops! Ocorreu algum erro inesperado! Tente novamente mais tarde.";
				}

				// Close statement
				mysqli_stmt_close($stmt);
			}
    }
    
    // Validate senha
    if(empty(trim($_POST["senha"]))){
			$senha_err = "Preencha uma senha.";     
    } elseif(strlen(trim($_POST["senha"])) < 6){
			$senha_err = "A senha deve ter ao menos 6 caracteres.";
    } else{
			$senha = trim($_POST["senha"]);
    }
    
    // Validate confirm senha
    if(empty(trim($_POST["confirm_senha"]))){
			$confirm_senha_err = "Confirme a senha.";     
    } else{
			$confirm_senha = trim($_POST["confirm_senha"]);
			if(empty($senha_err) && ($senha != $confirm_senha)){
				$confirm_senha_err = "Senhas diferentes.";
			}
    }
    
    // Check input errors before inserting in database
    if(empty($login_err) && empty($nome_err) && empty($cpf_err) && empty($senha_err) && empty($confirm_senha_err) && empty($email_err) && empty($telefone_err) && empty($tipo_err)){
        
			// Prepare an insert statement
			$sql = "INSERT INTO usuario (login, nome, cpf, senha, email) VALUES (?, ?, ?, ?, ?)";
				
			if($stmt = mysqli_prepare($link, $sql)){
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "sssss", $param_login, $param_nome, $param_cpf, $param_senha, $param_email);
				
				// Set parameters
				$param_login = $login;
				$param_nome = $nome;
				$param_cpf = $cpf;
				$param_email = $email;
				$param_senha = password_hash($senha, PASSWORD_DEFAULT); // Creates a password hash
								
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					 // Get the ID of the last inserted record (cod_usuario)
					 $cod_usuario = mysqli_insert_id($link);

					// Redirect to login page
					header("location: login.php");
				} else{
					echo "Oops! Ocorreu algum erro inesperado! Tente novamente mais tarde.";
				}

				// Close statement
				mysqli_stmt_close($stmt);
			}

			
			// Prepare an insert statement for telefone
			$sql = "INSERT INTO telefone (num1, tipo, cod_usuario) VALUES (?, ?, ?)";
			
			if($stmt = mysqli_prepare($link, $sql)){

				// Configurar parâmetros
				$param_telefone = trim($_POST["telefone"]);
				$param_tipo = trim($_POST["tipo"]);
				
				// Bind variables to the prepared statement as parameters
				mysqli_stmt_bind_param($stmt, "isi", $param_telefone, $param_tipo, $cod_usuario);
								
				// Attempt to execute the prepared statement
				if(mysqli_stmt_execute($stmt)){
					// Redirect to login page
					header("location: login.php");
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
			<p class="subtitle">Cadastro</p>

			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
				<div class="form-line">
					<label>Usuário:</label><br>
					<input type="text" name="login" class=" <?php echo (!empty($login_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $login; ?>">
				</div>
				<span class="warning"><?php echo $login_err; ?></span>

				<div class="form-line">
					<label>Nome:</label><br>
					<input type="text" name="nome" class=" <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
				</div>
				<span class="warning"><?php echo $nome_err; ?></span>

				<div class="form-line">
					<label>CPF:</label><br>
					<input type="text" name="cpf" class=" <?php echo (!empty($cpf_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cpf; ?>">
				</div>
				<span class="warning"><?php echo $cpf_err; ?></span>

				<div class="form-line">
					<label>Email:</label><br>
					<input type="text" name="email" class="<?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
				</div>
				<span class="warning"><?php echo $email_err; ?></span>

				<div class="form-line">
					<label>Telefone:</label><br>
					<input type="number" name="telefone" class="<?php echo (!empty($telefone_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $telefone; ?>">
				</div>
				<span class="warning"><?php echo $telefone_err; ?></span>

				<div class="form-line">
					<label>Tipo de telefone:</label><br>
					<input type="text" name="tipo" class="<?php echo (!empty($tipo_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $tipo; ?>">
				</div>
				<span class="warning"><?php echo $tipo_err; ?></span>

				<div class="form-line">
					<label>Senha:</label><br>
					<input type="password" name="senha" class="<?php echo (!empty($senha_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $senha; ?>">
				</div>
				<span class="warning"><?php echo $senha_err; ?></span>

				<div class="form-line">
					<label>Confirme a senha:</label><br>
					<input type="password" name="confirm_senha" class=" <?php echo (!empty($confirm_senha_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_senha; ?>">
				</div>
				<span class="warning"><?php echo $confirm_senha_err; ?></span>

				<div>
					<button type="submit" class="button" value="Cadastrar">Cadastrar</button>
					<!-- <input type="reset" class="w3-button w3-red w3-hover-red w3-round w3-margin-left" value="Reset"> -->
				</div>
				<p class="caixa-msg">Já tem uma conta? <a href="login.php">Faça login</a>.</p>
			</form>
		</div>
	</div>  

<?php require_once('footer.php') ?>