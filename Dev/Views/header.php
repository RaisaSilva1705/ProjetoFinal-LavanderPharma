<?php
    define("Home", "http://localhost/htdocs/Farmácia/");
?>

<nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
    <div class='container-fluid'>
        <a class='navbar-brand' href='<?php echo Home?>/dashboard.php'>[Nome Farmácia]</a>
        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
            <span class='navbar-toggler-icon'></span>
        </button>
        <div class='collapse navbar-collapse' id='navbarNav'>
            <ul class='navbar-nav ms-auto'>
                <li class='nav-item'><a class='nav-link' href='<?php echo Home?>/sistema/Clientes/index.php'>Clientes</a></li>
                <li class='nav-item'><a class='nav-link' href='<?php echo Home?>/sistema/Prescricoes/index.php'>Prescrições</a></li>
                <?php
                    if ($mostrarFunc == true)
                        echo "<li class='nav-item'><a class='nav-link' href='". Home ."/sistema/Funcionarios/index.php'>Funcionários</a></li>";
                ?>                
                <li class='nav-item'><a class='nav-link' href='<?php echo Home?>/sistema/Fornecedores/index.php'>Fornecedores</a></li>
                <li class='nav-item'><a class='nav-link' href='<?php echo Home?>/sistema/Pedidos/index.php'>Pedidos</a></li>
                <li class='nav-itam'><a class='nav-link' href='<?php echo Home?>/sistema/Estoque/index.php'>Estoque</a></li>
                <li class='nav-itam'><a class='nav-link' href='<?php echo Home?>/sistema/Caixas/index.php'>Caixa</a></li>
                <li class='nav-itam'><a class='nav-link' href='<?php echo Home?>/sistema/Relatorios/index.php'>Relatórios</a></li>
                <li class='nav-item'><a class='nav-link active' href='<?php echo Home?>/dashboard.php'>Menu Principal</a></li>
                <li class='nav-item'><a class='nav-link active' href="<?php echo Home?>/dev/Exec/sair.php">Sair</a></li>
            </ul>
        </div>
    </div>
</nav>