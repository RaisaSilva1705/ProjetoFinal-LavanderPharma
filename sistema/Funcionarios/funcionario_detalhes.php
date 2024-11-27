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

// Verificar se o parâmetro "codigo" foi passado pela URL
if (isset($_GET['codigo'])) {
    $codigo_funcionario = $_GET['codigo'];

    // Consultar os dados do cliente no banco de dados
    $sql = "SELECT * FROM FUNCIONARIOS WHERE ID_Funcionario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_funcionario);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o cliente foi encontrado
    if ($result->num_rows > 0)
        $funcionario = $result->fetch_assoc();
    else{
        echo "Funcionário não encontrado.";
        exit();
    }
}
else{
    echo "Código do funcionário não fornecido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
                    <li class='nav-item'><a class='nav-link active' aria-current='page' href='index.php'>Voltar</a></li>
                    <li class='nav-item'><a class='nav-link' href="../../../dev/Exec/sair.php">Sair</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container my-5">
        <h3 class="text-center mb-4">Detalhes do Funcionário</h3>
        <div class="row">
            <div class="col-3">
                <h5>Nome Completo:</h5>
                <p><?php echo $funcionario['Nome_Funcionario']; ?></p>
            </div>
            <div class="col-3">
                <h5>Tipo de Pessoa:</h5>
                <p><?php echo $funcionario['Tipo_Funcionario']; ?></p>
            </div>
            <div class="col-3">
                <h5>Documento:</h5>
                <p><?php echo $funcionario['Documento_Funcionario']; ?></p>
            </div>
        </div>

        <h3 class="text-center mb-4 mt-5">Contato</h3>
        <div class="row md-3">
            <div class="col-3">
                <h5>Telefone:</h5>
                <p><?php echo $funcionario['Tel_Funcionario']; ?></p>
            </div>
            <div class="col-3">
                <h5>E-mail:</h5>
                <p><?php echo $funcionario['Email_Funcionario']; ?></p>
            </div>
        </div>

        <h3 class="text-center mb-4 mt-5">Dados do Contrato</h3>
        <div class="row md-3">
            <div class="col-3">
                <h5>Cargo:</h5>
                <p><?php echo $funcionario['Cargo_Funcionario']; ?></p>
            </div>
            <div class="col-3">
                <h5>Salário:</h5>
                <p><?php echo $funcionario['Salario_Funcionario']; ?></p>
            </div>
            <div class="col-3">
                <h5>Data de Admissão:</h5>
                <p>
                    <?php 
                        $dataAdmissao = new DateTime($funcionario['DataAdmissao_Funcionario']);
                        echo $dataAdmissao->format('d/m/Y'); 
                    ?>
                </p>
            </div>
        </div>

        <h3 class="text-center mb-4 mt-5">Outras Informações</h3>
        <div class="row md-3">
            <div class="col-3">
                <h5>Status:</h5>
                <p><?php echo ($funcionario['Status_Funcionario'] == '1') ? 'Ativo' : 'Inativo'; ?></p>
            </div>
            <div class="col-3">
                <h5>Data de Cadastro:</h5>
                <p>
                    <?php 
                        $dataCadastro = new DateTime($funcionario['DataCadastro_Funcionario']);
                        echo $dataCadastro->format('d/m/Y H:i:s'); 
                    ?>
                </p>

            </div>
            <div class="col-3">
                <h5>Última Alteração:</h5>
                <p>
                    <?php 
                        if (!empty($funcionario['DataAlteracao_Funcionario'])) {
                            $dataAlteracao = new DateTime($funcionario['DataAlteracao_Funcionario']);
                            echo $dataAlteracao->format('d/m/Y H:i:s');
                        } else {
                            echo "Não alterado";
                        }
                    ?>
                </p>
            </div>
        </div>
        
        <a href="funcionario_editar.php?codigo=<?php echo $codigo_funcionario; ?>" class="btn btn-primary mt-3">Editar</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>