<?php
session_start();
include "../../Dev/Exec/config.php";
include DEV_PATH . 'Exec/conexao.php';

// Verifica se há itens no carrinho
if (empty($_SESSION['carrinho'])) {
    $_SESSION['msg'] = "Carrinho vazio. Nenhuma venda registrada.";
    header("Location: pdv.php");
    exit;
}

$conn->begin_transaction();

try {
    // Insere a venda
    $id_funcionario = $_SESSION['ID_Funcionario'];
    $id_caixaAberto = $_SESSION['ID_CaixaAberto'];
    $id_cliente = $_POST['id_cliente'] ?? null;
    $valorTotal = $_POST['valor_total'];
    $desconto = $_POST['desconto'] ?? 0.00;

    $stmt = $conn->prepare("INSERT INTO VENDAS 
                                (ID_Funcionario, ID_CaixaAberto, 
                                ID_Cliente, DataHora_Venda, 
                                Valor_Total, Desconto) 
                            VALUES (?, ?, ?, NOW(), ?, ?)");
    $stmt->bind_param("iiidd", $id_funcionario, $id_caixaAberto, $id_cliente, $valorTotal, $desconto);
    $stmt->execute();

    $idVenda = $stmt->insert_id;

    // Insere os itens da venda
    $stmtItem = $conn->prepare("INSERT INTO ITENS_VENDA (ID_Venda, ID_Produto, Quantidade, Valor_Total) 
                                VALUES (?, ?, ?, ?)");

    foreach ($_SESSION['carrinho'] as $item) {
        $codigo = $item['codigo'];

        // buscando ID_Produto
        $stmtProduto = $conn->prepare("SELECT ID_Produto FROM PRODUTOS WHERE EAN_GTIN = ?");
        $stmtProduto->bind_param("s", $codigo);
        $stmtProduto->execute();
        $resultProduto = $stmtProduto->get_result();
        $id_produto = $resultProduto->fetch_assoc()['ID_Produto'];

        $quantidade = $item['quantidade'];
        $preco = $item['preco'];
        $valor_total = $preco * $quantidade;

        $stmtItem->bind_param("isid", $idVenda, $id_produto, $quantidade, $valor_total);
        $stmtItem->execute();

        // Atualiza estoque (baixa)
        $stmtEstoque = $conn->prepare("UPDATE ESTOQUE 
                                        SET Quantidade = Quantidade - ? 
                                       WHERE ID_Produto = ?");
        $stmtEstoque->bind_param("is", $quantidade, $id_produto);
        $stmtEstoque->execute();
    }

    $conn->commit();
    $_SESSION['msg'] = "Venda nº $idVenda finalizada com sucesso!";
    unset($_SESSION['carrinho']);

    header("Location: pdv.php?id_venda=$idVenda");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    echo $erro = $e->getMessage();
    $_SESSION['msg'] = "Erro ao finalizar venda";
    //header("Location: pdv.php");

}

// implementar depois Controle de Estoque
/* 
$stmtEstoqueCheck = $conn->prepare("SELECT Quantidade FROM ESTOQUE WHERE ID_Produto = ?");
$stmtEstoqueCheck->bind_param("i", $id_produto);
$stmtEstoqueCheck->execute();
$qtdAtual = $stmtEstoqueCheck->get_result()->fetch_assoc()['Quantidade'];

if ($qtdAtual < $quantidade) {
    throw new Exception("Estoque insuficiente para o produto $codigo");
}

*/
?>
