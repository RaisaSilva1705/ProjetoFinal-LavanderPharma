<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$cargo = $_SESSION['ID_Cargo'];
if ($cargo == 7){
    $_SESSION["msg"] = "<div class='alert alert-danger'>Você não tem acesso a essa área.</div>";
    header('Location: http://localhost/htdocs/Farmácia/index2.php');
    exit;
}

include "../../Dev/Exec/config.php";

// Incluir o arquivo de conexão
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Fornecedores</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .content {
                flex: 1;
            }
            footer {
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #f8f9fa;
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/header2.php'?>

        <!-- Banner -->
        <div class="container-fluid bg-secondary text-white text-center p-4">
            <h3>Fornecedores</h3>
            <?php
                // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                    echo $_SESSION["msg"];
                    // Limpa a mensagem para evitar que seja exibida novamente
                    $_SESSION["msg"] = null;
                }
            ?>
        </div>

        <!-- Footer -->
        <br><br><br>
        <?php include_once DEV_PATH . 'Views/footer.php'?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>