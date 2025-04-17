<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../../Dev/Exec/config.php";

include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

$sql = "SELECT * FROM CARGOS_FUNCIONARIOS";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listagem de Cargo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
    </head>
    <body class="bg-light">
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Listagem de Cargos</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>

            <div class="container mt-3 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Lista de Cargos</h2>
                    <a href="cadastrar_cargo.php" class="btn btn-primary">Cadastrar Novo Cargo</a>
                </div>

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nome do Cargo</th>
                            <th scope="col">Descrição</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { // quebra de página após 20 resultados
                                    echo '<tr>';
                                    echo '<td>' . $row["ID_Cargo"] . '</td>';
                                    echo '<td>' . $row["Cargo"] . '</td>';
                                    echo '<td>' . $row["Descricao"] . '</td>';
                                    echo '<td>
                                            <a href="editar_cargo.php?codigo=' . $row["ID_Cargo"] . '" class="btn btn-info btn-sm">Editar</a>
                                        </td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">Nenhum cargo cadastrado.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </body>
</html>
