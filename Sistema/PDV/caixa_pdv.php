<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";

// Incluir o arquivo de conexão
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Busca caixas
$sqlCaixas = "SELECT ID_Caixa, Caixa FROM CAIXAS";
$caixas = $conn->query($sqlCaixas);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $_SESSION['ID_Caixa'] = $_POST['id_caixa'];

    header("Location: pdv.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Seleção de Caixa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
        <style>
            select > option:first-child {
                display: none;
            }
        </style>
    </head>
    <body class="bg-light">
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Seleção de Caixa</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>
            <div class="container m-4">
                <form action="#" method="POST">
                    <div class="col-md-3 mb-3">
                        <label for="id_caixa" class="form-label">Selecione o Caixa</label>
                        <select class="form-select" name="id_caixa" id="id_caixa" required>
                            <option value="">Selecione</option>
                            <?php while($caixa = $caixas->fetch_assoc()): ?>
                                <option value="<?= $caixa['ID_Caixa'] ?>"><?= $caixa['Caixa'] ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Abrir Caixa</button>
                </form>
            </div>
        </div>
    </body>
</html>