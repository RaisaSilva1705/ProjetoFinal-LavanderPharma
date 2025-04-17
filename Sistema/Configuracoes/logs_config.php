<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$cargo = $_SESSION['ID_Cargo'];
if ($cargo == 7){
    $_SESSION["msg"] = "<div class='alert alert-danger'>Você não tem acesso a essa área.</div>";
    header('Location: http://localhost/htdocs/Farmácia/index2.php');
    exit;
}

include "../../Dev/Exec/config.php";

// Incluir o arquivo de conexão
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";

?>