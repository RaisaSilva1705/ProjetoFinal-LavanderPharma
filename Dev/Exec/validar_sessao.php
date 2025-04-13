<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

if($_SESSION['Nome'] != null){
    $restaSessao = $_SESSION['expire'] - strtotime('now');

    if ($restaSessao < 1) {
        session_destroy();
        $_SESSION["msg"] = "<div class='alert alert-danger'>Sua sessão expirou. Faça login novamente.</div>";
        header('Location: http://localhost/htdocs/Farmácia/Sistema/index.php');
        exit;
    }
}
else{
    header('Location: http://localhost/htdocs/Farmácia/Sistema/index.php');
    exit;
}
?>
