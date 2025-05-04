<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";

include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

$sql = "SELECT
            P.ID_Produto,
            P.Nome,
            P.Marca,
            HP.Preco,
            C.Categoria,
            E.Quantidade
        FROM PRODUTOS P 
        LEFT JOIN HISTORICO_PRECOS HP 
            ON P.ID_Produto = HP.ID_Produto
        LEFT JOIN CATEGORIAS C
            ON C.ID_Categoria = P.ID_Categoria
        LEFT JOIN ESTOQUE E
            ON E.ID_Produto = P.ID_Produto";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listagem de Produtos</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
    </head>
    <body class="bg-light">
        <!-- Sidebar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Listagem de Produtos</h3>
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
                    <h2>Lista de Produto</h2>
                    <a href="cadastrar_produto.php" class="btn btn-primary">Cadastrar Novo Produto</a>
                </div>

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Produto</th>
                            <th scope="col">Fornecedor</th>
                            <th scope="col">Categoria</th>
                            <th scope="col">Estoque</th>
                            <th scope="col">Preço</th>
                            <th scope="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) { // quebra de página após 20 resultados
                                    $preco = ($row['Preco'] == null) ?  0.00 : $row['Preco']; 
                                    echo '<tr>';
                                        echo '<td>' . $row["ID_Produto"] . '</td>';
                                        echo '<td>' . $row["Nome"] . '</td>';
                                        echo '<td>' . $row["Marca"] . '</td>';
                                        echo '<td>' . $row["Categoria"] . '</td>';
                                        echo '<td>' . $row["Quantidade"] . '</td>';
                                        echo '<td> R$ ' . $preco . '</td>';
                                        echo '<td>
                                                <a href="editar_produto.php?codigo=' . $row["ID_Produto"] . '" class="btn btn-info btn-sm">Editar</a>
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
        </div>

    </body>
</html>
