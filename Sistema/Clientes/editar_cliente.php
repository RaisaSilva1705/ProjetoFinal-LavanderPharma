<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";

// Incluir o arquivo de conexão
include '../../dev/Exec/conexao.php';
include "../../dev/Exec/validar_sessao.php";

// Verificar se o parâmetro "codigo" foi passado pela URL
if (isset($_GET['codigo'])) {
    $codigo_cliente = $_GET['codigo'];

    // Consultar os dados do cliente no banco de dados
    $sql = "SELECT * FROM CLIENTES WHERE ID_Cliente = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_cliente);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o cliente foi encontrado
    if ($result->num_rows > 0) {
        $cliente = $result->fetch_assoc();
        /*
        $cep = $cliente['CEP'];

        // Consultar os dados do CEP no banco de dados
        $sql_cep = "SELECT * FROM CEPS WHERE CEP = ?";
        $stmt_cep = $conn->prepare($sql_cep);
        $stmt_cep->bind_param("s", $cep);
        $stmt_cep->execute();
        $result_cep = $stmt_cep->get_result();

        if ($result_cep->num_rows > 0) {
            $cep_data = $result_cep->fetch_assoc();
        } else {
            $cep_data = [];
        }
        */
    } else {
        echo "Cliente não encontrado.";
        exit();
    }
} else {
    echo "Código do cliente não fornecido.";
    exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_completo = $_POST['Nome_Cliente'];
    $tipo_cliente = $_POST['Tipo_Cliente'];
    $documento = $_POST['Documento_Cliente'];
    $email = $_POST['Email_Cliente'];
    $telefone = $_POST['Tel_Cliente'];
    $cep = $_POST['CEP'];
    $endereco = $_POST['Endereco_Cliente'];
    $endereco_numero = $_POST['EndNumero_Cliente'];
    $complemento = $_POST['Complemento_Cliente'];
    $bairro = $_POST['Bairro_Cliente'];
    $cidade = $_POST['Cidade_Cliente'];
    $estado = $_POST['Estado_Cliente'];
    $nascimento = $_POST['DataNasc_Cliente'];
    //$obs = $_POST['obs'];
    $status = $_POST['Status_Cliente'];

    // Atualizar os dados do cliente no banco de dados
    $sql = "UPDATE CLIENTES SET 
                Nome_Cliente = ?, Tipo_Cliente = ?, Documento_Cliente = ?, 
                Email_Cliente = ?, Tel_Cliente = ?, Status_Cliente = ?, DataAlteracao_Cliente = NOW() 
            WHERE ID_Cliente = ?";

    // 7: CEP = ?, Endereco_Cliente = ?, EndNumero_Cliente = ?, Complemento_Cliente = ?, Bairro_Cliente = ?, Cidade_Cliente = ?, Estado_Cliente = ?,        
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", 
        $nome_completo, $tipo_cliente, $documento,  
        $email, $telefone, $status, $codigo_cliente);

    if ($stmt->execute()) {
        session_start();
        $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Dados atualizados com sucesso!
                            </div>";
        header("Location: index.php"); // Redirecionar para a listagem de clientes
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
    <title>Editar Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include_once DEV_PATH . 'Views/header2.php'?>

    <!-- Formulário de Edição -->
    <div class="container my-5">
        <h3 class="text-center mb-4">Editar Cliente</h3>
        <form method="post" action="">
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="Nome_Cliente" class="form-label">Nome Completo</label>
                    <input type="text" class="form-control" id="Nome_Cliente" name="Nome_Cliente" value="<?php echo htmlspecialchars($cliente['Nome_Cliente']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="Tipo_Cliente" class="form-label">Tipo de Pessoa</label>
                    <select class="form-select" id="Tipo_Cliente" name="Tipo_Cliente" required>
                        <option value="PF" <?php if ($cliente['Tipo_Cliente'] == 'PF') echo 'selected'; ?>>Pessoa Física</option>
                        <option value="PJ" <?php if ($cliente['Tipo_Cliente'] == 'PJ') echo 'selected'; ?>>Pessoa Jurídica</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="Documento_Cliente" class="form-label">Documento</label>
                    <input type="text" class="form-control" id="Documento_Cliente" name="Documento_Cliente" value="<?php echo htmlspecialchars($cliente['Documento_Cliente']); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="Email_Cliente" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="Email_Cliente" name="Email_Cliente" value="<?php echo htmlspecialchars($cliente['Email_Cliente']); ?>" required>
                </div>
                <div class="col-md-3">
                    <label for="Tel_Cliente" class="form-label">Telefone</label>
                    <input type="text" class="form-control" id="Tel_Cliente" name="Tel_Cliente" value="<?php echo htmlspecialchars($cliente['Tel_Cliente']); ?>" required>
                </div>
                <?php /*
                <div class="col-md-3">
                    <label for="DataNasc_Cliente" class="form-label">Data de Nascimento</label>
                    <input type="date" class="form-control" id="DataNasc_Cliente" name="DataNasc_Cliente" value="<?php echo htmlspecialchars($cliente['DataNasc_Cliente']); ?>">
                </div>
                <div class="col-md-3">
                    <label for="CEP" class="form-label">CEP</label>
                    <input type="text" class="form-control" id="CEP" name="CEP" value="<?php echo htmlspecialchars($cep); ?>" required>
                </div>
                */ ?>
                
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="Endereco_Cliente" class="form-label">Endereço</label>
                    <input type="text" class="form-control" id="Endereco_Cliente" name="Endereco_Cliente" value="<?php echo htmlspecialchars($cep_data['Endereco_CEP'] ?? ''); ?>" required>
                </div>
                <div class="col-md-2">
                    <label for="EndNumero_Cliente" class="form-label">Número</label>
                    <input type="text" class="form-control" id="EndNumero_Cliente" name="EndNumero_Cliente" value="<?php echo htmlspecialchars($cliente['EndNumero_Cliente']); ?>" required>
                </div>
                <div class="col-md-4">
                    <label for="Complemento_Cliente" class="form-label">Complemento</label>
                    <input type="text" class="form-control" id="Complemento_Cliente" name="Complemento_Cliente" value="<?php echo htmlspecialchars($cliente['Complemento_Cliente'] ?? ''); ?>">
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
                        <option value="1" <?php if ($cliente['Status_Cliente'] == '1') echo 'selected'; ?>>Ativo</option>
                        <option value="0" <?php if ($cliente['Status_Cliente'] == '0') echo 'selected'; ?>>Inativo</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Salvar Alterações</button>
            <a href="index.php" class="btn btn-secondary mt-3">Cancelar</a>
        </form>
    </div>

    <!-- Footer -->
    <?php include_once '../../dev/Views/footer.php'?>

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
