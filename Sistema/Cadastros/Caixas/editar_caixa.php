<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../../Dev/Exec/config.php";

include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Verificar se o parâmetro "codigo" foi passado pela URL
if (isset($_GET['codigo'])) {
    $codigo_caixa = $_GET['codigo'];

    // Consultar os dados do caixa no banco de dados
    $sql = "SELECT * FROM CAIXAS_REGISTRADOS WHERE ID_CaixaRegistrado = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_caixa);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o caixa foi encontrado
    if ($result->num_rows > 0) {
        $caixa = $result->fetch_assoc();
    }
    else {
        echo "Caixa não encontrado.";
        exit();
    }
}
else {
    echo "Código do caixa não fornecido.";
    exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome_caixa = $_POST['nomeCaixa'];

    // Atualizar os dados do caixa no banco de dados
    $sql = "UPDATE CAIXAS_REGISTRADOS SET 
                Nome_Caixa = ?
            WHERE ID_CaixaRegistrado = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", 
        $nome_caixa, $codigo_caixa);

    if ($stmt->execute()) {
        session_start();
        $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Dados atualizados com sucesso!
                            </div>";
        header("Location: caixas.php"); // Redirecionar para a listagem de caixa
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
        <title>Cadastro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
    </head>
    <body>
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Edição de Caixa</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>

            <!-- Formulário de Edição -->
            <div class="container p-5">
                <form action="#" method="POST">
                    <div class="mb-4">
                    <label for="nomeCaixa" class="form-label">Nome do Caixa</label>
                    <input type="text" class="form-control" id="nomeCaixa" name="nomeCaixa" value="<?php echo htmlspecialchars($caixa['Nome_Caixa'] ?? ''); ?>" placeholder="Digite o nome do caixa" required>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Editar Caixa</button>
                </form>
            </div>
            
            <!-- Footer -->
            <br><br><br><br><br><br><br><br><br><br><br><br><br>
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>
        
    </body>
</html>