<?php
// Aumenta o limite de memória disponível para o script, permitindo processar arquivos grandes
ini_set('memory_limit', '2048M');

/**
 * Função que processa os dados do arquivo CSV e retorna um array com as informações de cada filme.
 * Cada item do array contém o título, avaliação e tempo de exibição do filme.
 */
function ProcessaDados(): array
{
    $filename = 'summer_movies.csv'; // Nome do arquivo CSV
    $dados = []; // Array onde os dados serão armazenados

    // Abre o arquivo CSV para leitura
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        $header = fgetcsv($handle); // Lê e descarta o cabeçalho do CSV
        // Lê cada linha do arquivo e armazena os dados relevantes
        while (($data = fgetcsv($handle)) !== FALSE) {
            $dados[] = [
                'titulo' => $data[2], // Coluna do título
                'avaliacao' => (float) $data[9], // Coluna da avaliação (convertida para float)
                'tempo_exibicao' => (float) $data[5] // Coluna do tempo de exibição (convertido para float)
            ];
        }
        fclose($handle); // Fecha o arquivo CSV
    }
    return $dados; // Retorna os dados lidos do arquivo
}

/**
 * Função que calcula a distância euclidiana entre dois pontos (baseada em avaliação e tempo de exibição).
 */
function calcular_distancia($p1, $p2)
{
    // Calcula a distância entre dois pontos usando a fórmula da distância euclidiana
    return sqrt(pow($p1['tempo_exibicao'] - $p2['tempo_exibicao'], 2) + pow($p1['avaliacao'] - $p2['avaliacao'], 2));
}

/**
 * Função KNN (K-Nearest Neighbors) para prever a avaliação de um filme baseado nos 'k' vizinhos mais próximos.
 * 
 * @param array $dados Array com os dados dos filmes.
 * @param array $ponto_novo O ponto (filme) para o qual queremos prever a avaliação.
 * @param int $k Número de vizinhos a considerar.
 * @return float A avaliação prevista para o filme.
 * @throws InvalidArgumentException Caso o valor de 'k' seja menor ou igual a 0.
 * @throws Exception Caso não haja vizinhos encontrados.
 */
function knn($dados, $ponto_novo, $k)
{
    // Verifica se o valor de K é válido
    if ($k <= 0) {
        throw new InvalidArgumentException('O valor de K deve ser maior que zero.');
    }

    $distancias = []; // Array para armazenar as distâncias entre o ponto novo e os outros filmes

    // Calcula a distância entre o ponto novo e cada filme nos dados
    foreach ($dados as $dado) {
        $distancia = calcular_distancia($ponto_novo, $dado);
        $distancias[] = ['distancia' => $distancia, 'avaliacao' => $dado['avaliacao']];
    }

    // Ordena as distâncias em ordem crescente
    usort($distancias, function ($a, $b) {
        return $a['distancia'] <=> $b['distancia'];
    });

    // Seleciona os 'k' vizinhos mais próximos
    $vizinhos = array_slice($distancias, 0, $k);
    // Verifica se existem vizinhos suficientes
    if (count($vizinhos) === 0) {
        throw new Exception('Nenhum vizinho encontrado. Verifique os dados de entrada.');
    }

    // Soma as avaliações dos vizinhos e calcula a média
    $soma_avaliacoes = 0;
    foreach ($vizinhos as $vizinho) {
        $soma_avaliacoes += $vizinho['avaliacao'];
    }

    // Retorna a média das avaliações dos k vizinhos
    return $soma_avaliacoes / count($vizinhos);
}

// Verifica se a requisição é um POST (quando o formulário é enviado)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recebe os dados enviados pelo formulário
    $tempo_exibicao = (float) $_POST['tempo_exibicao']; // Tempo de exibição
    $k = (int) $_POST['k']; // Número de vizinhos K

    try {
        // Processa os dados do arquivo CSV
        $dados = ProcessaDados();
        // Define o ponto novo para previsão, com avaliação inicial igual a 0
        $ponto_novo = ['tempo_exibicao' => $tempo_exibicao, 'avaliacao' => 0];
        // Realiza a previsão usando o algoritmo KNN
        $resultado = knn($dados, $ponto_novo, $k);
        $r = round($resultado, 2); // Arredonda o resultado para 2 casas decimais
        // Redireciona para a página 'knn_form.php' com o resultado da previsão
        header("Location: knn_form.php?resultado={$r}");
        exit();
    } catch (Exception $e) {
        // Em caso de erro, redireciona para 'knn_form.php' com a mensagem de erro
        header("Location: knn_form.php?erro=" . urlencode($e->getMessage()));
        exit();
    }
}
?>
