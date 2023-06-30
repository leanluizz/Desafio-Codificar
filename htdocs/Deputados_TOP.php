<?php

// Faz a requisição para a API de deputados em exercício
$deputadosUrl = 'http://dadosabertos.almg.gov.br/ws/deputados/em_exercicio?formato=json';
$deputadosResponse = file_get_contents($deputadosUrl);
$deputadosData = json_decode($deputadosResponse, true);

// Verifica se a requisição retornou algum resultado
if (isset($deputadosData['list']) && is_array($deputadosData['list'])) {
    $deputados = $deputadosData['list'];

    // Array para armazenar os deputados com seus totais de reembolso
    $deputadosTotais = array();

    // Loop pelos deputados em exercício
    foreach ($deputados as $deputado) {
        $deputadoId = $deputado['id'];

        // Monta a URL da API com os parâmetros
        $url = "https://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/deputados/{$deputadoId}/2019/2?formato=json";

        // Adiciona um atraso de 1 segundo entre as solicitações para evitar o limite de taxa
        sleep(1);

        // Faz a requisição para a API
        $response = file_get_contents($url);

        // Verifica se a requisição foi bem-sucedida
        if ($response !== false) {
            $responseData = json_decode($response, true);

            // Verifica se a resposta contém os dados esperados
            if (isset($responseData['list']) && is_array($responseData['list'])) {
                $verbas = $responseData['list'];

                // Variável para armazenar o total de reembolso do deputado
                $totalReembolso = 0;

                // Loop pelas verbas indenizatórias do deputado
                foreach ($verbas as $verba) {
                    // Obtém o valor da verba indenizatória e soma ao total de reembolso
                    $valorVerba = $verba['valor'];
                    $totalReembolso += $valorVerba;
                }

                // Armazena o total de reembolso do deputado no array
                $deputadosTotais[$deputado['nome']] = $totalReembolso;
            } else {
                echo "Erro: Dados inválidos na resposta da API\n";
            }
        } else {
            echo "Erro: Falha na requisição da API\n";
        }
    }

    // Função de comparação para ordenar os deputados pelo total de reembolso
    function compararTotalReembolso($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($a > $b) ? -1 : 1;
    }

    // Organiza os deputados pelo total de reembolso
    arsort($deputadosTotais);

    // Imprime o top 5 dos deputados com maior total de reembolso
    echo "Top 5 de Deputados com Maior Valor de Reembolso:\n";
    $contador = 1;
    foreach ($deputadosTotais as $nomeDeputado => $totalReembolso) {
        echo "{$contador}. {$nomeDeputado}: R$ {$totalReembolso}\n" . "<br>";
        $contador++;
        if ($contador > 5) {
            break;
        }
    }
} else {
    echo "Erro: Dados inválidos na resposta da API de deputados\n";
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

    // Faz a requisição para a API de deputados em exercício
    $deputadosUrl = 'http://dadosabertos.almg.gov.br/ws/deputados/em_exercicio?formato=json';
    $deputadosResponse = file_get_contents($deputadosUrl);
    $deputadosData = json_decode($deputadosResponse, true);

    // Verifica se a requisição retornou algum resultado
    if (isset($deputadosData['list']) && is_array($deputadosData['list'])) {
        $deputados = $deputadosData['list'];

        // Prepara a query de inserção dos deputados
        $insertDeputadoQuery = $pdo->prepare("INSERT INTO deputados (id, nome) VALUES (:id, :nome)");

        // Prepara a query de inserção dos reembolsos
        $insertReembolsoQuery = $pdo->prepare("INSERT INTO reembolsos (deputado_id, valor) VALUES (:deputado_id, :valor)");

        // Loop pelos deputados em exercício
        foreach ($deputados as $deputado) {
            $deputadoId = $deputado['id'];

            // Insere o deputado na tabela de deputados
            $insertDeputadoQuery->execute(array(
                ':id' => $deputadoId,
                ':nome' => $deputado['nome']
            ));

            // Monta a URL da API com os parâmetros
            $url = "https://dadosabertos.almg.gov.br/ws/prestacao_contas/verbas_indenizatorias/deputados/{$deputadoId}/2019/2?formato=json";

            // Adiciona um atraso de 1 segundo entre as solicitações para evitar o limite de taxa
            sleep(1);

            // Faz a requisição para a API
            $response = file_get_contents($url);

            // Verifica se a requisição foi bem-sucedida
            if ($response !== false) {
                $responseData = json_decode($response, true);

                // Verifica se a resposta contém os dados esperados
                if (isset($responseData['list']) && is_array($responseData['list'])) {
                    $verbas = $responseData['list'];

                    // Loop pelas verbas indenizatórias do deputado
                    foreach ($verbas as $verba) {
                        // Insere o reembolso na tabela de reembolsos
                        $insertReembolsoQuery->execute(array(
                            ':deputado_id' => $deputadoId,
                            ':valor' => $verba['valor']
                        ));
                    }
                } else {
                    echo "Erro: Dados inválidos na resposta da API\n";
                }
            } else {
                echo "Erro: Falha na requisição da API\n";
            }
        }

        echo "Dados enviados para o banco de dados com sucesso!\n";
    } else {
        echo "Erro: Dados inválidos na resposta da API de deputados\n";
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
}

?>
