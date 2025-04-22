<?php
include DEV_PATH . 'Exec/conexao.php';

// 1. Verificar se o usuário está logado
if (!isset($_SESSION['ID_Usuario']) || !isset($_SESSION['ID_Cargo'])) {
    $_SESSION['msg'] = "<div class='alert alert-danger'>Você precisa estar logado para acessar esta página.</div>";
    header("Location: http://localhost/htdocs/Farmácia/Sistema/index.php");
    exit;
}

// 2. Verificar se o usuário tem acesso ao módulo atual
// Pegue o nome da página atual
$paginaAtual = basename($_SERVER['PHP_SELF']); 

// Pega os módulos que o cargo atual pode acessar
$cargo = $_SESSION['ID_Cargo'];

$sql = "SELECT M.Modulo 
        FROM CARGOS_MODULOS CM INNER JOIN MODULOS M 
        ON CM.ID_Modulo = M.ID_Modulo
        WHERE CM.ID_Cargo = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cargo);
$stmt->execute();
$result = $stmt->get_result();

$modulosPermitidos = [];
while ($row = $result->fetch_assoc()) {
    $modulosPermitidos[] = $row['Modulo'];
}

// 3. Verifique se a página faz parte de um módulo permitido
$mapaPaginasModulos = [
    // ----- Sem grupo ------
    'Home' => ['dashboard.php'],
    'Caixa PDV' => ['caixa.php', 'caixas.php'],
    // 'Minhas Comissões' => [],
    'Configurações' => ['config.php'],
    
    // ------ Pessoas ------
    'Clientes' => ['cliente.php', 'clientes.php'],
    'Usuários' => ['usuario.php', 'usuarios.php'],
    'Funcionários' => ['funcionario.php', 'funcionarios.php'],
    'Fornecedores' => ['fornecedor.php', 'fornecedores.php'],
    
    // ------ Cadastros ------
    'Cargos' => ['cargo.php', 'cargos.php'],
    'Caixas' => ['caixa.php', 'caixas.php'],
    'Forma Pgto' => ['pagamento.php', 'pagamentos.php'],
    
    // ------ Produtos ------
    'Categorias' => ['categorias.php', 'categoria.php'],
    'Produtos' => ['produto.php', 'produtos.php'],
    'Estoque'  => ['estoque.php'],
    'Saídas'   => ['estoque.php'],
    'Entradas' => ['estoque.php'],
    // 'Trocas' => [],
    
    // ------ Financeiro ------
    // 'Contas à Receber' => [],
    // 'Despesas' => [],
    // 'Compras' => [],
    // 'Vendas' => [],
    // 'Fluxo de Caixa' => [],
    // 'Comissões' => [],
    // 'Contas Vencidas' => [],
    
    // ------ Relatórios ------
    'Relatório de Vendas' => ['relatorio_caixas.php'],
    'Relatório de Clientes' => ['relatorio_clientes.php'],
    // 'Relatório de Recebimentos' => [],
    // 'Relatório de Despesas' => [],
    // 'Relatório de Lucro' => [],
    'Relatório de Produtos' => ['relatorio_produtos.php'],
    'Relatório de Estoque' => ['relatorio_estoque.php'],
    // 'Relatório de Entrada/Saída' => [],
    'Relatório de Caixas' => ['relatorio_caixas.php'],
    // 'Relatório de Comissões' => [],
    // 'Relatório de Trocas' => [],
    // 'Relatório de Vendas Produtos' => [],
    
    // ------ Vendas ------
    // 'Orçamentos' => [],
    // 'Contas Pendentes' => [],
    // 'Todas as Vendas' => [],
    // 'Atualizar Vendas' => [],
];

$paginaAtual = $_SERVER['PHP_SELF'];
$moduloAtual = getModuloAtual($mapaPaginasModulos, $paginaAtual);

if ($moduloAtual && !in_array($moduloAtual, $modulosPermitidos)) {
    if (getIdentificadorPagina($_SERVER['PHP_SELF']) !== 'dashboard.php') {
        $_SESSION["msg"] = "<div class='alert alert-danger'>Você não tem permissão para acessar este módulo.</div>";
        header('Location: http://localhost/htdocs/Farmácia/Sistema/dashboard.php');
        exit;
    }
}

// Função para extrair identificador da página
function getIdentificadorPagina($arquivo) {
    $arquivo = basename($arquivo); 

    if (strpos($arquivo, '_') !== false) {
        $partes = explode('_', $arquivo, 2);
        return $partes[1]; 
    }

    return $arquivo;
}

// Função para encontrar o módulo atual
function getModuloAtual($mapa, $arquivo) {
    $pagina = getIdentificadorPagina($arquivo);

    foreach ($mapa as $modulo => $paginas) {
        if (in_array($pagina, $paginas)) {
            return $modulo;
        }
    }

    return null; // Módulo não encontrado
}
?>
