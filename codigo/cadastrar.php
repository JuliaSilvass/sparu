<?php
$title = 'Cadastrar local';
// Initialize the session
session_start();
 
include('./access/protect.php');

// Include db_config file
require_once "./access/db_config.php";
 
// Define variables and initialize with empty values
$nome = $descricao = $preco = $categoria = $rua = $numero = $bairro = $complemento = $cidade = $estado = $cep = "";
$nome_err = $descricao_err = $preco_err = $categoria_err = $rua_err = $numero_err = $bairro_err = $complemento_err = $cidade_err = $estado_err = $cep_err = $add_err = "";

$ano = $mes_inicio = $periodo = "";
$ano_err = $mes_inicio_err = $periodo_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

	// Check if nome is empty
	if(empty(trim($_POST["nome"]))){
		$nome_err = "Insira o nome para o local";
	} else{
		$nome = trim($_POST["nome"]);
	}
	// Check if preco is empty or 0
	if(empty(trim($_POST["preco"])) or trim($_POST["preco"]) == 0){
		$preco_err = "Insira o preço do local";
	} else{
		$preco = trim($_POST["preco"]);
	}
	// Check if descricao is empty
	if(empty(trim($_POST["descricao"]))){
		$descricao_err = "Insira a descrição";
	} else{
		$descricao = trim($_POST["descricao"]);
	}
	// Check if rua is empty
	if(empty(trim($_POST["rua"]))){
		$rua_err = "Insira a rua";
	} else{
		$rua = trim($_POST["rua"]);
	}
	// Check if bairro is empty
	if(empty(trim($_POST["bairro"]))){
		$bairro_err = "Insira o bairro";
	} else{
		$bairro = trim($_POST["bairro"]);
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

	// Check if ano is empty
	if(empty(trim($_POST["ano"]))){
		$ano_err = "Insira o ano de início";
	} else{
		$ano = trim($_POST["ano"]);
	}
	// Check if mes is empty
	if(empty(trim($_POST["mes_inicio"]))){
		$mes_inicio_err = "Insira o mes de início";
	} else{
		$mes_inicio = trim($_POST["mes_inicio"]);
	}

				
	// Save other data
	$categoria = trim($_POST["categoria"]);
	$numero = trim($_POST["numero"]);
	$complemento = trim($_POST["complemento"]);
	$cep = trim($_POST["cep"]);
	$periodo = trim($_POST["periodo"]);
	$id = $_SESSION["id"];
	
	// Check input errors before inserting in database
	if(empty($nome_err) && empty($preco_err) && empty($descricao_err) && empty($rua_err) && empty($bairro_err) && empty($cidade_err) && empty($estado_err)){
		// Prepare an insert statement
		$sql = "INSERT INTO imovel (nome, preco, descricao, categoria, rua, numero, bairro, complemento, cidade, estado, cep, cod_usuario) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
		$stmt = mysqli_prepare($link, $sql);	

		// Bind variables to the prepared statement as parameters
		mysqli_stmt_bind_param($stmt, "sdsssisssssi", $nome, $preco, $descricao, $categoria, $rua, $numero, $bairro, $complemento, $cidade, $estado, $cep, $id);
		
		// Se imovel cadastrado, cadastrar periodo disponivel
		if(mysqli_stmt_execute($stmt)){
			// obter ultimo cod_imovel da tabela imovel
			$temp_sql = "SELECT cod_imovel FROM imovel ORDER BY cod_imovel DESC LIMIT 1";
			$temp_stmt = mysqli_prepare($link, $temp_sql);
			// executa a query
			mysqli_stmt_execute($temp_stmt);
			// salva o resultado
			mysqli_stmt_store_result($temp_stmt);
			// grava o valor obtido na variável cod_imovel
			mysqli_stmt_bind_result($temp_stmt, $cod_imovel);
			mysqli_stmt_fetch($temp_stmt);

			$sql = "INSERT INTO reserva (ano, mes_inicio, periodo, cod_imovel) VALUES (?, ?, ?, ?)";
			$stmt = mysqli_prepare($link, $sql);	
			mysqli_stmt_bind_param($stmt, "iiii", $ano, $mes_inicio, $periodo, $cod_imovel);
			// Se disponibilidade cadastrada
			if(mysqli_stmt_execute($stmt)){
				// Redirect to cadastrar page
				header("location: cadastrar.php");
				$add_err = "Cadastro realizado com sucesso.";
			} else{
				echo "Oops! Ocorreu algum erro inesperado! Tente novamente mais tarde.";
			}
		} else{
			echo "Oops! Ocorreu algum erro inesperado! Tente novamente mais tarde.";
		}
		
		// Close statement
		mysqli_stmt_close($stmt);
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
			<p class="subtitle">Cadastrar residência</p>

			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

			<div class="form-line">
					<label>Nome*:</label><br>
					<input type="text" name="nome" class=" <?php echo (!empty($nome_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $nome; ?>">
				</div>
				<span class="warning"><?php echo $preco_err; ?></span>

				<div class="form-line">
					<label>Preço (mês)*:</label><br>
					<input type="number" name="preco" class=" <?php echo (!empty($preco_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $preco; ?>">
				</div>
				<span class="warning"><?php echo $preco_err; ?></span>

				<div class="form-line">
					<label>Descrição*:</label><br>
					<input type="text" name="descricao" class=" <?php echo (!empty($descricao_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $descricao; ?>">
				</div>
				<span class="warning"><?php echo $descricao_err; ?></span>

				<div class="form-line">
					<label>Categoria:</label><br>
					<input type="text" name="categoria" class="<?php echo (!empty($categoria_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $categoria; ?>">
				</div>
				<span class="warning"><?php echo $categoria_err; ?></span>

				<div class="form-line">
					<label>Rua*:</label><br>
					<input type="text" name="rua" class="<?php echo (!empty($rua_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $rua; ?>">
				</div>
				<span class="warning"><?php echo $rua_err; ?></span>
				<div class="form-line">
					<label>Número:</label><br>
					<input type="number" name="numero" class="<?php echo (!empty($numero_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $numero; ?>">
				</div>
				<span class="warning"><?php echo $numero_err; ?></span>
				<div class="form-line">
					<label>Bairro*:</label><br>
					<input type="text" name="bairro" class="<?php echo (!empty($bairro_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $bairro; ?>">
				</div>
				<span class="warning"><?php echo $bairro_err; ?></span>
				<div class="form-line">
					<label>Complemento:</label><br>
					<input type="text" name="complemento" class="<?php echo (!empty($complemento_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $complemento; ?>">
				</div>
				<span class="warning"><?php echo $complemento_err; ?></span>
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
				<div class="form-line">
					<label>CEP:</label><br>
					<input type="text" name="cep" class="<?php echo (!empty($cep_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cep; ?>">
				</div>
				<span class="warning"><?php echo $cep_err; ?></span>

				<div class="form-line">
					<p>Disponibilidade</p>
				</div>

				<div class="form-line">
					<label>Ano:</label><br>
					<select name="ano" id="ano">
						<option value="2023">2023</option>
						<option value="2024">2024</option>
					</select>
				</div>

				<div class="form-line">
					<label>Mês de início:</label><br>
					<select name="mes_inicio" id="mes_inicio">
					<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
				<!-- </div> -->
				<!-- <div class="form-line"> -->
					<label>Período (meses):</label><br>
					<select name="periodo" id="periodo">
					<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
				</div>

				<div>
					<button type="submit" class="button" value="Cadastrar">Cadastrar</button>
				</div>
			</form>
		</div>
	</div>

<?php require_once('footer.php') ?>
