<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$cargo = $_SESSION['Cargo_Funcionario'];
if ($cargo == 'Gerente' || $cargo == 'Subgerente' || $cargo == 'RH'){
    
}
else {
    $_SESSION["msg"] = "<div class='alert alert-danger'>Você não tem acesso a essa área.</div>";
    header('Location: http://localhost/htdocs/Farmácia/index2.php');
    exit;
}

// Incluir o arquivo de conexão
include '../../dev/Exec/conexao.php';
include "../../dev/Exec/validar_sessao.php";

// Definir valores padrão para filtro e ordenação
$order_by = "ID_Funcionario";
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
$sql = "SELECT * FROM FUNCIONARIOS";
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
                        <a href="funcionario_cadastrar.php">
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
            <h3>Gerenciamento de FUNCIONÁRIOS</h3>
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
                            <a class="col" href="?order_by=ID_Funcionario&order_dir=<?= $next_order_dir ?>">Código</a>
                        </th>
                        <th scope="col">
                            <a class="col" href="?order_by=Nome_Funcionario&order_dir=<?= $next_order_dir ?>">Nome Completo</a>
                        </th>
                        <th scope="col">
                            <a class="col" href="?order_by=Tipo_Funcionario&order_dir=<?= $next_order_dir ?>">Tipo Pessoa</a>
                        </th>
                        <th scope="col">Documento</th>
                        <th scope="col">Telefone</th>
                        <th scope="col">Cargo</th>
                        <th scope="col">
                            <a class="col" href="?order_by=Salario_Funcionario&order_dir=<?= $next_order_dir ?>">Salário</a>
                        </th>
                        <th scope="col">
                            <a class="col" href="?order_by=DataAdmissao_Funcionario&order_dir=<?= $next_order_dir ?>">Admissão</a>
                        </th>
                        <th scope="col">
                            <a class="col" href="?order_by=Status_Funcionario&order_dir=<?= $next_order_dir ?>">Status</a>
                        </th>
                        <th scope="col">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) { // quebra de página após 20 resultados
                            echo '<tr>';
                            echo '<td>' . $row["ID_Funcionario"] . '</td>';
                            echo '<td>' . $row["Nome_Funcionario"] . '</td>';
                            echo '<td>' . $row["Tipo_Funcionario"] . '</td>';
                            echo '<td>' . $row["Documento_Funcionario"] . '</td>';
                            echo '<td>' . $row["Tel_Funcionario"] . '</td>';
                            echo '<td>' . $row["Cargo_Funcionario"] . '</td>';
                            echo '<td>' . $row["Salario_Funcionario"] . '</td>';
                            $dataAdmissao = new DateTime($row['DataAdmissao_Funcionario']);
                            echo '<td>' . $dataAdmissao->format('d/m/Y') . '</td>';
                            echo '<td>' . ($row["Status_Funcionario"] == '1' ? 'Ativo' : 'Inativo') . '</td>';
                            echo '<td>
                                    <a href="funcionario_editar.php?codigo=' . $row["ID_Funcionario"] . '" class="btn btn-info btn-sm">Editar</a>
                                    <a href="funcionario_detalhes.php?codigo=' . $row["ID_Funcionario"] . '" class="btn btn-warning btn-sm">Ver Detalhes</a>
                                </td>';
                            echo '</tr>';
                        }
                    } else {
                        echo '<tr><td colspan="10" class="text-center">Nenhum funcionário encontrado.</td></tr>';
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