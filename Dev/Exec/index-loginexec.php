<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'config.php';
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($conn->connect_error) {
        die("Erro na conexão com o banco de dados: " . $conn->connect_error);
    }

    $user = $_POST["user"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT U.ID_Usuario,
                                   U.ID_Funcionario,
                                   U.Senha,
                                   U.Status,
                                   F.ID_Cargo,
                                   F.Nome
                            FROM USUARIOS U LEFT JOIN FUNCIONARIOS F
                            ON U.ID_Funcionario = F.ID_Funcionario
                            WHERE Usuario = ?");
    $stmt->bind_param("s", $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $dados = $result->fetch_assoc();
        if ($dados['Status'] == 'Ativo'){
            $passHash = $dados['Senha'];

            if (password_verify($password, $passHash)) {
                $_SESSION['ID_Usuario'] = $dados['ID_Usuario'];
                $_SESSION['ID_Funcionario'] = $dados['ID_Funcionario'];
                $_SESSION['Nome'] = $dados['Nome'];
                $_SESSION['ID_Cargo'] = $dados['ID_Cargo'];
                $_SESSION['expire'] = strtotime('+60 minutes', strtotime('now'));
                $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                        Olá <strong>".$_SESSION["Nome"]."</strong>, Login efetuado com sucesso!
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
        else {
            $_SESSION["msg"] = "<div class='alert alert-warning' role='aviso'>
                        Usuário não está ativo.
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