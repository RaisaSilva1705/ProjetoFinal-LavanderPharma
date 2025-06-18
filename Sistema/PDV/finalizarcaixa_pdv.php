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
        $relatorioCaixa = $resultado->fetch_assoc();

        $total_vendas = $relatorioCaixa['total_vendas'];
        $valor_total = $relatorioCaixa['valor_total'] ?? 0.0;

        // 3. Busca os pagamentos das vendas
        $sqlVendas = "SELECT V.ID_Venda,
                             V.DataHora_Venda,
                             V.Valor_Total,
                             F.Nome AS 'Nome_Funcionario',
                             C.Nome AS 'Nome_Cliente',
                             FP.Tipo AS 'Forma_Pag',
                             VP.Valor AS 'Valor_Pag',
                             VP.Troco
                    FROM VENDA_PAGAMENTOS VP INNER JOIN VENDAS V
                        ON VP.ID_Venda = V.ID_Venda
                    LEFT JOIN FUNCIONARIOS F
                        ON V.ID_Funcionario = F.ID_Funcionario
                    LEFT JOIN CLIENTES C
                        ON V.ID_Cliente = C.ID_Cliente
                    LEFT JOIN FORMAS_PAGAMENTO FP
                        ON VP.ID_Forma_Pag = FP.ID_Forma_Pag
                    LEFT JOIN CAIXAS_ABERTOS CA
                        ON V.ID_CaixaAberto = CA.ID_CaixaAberto
                    WHERE CA.ID_CaixaAberto = ?
                    ORDER BY V.ID_Venda ASC, VP.ID_VendaPagamento ASC";
        $stmtVendas = $conn->prepare($sqlVendas);
        $stmtVendas->bind_param("i", $id_caixaAberto);
        $stmtVendas->execute();
        $resultVendas = $stmtVendas->get_result();

        // 4. Busca total vendido por método de pagamento
        $sqlMetodos = "SELECT FP.Tipo,
                            SUM(VP.Valor) AS 'Total_Recebido',
                            SUM(VP.Troco) AS 'Troco_Total'
                        FROM VENDA_PAGAMENTOS VP INNER JOIN VENDAS V 
                            ON VP.ID_Venda = V.ID_Venda
                        INNER JOIN FORMAS_PAGAMENTO FP 
                            ON VP.ID_Forma_Pag = FP.ID_Forma_Pag
                        WHERE V.ID_CaixaAberto = ?
                        GROUP BY FP.Tipo";
        $stmtMetodos = $conn->prepare($sqlMetodos);
        $stmtMetodos->bind_param("i", $id_caixaAberto);
        $stmtMetodos->execute();
        $resultMetodos = $stmtMetodos->get_result();

        $valor_dinheiro = 0;
        $valor_credito = 0;
        $valor_debito = 0;
        $valor_pix = 0;
        $troco = 0;

        while ($row = $resultMetodos->fetch_assoc()){
            $forma = $row['Tipo'];
            $valor = (float)$row['Total_Recebido'];
            $trocoForma = (float)$row['Troco_Total'];

            switch ($forma){
                case 'Dinheiro': 
                    $valor_dinheiro += $valor;
                    $troco += $trocoForma;
                    break;
                case 'Cartão de Crédito': $valor_credito += $valor; break;
                case 'Cartão de Débito': $valor_debito += $valor; break;
                case 'PIX': $valor_pix += $valor; break;
            }
            
        }

        $saldoFinal = $saldoInicial + $valor_dinheiro - $troco;
        $dataAtual = date('Y-m-d H:i:s');

        // 4. Fecha o caixa aberto
        $sql = "UPDATE CAIXAS_ABERTOS SET Data_Fechamento = ?, Saldo_Final = ?, Valor_Vendido = ? WHERE ID_CaixaAberto = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sddi", $dataAtual, $saldoFinal, $valor_total, $id_caixaAberto);
        $stmt->execute();

        $stmtRelatorio->close();
        $stmt->close();

        unset(
            $_SESSION['ID_Caixa'],
            $_SESSION['ID_CaixaAberto'],
            $_SESSION['Saldo_Inicial']
        );
    } 
    else {
        echo "<p>Erro ao finalizar o caixa.</p>";
    }

    $stmtFechar->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Relatório - Caixa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
    </head>
    <body class="bg-light">
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Seleção de Caixa</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>
            
            <div class="container mt-3 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2>Relatório do Caixa</h2>
                    <a href="caixa_pdv.php" class="btn btn-primary">Abrir novo Caixa</a>
                </div>

                <div>
                    <p>Saldo Inicial: R$ <?= number_format($saldoInicial, 2, ',', '.') ?></p>
                    <p>Total de Vendas: <?= $total_vendas ?></p>
                    <p>Valor Total (Vendido): R$ <?= number_format($valor_total, 2, ',', '.') ?></p>
                    <p>Vendido Dinheiro: R$ <?= number_format($valor_dinheiro, 2, ',', '.') ?></p>
                    <p>Vendido Crédito: R$ <?= number_format($valor_credito, 2, ',', '.') ?></p>
                    <p>Vendido Débito: R$ <?= number_format($valor_debito, 2, ',', '.') ?></p>
                    <p>Vendido PIX: R$ <?= number_format($valor_pix, 2, ',', '.') ?></p>
                    <p>Saldo Final: R$ <?= number_format($saldoFinal, 2, ',', '.') ?></p>
                </div>

                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">Nº Venda</th>
                            <th scope="col">Caixa</th>
                            <th scope="col">Funcionario</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Data e Hora</th>
                            <th scope="col">Formas Pag</th>
                            <th scope="col">R$ Pag</th>
                            <th scope="col">R$ Troco</th>
                            <th scope="col">R$ Total</th>
                            <th scope="col">Cupom Fiscal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($resultVendas->num_rows > 0) {
                                while ($row = $resultVendas->fetch_assoc()) { // quebra de página após 20 resultados
                                    $nomeCli = ($row['Nome_Cliente']) ? $row['Nome_Cliente'] : '--';
                                    echo '<tr>';
                                        echo '<td>' . $row["ID_Venda"] . '</td>';
                                        echo '<td>' . $id_caixa . '</td>';
                                        echo '<td>' . $row["Nome_Funcionario"] . '</td>';
                                        echo '<td>' . $nomeCli . '</td>';
                                        echo '<td>' . $row["DataHora_Venda"] . '</td>';
                                        echo '<td>' . $row["Forma_Pag"] . '</td>';
                                        echo '<td>' . number_format($row["Valor_Pag"], 2, ',', '.') . '</td>';
                                        echo '<td>' . number_format($row["Troco"], 2, ',', '.') . '</td>';
                                        echo '<td>' . number_format($valor_total, 2, ',', '.') . '</td>';
                                        echo '<td>
                                                <a href="cupomNfiscal.php?ID_Venda=' . $row["ID_Venda"] . '" class="btn btn-info btn-sm">Ver</a>
                                             </td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="10" class="text-center">Nenhuma venda realizada</td></tr>';
                            }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- Footer -->
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>
    </body>
</html>