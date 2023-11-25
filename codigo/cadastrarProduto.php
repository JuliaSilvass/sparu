<?php
$title = 'Cadastrar produto';
// Initialize the session
session_start();
 
include('./access/protect.php');

// Include db_config file
require_once "./access/db_config.php";
 
// Define variables and initialize with empty values
$descricao = $preco = $status = $cidade = $estado = "";
$descricao_err = $preco_err = $status_err = $cidade_err = $estado_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Check if descricao is empty
	if(empty(trim($_POST["descricao"]))){
		$descricao_err = "Insira uma descrição para o produto";
	} else{
		$descricao = trim($_POST["descricao"]);
	}
	// Check if preco is empty or 0
	if(empty(trim($_POST["preco"])) or trim($_POST["preco"]) == 0){
		$preco_err = "Insira o preço do produto";
	} else{
		$preco = trim($_POST["preco"]);
	}
	// Check if cidade is empty
	if(empty(trim($_POST["cidade"]))){
		$cidade_err = "Insira a cidade";
	} else{
		$cidade = trim($_POST["cidade"]);
	}
	// Check if estado is empty
	if(empty(trim($_POST["estado"]))){
		$estado_err = "Insira o estado";
	} else{
		$estado = trim($_POST["estado"]);
	}
    // Check if status is empty
	if(empty(trim($_POST["status"]))){
		$status_err = "Insira um status para o produto";
	} else{
		$status = trim($_POST["status"]);
	}

    $id = $_SESSION["id"];



	// Check input errors before inserting in database
    if(empty($preco_err) && empty($descricao_err) && empty($cidade_err) && empty($estado_err)){

        // Assume que o usuário já está logado e o cod_usuario está na sessão
        if (isset($_SESSION["id"])) {
            $cod_usuario = $_SESSION["id"];

            // Prepare an insert statement
            $sql = "INSERT INTO produto (preco, descricao, cidade, estado, status, cod_usuario) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($link, $sql);

            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "dssssi", $preco, $descricao, $cidade, $estado, $status, $cod_usuario);

            // Execute the statement
            if (mysqli_stmt_execute($stmt)) {
                // Successo
                echo "Produto cadastrado com sucesso!";
            } else {
                // Error
                echo "Erro ao cadastrar o produto.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        } else {
            echo "Erro: 'cod_usuario' não está definido na sessão.";
        }
    }
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
			<p class="subtitle">Cadastrar residência</p>

			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-line">
                    <label>Descrição*:</label><br>
                    <input type="text" name="descricao" class=" <?php echo (!empty($descricao_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $descricao; ?>">
                </div>
                <span class="warning"><?php echo $descricao_err; ?></span>

				<div class="form-line">
					<label>Preço*:</label><br>
					<input type="number" name="preco" class=" <?php echo (!empty($preco_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $preco; ?>">
				</div>
				<span class="warning"><?php echo $preco_err; ?></span>

                <div class="form-line">
					<label>Status:</label><br>
					<input type="text" name="status">
				</div>
				<span class="warning"><?php echo $status_err; ?></span>

				<div class="form-line">
					<label>Cidade*:</label><br>
					<input type="text" name="cidade" class="<?php echo (!empty($cidade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cidade; ?>">
				</div>
				<span class="warning"><?php echo $cidade_err; ?></span>

				<div class="form-line">
					<label>Estado*:</label><br>
					<input type="text" name="estado" class="<?php echo (!empty($estado_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $estado; ?>">
				</div>
				<span class="warning"><?php echo $estado_err; ?></span>

				<div>
					<button type="submit" class="button" value="Cadastrar">Cadastrar</button>
				</div>
			</form>
		</div>
	</div>

<?php require_once('footer.php') ?>
