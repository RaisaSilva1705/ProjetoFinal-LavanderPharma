<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";

// Incluir o arquivo de conexão
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Simulação de produtos cadastrados
$produtos = [
    '123456' => ['nome' => 'Dipirona 500mg', 'valor' => 5.99, 'foto' => 'dipirona.jpg'],
    '789101' => ['nome' => 'Paracetamol 750mg', 'valor' => 7.50, 'foto' => 'paracetamol.jpg'],
];

// Inicializa o carrinho
if (!isset($_SESSION['carrinho'])) $_SESSION['carrinho'] = [];

// Adiciona item ao carrinho
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo'])) {
    $codigo = $_POST['codigo'];
    $quantidade = max(1, intval($_POST['quantidade']));

    if (isset($produtos[$codigo])) {
        // Adiciona com quantidade personalizada
        $_SESSION['carrinho'][] = [
            'codigo' => $codigo,
            'nome' => $produtos[$codigo]['nome'],
            'valor' => $produtos[$codigo]['valor'],
            'foto' => $produtos[$codigo]['foto'],
            'quantidade' => $quantidade
        ];
    }
}

// Remove item do carrinho
if (isset($_GET['remover'])) {
    $index = intval($_GET['remover']);
    if (isset($_SESSION['carrinho'][$index])) {
        unset($_SESSION['carrinho'][$index]);
        $_SESSION['carrinho'] = array_values($_SESSION['carrinho']); // reorganiza os índices
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Frente de Caixa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
    </head>
    <body class="bg-light">
        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Frente de Caixa</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>
            <div class="container mt-4">

                <!-- TOPO -->
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label>Nº Venda:</label>
                        <input type="text" class="form-control" value="0001" readonly>
                    </div>
                    <div class="col-md-3">
                        <label>Data Venda:</label>
                        <input type="text" class="form-control" value="<?= date('d/m/Y H:i') ?>" readonly>
                    </div>
                    <div class="col-md-6">
                        <label>Cliente:</label>
                        <input type="text" class="form-control" placeholder="Consumidor Final">
                    </div>
                </div>

                <!-- CENTRO -->
                <div class="row mb-3">
                    <!-- COLUNA PRODUTO -->
                    <div class="col-md-8 border p-2">
                        <form action="#" method="POST">
                            <div class="row mb-3">
                                <div class="col-2">
                                    <label for="quantidade">Qtd:</label>
                                    <input type="number" id="quantidade" name="quantidade" class="form-control input-big" value="1" min="1">
                                </div>
                                <div class="col-7">
                                    <label for="descricao">Descrição:</label>
                                    <input type="text" id="descricao" name="descricao" class="form-control input-big" readonly value="Descrição do Produto">
                                </div>
                                <div class="col-2">
                                    <label for="valor">Valor Unitário:</label>
                                    <input type="text" id="valor" name="valor" class="form-control" value="R$ 0,00" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <img src="imagens/sem-imagem.png" id="foto" name="foto" class="product-img mb-2" alt="Imagem da Embalagem do Produto">
                                </div>
                                <div class="col-md-4">
                                    <label for="codigo">Código Barras:</label>
                                    <input type="text" id="codigo" name="codigo" class="form-control">
                                </div>
                                <div class="col-md-4 mt-4" >
                                    <button type="submit" class="btn btn-primary w-100">Adicionar</button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-4 border p-2">
                        <!-- COLUNA VALORES/LOGO -->
                        <h5>Carrinho:</h5>
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Nome</th>
                                    <th>Valor</th>
                                    <th>Quant</th>
                                    <th>Total</th>
                                    <th>Ação</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $totalGeral = 0;
                                foreach ($_SESSION['carrinho'] as $index => $item):
                                    $subtotal = $item['valor'] * $item['quantidade'];
                                    $totalGeral += $subtotal;
                                ?>
                                <tr>
                                    <td><?= $item['nome'] ?></td>
                                    <td>R$ <?= number_format($item['valor'], 2, ',', '.') ?></td>
                                    <td><?= $item['quantidade'] ?></td>
                                    <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                                    <td>
                                        <a href="?remover=<?= $index ?>" class="btn btn-sm btn-danger">Remover</a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot class="table-secondary">
                                <tr>
                                    <td colspan="4" class="text-end"><strong>Total Geral:</strong></td>
                                    <td colspan="2"><strong>R$ <?= number_format($totalGeral, 2, ',', '.') ?></strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <!-- RODAPÉ -->
                <div class="row">
                    <div class="col-md-3">
                    <label>Forma Pgto (1):</label>
                    <input type="text" class="form-control" value="À VISTA">
                    </div>
                    <div class="col-md-3">
                    <label>Valor:</label>
                    <input type="text" class="form-control" value="R$ 0,00">
                    </div>
                    <div class="col-md-3">
                    <label>Forma Pgto (2):</label>
                    <input type="text" class="form-control">
                    </div>
                    <div class="col-md-3">
                    <label>Valor:</label>
                    <input type="text" class="form-control">
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-3">
                    <label>Vendedor:</label>
                    <input type="text" class="form-control" value="<?php echo $_SESSION['Nome'] ?>">
                    </div>
                    <div class="col-md-3">
                    <label>Total Bruto:</label>
                    <input type="text" class="form-control" value="R$ 0,00" readonly>
                    </div>
                    <div class="col-md-3">
                    <label>Qtd. Itens:</label>
                    <input type="text" class="form-control" value="0" readonly>
                    </div>
                    <div class="col-md-3 d-flex align-items-end justify-content-end gap-2">
                    <button class="btn btn-secondary">Nova Venda</button>
                    <button class="btn btn-success">Finalizar</button>
                    <button class="btn btn-danger">Cancelar</button>
                    </div>
                </div>

            </div>
        </div>
    </body>
</html>