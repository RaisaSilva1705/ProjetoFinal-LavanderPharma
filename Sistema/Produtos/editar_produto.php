<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../../Dev/Exec/config.php";
include DEV_PATH . 'Exec/conexao.php';
include DEV_PATH . "Exec/validar_sessao.php";
include DEV_PATH . "Exec/validar_acesso.php";

// Verificar se o parâmetro "codigo" foi passado pela URL
if (isset($_GET['codigo'])) {
    $id_produto = intval($_GET['codigo']);

    // Buscar dados do produto
    $sqlProduto = "SELECT * FROM PRODUTOS WHERE ID_Produto = ?";
    $stmt = $conn->prepare($sqlProduto);
    $stmt->bind_param("i", $id_produto);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $produto = $result->fetch_assoc();

        // Se for medicamento, buscar também os dados
        $medicamento = null;
        if ($produto['ID_Categoria'] == 1) {
            $sqlMedicamento = "SELECT * FROM MEDICAMENTOS WHERE ID_Produto = ?";
            $stmtMed = $conn->prepare($sqlMedicamento);
            $stmtMed->bind_param("i", $id_produto);
            $stmtMed->execute();
            $resMed = $stmtMed->get_result();
            if ($resMed->num_rows > 0) {
                $medicamento = $resMed->fetch_assoc();
            }
        }

        // Categorias
        $sqlCategorias = "SELECT ID_Categoria, Categoria FROM CATEGORIAS";
        $categorias = $conn->query($sqlCategorias);

        // Unidades
        $sqlUnidades = "SELECT ID_Unidade, Unidade FROM UNIDADES";
        $unidades = $conn->query($sqlUnidades);

        // Categorias de medicamentos
        $sqlCategoriasMed = "SELECT ID_CategoriaMed, Categoria_Med FROM CATEGORIAS_MEDICAMENTOS";
        $categoriasMed = $conn->query($sqlCategoriasMed);

        // Tarjas de medicamentos
        $sqlTarjasMed = "SELECT ID_Tarja, Tarja FROM TARJAS_MEDICAMENTOS";
        $tarjasMed = $conn->query($sqlTarjasMed);
    }
    else {
        echo "Unidade não encontrada.";
        exit();
    }
}
else {
    echo "Código da unidade não fornecida.";
    exit();
}

