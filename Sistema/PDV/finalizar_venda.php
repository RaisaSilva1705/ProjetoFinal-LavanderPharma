<?php
session_start();
include "../../Dev/Exec/config.php";
include DEV_PATH . 'Exec/conexao.php';

// Verifica se há itens no carrinho
if (empty($_SESSION['carrinho'])) {
    $_SESSION['msg'] = "Carrinho vazio. Nenhuma venda registrada.";
    header("Location: caixa.php");
    exit;
}

$conn->begin_transaction();

try {
    // Insere a venda
    $vendedor = $_SESSION['ID_Funcionario'];
    $dataVenda = date("Y-m-d H:i:s");

    $stmt = $conn->prepare("INSERT INTO VENDAS (ID_Funcionario, Data_Venda) VALUES (?, ?)");
    $stmt->bind_param("is", $vendedor, $dataVenda);
    $stmt->execute();

    $idVenda = $stmt->insert_id;

    // Insere os itens da venda
    $stmtItem = $conn->prepare("INSERT INTO ITENS_VENDAS (ID_Venda, ID_Produto, Quantidade, Preco_Vendido) 
                                VALUES (?, (SELECT ID_Produto FROM PRODUTOS WHERE EAN_GTIN = ?), ?, ?)");

    foreach ($_SESSION['carrinho'] as $item) {
        $codigo = $item['codigo'];
        $quantidade = $item['quantidade'];
        $preco = $item['preco'];

        $stmtItem->bind_param("isid", $idVenda, $codigo, $quantidade, $preco);
        $stmtItem->execute();

        // Atualiza estoque (baixa)
        $stmtEstoque = $conn->prepare("
            UPDATE ESTOQUE 
            SET Quantidade = Quantidade - ? 
            WHERE ID_Produto = (SELECT ID_Produto FROM PRODUTOS WHERE EAN_GTIN = ?)
        ");
        $stmtEstoque->bind_param("is", $quantidade, $codigo);
        $stmtEstoque->execute();
    }

    $conn->commit();
    $_SESSION['msg'] = "Venda nº $idVenda finalizada com sucesso!";
    unset($_SESSION['carrinho']);

    header("Location: recibo.php?id_venda=$idVenda");
    exit;

} catch (Exception $e) {
    $conn->rollback();
    $_SESSION['msg'] = "Erro ao finalizar venda: " . $e->getMessage();
    header("Location: caixa.php");
}
?>
