<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Incluir o arquivo de conexão
include '../../dev/Exec/conexao.php';
include "../../dev/Exec/validar_sessao.php";

$cargo = $_SESSION['Cargo_Funcionario'];
if ($cargo == 'Gerente' || $cargo == 'Subgerente' || $cargo == 'RH'){
    
}
else {
    $_SESSION["msg"] = "<div class='alert alert-danger'>Você não tem acesso a essa área.</div>";
    header('Location: http://localhost/htdocs/Farmácia/index2.php');
    exit;
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $_POST['Nome_Funcionario'] ?? null;
    $tipo_funcionario = $_POST['Tipo_Funcionario'] ?? null;
    $documento = $_POST['Documento_Funcionario'] ?? null;
    $email = $_POST['Email_Funcionario'] ?? null;
    $telefone = $_POST['Tel_Funcionario'] ?? null;
    $cargo = $_POST['Cargo_Funcionario'] ?? null;
    $salario = $_POST['Salario_Funcionario'] ?? null;
    $data_adimissao = $_POST['DataAdmissao_Funcionario'] ?? null;
    //$obs = $_POST['obs'] ?? null;
    $status = $_POST['Status_Funcionario'] ?? null;

    if (isset($_POST['Senha']) && isset($_POST['confirmeSenha'])){
        if ($_POST['Senha'] === $_POST['confirmeSenha'])
            $passHash = password_hash($_POST['Senha'], PASSWORD_DEFAULT);
        else
            echo "<script>alert('As senhas não coincidem. Por favor, tente novamente.');</script>";
    }
    else
        echo "<script>alert('Por favor, preencha os campos de senha.');</script>";

    // Cadastrar o novo funcionário no banco de dados
    $sql = "INSERT INTO FUNCIONARIOS (
            Nome_Funcionario, Tipo_Funcionario, Documento_Funcionario, Tel_Funcionario, Cargo_Funcionario,
            Email_Funcionario, Senha_Funcionario, Salario_Funcionario, DataAdmissao_Funcionario,
            Status_Funcionario, DataCadastro_Funcionario, DataAlteracao_Funcionario)
            VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssssisi", 
        $nome_completo, $tipo_funcionario, $documento, $telefone, $cargo,
        $email, $passHash, $salario, $data_adimissao, $status);

    if ($stmt->execute()) {
        session_start();
        $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Funcionário cadastrado com sucesso!
                            </div>";
        header("Location: index.php"); // Redirecionar para a listagem de funcionários
        exit();
    } else {
        echo "Erro ao cadastrar funcionário: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Funcionário</title>
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
                        <li class='nav-item'><a class='nav-link active' href='index.php'>Voltar</a></li>
                        <li class='nav-item'><a class='nav-link' href="../../../dev/Exec/sair.php">Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>

    <!-- Formulário de Cadastro -->
    <div class="container my-5">
        <h3 class="text-center mb-4">Cadastrar Funcionário</h3>
        <form method="post" action="">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="Nome_Funcionario" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="Nome_Funcionario" name="Nome_Funcionario" required>
                </div>
                <div class="col-md-3">
                    <label for="Tipo_Cliente" class="form-label">Tipo de Pessoa</label>
                    <select class="form-select" id="Tipo_Cliente" name="Tipo_Cliente" required>
                        <option value="PF">Pessoa Física</option>
                        <option value="PJ">Pessoa Jurídica</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="Documento_Funcionario" class="form-label">Documento</label>
                    <input type="text" class="form-control" id="Documento_Funcionario" name="Documento_Funcionario" required>
                </div>
                <div class="col-md-3">
                    <label for="Tel_Funcionario" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="Tel_Funcionario" name="Tel_Funcionario" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="Email_Funcionario" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="Email_Funcionario" name="Email_Funcionario" required>
                </div>
                <div class="col-md-3">
                    <label for="Senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="Senha" name="Senha" required>
                </div>
                <div class="col-md-3">
                    <label for="confirmeSenha" class="form-label">Confirme a Senha</label>
                    <input type="password" class="form-control" id="confirmeSenha" name="confirmeSenha" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="Cargo_Funcionario" class="form-label">Cargo</label>
                    <select class="form-select" id="Cargo_Funcionario" name="Cargo_Funcionario" required>
                        <option value="Farmacêutico">Farmacêutico</option>
                        <option value="Auxilizar de Farmácia">Auxilizar de Farmácia</option>
                        <option value="Atendente de Farmácia">Atendente de Farmácia</option>
                        <option value="Gerente">Gerente</option>
                        <option value="Subgerente">Subgerente</option>
                        <option value="RH">RH</option>
                        <option value="Auxiliar Administrativo">Auxiliar Administrativo</option>
                        <option value="Auxiliar de Limpeza">Auxiliar de Limpeza</option>
                        <option value="Consultor(a) de Dermocosméticos">Consultor(a) de Dermocosméticos</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="Salario_Funcionario" class="form-label">Salário</label>
                    <input type="number" class="form-control" id="Salario_Funcionario" name="Salario_Funcionario" required>
                </div>
                <div class="col-md-3">
                    <label for="DataAdmissao_Funcionario" class="form-label">Data de Admissão</label>
                    <input type="date" class="form-control" id="DataAdmissao_Funcionario" name="DataAdmissao_Funcionario" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="Status_Funcionario" class="form-label">Status</label>
                    <select class="form-select" id="Status_Funcionario" name="Status_Funcionario" required>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Cadastrar Cliente</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.getElementById('CEP').addEventListener('input', function() {
            let cep = this.value.trim();

            if (cep.length === 8) {  // Verifica se o CEP possui 8 dígitos
                fetch('busca_cep.php?cep=' + cep)
                    .then(response => response.text())
                    .then(text => {
                        console.log("Resposta recebida:", text);
                        const data = JSON.parse(text);
                        if (data.success) {
                            // Preencher os campos com os dados retornados
                            document.getElementById('Endereco_Cliente').value = data.endereco || '';
                            document.getElementById('Bairro_Cliente').value = data.bairro || '';
                            document.getElementById('Cidade_Cliente').value = data.cidade || '';
                            document.getElementById('Estado_Cliente').value = data.uf || '';
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => console.error('Erro:', error));
            }
        });
    </script>
</body>
</html>
