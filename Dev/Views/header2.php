<style> 
  /* Suporte para submenu aninhado */
.dropdown-submenu {
  position: relative;
}

.dropdown-submenu > .dropdown-menu {
  top: 0;
  left: 100%;
  margin-top: -0.125rem;
  display: none;
}

.dropdown-submenu:hover > .dropdown-menu {
  display: block;
}

</style>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href='<?php echo SISTEMA_URL?>dashboard.php'><?php echo NOME?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav ms-auto">

        <!-- PDV -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pdvDropdown" role="button" data-bs-toggle="dropdown">
            PDV
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>PDV/pdv.php">Realizar Venda</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>PDV/relatorio_vendas.php">Relatório de Vendas</a></li>
          </ul>
        </li>
        
        <!-- Pessoas -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="pessoasDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Pessoas
          </a>
          <ul class="dropdown-menu" aria-labelledby="pessoasDropdown">
            
            <!-- Clientes -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#">Clientes</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Clientes/clientes.php">Lista de Clientes</a></li>
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Clientes/cadastrar_cliente.php">Adicionar Cliente</a></li>
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Clientes/relatorio_clientes.php">Relatório de Clientes</a></li>
              </ul>
            </li>

            <!-- Usuários -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#">Usuários</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Usuarios/usuarios.php">Lista de Usuários</a></li>
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Usuarios/adicionar_usuario.php">Adicionar Usuário</a></li>
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Usuarios/relatorio_usuarios.php">Relatório de Usuários</a></li>
              </ul>
            </li>

            <!-- Funcionários -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#">Funcionários</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Funcionarios/funcionarios.php">Lista de Funcionários</a></li>
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Funcionarios/adicionar_funcionario.php">Adicionar Funcionário</a></li>
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Funcionarios/relatorio_funcionarios.php">Relatório de Funcionários</a></li>
              </ul>
            </li>

            <!-- Fornecedores -->
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#">Fornecedores</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Fornecedores/fornecedores.php">Lista de Fornecedores</a></li>
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Fornecedores/adicionar_fornecedor.php">Adicionar Fornecedor</a></li>
                <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Fornecedores/relatorio_fornecedores.php">Relatório de Fornecedores</a></li>
              </ul>
            </li>

          </ul>
        </li>

        <!-- Produtos -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="produtosDropdown" role="button" data-bs-toggle="dropdown">
            Produtos
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Produtos/produtos.php">Lista de Produtos</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Produtos/cadastrar_produto.php">Adicionar Produto</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Produtos/relatorio_produtos.php">Relatório de Produtos</a></li>
          </ul>
        </li>

        <!-- Estoque -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="estoqueDropdown" role="button" data-bs-toggle="dropdown">
            Estoque
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Estoque/estoque.php">Controle de Estoque</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Estoque/movimentacao_estoque.php?mov=E">Entrada de Estoque</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Estoque/movimentacao_estoque.php?mov=S">Saída de Estoque</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Estoque/historico_estoque.php">Histórico</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Estoque/relatorio_estoque.php">Relatório de Estoque</a></li>
          </ul>
        </li>

        <!-- Caixa -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="caixaDropdown" role="button" data-bs-toggle="dropdown">
            Caixa
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Caixa/caixa.php">Visão Geral</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Caixa/abrir_caixa.php">Abrir Caixa</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Caixa/movimentacao_caixa.php?mov=E">Registrar Entrada</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Caixa/movimentacao_caixa.php?mov=S">Registrar Saída</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Caixa/fechar_caixa.php">Fechar Caixa</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Caixa/historico_caixa.php">Histórico</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Caixa/relatorio_caixa.php">Relatório de Caixa</a></li>
          </ul>
        </li>

        <!-- Configurações -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="configDropdown" role="button" data-bs-toggle="dropdown">
            Configurações
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Configuracoes/empresa.php">Dados da Empresa</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Configuracoes/logs.php">Logs</a></li>
            <li><a class="dropdown-item" href="<?php echo SISTEMA_URL?>Configuracoes/backup.php">Backup</a></li>
          </ul>
        </li>

        <!-- Logout -->
        <li class="nav-item">
          <a class="nav-link text-white" href="<?php echo DEV_URL?>Exec/logout.php">Sair</a>
        </li>

      </ul>
    </div>
  </div>
</nav>
