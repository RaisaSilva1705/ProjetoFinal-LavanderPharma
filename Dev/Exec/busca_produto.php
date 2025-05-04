<?php
include "../../Dev/Exec/config.php";
include DEV_PATH . "Exec/conexao.php";

if (isset($_GET['codigo'])) {
    $codigo = $_GET['codigo'];

    $stmt = $conn->prepare("SELECT P.Nome, 
                                   L.Preco_Unitario, 
                                   P.Foto 
                            FROM PRODUTOS P 
                            LEFT JOIN LOTES L ON P.ID_Produto = L.ID_Produto
                            WHERE P.EAN_GTIN = ? LIMIT 1");
    $stmt->bind_param("s", $codigo);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($nome, $preco, $foto);
        $stmt->fetch();

        echo json_encode([
            'success' => true,
            'nome' => $nome,
            'preco' => $preco ?: 0.00,
            'foto' => $foto ?: 'sem-imagem.jpg'
        ]);
    } else {
        echo json_encode(['success' => false, 'msg' => 'Produto não encontrado.']);
    }

    $stmt->close();
    $conn->close();
}
?>
