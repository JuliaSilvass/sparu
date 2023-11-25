<?php
$title = 'Buscar Produto';
// Initialize the session
session_start();
 
include('./access/protect.php');
require_once "./access/db_config.php";
 
$descricao = $preco = $status = $cidade = $estado = $usuario = $telefone = "";
$descricao_err = $preco_err =  $status_err = $cidade_err = $estado_err = $usuario_err = $telefone_err = "";

//Para verificar se a busca ocorreu 
$busca_sucesso =  false;

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(empty(trim($_POST["estado"]))){
        $estado_err = "Insira o estado";
    } else {
        $estado = trim($_POST["estado"]);
    }
    if(empty(trim($_POST["cidade"]))){
        $cidade_err = "Insira a cidade";
    } else {
        $cidade = trim($_POST["cidade"]);
    } 
    
    if (empty($estado_err) && empty($cidade_err)) {
        $sql = "SELECT produto.*, usuario.nome AS vendedor, telefone.num1 AS contato 
            FROM produto 
            JOIN usuario ON produto.cod_usuario = usuario.cod_usuario 
            JOIN telefone ON usuario.cod_usuario = telefone.cod_usuario
            WHERE produto.cidade = ? AND produto.estado = ?";
        
        $stmt = mysqli_prepare($link, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $cidade, $estado);

            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);

                // Verifica se tem resultados
                if (mysqli_num_rows($result) > 0){

                    $busca_sucesso = true;

                } else {
                    echo "Nenhum resultado encontrado.";
                }
            }

            if (mysqli_stmt_execute($stmt)) {
                //header("location: buscar.php");
                $add_err = "Busca realizada.";
            } else {
                echo "Oops! Ocorreu algum erro inesperado! Tente novamente mais tarde.";
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Erro na preparação da declaração SQL.";
        }

    }
    mysqli_close($link);
}
?>

<?php require_once('base.php') ?>
<?php
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    require_once('nav-logged.php');
} else {
    require_once('nav.php');
}
?>

<?php require_once('header.php') ?>

<div class="caixa">
    <center>
        <p class="subtitle">Buscar produto</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-line">
                <label>Estado*:</label><br>
                <input type="text" name="estado" class="<?php echo (!empty($estado_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $estado; ?>">
                <?php if (!empty($estado_err)) echo '<div class="invalid-feedback">' . $estado_err . '</div>'; ?>
            </div>
            <div class="form-line">
                <label>Cidade*:</label><br>
                <input type="text" name="cidade" class="<?php echo (!empty($cidade_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $cidade; ?>">
                <?php if (!empty($cidade_err)) echo '<div class="invalid-feedback">' . $cidade_err . '</div>'; ?>
            </div>

            <div>
                <button type="submit" class="button" value="Buscar">Buscar</button>
            </div>
        </form>
        <?php 
        if (!empty($add_err)){
            echo '<div class="">' . $add_err . '</div>';
        }
        ?>
    </center>
</div>
<div>
    <br>
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
</div>

<?php require_once('footer.php') ?>