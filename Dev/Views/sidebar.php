<style>
  .sidebar {
    width: 200px;
    height: 100vh;
    background-color: #343a40;
    padding-top: 1rem;
    top: 0;
    left: 0;
    overflow-y: auto;
  }

  .sidebar a, .sidebar .dropdown-toggle {
    color: white;
    padding: 0.75rem 1rem;
    display: block;
    text-decoration: none;
  }

  .sidebar a:hover,
  .sidebar .dropdown-toggle:hover {
    background-color: #495057;
    color: white;
  }

  .sidebar .dropdown-menu {
    background-color:rgb(41, 44, 47);
    border: none;
    padding-left: 1rem;
  }

  .dropdown-submenu {
    position: relative;
  }

  .dropdown-submenu > .dropdown-menu {
    display: none;
    position: relative;
    background-color:rgb(25, 26, 27);
    z-index: 1;
  }

  .dropdown-submenu:hover > .dropdown-menu {
    display: block;
    position: relative;
  }

  .sidebar .nav-item .dropdown-toggle::after {
    float: right;
    margin-top: 0.5rem;
  }
</style>

<div class="sidebar">
  <a href="<?php echo SISTEMA_URL ?>dashboard.php" class="navbar-brand text-white mb-3 d-block fs-6 fw-bold"><?php echo NOME ?></a>

  <!-- PDV -->
  <div class="nav-item dropdown">
    <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">PDV</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>PDV/pdv.php">Realizar Venda</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>PDV/relatorio_pdv.php">Relatório de Vendas</a></li>
    </ul>
  </div>

  <!-- Cadastros -->
  <div class="nav-item dropdown">
    <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown" role="button">Cadastros</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Cadastros/Caixas/caixas.php">Caixa</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Cadastros/Cargos/cargos.php">Cargo</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Cadastros/Unidades/unidades.php">Unidade</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Cadastros/Pagamentos/formas_pagamentos.php">Forma de Pagamento</a></li>
    </ul>
  </div>

  <!-- Produtos -->
  <div class="nav-item dropdown">
    <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">Produtos</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Produtos/produtos.php">Lista de Produtos</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Produtos/cadastrar_produto.php">Adicionar Produto</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Produtos/relatorio_produtos.php">Relatório de Produtos</a></li>
    </ul>
  </div>

  <!-- Estoque -->
  <div class="nav-item dropdown">
    <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">Estoque</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Estoque/estoque.php">Conferir Estoque</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Estoque/movimentacao_estoque.php?mov=E">Entrada</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Estoque/movimentacao_estoque.php?mov=S">Saída</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Estoque/relatorio_estoque.php">Relatório</a></li>
    </ul>
  </div>

  <!-- Pessoas -->
  <div class="nav-item dropdown">
    <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">Pessoas</a>
    <ul class="dropdown-menu">

      <!-- Clientes -->
      <li class="dropdown-submenu">
        <a class="dropdown-item dropdown-toggle" href="#">Clientes</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Clientes/clientes.php">Lista de Clientes</a></li>
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Clientes/cadastrar_cliente.php">Adicionar Cliente</a></li>
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Clientes/relatorio_clientes.php">Relatório</a></li>
        </ul>
      </li>

      <!-- Usuários -->
      <li class="dropdown-submenu">
        <a class="dropdown-item dropdown-toggle" href="#">Usuários</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Usuarios/usuarios.php">Lista</a></li>
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Usuarios/adicionar_usuario.php">Adicionar</a></li>
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Usuarios/relatorio_usuarios.php">Relatório</a></li>
        </ul>
      </li>

      <!-- Funcionários -->
      <li class="dropdown-submenu">
        <a class="dropdown-item dropdown-toggle" href="#">Funcionários</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Funcionarios/funcionarios.php">Lista</a></li>
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Funcionarios/adicionar_funcionario.php">Adicionar</a></li>
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Funcionarios/relatorio_funcionarios.php">Relatório</a></li>
        </ul>
      </li>

      <!-- Fornecedores -->
      <li class="dropdown-submenu">
        <a class="dropdown-item dropdown-toggle" href="#">Fornecedores</a>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Fornecedores/fornecedores.php">Lista</a></li>
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Fornecedores/adicionar_fornecedor.php">Adicionar</a></li>
          <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Fornecedores/relatorio_fornecedores.php">Relatório</a></li>
        </ul>
      </li>
    </ul>
  </div>

  <!-- Caixa -->
  <div class="nav-item dropdown">
    <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">Caixa</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Caixa/caixa.php">Visão Geral</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Caixa/abrir_caixa.php">Abrir Caixa</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Caixa/movimentacao_caixa.php?mov=E">Registrar Entrada</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Caixa/movimentacao_caixa.php?mov=S">Registrar Saída</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Caixa/fechar_caixa.php">Fechar Caixa</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Caixa/historico_caixa.php">Histórico</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Caixa/relatorio_caixa.php">Relatório</a></li>
    </ul>
  </div>

  <!-- Configurações -->
  <div class="nav-item dropdown">
    <a class="dropdown-toggle" href="#" data-bs-toggle="dropdown">Configurações</a>
    <ul class="dropdown-menu">
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Configuracoes/empresa.php">Dados da Empresa</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Configuracoes/logs.php">Logs</a></li>
      <li><a class="dropdown-item" href="<?php echo SISTEMA_URL ?>Configuracoes/backup.php">Backup</a></li>
    </ul>
  </div>

  <!-- Sair -->
  <a href="<?php echo DEV_URL ?>Exec/logout.php">Sair</a>
</div>