// Verificar se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe dados do produto
    $nome = $_POST['nome'];
    $marca = $_POST['marca'];
    $descricao = $_POST['descricao'];
    $id_categoria = $_POST['id_categoria'];
    $id_unidade = $_POST['id_unidade'];
    $quant_minima = $_POST['quant_minima'];
    $obs = $_POST['obs'];
    $status = $_POST['status'];
    $ncm = $_POST['ncm'];
    $ean_gtin = $_POST['ean_gtin'];
    $cbenef = $_POST['cbenef'];
    $cest = $_POST['cest'];
    $extipi = $_POST['extipi'];
    $cfop = $_POST['cfop'];
    $mva = $_POST['mva'];
    $nfci = $_POST['nfci'];

    // Atualiza foto se enviada
    $foto = $produto['Foto'];
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto_nome = uniqid() . "_" . basename($_FILES["foto"]["name"]);
        $foto_destino = DEV_PATH . "Imagens/" . $foto_nome;
        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_destino)) {
            $foto = $foto_nome;
        }
    }

    // Atualiza tabela PRODUTOS
    $sqlUpdate = "UPDATE PRODUTOS 
                  SET ID_Categoria = ?, Nome = ?, Marca = ?, Descricao = ?, ID_Unidade = ?, 
                      Quant_Minima = ?, Status = ?, OBS = ?, NCM = ?, EAN_GTIN = ?, CBENEF = ?, 
                      CEST = ?, EXTIPI = ?, CFOP = ?, MVA = ?, NFCI = ?, Foto = ?
                  WHERE ID_Produto = ?";
    $stmtUpdate = $conn->prepare($sqlUpdate);
    $stmtUpdate->bind_param("isssiisssssssidssi",
        $id_categoria, $nome, $marca, $descricao, $id_unidade,
        $quant_minima, $status, $obs, $ncm, $ean_gtin, $cbenef, $cest,
        $extipi, $cfop, $mva, $nfci, $foto, $id_produto
    );

    if ($stmtUpdate->execute()) {
        if ($id_categoria == 1) {
            // Dados medicamento
            $id_categoria_med = $_POST['id_categoria_med'];
            $prin_ativo = $_POST['prin_ativo'];
            $id_tarja = $_POST['id_tarja_med'];
            $tipo = $_POST['tipo_med'];

            if ($medicamento) {
                $sqlUpdateMed = "UPDATE MEDICAMENTOS 
                                 SET ID_CategoriaMed = ?, ID_Tarja = ?, Tipo = ?, Prin_Ativo = ?
                                 WHERE ID_Produto = ?";
                $stmtUpdateMed = $conn->prepare($sqlUpdateMed);
                $stmtUpdateMed->bind_param("iissi", $id_categoria_med, $id_tarja, $tipo, $prin_ativo, $id_produto);
                $stmtUpdateMed->execute();
            } else {
                $sqlInsertMed = "INSERT INTO MEDICAMENTOS (ID_Produto, ID_CategoriaMed, ID_Tarja, Tipo, Prin_Ativo)
                                 VALUES (?, ?, ?, ?, ?)";
                $stmtInsertMed = $conn->prepare($sqlInsertMed);
                $stmtInsertMed->bind_param("iiiss", $id_produto, $id_categoria_med, $id_tarja, $tipo, $prin_ativo);
                $stmtInsertMed->execute();
            }
        } else {
            if ($medicamento) {
                $conn->query("DELETE FROM MEDICAMENTOS WHERE ID_Produto = $id_produto");
            }
        }

        $_SESSION["msg"] = "<div class='alert alert-success'>Produto atualizado com sucesso!</div>";
        header("Location: produtos.php");
        exit();
    } else {
        echo "Erro ao atualizar produto: " . $stmtUpdate->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Editar Produto</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <link rel="stylesheet" href="<?php echo DEV_URL ?>CSS/global.css">
        <style>
            select > option:first-child {
                display: none;
            }
        </style>
    </head>
    <body>
        <?php include_once DEV_PATH . 'Views/sidebar.php'; ?>

        <div class="content">
            <div class="container-fluid bg-secondary text-white text-center p-4">
                <h3>Editar Produto</h3>
                <?php if (isset($_SESSION["msg"])) { echo $_SESSION["msg"]; unset($_SESSION["msg"]); } ?>
            </div>

            <div class="container p-5">
                <form action="" method="POST" enctype="multipart/form-data">
                    <h5 class="mt-4">Informações do Produto</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Nome</label>
                            <input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($produto['Nome']) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Marca</label>
                            <input type="text" name="marca" class="form-control" value="<?= htmlspecialchars($produto['Marca']) ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Categoria</label>
                            <select class="form-select" name="id_categoria" id="id_categoria" required>
                                <option value="">Selecione</option>
                                <?php while($cat = $categorias->fetch_assoc()): ?>
                                    <option value="<?= $cat['ID_Categoria'] ?>" <?= ($produto['ID_Categoria'] == $cat['ID_Categoria']) ? 'selected' : '' ?>>
                                        <?= $cat['Categoria'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Descrição</label>
                            <textarea name="descricao" class="form-control"><?= htmlspecialchars($produto['Descricao'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Unidade</label>
                            <select class="form-select" name="id_unidade" required>
                                <option value="">Selecione</option>
                                <?php while($uni = $unidades->fetch_assoc()): ?>
                                    <option value="<?= $uni['ID_Unidade'] ?>" <?= ($produto['ID_Unidade'] == $uni['ID_Unidade']) ? 'selected' : '' ?>>
                                        <?= $uni['Unidade'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Quantidade Mínima</label>
                            <input type="number" name="quant_minima" class="form-control" value="<?= htmlspecialchars($produto['Quant_Minima']) ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Observações</label>
                            <textarea name="obs" class="form-control"><?= htmlspecialchars($produto['OBS'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Status</label>
                            <select class="form-select" name="status" required>
                                <option value="Ativo" <?= ($produto['Status'] == "Ativo") ? 'selected' : '' ?>>Ativo</option>
                                <option value="Inativo" <?= ($produto['Status'] == "Inativo") ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>Foto</label>
                            <input type="file" name="foto" class="form-control">
                            <?php if (!empty($produto['Foto'])): ?>
                                <img src="<?= DEV_URL ?>Imagens/<?= $produto['Foto'] ?>" alt="Foto Produto" class="img-thumbnail mt-2" width="100">
                            <?php endif; ?>
                        </div>


                        <!-- Campos de Medicamento -->
                        <div id="campos_medicamento" style="display: none;">
                            <hr>

                            <h5 class="mt-4">Informações do Medicamento</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Categoria do Medicamento</label>
                                    <select class="form-select" name="id_categoria_med">
                                        <option value="">Selecione</option>
                                        <?php while($catMed = $categoriasMed->fetch_assoc()): ?>
                                            <option value="<?= $catMed['ID_CategoriaMed'] ?>" <?= ($medicamento && $medicamento['ID_CategoriaMed'] == $catMed['ID_CategoriaMed']) ? 'selected' : '' ?>>
                                                <?= $catMed['Categoria_Med'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Tarja</label>
                                    <select class="form-select" name="id_tarja_med">
                                        <option value="">Selecione</option>
                                        <?php while($tarja = $tarjasMed->fetch_assoc()): ?>
                                            <option value="<?= $tarja['ID_Tarja'] ?>" <?= ($medicamento && $medicamento['ID_Tarja'] == $tarja['ID_Tarja']) ? 'selected' : '' ?>>
                                                <?= $tarja['Tarja'] ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Tipo de Medicamento</label>
                                    <input type="text" name="tipo_med" class="form-control" value="<?= $medicamento ? htmlspecialchars($medicamento['Tipo']) : '' ?>">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label>Princípio Ativo</label>
                                    <input type="text" name="prin_ativo" class="form-control" value="<?= $medicamento ? htmlspecialchars($medicamento['Prin_Ativo']) : '' ?>">
                                </div>
                            </div>
                        </div>

                        <hr>

                        <!-- Campos fiscais -->
                        <div class="col-md-3 mb-3">
                            <label>NCM</label>
                            <input type="text" name="ncm" class="form-control" value="<?= htmlspecialchars($produto['NCM'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>EAN/GTIN</label>
                            <input type="text" name="ean_gtin" class="form-control" value="<?= htmlspecialchars($produto['EAN_GTIN'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>CBENEF</label>
                            <input type="text" name="cbenef" class="form-control" value="<?= htmlspecialchars($produto['CBENEF'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>CEST</label>
                            <input type="text" name="cest" class="form-control" value="<?= htmlspecialchars($produto['CEST'] ?? '') ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label>EXTIPI</label>
                            <input type="text" name="extipi" class="form-control" value="<?= htmlspecialchars($produto['EXTIPI'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>CFOP</label>
                            <input type="text" name="cfop" class="form-control" value="<?= htmlspecialchars($produto['CFOP'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>MVA</label>
                            <input type="text" name="mva" class="form-control" value="<?= htmlspecialchars($produto['MVA'] ?? '') ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label>NFCI</label>
                            <input type="text" name="nfci" class="form-control" value="<?= htmlspecialchars($produto['NFCI'] ?? '') ?>">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Salvar Alterações</button>
                    <a href="produtos.php" class="btn btn-secondary mt-4 ms-2">Cancelar</a>
                </form>
            </div>

            <?php include_once DEV_PATH . 'Views/footer.php'; ?>
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const categoria = document.getElementById("id_categoria");
                const camposMedicamento = document.getElementById("campos_medicamento");

                function toggleCamposMedicamento() {
                    if (categoria.value == "1") {
                        camposMedicamento.style.display = "block";
                    } else {
                        camposMedicamento.style.display = "none";
                    }
                }

                toggleCamposMedicamento();
                categoria.addEventListener("change", toggleCamposMedicamento);
            });
        </script>

    </body>
</html>
