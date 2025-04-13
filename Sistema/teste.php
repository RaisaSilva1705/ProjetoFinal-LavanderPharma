<?php
// session_start();
// if (!isset($_SESSION['usuario'])) {
//     header("Location: login.php");
//     exit();
// }

include "../Dev/Exec/config.php";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    .card {
      border-radius: 1rem;
    }
    .dashboard-title {
      font-size: 1.8rem;
      font-weight: 600;
    }
  </style>
</head>
<body>

  <?php include_once DEV_PATH . 'Views/header2.php'?>

  <div class="container mt-4">
    <div class="dashboard-title mb-4">Teste</div>

    <div class="row g-4">
      
      <div class="col-md-3">
        <div class="card text-white bg-success shadow">
          <div class="card-body">
            <h5 class="card-title">Vendas Hoje</h5>
            <p class="card-text fs-4">R$ 1.235,00</p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-white bg-warning shadow">
          <div class="card-body">
            <h5 class="card-title">Produtos Baixo Estoque</h5>
            <p class="card-text fs-4">12 itens</p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-white bg-primary shadow">
          <div class="card-body">
            <h5 class="card-title">Clientes Ativos</h5>
            <p class="card-text fs-4">215</p>
          </div>
        </div>
      </div>

      <div class="col-md-3">
        <div class="card text-white bg-danger shadow">
          <div class="card-body">
            <h5 class="card-title">Caixa Atual</h5>
            <p class="card-text fs-4">R$ 3.480,00</p>
          </div>
        </div>
      </div>

    </div>
    </div>

    <!-- Área para gráficos ou relatórios -->
    <div class="row mt-5">
      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-light">
            Vendas da Semana
          </div>
          <div class="card-body">
            <p>Gráfico aqui (ex: Chart.js)</p>
          </div>
        </div>
      </div>

      <div class="col-md-6">
        <div class="card shadow">
          <div class="card-header bg-light">
            Últimas Movimentações
          </div>
          <div class="card-body">
            <ul class="list-group">
              <li class="list-group-item">+ R$ 200,00 - Venda #124</li>
              <li class="list-group-item">- R$ 50,00 - Saída de Caixa</li>
              <li class="list-group-item">+ R$ 110,00 - Venda #125</li>
              <li class="list-group-item">+ R$ 89,90 - Venda #126</li>
            </ul>
          </div>
        </div>
      </div>
    </div>

  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
