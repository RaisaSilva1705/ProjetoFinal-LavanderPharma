<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';
include 'conexao.php';
$_SESSION["msg"] = "teste";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT ID_Funcionario, Email, Senha, Nome, ID_Cargo
                              FROM FUNCIONARIOS
                              WHERE Email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dados = $result->fetch_assoc();
        $passHash = $dados['Senha'];

        if (password_verify($password, $passHash)) {
            $_SESSION['ID_Funcionario'] = $dados['ID_Funcionario'];
            $_SESSION['Nome'] = $dados['Nome'];
            $_SESSION['ID_Cargo'] = $dados['ID_Cargo'];
            $_SESSION['expire'] = strtotime('+30 minutes', strtotime('now'));
            $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                    Olá <strong>".$_SESSION["Nome"]."</strong>, Login Efetuado com sucesso!
                                </div>";
            mysqli_close($conn);                    
            header('Location: http://localhost/htdocs/Farmácia/Sistema/dashboard.php');
            exit();
        }
        else{
            $_SESSION["msg"] = "<div class='alert alert-warning' role='aviso'>
                                    Usuário ou senha estão incorretos. Por favor, verifique suas credenciais.
                                </div>";
            mysqli_close($conn);
            header('Location: http://localhost/htdocs/Farmácia/Sistema/index.php');
            exit;
        }
    }
    else{
        $_SESSION["msg"] = "<div class='alert alert-warning' role='aviso'>
                                Usuário ou senha incorretas. Por favor, verifique suas credenciais.
                            </div>";
        mysqli_close($conn);
        header('Location: http://localhost/htdocs/Farmácia/Sistema/index.php');
        exit;
    }
}
?>