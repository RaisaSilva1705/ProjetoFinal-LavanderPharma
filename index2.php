<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

include "./dev/Exec/validar_sessao.php";

$cargo = $_SESSION['Cargo_Funcionario'];

if ($cargo == 'Gerente' || $cargo == 'Subgerente' || $cargo == 'RH'){
    $mostrar = true;
    $tamGridCima = 'col-lg-3';
    $tamGridBaixo = 'col-lg-4';
    $corCli = 'bg-info';
    $corPre = 'bg-primary';
}
else{
    $mostrar = false;
    $tamGridCima = 'col-lg-5';
    $tamGridBaixo = 'col-lg-5';
    $corCli = 'bg-primary';
    $corPre = 'bg-success';
}
    
?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>LavanderPharma</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .content {
                flex: 1;
            }
            footer {
                bottom: 0;
                width: 100%;
                background-color: #f8f9fa;
            }
        </style>
    </head>
    <body>
        <!-- Navbar -->
        <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
            <div class='container-fluid'>
                <a class='navbar-brand' href='#'>LavanderPharma</a>
                <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
                    <span class='navbar-toggler-icon'></span>
                </button>
                <div class='collapse navbar-collapse' id='navbarNav'>
                    <ul class='navbar-nav ms-auto'>
                        <li class='nav-item'><a class='nav-link' href='./sistema/Clientes/index.php'>Clientes</a></li>
                        <li class='nav-item'><a class='nav-link' href='./sistema/Prescricoes/index.php'>Prescrições</a></li>
                        <?php
                            if ($mostrar == true)
                                echo "<li class='nav-item'><a class='nav-link' href='./sistema/Funcionarios/index.php'>Funcionários</a></li>";
                        ?>                
                        <li class='nav-item'><a class='nav-link' href='./sistema/Fornecedores/index.php'>Fornecedores</a></li>
                        <li class='nav-item'><a class='nav-link' href='./sistema/Pedidos/index.php'>Pedidos</a></li>
                        <li class='nav-item'><a class='nav-link active' href='../index.php'>Menu Principal</a></li>
                        <li class='nav-item'><a class='nav-link active' href="./dev/Exec/sair.php">Sair</a></li>
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Banner -->
        <div class="container-fluid bg-secondary text-white text-center p-4">
            <h3>Menu do funcionário</h3>
            <?php
                // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                    echo $_SESSION["msg"];
                    // Limpa a mensagem para evitar que seja exibida novamente
                    $_SESSION["msg"] = null;
                }
            ?>
        </div>

        <!-- Dashboard Cards -->
        <div class="container d-flex justify-content-center align-items-center content">
            <div class="row justify-content-center">
                <div class="col-md-6 <?php echo $tamGridCima ?> mb-4">
                    <div class="card text-white <?php echo $corCli ?>">
                        <div class="card-body">
                            <h5 class="card-title">Clientes</h5>
                            <p class="card-text">Gerenciamento dos clientes.</p>
                            <a href="./sistema/Clientes/index.php" class="btn btn-light">Acessar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 <?php echo $tamGridCima ?> mb-4">
                    <div class="card text-white <?php echo $corPre ?>">
                        <div class="card-body">
                            <h5 class="card-title">Prescrições</h5>
                            <p class="card-text">Gerenciar as prescrições.</p>
                            <a href="./sistema/Prescricoes/index.php" class="btn btn-light">Acessar</a>
                        </div>
                    </div>
                </div>
                <?php
                    if ($mostrar == true){
                        echo '<div class="col-md-6 col-lg-3 mb-4">
                                 <div class="card text-white bg-success">
                                    <div class="card-body">
                                        <h5 class="card-title">Funcionários</h5>
                                        <p class="card-text">Gerenciamento dos funcionários.</p>
                                        <a href="./sistema/Funcionarios/index.php" class="btn btn-light">Acessar</a>
                                    </div>
                                </div>
                              </div>';
                    }
                ?>
                
                <div class="col-md-6 <?php echo $tamGridBaixo ?> mb-4">
                    <div class="card text-white bg-warning">
                        <div class="card-body">
                            <h5 class="card-title">Fornecedores</h5>
                            <p class="card-text">Gerenciamento dos fornecedores.</p>
                            <a href="./sistema/Fornecedores/index.php" class="btn btn-light">Acessar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 <?php echo $tamGridBaixo ?> mb-4">
                    <div class="card text-white bg-danger">
                        <div class="card-body">
                            <h5 class="card-title">Pedidos</h5>
                            <p class="card-text">Gerenciar os pedidos.</p>
                            <a href="./sistema/Pedidos/index.php" class="btn btn-light">Acessar</a>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-light text-center text-lg-start">
            <div class="text-center p-3 bg-dark text-white">
                <p>© 2024 LavanderPharma - Todos os direitos reservados.</p>
            </div>
        </footer>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>