<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../Dev/Exec/config.php";

if(isset($_SESSION['nome']) && $_SESSION['nome'] != null) {
    header('Location: http://localhost/htdocs/Farmácia/index2.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo NOME?> - Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">    
    <!-- Tema Dark -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/5.3.0-alpha1/darkly/bootstrap.min.css">
</head>
<body>
    <!-- Navbar -->
    <nav class='navbar navbar-expand-lg navbar-dark bg-dark'>
        <div class='container-fluid'>
            <a class='navbar-brand' href='#'>LOGIN</a>
            <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbarNav' aria-controls='navbarNav' aria-expanded='false' aria-label='Toggle navigation'>
                <span class='navbar-toggler-icon'></span>
            </button>
            <div class='collapse navbar-collapse' id='navbarNav'>
                <ul class='navbar-nav ms-auto'>
                </ul>
            </div>
        </div>
    </nav>
    <br><br><br>
    <div class="container">
        <div class="row justify-content-center login-container mt-5">
            <div class="col-md-4 mt-5">
                <h2 class="texto-site">Login</h2>
                <div class="row">
                    <?php
                        // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                        if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null) 
                        {
                            echo $_SESSION["msg"];
                            // Limpa a mensagem para evitar que seja exibida novamente
                            $_SESSION["msg"] = null;
                        }
                    ?>                    
                </div>
                <form method="post" action="../Dev/Exec/index-loginexec.php">
                    <div class="mb-3">
                        <label for="user" class="texto-site">Usuário</label>
                        <input type="text" class="form-control" id="user" name="user" placeholder="Digite seu usuário">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="texto-site">Senha</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Digite sua senha">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Entrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
