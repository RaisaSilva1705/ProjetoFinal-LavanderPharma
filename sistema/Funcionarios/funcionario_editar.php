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

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $_POST['Nome_Funcionario'];
    $tipo_funcionario = $_POST['Tipo_Funcionario'];
    $documento = $_POST['Documento_Funcionario'];
    $email = $_POST['Email_Funcionario'];
    $telefone = $_POST['Tel_Funcionario'];
    $cargo = $_POST['Cargo_Funcionario'];
    $salario = $_POST['Salario_Funcionario'];
    $data_adimissao = $_POST['DataAdmissao_Funcionario'];
    //$obs = $_POST['obs'];
    $status = $_POST['Status_Funcionario'];

    // Atualizar os dados do funcionário no banco de dados
    $sql = "UPDATE FUNCIONARIOS SET 
                Nome_Funcionario = ?, Tipo_Funcionario = ?, Documento_Funcionario = ?, 
                Email_Funcionario = ?, Tel_Funcionario = ?, Cargo_Funcionario = ?, Salario_Funcionario = ?, 
                DataAdmissao_Funcionario = ?, Status_Funcionario = ?, DataAlteracao_Funcionario = NOW() 
            WHERE ID_Funcionario = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssisss", 
        $nome_completo, $tipo_funcionario, $documento, $email, $telefone,
        $cargo, $salario, $data_adimissao, $status, $codigo_funcionario);

    if ($stmt->execute()) {
        session_start();
        $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Dados atualizados com sucesso!
                            </div>";
        header("Location: index.php"); // Redirecionar para a listagem de funcionários
        exit();
    } else {
        echo "Erro ao atualizar os dados: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Funcionário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
        <div class='container-fluid'>
            <a class='navbar-brand' href='../../index2.php'>LavanderPharma</a>
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

    <!-- Formulário de Edição -->
    <div class="container my-5">
        <h3 class="text-center mb-4">Editar Funcionário</h3>
        <form method="post" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="Nome_Funcionario" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="Nome_Funcionario" name="Nome_Funcionario" value="<?php echo htmlspecialchars($funcionario['Nome_Funcionario']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="Tipo_Funcionario" class="form-label">Tipo de Pessoa</label>
                    <select class="form-select" id="Tipo_Funcionario" name="Tipo_Funcionario" required>
                        <option value="PF" <?php if ($funcionario['Tipo_Funcionario'] == 'PF') echo 'selected'; ?>>Pessoa Física</option>
                        <option value="PJ" <?php if ($funcionario['Tipo_Funcionario'] == 'PJ') echo 'selected'; ?>>Pessoa Jurídica</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="Documento_Funcionario" class="form-label">Documento</label>
                    <input type="text" class="form-control" id="Documento_Funcionario" name="Documento_Funcionario" value="<?php echo htmlspecialchars($funcionario['Documento_Funcionario']); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="Email_Funcionario" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="Email_Funcionario" name="Email_Funcionario" value="<?php echo htmlspecialchars($funcionario['Email_Funcionario']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="Tel_Funcionario" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="Tel_Funcionario" name="Tel_Funcionario" value="<?php echo htmlspecialchars($funcionario['Tel_Funcionario']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="Cargo_Funcionario" class="form-label">Cargo</label>
                    <select class="form-select" id="Cargo_Funcionario" name="Cargo_Funcionario" required>
                        <option value="Farmacêutico" <?php if ($funcionario['Cargo_Funcionario'] == 'Farmacêutico') echo 'selected'; ?>>Farmacêutico</option>
                        <option value="Auxilizar de Farmácia" <?php if ($funcionario['Cargo_Funcionario'] == 'Auxilizar de Farmácia') echo 'selected'; ?>>Auxilizar de Farmácia</option>
                        <option value="Atendente de Farmácia" <?php if ($funcionario['Cargo_Funcionario'] == 'Atendente de Farmácia') echo 'selected'; ?>>Atendente de Farmácia</option>
                        <option value="Gerente" <?php if ($funcionario['Cargo_Funcionario'] == 'Gerente') echo 'selected'; ?>>Gerente</option>
                        <option value="Subgerente" <?php if ($funcionario['Cargo_Funcionario'] == 'Subgerente') echo 'selected'; ?>>Subgerente</option>
                        <option value="RH" <?php if ($funcionario['Cargo_Funcionario'] == 'RH') echo 'selected'; ?>>RH</option>
                        <option value="Auxiliar Administrativo" <?php if ($funcionario['Cargo_Funcionario'] == 'Auxiliar Administrativo') echo 'selected'; ?>>Auxiliar Administrativo</option>
                        <option value="Auxiliar de Limpeza" <?php if ($funcionario['Cargo_Funcionario'] == 'Auxiliar de Limpeza') echo 'selected'; ?>>Auxiliar de Limpeza</option>
                        <option value="Consultor(a) de Dermocosméticos" <?php if ($funcionario['Cargo_Funcionario'] == 'Consultor(a) de Dermocosméticos') echo 'selected'; ?>>Consultor(a) de Dermocosméticos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="Salario_Funcionario" class="form-label">Salário</label>
                    <input type="number" class="form-control" id="Salario_Funcionario" name="Salario_Funcionario" value="<?php echo htmlspecialchars($funcionario['Salario_Funcionario']); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="DataAdmissao_Funcionario" class="form-label">Data de Admissão</label>
                    <input type="date" class="form-control" id="DataAdmissao_Funcionario" name="DataAdmissao_Funcionario" value="<?php echo htmlspecialchars($funcionario['DataAdmissao_Funcionario'] ?? ''); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="Status_Funcionario" class="form-label">Status</label>
                    <select class="form-select" id="Status_Funcionario" name="Status_Funcionario" required>
                        <option value="1" <?php if ($funcionario['Status_Funcionario'] == '1') echo 'selected'; ?>>Ativo</option>
                        <option value="0" <?php if ($funcionario['Status_Funcionario'] == '0') echo 'selected'; ?>>Inativo</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
            <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>
        </form>
    </div>
</body>
</html>
