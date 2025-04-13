<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$cargo = $_SESSION['ID_Cargo'];
if ($cargo == 7){
    $_SESSION["msg"] = "<div class='alert alert-danger'>Você não tem acesso a essa área.</div>";
    header('Location: http://localhost/htdocs/Farmácia/index2.php');
    exit;
}

include "../../Dev/Exec/config.php";

// Incluir o arquivo de conexão
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";

// Construir a consulta SQL
$sql = "SELECT
        P.ID_Produto,
        P.Nome AS Nome_Prod,
        E.Preco_Venda,
        E.Quantidade
        FROM PRODUTOS P LEFT JOIN ESTOQUE E
        ON P.ID_Produto = E.ID_Produto";

$result = $conn->query($sql);

$tipo = ($_GET["mov"] == 'E') ? 'Entrada' : 'Saída';

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $row = $result->fetch_assoc();

    $idProd = $row['ID_Produto'];
    $idFunc = $_SESSION['ID_Funcionario'];
    $nomeProd = $_POST['produto'];
    //$valor = $_POST['preco'];
    $quantidade = $_POST['quantProd'];
    $valorTotal = $_POST['total'];
    echo $valorTotal;
    $quantEstoque = $row['Quantidade'];

    // Atualizar os dados da MOVIMENTACAO_ESTOQUE no banco de dados
    $sql = "INSERT INTO MOVIMENTACAO_ESTOQUE 
            (ID_Produto, ID_Funcionario, Tipo, Quantidade, Valor, Data_Movimentacao)
            VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", 
        $idProd, $idFunc, $tipo, $quantidade, $valorTotal);

    if ($stmt->execute()) {
        echo $tipo;
        if ($tipo == 'Entrada'){ $NovoEstoqueAtual = $quantEstoqueAtual + $quantidade; }
        else { $NovoEstoqueAtual = $quantEstoqueAtual - $quantidade; }

        // Atualizar os dados do PRODUTOS no banco de dados
        $sql = "UPDATE PRODUTOS SET 
                Estoque_Atual = ? 
                WHERE ID_Produto = ?";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", 
        $NovoEstoqueAtual, $idProd);

        if ($stmt->execute()){
            echo $tipo;
            if ($tipo == 'Entrada'){ $NovoEstoque = $quantEstoque + $quantidade; }
            else { $NovoEstoque = $quantEstoque - $quantidade; }

            // Atualizar os dados do ESTOQUE no banco de dados
            $sql = "UPDATE ESTOQUE SET 
                    Quantidade = ? 
                    WHERE ID_Produto = ?";

            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", 
            $NovoEstoque, $idProd);

            if ($stmt->execute()){
                session_start();
                $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                        Movimentação cadastrada com sucesso!
                                    </div>";
                header("Location: hist_movimentacoes.php"); // Redirecionar para o index
                exit();
            }
            else {
                echo "Erro ao atualizar os dados: " . $conn->error;
            }
        }
        else {
            echo "Erro ao atualizar os dados: " . $conn->error;
        }
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
        <title>LavanderPharma</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .content {
                flex: 1;
            }
            footer {
                position: fixed;
                bottom: 0;
                left: 0;
                width: 100%;
                background-color: #f8f9fa;
            }
            .navbu {
                margin: 2px;
            }
        </style>
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
                        <a href="movimentacao.php?mov=<?php echo "E" ?>" class="navbu">
                            <button class="btn btn-secondary" type="button">Entrada Avulsa</button>
                        </a>
                        <a href="movimentacao.php?mov=<?php echo "S" ?>" class="navbu">
                            <button class="btn btn-secondary" type="button">Saída Avulsa</button>
                        </a>
                        <a href="hist_movimentacoes.php" class="navbu">
                            <button class="btn btn-secondary" type="button">Histório</button>
                        </a>
                        <a href="index.php" class="navbu">
                            <button class="btn btn-secondary" type="button">Estoque</button>
                        </a>
                        <li class='nav-item'><a class='nav-link active' href='../../index2.php'>Menu Principal</a></li>
                        <li class='nav-item'><a class='nav-link' href="../../dev/Exec/sair.php">Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Banner -->
        <div class="container-fluid bg-secondary text-white text-center p-4">
            <h3><?php echo ($_GET["mov"] == 'E') ? 'Entrada' : 'Saída'; ?> Avulsa</h3>
            <?php
                // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                    echo $_SESSION["msg"];
                    // Limpa a mensagem para evitar que seja exibida novamente
                    $_SESSION["msg"] = null;
                }
            ?>
        </div>
        
        <!-- Formulário -->
        <div class="container mt-4">
            <form action="movimentacao.php?mov=<?php echo $_GET["mov"]; ?>" method="POST">
                <div class="mb-3">
                    <label for="produto" class="form-label">Produto</label>
                    <input type="text" class="form-control" id="produto" name="produto" list="produtos" required oninput="filtrarProdutos()" placeholder="Digite o nome do produto...">
                    <datalist id="produtos">
                        <?php while ($row = $result->fetch_assoc()) { ?>
                            <option value="<?php echo $row['Nome_Prod']; ?>" data-id="<?php echo $row['ID_Produto']; ?>" data-preco="<?php echo $row['Preco']; ?>" data-estoque="<?php echo $row['Estoque_Atual']; ?>">
                                <?php echo $row['Nome_Prod']; ?>
                            </option>
                        <?php } ?>
                    </datalist>
                </div>

                <div class="mb-3">
                    <label for="quantProd" class="form-label">Quantidade</label>
                    <input type="number" class="form-control" id="quantProd" name="quantProd" min="1" required onchange="calcularTotal()">
                </div>

                <div class="mb-3">
                    <label for="preco" class="form-label">Preço Unitário</label>
                    <input type="text" class="form-control" id="preco" name="preco" readonly>
                </div>

                <div class="mb-3">
                    <label for="total" class="form-label">Valor Total</label>
                    <input type="text" class="form-control" id="total" name="total" readonly>
                </div>

                <button type="submit" class="btn btn-primary">Confirmar <?php echo ($_GET["mov"] == 'E') ? 'Entrada' : 'Saída'; ?></button>
            </form>
        </div>
        
        <!-- Footer -->
        <footer class="bg-light text-center text-lg-start">
            <div class="text-center p-3 bg-dark text-white">
                <p>© 2024 LavanderPharma - Todos os direitos reservados.</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.getElementById("produto").addEventListener("input", function () {
                var input = this.value;
                var datalist = document.getElementById("produtos");
                var options = datalist.getElementsByTagName("option");
                var precoCampo = document.getElementById("preco");
                var totalCampo = document.getElementById("total");
                var quantidadeCampo = document.getElementById("quantProd");

                // Percorre todas as opções do datalist para encontrar o produto correspondente
                for (var i = 0; i < options.length; i++) {
                    if (options[i].value === input) {
                        var preco = options[i].getAttribute("data-preco");
                        precoCampo.value = preco; // Define o preço unitário
                        
                        // Se houver uma quantidade definida, já calcula o total
                        if (quantidadeCampo.value) {
                            var total = parseFloat(preco) * parseFloat(quantidadeCampo.value);
                            totalCampo.value = total.toFixed(2);
                        }
                        return;
                    }
                }

                // Caso o produto não seja encontrado, limpa os campos de preço e total
                precoCampo.value = "";
                totalCampo.value = "";
            });

            // Atualiza o total quando a quantidade for alterada
            document.getElementById("quantProd").addEventListener("input", function () {
                var preco = parseFloat(document.getElementById("preco").value);
                var quantidade = parseFloat(this.value);
                if (!isNaN(preco) && !isNaN(quantidade)) {
                    document.getElementById("total").value = (preco * quantidade).toFixed(2);
                } else {
                    document.getElementById("total").value = "";
                }
            });
        </script>

    </body>
</html>