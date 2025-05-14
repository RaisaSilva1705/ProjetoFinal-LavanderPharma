<?php
session_start();
header('Content-Type: text/html; charset=utf-8');
include "../../Dev/Exec/config.php";
include DEV_PATH . 'Exec/conexao.php';

if (isset($_POST['finalizar_caixa'])) {

    if (!isset($_SESSION['ID_Caixa'], $_SESSION['ID_CaixaAberto'], $_SESSION['Saldo_Inicial'])) {
        echo "<p>Erro: sessão inválida ou expirada.</p>";
        exit;
    }

    $id_caixa = $_SESSION['ID_Caixa'];
    $id_caixaAberto = $_SESSION['ID_CaixaAberto'];
    $saldoInicial = $_SESSION['Saldo_Inicial'];

    // 1. Atualiza status do caixa
    $sqlFechar = "UPDATE CAIXAS SET Status = 'Fechado' WHERE ID_CAIXA = ?";
    $stmtFechar = $conn->prepare($sqlFechar);
    $stmtFechar->bind_param("i", $id_caixa);

    if ($stmtFechar->execute()) {

        // 2. Busca relatório
        $sqlRelatorio = "SELECT COUNT(*) AS total_vendas, SUM(Valor_Total) AS valor_total FROM VENDAS WHERE ID_CaixaAberto = ?";
        $stmtRelatorio = $conn->prepare($sqlRelatorio);
        $stmtRelatorio->bind_param("i", $id_caixaAberto);
        $stmtRelatorio->execute();
        $resultado = $stmtRelatorio->get_result();
        $relatorio = $resultado->fetch_assoc();

        $total_vendas = $relatorio['total_vendas'];
        $valor_total = $relatorio['valor_total'] ?? 0.0;

        // Corrigido: soma valor das vendas ao saldo
        $saldoFinal = $saldoInicial + $valor_total;
        $dataAtual = date('Y-m-d H:i:s');

        // 3. Fecha o caixa aberto
        $sql = "UPDATE CAIXAS_ABERTOS SET Data_Fechamento = ?, Saldo_Final = ?, Valor_Vendido = ? WHERE ID_CaixaAberto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sddi", $dataAtual, $saldoFinal, $valor_total, $id_caixaAberto);

        if ($stmt->execute()) {
            echo "<h2>Caixa Finalizado com Sucesso</h2>";
            echo "<p>Saldo Inicial: R$ " . number_format($saldoInicial, 2, ',', '.') . "</p>";
            echo "<p>Total de Vendas: $total_vendas</p>";
            echo "<p>Valor Total: R$ " . number_format($valor_total, 2, ',', '.') . "</p>";
            echo "<p>Saldo Final: R$ " . number_format($saldoFinal, 2, ',', '.') . "</p>";
        } else {
            echo "<p>Erro ao atualizar informações do caixa aberto.</p>";
        }

        $stmtRelatorio->close();
        $stmt->close();

        unset(
            $_SESSION['ID_Caixa'],
            $_SESSION['ID_CaixaAberto'],
            $_SESSION['Saldo_Inicial']
        );
    } else {
        echo "<p>Erro ao finalizar o caixa.</p>";
    }

    $stmtFechar->close();
    $conn->close();
}
?>