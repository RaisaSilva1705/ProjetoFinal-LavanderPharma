<?php
$servername = "localhost";
$username = "root";
$password = "1705";
$dbname = "lavanderpharma";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
//echo "Conexão bem-sucedida";
?>
