<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

$produtoSelecionado = null;
$listaProdutos = [];

if (isset($_GET['codigo'])) {
    $id_produto = $_GET['codigo'];

    $sql = "SELECT P.ID_Produto, P.Nome, C.Categoria, E.Quantidade, P.Quant_Minima
            FROM PRODUTOS P 
            LEFT JOIN CATEGORIAS C ON P.ID_Categoria = C.ID_Categoria
            LEFT JOIN ESTOQUE E ON P.ID_Produto = E.ID_Produto
            WHERE P.ID_Produto = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $result = $stmt->get_result();
    $produtoSelecionado = $result->fetch_assoc();
} else {
    // Carregar todos os produtos para autocomplete
    $sql = "SELECT P.ID_Produto, P.Nome, E.Quantidade FROM PRODUTOS P 
            LEFT JOIN ESTOQUE E ON P.ID_Produto = E.ID_Produto";
    $result = $conn->query($sql);
    while ($row = $result->fetch_assoc()) {
        $listaProdutos[] = $row;
    }
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nomeProd = $_POST['produto'];
    $quantidade = (int) $_POST['quantProd'];
    $idFunc = $_SESSION['ID_Funcionario'];
    $tipo = ($_GET["mov"] == 'E') ? 'Entrada' : 'Saída';

    // Buscar o ID e estoque do produto com base no nome
    $sql = "SELECT 
                P.ID_Produto, 
                E.Quantidade 
            FROM PRODUTOS P LEFT JOIN ESTOQUE E 
                ON P.ID_Produto = E.ID_Produto
            WHERE P.Nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nomeProd);
    $stmt->execute();
    $result = $stmt->get_result();
    $dados = $result->fetch_assoc();

    if ($dados) {
        $idProd = $dados['ID_Produto'];
        $antigoEstoque = (int) $dados['Quantidade'] ?? 0;
        $novoEstoque = ($tipo == 'Entrada') ? $antigoEstoque + $quantidade : $antigoEstoque - $quantidade;

        // Inserir movimentação
        $sql = "INSERT INTO MOVIMENTACAO_ESTOQUE 
                (ID_Produto, ID_Funcionario, Tipo, Antiga_Quantidade, Nova_Quantidade, Data_Movimentacao)
                VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisii", $idProd, $idFunc, $tipo, $antigoEstoque, $novoEstoque);
        $stmt->execute();

        // Atualizar estoque
        $sql = "UPDATE ESTOQUE SET Quantidade = ? WHERE ID_Produto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $novoEstoque, $idProd);
        $stmt->execute();

        $_SESSION["msg"] = "<div class='alert alert-primary'>Movimentação cadastrada com sucesso!</div>";
        header("Location: estoque.php");
        exit();
    }
    else {
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
                <h3><?php echo ($_GET["mov"] == 'E') ? 'Entrada' : 'Saída'; ?></h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>
        
            <div class="container p-5">
                <!-- Tabela de Movimentação -->
                <form action="movimentacao_estoque.php?mov=<?php echo $_GET["mov"]; ?>" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="produto" class="form-label">Produto</label>
                            <input type="text" class="form-control" id="produto" name="produto"
                                list="produtos"
                                value="<?php echo htmlspecialchars($produtoSelecionado['Nome'] ?? '') ?>"
                                placeholder="Digite o nome do produto..." required>

                            <datalist id="produtos">
                                <?php foreach ($listaProdutos as $produto) { ?>
                                    <option value="<?php echo htmlspecialchars($produto['Nome']); ?>"></option>
                                <?php } ?>
                            </datalist>

                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="quantProd" class="form-label">Quantidade</label>
                            <input type="number" class="form-control" id="quantProd" name="quantProd" min="1" required>
                        </div>

                        <?php 
                        /*
                        <div class="col-md-3 mb-3">
                            <label for="preco" class="form-label">Preço Unitário</label>
                            <input type="text" class="form-control" id="preco" name="preco" readonly>
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="total" class="form-label">Valor Total</label>
                            <input type="text" class="form-control" id="total" name="total" readonly>
                        </div>
                        */
                        ?>
                    </div>
                    

                    <button type="submit" class="btn btn-primary">Confirmar <?php echo ($_GET["mov"] == 'E') ? 'Entrada' : 'Saída'; ?></button>
                </form>
            </div>
        
        
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <!-- Footer -->
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>

        <script>

        </script>
    </body>
</html>