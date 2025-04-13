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
include '../../dev/Exec/conexao.php';
include "../../dev/Exec/validar_sessao.php";

// Construir a consulta SQL
$sql = "SELECT
            P.ID_Produto,
            P.Nome,
            P.Med,
            C.Categoria,
            E.Preco_Venda,
            E.Quantidade,
            P.Quant_Minima
        FROM PRODUTOS P LEFT JOIN CATEGORIAS C ON P.ID_Categoria = C.ID_Categoria
        LEFT JOIN ESTOQUE E ON P.ID_Produto = E.ID_Produto";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LavanderPharma</title>
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
            .navbu {
                margin: 2px;
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/header2.php'?>

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

        <!-- Filtros e Ordenação -->
        <div class="container my-5">
            <!-- Tabela de Clientes -->
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">Produto</th>
                        <th scope="col">Medicamento</th>
                        <th scope="col">Categorias</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Estoque Atual</th>
                        <th scope="col">Quant. Mínima</th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { // quebra de página após 20 resultados
                            $med = ($row["Med"] == TRUE) ? 'Sim' : 'Não';
                            echo '<tr>';
                            echo '<td>' . $row["Nome"] . '</td>';
                            echo '<td>' . $med . '</td>';
                            echo '<td>' . $row["Categoria"] . '</td>';
                            echo '<td>R$ ' . $row["Preco_Venda"] . '</td>';
                            echo '<td>' . $row["Quantidade"] . '</td>';
                            echo '<td>' . $row["Quant_Minima"] . '</td>';
                            echo '<td>
                                    <a href="movimentacao.php?mov=E" class="btn btn-info btn-sm">Adicionar</a>
                                    <a href="movimentacao.php?mov=S" class="btn btn-warning btn-sm">Remover</a>
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
        
        <br>
        <!-- Footer -->
        <footer class="bg-light text-center text-lg-start">
            <div class="text-center p-3 bg-dark text-white">
                <p>© 2024 LavanderPharma - Todos os direitos reservados.</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>