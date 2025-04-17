<style>
    .wrapper {
      display: flex;
      height: 100vh;
    }

    .sidebar {
      width: 180px;
      background-color: #343a40;
      padding: 1rem;
    }

    .sidebar .nav-link,
    .sidebar .dropdown-toggle {
      color: #fff;
    }

    .sidebar .nav-link:hover,
    .sidebar .dropdown-toggle:hover {
      background-color: #495057;
    }

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

    .sidebar .dropdown-menu {
      background-color: #343a40;
    }

    .sidebar .dropdown-item {
      color: #fff;
    }

    .sidebar .dropdown-item:hover {
      background-color: #495057;
    }
  </style>

  <div class="wrapper">
    <!-- Sidebar -->
    <nav class="sidebar">
      <a href="<?php echo SISTEMA_URL ?>dashboard.php" class="navbar-brand text-white mb-3 d-block"><?php echo NOME ?></a>

      <ul class="nav flex-column">
        <!-- PDV -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">PDV</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Realizar Venda</a></li>
            <li><a class="dropdown-item" href="#">Relatório de Vendas</a></li>
          </ul>
        </li>

        <!-- Produtos -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Produtos</a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Lista de Produtos</a></li>
            <li><a class="dropdown-item" href="#">Adicionar Produto</a></li>
            <li><a class="dropdown-item" href="#">Relatório</a></li>
          </ul>
        </li>

        <!-- Pessoas com submenus -->
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">Pessoas</a>
          <ul class="dropdown-menu">
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#">Clientes</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Lista de Clientes</a></li>
                <li><a class="dropdown-item" href="#">Adicionar Cliente</a></li>
              </ul>
            </li>
            <li class="dropdown-submenu">
              <a class="dropdown-item dropdown-toggle" href="#">Usuários</a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#">Lista de Usuários</a></li>
                <li><a class="dropdown-item" href="#">Adicionar Usuário</a></li>
              </ul>
            </li>
          </ul>
        </li>

        <!-- Logout -->
        <li class="nav-item">
          <a class="nav-link" href="#">Sair</a>
        </li>
      </ul>
    </nav>
  </div>