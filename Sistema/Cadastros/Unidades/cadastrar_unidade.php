<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../../Dev/Exec/config.php";

include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $unidade = $_POST['unidade'];
    $abreviacao = $_POST['abreviacao'];
    $tipo = $_POST['tipo'];

    // Inserir os dados da unidade no banco de dados
    $sql = "INSERT INTO UNIDADES (Unidade, Abreviacao, Tipo) VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $unidade, $abreviacao, $tipo);

    if ($stmt->execute()) {
        $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Unidade cadastrada com sucesso!
                            </div>";
        header("Location: unidades.php"); // Redirecionar para a listagem de unidades
        exit();
    }
    else {
        echo "Erro ao cadastrar os dados: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro de Unidade</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
        <style>
            select > option:first-child {
                display: none;
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Cadastro de Unidade</h3>
                <?php
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>

            <!-- Formulário -->
            <div class="container p-5">
                <form action="#" method="POST">
                    <div class="mb-4">
                        <label for="unidade" class="form-label">Nome da Unidade</label>
                        <input type="text" class="form-control" name="unidade" id="unidade" placeholder="Ex: Caixa, Frasco, Litro" required>
                    </div>
                    <div class="mb-4">
                        <label for="abreviacao" class="form-label">Abreviação</label>
                        <input type="text" class="form-control" name="abreviacao" id="abreviacao" placeholder="Ex: cx, fr, L" maxlength="10">
                    </div>
                    <div class="mb-4">
                        <label for="tipo" class="form-label">Tipo de Unidade</label>
                        <select class="form-select" name="tipo" id="tipo" required>
                            <option value="">Selecione</option>
                            <option value="Contagem">Contagem</option>
                            <option value="Peso">Peso</option>
                            <option value="Volume">Volume</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Cadastrar Unidade</button>
                    <a href="unidades.php" class="btn btn-secondary mt-4 ms-2">Cancelar</a>
                </form>
            </div>

            <!-- Footer -->
            <br><br><br><br><br><br><br><br><br><br><br><br><br>
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>
        
    </body>
</html>
