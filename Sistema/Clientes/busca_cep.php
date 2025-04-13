<?php
header('Content-Type: application/json'); // Define o cabeçalho para JSON

// Incluir o arquivo de conexão com o banco de dados
include '../../central/includes/conexao.php';

// Verificar se o parâmetro 'cep' foi passado via GET
if (isset($_GET['cep'])) {
    // Sanitiza o CEP recebido para evitar SQL Injection
    $cep = filter_input(INPUT_GET, 'cep', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Preparar a consulta SQL para buscar o CEP na tabela 'ceps'
    $sql = "SELECT * FROM CEPS WHERE CEP = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cep);
    $stmt->execute();
    $result = $stmt->get_result();

    // Verificar se o CEP foi encontrado
    if ($result->num_rows > 0) {
        // Obter o primeiro resultado da consulta
        $row = $result->fetch_assoc();
        
        // Retornar os dados em formato JSON
        echo json_encode([
            'success' => true,
            'estado' => $row['Estado_CEP'],
            'cidade' => $row['Cidade_CEP'],
            'bairro' => $row['Bairro_CEP'],
            'endereco' => $row['Endereco_CEP']
        ]);
    } else {
        // Se o CEP não for encontrado, retorna uma resposta de erro
        echo json_encode(['success' => false, 'message' => 'CEP não encontrado.']);
    }
} else {
    // Se o CEP não foi fornecido, retorna uma resposta de erro
    echo json_encode(['success' => false, 'message' => 'CEP não fornecido.']);
}

// Fechar a conexão
$conn->close();
?>
