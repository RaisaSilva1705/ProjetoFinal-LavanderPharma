<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../../Dev/Exec/config.php";

include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Verificar se o parâmetro "codigo" foi passado pela URL
if (isset($_GET['codigo'])) {
    $codigo_caixa = $_GET['codigo'];

    // Consultar os dados do caixa no banco de dados
    $sql = "SELECT * FROM CAIXAS_REGISTRADOS WHERE ID_CaixaRegistrado = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $codigo_caixa);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o caixa foi encontrado
    if ($result->num_rows > 0) {
        // Deletar os dados do caixa no banco de dados
        $sql = "DELETE FROM CAIXAS_REGISTRADOS WHERE ID_CaixaRegistrado = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $codigo_caixa);

        if ($stmt->execute()) {
            session_start();
            $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Caixa excluído com sucesso!
                            </div>";
            header("Location: caixas.php"); // Redirecionar para a listagem de caixa
            exit();
        } 
        else {
            echo "Erro ao excluir os dados: " . $conn->error;
        }
    }
    else {
        echo "Caixa não encontrado.";
        exit();
    }
}
else {
    echo "Código do caixa não fornecido.";
    exit();
}
?>