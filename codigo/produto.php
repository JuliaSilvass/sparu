<?php
    
$title = 'Produto';
// Initialize the session
session_start();

include('./access/protect.php');
require_once "./access/db_config.php";

$descricao = $preco = $status = $cidade = $estado = $usuario = $telefone = "";
$descricao_err = $preco_err =  $status_err = $cidade_err = $estado_err = $usuario_err = $telefone_err = "";

//Para verificar se a busca ocorreu 
$busca_sucesso =  false;

$sql = "SELECT produto.*, usuario.nome AS vendedor, telefone.num1 AS contato 
	FROM produto 
	JOIN usuario ON produto.cod_usuario = usuario.cod_usuario 
	JOIN telefone ON usuario.cod_usuario = telefone.cod_usuario";
        
$stmt = mysqli_prepare($link, $sql);

if ($stmt) {
	if(mysqli_stmt_execute($stmt)){
		$result = mysqli_stmt_get_result($stmt);

		// Verifica se tem resultados
		if (mysqli_num_rows($result) > 0){
			$busca_sucesso = true;
		} else {
			echo "Nenhum resultado encontrado.";
		}
    }

	mysqli_stmt_close($stmt);
} else {
	echo "Erro na preparação da declaração SQL.";
}

mysqli_close($link);
?>

<?php 
require_once ('base.php');?>
<?php 
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
	require_once('nav-logged.php');
}
else{
	require_once('nav.php');
}
?>

<?php require_once("header.php") ?>

<div class="tabela">
	<center>
    <?php
        if($busca_sucesso){
			echo "<table border='1'>";
			echo "<tr><th>Descrição</th><th>Preço</th><th>Status</th><th>Cidade</th><th>Estado</th><th>Vendedor</th><th>Contato</th></tr>";
			
			while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
				echo "<td>" . $row['descricao'] . "</td>";
				echo "<td>" . $row['preco'] . "</td>";
                echo "<td>" . $row['status'] . "</td>";
				echo "<td>" . $row['cidade'] . "</td>";
				echo "<td>" . $row['estado'] . "</td>";
				echo "<td>" . $row['vendedor'] . "</td>";
				echo "<td>" . $row['contato'] . "</td>";
				echo "</tr>";
            }

			echo "</table>";
        }
    ?>
	</center>
</div>

<?php require_once('footer.php') ?>