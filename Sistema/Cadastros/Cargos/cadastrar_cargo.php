<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../../Dev/Exec/config.php";

include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro de Cargo</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
    </head>
    <body>
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Cadastro de Novo Cargo</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>

            <!-- Formulário de Edição -->
            <div class="container  p-5">
                <form>
                    <div class="mb-4">
                    <label for="nomeCargo" class="form-label">Nome do Cargo</label>
                    <input type="text" class="form-control" id="nomeCargo" placeholder="Digite o nome do cargo" required>
                    <label for="descCargo" class="form-label mt-2">Descrição do Cargo</label>
                    <input type="text" class="form-control" id="descCargo" placeholder="Digite a descrição do cargo">
                    </div>

                    <!-- Função para gerar os grupos -->
                    <div class="accordion" id="acessosAccordion">

                    <!-- Grupo: Sem grupo -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingSemGrupo">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSemGrupo">
                            Sem grupo
                        </button>
                        </h2>
                        <div id="collapseSemGrupo" class="accordion-collapse collapse show">
                        <div class="accordion-body">
                            <div class="form-check"><input class="form-check-input" type="checkbox" value="home" id="home" name="modulos[]"><label class="form-check-label" for="home">Home</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" value="caixa_pdv" id="caixaPDV" name="modulos[]"><label class="form-check-label" for="caixaPDV">Caixa PDV</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" value="comissoes" id="comissoes" name="modulos[]"><label class="form-check-label" for="comissoes">Minhas Comissões</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" value="configuracoes" id="configuracoes" name="modulos[]"><label class="form-check-label" for="configuracoes">Configurações</label></div>
                        </div>
                        </div>
                    </div>

                    <!-- Grupo: Pessoas -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingPessoas">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePessoas">
                            Pessoas
                        </button>
                        </h2>
                        <div id="collapsePessoas" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="clientes" name="modulos[]"><label class="form-check-label" for="clientes">Clientes</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="usuarios" name="modulos[]"><label class="form-check-label" for="usuarios">Usuários</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="funcionarios" name="modulos[]"><label class="form-check-label" for="funcionarios">Funcionários</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="fornecedores" name="modulos[]"><label class="form-check-label" for="fornecedores">Fornecedores</label></div>
                        </div>
                        </div>
                    </div>

                    <!-- Você pode copiar e colar este modelo para os próximos grupos -->
                    <!-- Grupo: Cadastros -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingCadastros">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseCadastros">
                            Cadastros
                        </button>
                        </h2>
                        <div id="collapseCadastros" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="cargos" name="modulos[]"><label class="form-check-label" for="cargos">Cargos</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="caixas" name="modulos[]"><label class="form-check-label" for="caixas">Caixas</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="formaPgto" name="modulos[]"><label class="form-check-label" for="formaPgto">Forma de Pagamento</label></div>
                        </div>
                        </div>
                    </div>

                    <!-- Grupo: Produtos -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingProdutos">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseProdutos">
                            Produtos
                        </button>
                        </h2>
                        <div id="collapseProdutos" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="categorias" name="modulos[]"><label class="form-check-label" for="categorias">Categorias</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="produtos" name="modulos[]"><label class="form-check-label" for="produtos">Produtos</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="entradas" name="modulos[]"><label class="form-check-label" for="entradas">Entradas</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="saidas" name="modulos[]"><label class="form-check-label" for="saidas">Saídas</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="estoque" name="modulos[]"><label class="form-check-label" for="estoque">Estoque</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="trocas" name="modulos[]"><label class="form-check-label" for="trocas">Trocas</label></div>
                        </div>
                        </div>
                    </div>

                    <!-- Grupo: Financeiro -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFinanceiro">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFinanceiro">
                            Financeiro
                        </button>
                        </h2>
                        <div id="collapseFinanceiro" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="contasReceber" name="modulos[]"><label class="form-check-label" for="contasReceber">Contas à Receber</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="despesas" name="modulos[]"><label class="form-check-label" for="despesas">Despesas</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="compras" name="modulos[]"><label class="form-check-label" for="compras">Compras</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="vendas" name="modulos[]"><label class="form-check-label" for="vendas">Vendas</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="fluxoCaixa" name="modulos[]"><label class="form-check-label" for="fluxoCaixa">Fluxo de Caixa</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="comissoesFinanceiro" name="modulos[]"><label class="form-check-label" for="comissoesFinanceiro">Comissões</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="contasVencidas" name="modulos[]"><label class="form-check-label" for="contasVencidas">Contas Vencidas</label></div>
                        </div>
                        </div>
                    </div>

                    <!-- Grupo: Relatórios -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingRelatorios">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseRelatorios">
                            Relatórios
                        </button>
                        </h2>
                        <div id="collapseRelatorios" class="accordion-collapse collapse">
                        <div class="accordion-body row">
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioVendas" name="modulos[]"><label class="form-check-label" for="relatorioVendas">Relatório de Vendas</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioClientes" name="modulos[]"><label class="form-check-label" for="relatorioClientes">Relatório de Clientes</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioRecebimentos" name="modulos[]"><label class="form-check-label" for="relatorioRecebimentos">Relatório de Recebimentos</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioDespesas" name="modulos[]"><label class="form-check-label" for="relatorioDespesas">Relatório de Despesas</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioLucro" name="modulos[]"><label class="form-check-label" for="relatorioLucro">Relatório de Lucro</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioProdutos" name="modulos[]"><label class="form-check-label" for="relatorioProdutos">Relatório de Produtos</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioEstoque" name="modulos[]"><label class="form-check-label" for="relatorioEstoque">Relatório de Estoque</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioEntradaSaida" name="modulos[]"><label class="form-check-label" for="relatorioEntradaSaida">Relatório de Entrada/Saída</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioCaixas" name="modulos[]"><label class="form-check-label" for="relatorioCaixas">Relatório de Caixas</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioComissoes" name="modulos[]"><label class="form-check-label" for="relatorioComissoes">Relatório de Comissões</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioTrocas" name="modulos[]"><label class="form-check-label" for="relatorioTrocas">Relatório de Trocas</label></div>
                            <div class="form-check col-md-6"><input class="form-check-input" type="checkbox" id="relatorioVendasProdutos" name="modulos[]"><label class="form-check-label" for="relatorioVendasProdutos">Relatório de Vendas Produtos</label></div>
                        </div>
                        </div>
                    </div>

                    <!-- Grupo: Vendas -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingVendas">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseVendas">
                            Vendas
                        </button>
                        </h2>
                        <div id="collapseVendas" class="accordion-collapse collapse">
                        <div class="accordion-body">
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="orcamentos" name="modulos[]"><label class="form-check-label" for="orcamentos">Orçamentos</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="contasPendentes" name="modulos[]"><label class="form-check-label" for="contasPendentes">Contas Pendentes</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="todasVendas" name="modulos[]"><label class="form-check-label" for="todasVendas">Todas as Vendas</label></div>
                            <div class="form-check"><input class="form-check-input" type="checkbox" id="atualizarVendas" name="modulos[]"><label class="form-check-label" for="atualizarVendas">Atualizar Vendas</label></div>
                        </div>
                        </div>
                    </div>

                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Salvar Cargo</button>
                </form>
            </div>

            <!-- Footer -->
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>