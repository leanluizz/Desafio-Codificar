<?php

// Faz a requisição para a API de lista telefônica de deputados
$deputadosUrl = 'http://dadosabertos.almg.gov.br/ws/deputados/lista_telefonica?formato=json';
$deputadosResponse = file_get_contents($deputadosUrl);
$deputadosData = json_decode($deputadosResponse, true);

// Verifica se a requisição retornou algum resultado
if (isset($deputadosData['list']) && is_array($deputadosData['list'])) {
    $deputados = $deputadosData['list'];

    // Array para armazenar a contagem das redes sociais
    $redesSociaisCount = array();

    // Itera sobre os deputados
    foreach ($deputados as $deputado) {
        if (isset($deputado['redesSociais']) && is_array($deputado['redesSociais'])) {
            $redesSociais = $deputado['redesSociais'];

            // Itera sobre as redes sociais do deputado
            foreach ($redesSociais as $redeSocial) {
                if (isset($redeSocial['redeSocial']['nome'])) {
                    $redeSocialNome = $redeSocial['redeSocial']['nome'];

                    // Verifica se a rede social já está no array de contagem
                    if (isset($redesSociaisCount[$redeSocialNome])) {
                        // Se já existir, incrementa a contagem
                        $redesSociaisCount[$redeSocialNome]++;
                    } else {
                        // Caso contrário, inicializa a contagem
                        $redesSociaisCount[$redeSocialNome] = 1;
                    }
                }
            }
        }
    }

    // Ordena o array de contagem em ordem decrescente
    arsort($redesSociaisCount);

    // Exibe o ranking das redes sociais mais comuns
    echo "Ranking das Redes Sociais:\n" . "<br>";
    $posicao = 1;
    foreach ($redesSociaisCount as $redeSocial => $count) {
        echo $posicao . ". " . $redeSocial . " (" . $count . " deputados)\n" . "<br>";
        $posicao++;
    }
} else {
    echo "Não foi possível obter os dados da API de lista telefônica de deputados.";
}


// Configurações do banco de dados
$host = 'localhost';
$dbName = 'nome_do_banco';
$username = 'usuario';
$password = 'senha';

try {
    // Conexão com o banco de dados
    $dsn = "mysql:host=$host;dbname=$dbName;charset=utf8";
    $pdo = new PDO($dsn, $username, $password);

    // Define o modo de erro do PDO para exceções
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Faz a requisição para a API de lista telefônica de deputados
    $deputadosUrl = 'http://dadosabertos.almg.gov.br/ws/deputados/lista_telefonica?formato=json';
    $deputadosResponse = file_get_contents($deputadosUrl);
    $deputadosData = json_decode($deputadosResponse, true);

    // Verifica se a requisição retornou algum resultado
    if (isset($deputadosData['list']) && is_array($deputadosData['list'])) {
        $deputados = $deputadosData['list'];

        // Prepara a query de inserção dos deputados
        $insertDeputadoQuery = $pdo->prepare("INSERT INTO deputados (nome, telefone) VALUES (:nome, :telefone)");

        // Loop pelos deputados
        foreach ($deputados as $deputado) {
            $nome = $deputado['nome'];
            $telefone = $deputado['telefone'];

            // Insere o deputado na tabela de deputados
            $insertDeputadoQuery->execute(array(
                ':nome' => $nome,
                ':telefone' => $telefone
            ));
        }

        echo "Dados enviados para o banco de dados com sucesso!\n";
    } else {
        echo "Não foi possível obter os dados da API de lista telefônica de deputados.";
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}
?>
