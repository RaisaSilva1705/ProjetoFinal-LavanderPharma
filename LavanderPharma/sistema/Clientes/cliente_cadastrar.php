<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
// Incluir o arquivo de conexão
include '../../dev/Exec/conexao.php';
include "../../dev/Exec/validar_sessao.php";

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $_POST['Nome_Cliente'] ?? null;
    $tipo_cliente = $_POST['Tipo_Cliente'] ?? null;
    $documento = $_POST['Documento_Cliente'] ?? null;
    $email = $_POST['Email_Cliente'] ?? null;
    $telefone = $_POST['Tel_Cliente'] ?? null;
    $cep = $_POST['CEP'] ?? null;
    $endereco = $_POST['Endereco_Cliente'] ?? null;
    $endereco_numero = $_POST['EndNumero_Cliente'] ?? null;
    $complemento = $_POST['Complemento_Cliente'] ?? null;
    $bairro = $_POST['Bairro_Cliente'] ?? null;
    $cidade = $_POST['Cidade_Cliente'] ?? null;
    $estado = $_POST['Estado_Cliente'] ?? null;
    $nascimento = $_POST['DataNasc_Cliente'] ?? null;
    //$obs = $_POST['obs'] ?? null;
    $status = $_POST['Status_Cliente'] ?? null;

    // Verificar se o CEP já existe na tabela 'ceps'
    $sql = "SELECT * FROM CEPS WHERE CEP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cep);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        // Caso o CEP não exista, inserir na tabela 'ceps'
        $sql_insert_cep = "INSERT INTO CEPS (
            CEP, Endereco_CEP, Bairro_CEP, Cidade_CEP, Estado_CEP) VALUES (?, ?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert_cep);

        $stmt_insert->bind_param("sssss", $cep, $endereco, $bairro, $cidade, $estado);
        $stmt_insert->execute();
    }

    if (isset($_POST['Senha']) && isset($_POST['confirmeSenha'])){
        if ($_POST['Senha'] === $_POST['confirmeSenha'])
            $passHash = password_hash($_POST['Senha'], PASSWORD_DEFAULT);
        else
            echo "<script>alert('As senhas não coincidem. Por favor, tente novamente.');</script>";
    }
    else
        echo "<script>alert('Por favor, preencha os campos de senha.');</script>";

    // Cadastrar o novo cliente no banco de dados
    $sql = "INSERT INTO clientes (
            Nome_Cliente, Tipo_Cliente, Documento_Cliente, Email_Cliente, Senha_Cliente,
            Tel_Cliente, CEP, Endereco_Cliente, EndNumero_Cliente, Complemento_Cliente, Bairro_Cliente,
            Cidade_Cliente, Estado_Cliente, DataNasc_Cliente, Status_Cliente, DataCadastro_Cliente, DataAlteracao_Cliente)
            VALUES (
            ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssssssssi", 
        $nome_completo, $tipo_cliente, $documento, 
        $email, $passHash, $telefone, $cep, $endereco, $endereco_numero, 
        $complemento, $bairro, $cidade, $estado, $nascimento, $status);

    if ($stmt->execute()) {
        session_start();
        $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Cliente cadastrado com sucesso!
                            </div>";
        header("Location: index.php"); // Redirecionar para a listagem de clientes
        exit();
    } else {
        echo "Erro ao cadastrar cliente: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Cliente</title>
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

    <!-- Formulário de Edição -->
    <div class="container my-5">
        <h3 class="text-center mb-4">Cadastrar Cliente</h3>
        <form method="post" action="">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="Nome_Cliente" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="Nome_Cliente" name="Nome_Cliente" required>
                </div>
                <div class="col-md-3">
                    <label for="Tipo_Cliente" class="form-label">Tipo de Pessoa</label>
                    <select class="form-select" id="Tipo_Cliente" name="Tipo_Cliente" required>
                        <option value="PF">Pessoa Física</option>
                        <option value="PJ">Pessoa Jurídica</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="Documento_Cliente" class="form-label">Documento</label>
                    <input type="text" class="form-control" id="Documento_Cliente" name="Documento_Cliente" required>
                </div>
                <div class="col-md-3">
                    <label for="DataNasc_Cliente" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="DataNasc_Cliente" name="DataNasc_Cliente" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="Email_Cliente" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="Email_Cliente" name="Email_Cliente" required>
                </div>
                <div class="col-md-3">
                    <label for="Senha" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="Senha" name="Senha" required>
                </div>
                <div class="col-md-3">
                    <label for="confirmeSenha" class="form-label">Confirme a Senha</label>
                    <input type="password" class="form-control" id="confirmeSenha" name="confirmeSenha" required>
                </div>
                <div class="col-md-3">
                    <label for="Tel_Cliente" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="Tel_Cliente" name="Tel_Cliente" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="CEP" class="form-label">CEP</label>
                    <input type="text" class="form-control" id="CEP" name="CEP" required>
                </div>
                <div class="col-md-6">
                    <label for="Endereco_Cliente" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="Endereco_Cliente" name="Endereco_Cliente" required>
                </div>
                <div class="col-md-2">
                    <label for="EndNumero_Cliente" class="form-label">Número</label>
                    <input type="text" class="form-control" id="EndNumero_Cliente" name="EndNumero_Cliente" required>
                </div>
                <div class="col-md-4">
                    <label for="Complemento_Cliente" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="Complemento_Cliente" name="Complemento_Cliente">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="Cidade_Cliente" class="form-label">Cidade</label>
                    <input type="text" class="form-control" id="Cidade_Cliente" name="Cidade_Cliente" value="<?php echo htmlspecialchars($cep_data['Cidade_CEP'] ?? ''); ?>" required>
                </div>

                <div class="col-md-2">
                    <label for="Bairro_Cliente" class="form-label">Bairro</label>
                    <input type="text" class="form-control" id="Bairro_Cliente" name="Bairro_Cliente" value="<?php echo htmlspecialchars($cep_data['Bairro_CEP'] ?? ''); ?>" required>
                </div>

                <div class="col-md-2">
                    <label for="Estado_Cliente" class="form-label">Estado</label>
                    <input type="text" class="form-control" id="Estado_Cliente" name="Estado_Cliente" value="<?php echo htmlspecialchars($cep_data['Estado_CEP'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="Status_Cliente" class="form-label">Status</label>
                    <select class="form-select" id="Status_Cliente" name="Status_Cliente" required>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Cadastrar Cliente</button>
            <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>
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
