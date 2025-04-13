<?php
    include '../../dev/Exec/conexao.php';

    // Definir tempo de execução ilimitado e aumentar o limite de memória
    set_time_limit(0);
    ini_set('memory_limit', '1024M');

    // Caminho para o arquivo CSV
    $arquivo_csv = 'arquivo_ceps.csv';

    // Abre o arquivo CSV
    if (($handle = fopen($arquivo_csv, "r")) !== FALSE){
        // Ignorar a primeira linha
        fgetcsv($handle, 1000, ";");

        // Prepara a consulta SQL
        $sql = "INSERT INTO ceps (CEP, Endereco_CEP, Bairro_CEP, Cidade_CEP, Estado_CEP) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        // Definir o charset da conexão para UTF-8
        $conn->set_charset('utf8mb4');

        // Lendo o arquivo CSV linha por linha
        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE){
            // Realizar o bind e a execução da query
            $stmt->bind_param("sssss", $cep, $endereco, $bairro, $cidade, $estado);

            // Atribuindo as variaveis ao valores dentro de $data
            list($cep, $endereco, $bairro, $cidade, $estado) = $data;

            // convertendo os caracteres "errados" para especiais latinos 
            $cep = mb_convert_encoding($data[0], "UTF-8", "ISO-8859-1");
            $endereco = mb_convert_encoding($data[1], "UTF-8", "ISO-8859-1");
            $bairro = mb_convert_encoding($data[2], "UTF-8", "ISO-8859-1");
            $cidade = mb_convert_encoding($data[3], "UTF-8", "ISO-8859-1");
            $estado = mb_convert_encoding($data[4], "UTF-8", "ISO-8859-1");

            if (!($stmt->execute()))
                echo "Erro ao inserir os dados: " . $stmt->error . "<br>";
        }

        echo "Importação concluída.<br>";

        fclose($handle);
    }
    else{
        echo "Erro ao abrir o arquivo CSV.";
    }
?>
