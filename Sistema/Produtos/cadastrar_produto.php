<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Busca categorias
$sqlCategorias = "SELECT ID_Categoria, Categoria FROM CATEGORIAS";
$categorias = $conn->query($sqlCategorias);

// Busca unidades
$sqlUnidades = "SELECT ID_Unidade, Unidade FROM UNIDADES";
$unidades = $conn->query($sqlUnidades);

// Busca categorias de medicamentos
$sqlCategoriasMed = "SELECT ID_Categoria_Med, Categoria FROM CATEGORIAS_MEDICAMENTOS";
$categoriasMed = $conn->query($sqlCategoriasMed);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Dados gerais do produto
    $nome = $_POST['nome'];
    $med = isset($_POST['med']) ? 1 : 0;
    $marca = $_POST['marca'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];
    $id_unidade = $_POST['id_unidade'];
    $quant_minima = $_POST['quant_minima'];
    $status = $_POST['status'];
    $ncm = $_POST['ncm'];
    $ean_gtin = $_POST['ean_gtin'];
    $cbenef = $_POST['cbenef'];
    $cest = $_POST['cest'];
    $extipi = $_POST['extipi'];
    $cfop = $_POST['cfop'];
    $mva = $_POST['mva'];
    $nfci = $_POST['nfci'];

    // Foto
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_nome = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        $foto_destino = DEV_PATH . "Imagens/" . $foto_nome;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_destino)) {
            $foto = $foto_nome;
        }
    }

    // Inserção na tabela PRODUTOS
    $sql = "INSERT INTO PRODUTOS 
                (ID_Categoria, Nome, Med, Marca, Descricao, ID_Unidade,
                Quant_Minima, Status, NCM, EAN_GTIN, CBENEF, CEST, EXTIPI, CFOP, MVA, NFCI, Foto) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isisssiisssssssdss",
        $id_categoria, $nome, $med, $marca, $descricao, $id_unidade,
        $quant_minima, $status, $ncm, $ean_gtin, $cbenef, $cest,
        $extipi, $cfop, $mva, $nfci, $foto
    );

    if ($stmt->execute()) {
        $id_produto = $stmt->insert_id;

        // Se for medicamento, insere também na tabela MEDICAMENTOS
        if ($med) {
            $id_categoria_med = $_POST['id_categoria_med'];
            $prin_ativo = $_POST['prin_ativo'];
            $obs = $_POST['obs'];

            $sql_medicamento = "INSERT INTO MEDICAMENTOS (ID_Produto, ID_Categoria_Med, Prin_Ativo, OBS)
                                VALUES (?, ?, ?, ?)";
            $stmt_medicamento = $conn->prepare($sql_medicamento);
            $stmt_medicamento->bind_param("iiss", $id_produto, $id_categoria_med, $prin_ativo, $obs);
            $stmt_medicamento->execute();
        }

        $_SESSION["msg"] = "<div class='alert alert-success'>Produto cadastrado com sucesso!</div>";
        header("Location: produtos.php");
        exit();
    }
    else {
        echo "Erro ao inserir produto: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cadastro</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
    </head>
    <body>
        <!-- Navbar -->
        <?php include_once DEV_PATH . 'Views/sidebar.php'?>

        <div class="content">
            <!-- Banner -->
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Cadastro de Produto</h3>
                <?php
                    // Verifica se $_SESSION["msg"] não é nulo e imprime a mensagem
                    if(isset($_SESSION["msg"]) && $_SESSION["msg"] != null){
                        echo $_SESSION["msg"];
                        // Limpa a mensagem para evitar que seja exibida novamente
                        $_SESSION["msg"] = null;
                    }
                ?>
            </div>

            <!-- Formulário de Edição -->
            <div class="container p-5">
                <form action="#" method="POST">
                    <h5>Informações do Produto</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nome">Nome do Produto</label>
                            <input type="text" name="nome" class="form-control" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="med" class="mb-2">Medicamento?</label><br>
                            <input type="checkbox" class="form-check-input" id="med" name="med" onclick="toggleMedicamentoFields()"> Sim
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="marca">Marca</label>
                            <input type="text" name="marca" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="descricao">Descrição</label>
                            <input type="text" name="descricao" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="categoria">Categoria</label>
                            <select class="form-select" name="categoria" id="categoria" required>
                                <option value="">Selecione</option>
                                <?php while($cat = $categorias->fetch_assoc()): ?>
                                    <option value="<?= $cat['ID_Categoria'] ?>"><?= $cat['Categoria'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="unidade">Unidade</label>
                            <select class="form-select" name="unidade" id="unidade" required>
                                <option value="">Selecione</option>
                                <?php while($uni = $unidades->fetch_assoc()): ?>
                                    <option value="<?= $uni['ID_Unidade'] ?>"><?= $uni['Unidade'] ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="quant_min">Quantidade Mínima</label>
                            <input type="number" name="quant_min" class="form-control" value="10">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="status">Status</label>
                            <select name="status" class="form-control">
                                <option value="Ativo">Ativo</option>
                                <option value="Inativo">Inativo</option>
                            </select>
                        </div>

                        
                            <div class="col-md-3 mb-3" id="catMed" style="display: none;">
                                <label for="id_categoria_med" class="form-label">Categoria Medicamento</label>
                                <select class="form-select" name="id_categoria_med" id="id_categoria_med">
                                    <option value="">Selecione</option>
                                    <?php while($catMed = $categoriasMed->fetch_assoc()): ?>
                                        <option value="<?= $catMed['ID_Categoria_Med'] ?>"><?= $catMed['Categoria'] ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>

                            <div class="col-md-3 mb-3" id="prinatiMed" style="display: none;">
                                <label for="prin_ativo" class="form-label">Princípio Ativo</label>
                                <input type="text" class="form-control" name="prin_ativo" id="prin_ativo">
                            </div>

                            <div class="col-md-6 mb-3" id="obsMed" style="display: none;">
                                <label for="obs" class="form-label">Observações</label>
                                <textarea class="form-control" name="obs" id="obs" rows="2"></textarea>
                            </div>

                        <div class="col-md-12 mb-3">
                            <label for="foto">Foto (URL ou nome do arquivo)</label>
                            <input type="file" name="foto" class="form-control">
                        </div>
                    </div>

                    <hr>

                    <h5>Informações Fiscais</h5>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label>NCM</label>
                            <input type="text" name="ncm" class="form-control" maxlength="8" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>EAN/GTIN</label>
                            <input type="text" name="ean_gtin" class="form-control" maxlength="14" required>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>CBENEF</label>
                            <input type="text" name="cbenef" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>CEST</label>
                            <input type="text" name="cest" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>EXTIPI</label>
                            <input type="text" name="extipi" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>CFOP</label>
                            <input type="number" name="cfop" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>MVA</label>
                            <input type="text" name="mva" class="form-control">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>NFCI</label>
                            <input type="text" name="nfci" class="form-control">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Cadastrar Produto</button>
                </form>
            </div>
            
            <!-- Footer -->
            <?php include_once DEV_PATH . 'Views/footer.php'?>
        </div>

        
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const checkboxMed = document.getElementById("med");
                const catMed = document.getElementById("catMed");
                const prinatiMed = document.getElementById("prinatiMed");
                const obsMed = document.getElementById("obsMed");

                function toggleMedicamentoFields() {
                    if (checkboxMed.checked) {
                        catMed.style.display = "block";
                        prinatiMed.style.display = "block";
                        obsMed.style.display = "block";
                    } else {
                        catMed.style.display = "none";
                        prinatiMed.style.display = "none";
                        obsMed.style.display = "none";
                    }
                }

                // Inicializa ao carregar a página
                toggleMedicamentoFields();

                // Atualiza ao clicar no checkbox
                checkboxMed.addEventListener("change", toggleMedicamentoFields);
            });
        </script>
    </body>
</html>