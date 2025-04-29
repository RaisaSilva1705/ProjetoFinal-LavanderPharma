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
    $id_cargo = $_GET['codigo'];

    // Consultar os dados do cargo no banco de dados
    $sql = "SELECT * FROM CARGOS_FUNCIONARIOS WHERE ID_Cargo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id_cargo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $cargo = $result->fetch_assoc();

        // Módulos permitidos
        $sqlModulos = "SELECT M.Modulo FROM CARGOS_MODULOS CM
                       JOIN MODULOS M ON CM.ID_Modulo = M.ID_Modulo
                       WHERE CM.ID_Cargo = ? AND CM.Acesso_Permitido = 1";

        $stmt = $conn->prepare($sqlModulos);
        $stmt->bind_param("i", $id_cargo);                       
        $stmt->execute();
        $resultModulos = $stmt->get_result();

        $modulosPermitidos = [];
        while ($row = $resultModulos->fetch_assoc()) {
            $modulosPermitidos[] = $row['Modulo'];
        }

        // Lista organizada por grupos
        $modulosPorGrupo = [
            'Sem grupo' => ['Home', 'Caixa PDV', 'Minhas Comissões', 'Configurações'],
            'Pessoas' => ['Clientes', 'Usuários', 'Funcionários', 'Fornecedores'],
            'Cadastros' => ['Cargos', 'Caixas', 'Forma Pgto'],
            'Produtos' => ['Categorias', 'Produtos', 'Entradas', 'Saídas', 'Estoque', 'Trocas'],
            'Financeiro' => ['Contras à Receber', 'Despesas', 'Compras', 'Vendas', 'Fluxo de Caixa', 'Comissões', 'Contas Vencidas'],
            'Relatórios' => [
                'Relatório de Vendas', 'Relatório de Clientes', 'Relatório de Recebimentos', 'Relatório de Despesas',
                'Relatório de Lucro', 'Relatório de Produtos', 'Relatório de Estoque', 'Relatório de Entrada/Saída',
                'Relatório de Caixas', 'Relatório de Comissões', 'Relatório de Trocas', 'Relatório de Vendas Produtos'
            ],
            'Vendas' => ['Orçamentos', 'Contas Pendentes', 'Todas as Vendas', 'Atualizar Vendas']
        ];

    } else {
        echo "Cargo não encontrado.";
        exit();
    }
} else {
    echo "Código do Cargo não fornecido.";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_cargo = $_POST['ID_Cargo'];
    $cargo = $_POST['nomeCargo'];
    $descricao = $_POST['descCargo'];
    $modulosMarcados = $_POST['modulos'] ?? []; // Checkbox marcados

    // Atualizar dados do cargo
    $sql = "UPDATE CARGOS_FUNCIONARIOS SET Cargo = ?, Descricao = ? WHERE ID_Cargo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssi", $cargo, $descricao, $id_cargo);
    $stmt->execute();

    // Remover permissões antigas
    $sqlDelete = "DELETE FROM CARGOS_MODULOS WHERE ID_Cargo = ?";
    $stmtDelete = $conn->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $id_cargo);
    $stmtDelete->execute();

    // Inserir novas permissões
    $sqlInsert = "INSERT INTO CARGOS_MODULOS (ID_Cargo, ID_Modulo, Acesso_Permitido) VALUES (?, ?, 1)";
    $stmtInsert = $conn->prepare($sqlInsert);

    foreach ($modulosMarcados as $moduloNome) {
        $sqlBuscaModulo = "SELECT ID_Modulo FROM MODULOS WHERE Modulo = ?";
        $stmtBusca = $conn->prepare($sqlBuscaModulo);
        $stmtBusca->bind_param("s", $moduloNome);
        $stmtBusca->execute();
        $resultadoModulo = $stmtBusca->get_result();

        if ($modulo = $resultadoModulo->fetch_assoc()) {
            $id_modulo = $modulo['ID_Modulo'];
            $stmtInsert->bind_param("ii", $id_cargo, $id_modulo);
            $stmtInsert->execute();
        }
    }

    $_SESSION["msg"] = "<div class='alert alert-primary' role='alert'>Cargo atualizado com sucesso!</div>";
    header("Location: cargos.php");
    exit();
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
                <h3>Cadastro de Forma de Pagamento</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>

            <div class="container mt-5">
            <h2 class="mb-4">Editar Cargo</h2>

            <form action="editar_cargo.php?codigo=<?php echo $id_cargo ?>" method="POST">
                <input type="hidden" name="ID_Cargo" value="<?= htmlspecialchars($cargo['ID_Cargo']) ?>">

                <div class="mb-3">
                    <label for="nomeCargo" class="form-label">Nome do Cargo</label>
                    <input type="text" class="form-control" id="nomeCargo" name="nomeCargo" value="<?= htmlspecialchars($cargo['Cargo']) ?>" required>
                    <label for="descCargo" class="form-label mt-3">Descrição</label>
                    <input type="text" class="form-control" id="descCargo" name="descCargo" value="<?= htmlspecialchars($cargo['Descricao']) ?>" required>
                </div>

                <h4 class="mt-4">Permissões de Acesso</h4>

                <?php foreach ($modulosPorGrupo as $grupo => $modulos): ?>
                    <div class="card my-3 shadow-sm">
                        <div class="card-header bg-secondary text-white">
                            <?= $grupo ?>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <?php foreach ($modulos as $modulo): ?>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input 
                                                class="form-check-input" 
                                                type="checkbox" 
                                                name="modulos[]" 
                                                value="<?= htmlspecialchars($modulo) ?>"
                                                id="modulo_<?= md5($modulo) ?>"
                                                <?= in_array($modulo, $modulosPermitidos) ? 'checked' : '' ?>
                                            >
                                            <label class="form-check-label" for="modulo_<?= md5($modulo) ?>">
                                                <?= $modulo ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <div class="mt-4">
                    <button type="submit" class="btn btn-success">Salvar Alterações</button>
                    <a href="cargos.php" class="btn btn-secondary ms-2">Cancelar</a>
                </div>
            </form>
        </div>
            
            <!-- Footer -->
            <br><br><br>
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>
        
    </body>
</html>