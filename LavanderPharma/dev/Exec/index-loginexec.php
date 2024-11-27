<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT ID_Funcionario, Email_Funcionario, Senha_Funcionario, Nome_Funcionario, Cargo_Funcionario
                              FROM FUNCIONARIOS
                              WHERE Email_Funcionario = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dados = $result->fetch_assoc();
        $passHash = $dados['Senha_Funcionario'];

        if (password_verify($password, $passHash)) {
            $_SESSION['ID_Funcionario'] = $dados['ID_Funcionario'];
            $_SESSION['Nome_Funcionario'] = $dados['Nome_Funcionario'];
            $_SESSION['Cargo_Funcionario'] = $dados['Cargo_Funcionario'];
            $_SESSION['expire'] = strtotime('+30 minutes', strtotime('now'));
            $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                    Olá <strong>".$_SESSION["Nome_Funcionario"]."</strong>, Login Efetuado com sucesso!
                                </div>";
            mysqli_close($conn);                    
            header('Location: http://localhost/htdocs/Farmácia/index2.php');
            exit();
        } else {
            $_SESSION["msg"] = "<div class='alert alert-danger' role='aviso'>
                                    Senha incorreta. Por favor, tente novamente.
                                </div>";
            mysqli_close($conn);
            header('Location: http://localhost/htdocs/Farmácia/index.php');
            exit();
        }
    }
    else{
        $_SESSION["msg"] = "<div class='alert alert-warning' role='aviso'>
                                Usuário não encontrado. Por favor, verifique suas credenciais.". var_dump($email) ."
                            </div>";
        mysqli_close($conn);
        header('Location: http://localhost/htdocs/Farmácia/index.php');
        exit;
    }
}
?>

