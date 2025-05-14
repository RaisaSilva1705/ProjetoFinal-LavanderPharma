<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Construir a consulta SQL
$sql = "SELECT
            P.ID_Produto,
            P.Nome,
            C.Categoria,
            E.Quantidade,
            P.Quant_Minima
        FROM PRODUTOS P LEFT JOIN CATEGORIAS C
            ON P.ID_Categoria = C.ID_Categoria
        LEFT JOIN ESTOQUE E 
            ON P.ID_Produto = E.ID_Produto";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
    </head>
    <body>
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Gerenciamento de ESTOQUE</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>
        
            <div class="container p-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Lista de Produtos</h2>
                    <div>
                        <a href="movimentacao_estoque.php?mov=E" class="btn btn-primary">Entrada</a>
                        <a href="movimentacao_estoque.php?mov=S" class="btn btn-primary">Saída</a>
                    </div>
                </div>
                <!-- Tabela de Clientes -->
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Produto</th>
                            <th scope="col">Categorias</th>
                            <th scope="col">Estoque Atual</th>
                            <th scope="col">Quant. Mínima</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) { // quebra de página após 20 resultados
                                if($row["Quantidade"] <= $row["Quant_Minima"]){
                                    $class = ($row["Quantidade"] == $row["Quant_Minima"]) ? "table-warning" : "table-danger";
                                }
                                else $class = "table-success";

                                echo '<tr>';
                                echo '<td>' . $row["Nome"] . '</td>';
                                echo '<td>' . $row["Categoria"] . '</td>';
                                echo '<td class="' . $class . '">' . $row["Quantidade"] . '</td>';
                                echo '<td>' . $row["Quant_Minima"] . '</td>';
                                echo '<td>
                                        <a href="movimentacao_estoque.php?mov=E&codigo=' . $row['ID_Produto'] . '" class="btn btn-primary btn-sm">Entrada</a>
                                        <a href="movimentacao_estoque.php?mov=S&codigo=' . $row['ID_Produto'] . '" class="btn btn-danger btn-sm">Saída</a>
                                    </td>';
                                echo '</tr>';
                            }
                        } else {
                            echo '<tr><td colspan="10" class="text-center">Nenhum produto cadastrado.</td></tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        
        
            <br><br><br><br><br><br><br><br><br><br><br>
            <!-- Footer -->
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>
    </body>
</html>