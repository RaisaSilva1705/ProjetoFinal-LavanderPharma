<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir o arquivo de conexão
include '../../dev/Exec/conexao.php';
include "../../dev/Exec/validar_sessao.php";

// Definir valores padrão para filtro e ordenação
$order_by = "ID_Cliente";
$order_dir = "ASC";  // Ordem ascendente por padrão
$status_filter = ""; // Sem filtro de status por padrão

// Verificar se o usuário selecionou algum critério de ordenação ou filtro
if (isset($_GET['order_by'])) {
    $order_by = $_GET['order_by']; // Capturar o critério de ordenação
}

if (isset($_GET['order_dir']) && ($_GET['order_dir'] == 'ASC' || $_GET['order_dir'] == 'DESC')) {
    $order_dir = $_GET['order_dir']; // Capturar a direção da ordenação
}

if (isset($_GET['status'])) {
    $status_filter = $_GET['status']; // Capturar o filtro de status (ativo/inativo)
}

// Alternar entre ASC e DESC para o próximo clique
$next_order_dir = $order_dir == "ASC" ? "DESC" : "ASC";

// Construir a consulta SQL com base na ordenação e filtro
$sql = "SELECT * FROM CLIENTES";
if ($status_filter !== "") {
    $sql .= " WHERE status = '$status_filter'";
}
$sql .= " ORDER BY $order_by $order_dir";

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
                width: 100%;
                background-color: #f8f9fa;
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
            <div class='container-fluid'>
                <a class='navbar-brand' href='#'>LavanderPharma</a>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
                    <span class='navbar-toggler-icon'></span>
                </button>
                <div class='collapse navbar-collapse' id='navbarNav'>
                    <ul class='navbar-nav ms-auto'>
                        <a href="cliente_cadastrar.php">
                            <button class="btn btn-secondary" type="button">Cadastrar</button>
                        </a>
                        <li class='nav-item'><a class='nav-link active' href='../../index2.php'>Menu Funcionário</a></li>
                        <li class='nav-item'><a class='nav-link' href="../../dev/Exec/sair.php">Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Banner -->
        <div class="container-fluid bg-secondary text-white text-center p-4">
            <h3>Gerenciamento de CLIENTES</h3>
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
                        <th scope="col">
                            <a class="col" href="?order_by=ID_Cliente&order_dir=<?= $next_order_dir ?>">Código</a>
                        </th>
                        <th scope="col">
                            <a class="col" href="?order_by=Nome_Cliente&order_dir=<?= $next_order_dir ?>">Nome Completo</a>
                        </th>
                        <th scope="col">
                            <a class="col" href="?order_by=Tipo_Cliente&order_dir=<?= $next_order_dir ?>">Tipo Pessoa</a>
                        </th>
                        <th scope="col">Documento</th>
                        <th scope="col">E-mail</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">
                            <a class="col" href="?order_by=Cidade_Cliente&order_dir=<?= $next_order_dir ?>">Cidade</a>
                        </th>
                        <th scope="col">
                            <a class="col" href="?order_by=Estado_Cliente&order_dir=<?= $next_order_dir ?>">Estado</a>
                        </th>
                        <th scope="col">
                            <a class="col" href="?order_by=Status_Cliente&order_dir=<?= $next_order_dir ?>">Status</a>
                        </th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { // quebra de página após 20 resultados
                            echo '<tr>';
                            echo '<td>' . $row["ID_Cliente"] . '</td>';
                            echo '<td>' . $row["Nome_Cliente"] . '</td>';
                            echo '<td>' . $row["Tipo_Cliente"] . '</td>';
                            echo '<td>' . $row["Documento_Cliente"] . '</td>';
                            echo '<td>' . $row["Email_Cliente"] . '</td>';
                            echo '<td>' . $row["Tel_Cliente"] . '</td>';
                            echo '<td>' . $row["Cidade_Cliente"] . '</td>';
                            echo '<td>' . $row["Estado_Cliente"] . '</td>';
                            echo '<td>' . ($row["Status_Cliente"] == '1' ? 'Ativo' : 'Inativo') . '</td>';
                            echo '<td>
                                    <a href="cliente_editar.php?codigo=' . $row["ID_Cliente"] . '" class="btn btn-info btn-sm">Editar</a>
                                    <a href="cliente_detalhes.php?codigo=' . $row["ID_Cliente"] . '" class="btn btn-warning btn-sm">Ver Detalhes</a>
                                </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="10" class="text-center">Nenhum cliente encontrado.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <footer class="bg-light text-center text-lg-start">
            <div class="text-center p-3 bg-dark text-white">
                <p>© 2024 LavanderPharma - Todos os direitos reservados.</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>