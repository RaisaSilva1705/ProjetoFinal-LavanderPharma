<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

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
    }
    else{
        echo "Cliente não encontrado.";
        exit();
    }
}
else{
    echo "Código do cliente não fornecido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Cliente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Navbar -->
    <?php include_once DEV_PATH . 'Views/header2.php'?>

    <div class="container my-5">
        <h3 class="text-center mb-4">Detalhes do Cliente</h3>
        <div class="row">
            <div class="col-3">
                <h5>Nome Completo:</h5>
                <p><?php echo $cliente['Nome_Cliente']; ?></p>
            </div>
            <div class="col-3">
                <h5>Tipo de Pessoa:</h5>
                <p><?php echo $cliente['Tipo_Cliente']; ?></p>
            </div>
            <div class="col-3">
                <h5>Documento:</h5>
                <p><?php echo $cliente['Documento_Cliente']; ?></p>
            </div>
            <div class="col-3">
                <h5>Data de Nascimento:</h5>
                <p>
                    <?php 
                        $dataNascimento = new DateTime($cliente['DataNasc_Cliente']);
                        echo $dataNascimento->format('d/m/Y'); 
                    ?>
                </p>
            </div>
        </div>

        <h3 class="text-center mb-4 mt-5">Contato</h3>
        <div class="row md-3">
            <div class="col-3">
                <h5>Telefone:</h5>
                <p><?php echo $cliente['Tel_Cliente']; ?></p>
            </div>
            <div class="col-3">
                <h5>E-mail:</h5>
                <p><?php echo $cliente['Email_Cliente']; ?></p>
            </div>
        </div>

        <h3 class="text-center mb-4 mt-5">Endereço</h3>
        <div class="row md-3">
            <div class="col-3">
                <h5>CEP:</h5>
                <p><?php echo $cliente['CEP']; ?></p>
            </div>
            <div class="col-3">
                <h5>Endereço:</h5>
                <p><?php echo $cliente['Endereco_Cliente']; ?>, Nº <?php echo $cliente['EndNumero_Cliente']; ?></p>
            </div>
            <div class="col-3">
                <h5>Bairro:</h5>
                <p><?php echo $cliente['Bairro_Cliente']; ?></p>
            </div>
            <div class="col-3">
                <h5>Cidade:</h5>
                <p><?php echo $cliente['Cidade_Cliente']; ?></p>
            </div>
            <div class="col-3">
                <h5>Estado:</h5>
                <p><?php echo $cliente['Estado_Cliente']; ?></p>
            </div>
            <div class="col-3">
                <h5>Complemento:</h5>
                <p><?php echo $cliente['Complemento_Cliente']; ?></p>
            </div>
        </div>

        <h3 class="text-center mb-4 mt-5">Outras Informações</h3>
        <div class="row md-3">
            <div class="col-3">
                <h5>Status:</h5>
                <p><?php echo ($cliente['Status_Cliente'] == '1') ? 'Ativo' : 'Inativo'; ?></p>
            </div>
            <div class="col-3">
                <h5>Data de Cadastro:</h5>
                <p>
                    <?php 
                        $dataNascimento = new DateTime($cliente['DataCadastro_Cliente']);
                        echo $dataNascimento->format('d/m/Y H:i:s'); 
                    ?>
                </p>

            </div>
            <div class="col-3">
                <h5>Última Alteração:</h5>
                <p>
                    <?php 
                        if (!empty($cliente['DataAlteracao_Cliente'])) {
                            $dataAlteracao = new DateTime($cliente['DataAlteracao_Cliente']);
                            echo $dataAlteracao->format('d/m/Y H:i:s');
                        } else {
                            echo "Não alterado";
                        }
                    ?>
                </p>
            </div>
        </div>
        
        <a href="cliente_editar.php?codigo=<?php echo $cliente['ID_Cliente']; ?>" class="btn btn-primary">Editar</a>
    </div>

    <!-- Footer -->
    <?php include_once '../../dev/Views/footer.php'?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>