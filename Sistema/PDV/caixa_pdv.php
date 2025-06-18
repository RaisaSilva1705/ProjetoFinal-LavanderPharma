<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";

// Incluir o arquivo de conexão
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Busca caixas
$sqlCaixas = "SELECT ID_Caixa,
                     Caixa,
                     Status
              FROM CAIXAS";
$caixas = $conn->query($sqlCaixas);

// Busca turnos
$sqlTurnos = "SELECT ID_Turno,
                     Turno
              FROM TURNOS";
$turnos = $conn->query($sqlTurnos);

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $id_caixa = $_POST['id_caixa'];
    $id_funcionario = $_SESSION['ID_Funcionario'];
    $saldoInicial = $_POST['saldo_inicial'];
    $turno = $_POST['id_turno'];

    // Buscar o status real do caixa informado
    $sqlStatus = "SELECT 
                    Status 
                  FROM CAIXAS 
                  WHERE ID_Caixa = ?";
    $stmtStatus = $conn->prepare($sqlStatus);
    $stmtStatus->bind_param("i", $id_caixa);
    $stmtStatus->execute();
    $resultStatus = $stmtStatus->get_result();
    $caixa = $resultStatus->fetch_assoc();

    if($caixa['Status'] == 'Fechado'){

        $sql ="UPDATE CAIXAS SET
                Status = 'Aberto'
               WHERE ID_Caixa = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_caixa);
        
        if ($stmt->execute()){
            $sql ="INSERT INTO CAIXAS_ABERTOS 
                    (ID_Caixa, ID_Funcionario, ID_Turno,
                    Data_Abertura, Saldo_Inicial)
                   VALUES (?, ?, ?, NOW(), ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iiid", $id_caixa, $id_funcionario, $turno, $saldoInicial);
            $stmt->execute();

            $_SESSION['ID_CaixaAberto'] = $stmt->insert_id;
            $_SESSION['ID_Caixa'] = $id_caixa;
            $_SESSION['Saldo_Inicial'] = $saldoInicial;
            header("Location: pdv.php");
            exit();
        }
        else {
            $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                    Erro ao abrir caixa.
                                </div>";
            header("Location: caixa_pdv.php"); 
            exit();
        }
    }
    else {
        $_SESSION["msg"] = "<div class='alert alert-primary' role='aviso'>
                                Caixa já está aberto.
                            </div>";
        header("Location: caixa_pdv.php"); 
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Seleção de Caixa</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
        <style>
            select > option:first-child {
                display: none;
            }
        </style>
    </head>
    <body class="bg-light">
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Seleção de Caixa</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>
            <div class="container m-4">
                <form action="#" method="POST">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="id_caixa" class="form-label">Selecione o Caixa</label>
                            <select class="form-select" name="id_caixa" id="id_caixa" required>
                                <option value="">Selecione</option>
                                <?php while($caixa = $caixas->fetch_assoc()): ?>
                                    <option value="<?= $caixa['ID_Caixa'] ?>"><?= $caixa['Caixa'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="id_turno" class="form-label">Selecione o Turno</label>
                            <select class="form-select" name="id_turno" id="id_turno" required>
                                <option value="">Selecione</option>
                                <?php while($turno = $turnos->fetch_assoc()): ?>
                                    <option value="<?= $turno['ID_Turno'] ?>"><?= $turno['Turno'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label" for="saldo_inicial">Saldo Inicial</label>
                            <input class="form-control" type="number" name="saldo_inicial" id="saldo_inicial" required placeholder="Digite o valor inicial...">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary mt-4">Abrir Caixa</button>
                </form>
            </div>
            <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            <!-- Footer -->
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>
    </body>
</html>