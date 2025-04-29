<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../../Dev/Exec/config.php";

include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

$sql = "SELECT * FROM UNIDADES";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listagem de Unidades</title>
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
                <h3>Listagem de Unidades</h3>
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
                    <h2>Lista de Unidade</h2>
                    <a href="cadastrar_unidade.php" class="btn btn-primary">Cadastrar Nova Unidade</a>
                </div>

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Unidade</th>
                            <th scope="col">Abreviação</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { // quebra de página após 20 resultados
                                    echo '<tr>';
                                    echo '<td>' . $row["ID_Unidade"] . '</td>';
                                    echo '<td>' . $row["Unidade"] . '</td>';
                                    echo '<td>' . $row["Abreviacao"] . '</td>';
                                    echo '<td>' . $row["Tipo"] . '</td>';
                                    echo '<td>
                                            <a href="editar_unidade.php?codigo=' . $row["ID_Unidade"] . '" class="btn btn-info btn-sm">Editar</a>
                                            <a href="excluir_unidade.php?codigo=' . $row["ID_Unidade"] . '" class="btn btn-danger btn-sm">Excluir</a>
                                          </td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">Nenhuma unidade cadastrada.</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </body>
</html>
