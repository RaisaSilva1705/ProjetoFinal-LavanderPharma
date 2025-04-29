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
    $codigo_unidade = $_GET['codigo'];

    // Consultar os dados do unidade no banco de dados
    $sql = "SELECT * FROM UNIDADES WHERE ID_Unidade = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_unidade);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o unidade foi encontrada
    if ($result->num_rows > 0) {
        $unidade = $result->fetch_assoc();
    }
    else {
        echo "Unidade não encontrada.";
        exit();
    }
}
else {
    echo "Código da unidade não fornecida.";
    exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $unidade = $_POST['unidade'];
    $abreviacao = $_POST['abreviacao'];
    $tipo = $_POST['tipo'];

    // Atualizar os dados da unidade no banco de dados
    $sql = "UPDATE UNIDADES SET 
                Unidade = ?,
                Abreviacao = ?,
                Tipo = ?
            WHERE ID_Unidade = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssi", 
        $unidade, $abreviacao, $tipo, $codigo_unidade);

    if ($stmt->execute()) {
        session_start();
        $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Dados atualizados com sucesso!
                            </div>";
        header("Location: unidades.php"); // Redirecionar para a listagem de unidade
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
                <h3>Edição de Unidade</h3>
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
                    <label for="unidade" class="form-label">Unidade</label>
                    <input type="text" class="form-control" id="unidade" name="unidade" value="<?php echo htmlspecialchars($unidade['Unidade'] ?? ''); ?>" placeholder="Digite o nome da unidade" required>
                    </div>
                    <div class="mb-4">
                        <label for="abreviacao" class="form-label">Abreviação</label>
                        <input type="text" class="form-control" name="abreviacao" id="abreviacao" placeholder="Ex: cx, fr, L" maxlength="10" value="<?= htmlspecialchars($unidade['Abreviacao']) ?>">
                    </div>
                    <div class="mb-4">
                        <label for="tipo" class="form-label">Tipo de Unidade</label>
                        <select class="form-select" name="tipo" id="tipo" required>
                            <option value="" <?php if ($unidade['Tipo'] == null) echo 'selected'; ?>>Selecione</option>
                            <option value="Contagem" <?php if ($unidade['Tipo'] == 'Contagem') echo 'selected'; ?>>Contagem</option>
                            <option value="Peso" <?php if ($unidade['Tipo'] == 'Peso') echo 'selected'; ?>>Peso</option>
                            <option value="Volume" <?php if ($unidade['Tipo'] == 'Volume') echo 'selected'; ?>>Volume</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Editar Unidade</button>
                    <a href="unidades.php" class="btn btn-secondary mt-4 ms-2">Cancelar</a>
                </form>
            </div>
            
            <!-- Footer -->
            <br><br><br><br><br><br><br><br><br><br><br><br><br>
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>
        
    </body>
</html>