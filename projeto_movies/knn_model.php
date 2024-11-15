<?php
// Aumenta o limite de memória disponível para o script, permitindo processar arquivos grandes
ini_set('memory_limit', '2048M');

/**
 * Função que processa os dados do arquivo CSV, retornando um array com informações de cada filme.
 * Cada item do array contém o código do filme (tconst), título, tempo de exibição, avaliação e número de votos.
 *
 * @param string $filename Nome do arquivo CSV que será lido.
 * @return array Array com dados dos filmes.
 */
function processaDados($filename): array
{
    $dados = []; // Array onde os dados dos filmes serão armazenados
    // Abre o arquivo CSV para leitura
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        fgetcsv($handle); // Lê e descarta o cabeçalho do CSV
        // Lê cada linha do arquivo e armazena os dados relevantes
        while (($data = fgetcsv($handle)) !== FALSE) {
            $dados[] = [
                'tconst' => $data[0], // Código do filme
                'titulo' => $data[2], // Título do filme
                'tempo_exibicao' => (int)$data[5], // Tempo de exibição do filme (convertido para inteiro)
                'avaliacao' => (float)$data[8], // Avaliação do filme (convertido para float)
                'num_votos' => (int)$data[9] // Número de votos do filme (convertido para inteiro)
            ];
        }
        fclose($handle); // Fecha o arquivo CSV
    }
    return $dados; // Retorna o array com os dados dos filmes
}

/**
 * Função que calcula a distância euclidiana entre dois filmes, levando em consideração o tempo de exibição e o número de votos.
 *
 * @param array $a Primeiro filme.
 * @param array $b Segundo filme.
 * @return float Distância euclidiana entre os filmes.
 */
function distanciaEuclidiana($a, $b): float
{
    // Calcula a distância euclidiana entre dois pontos (baseada em tempo de exibição e número de votos)
    return sqrt(pow($a['tempo_exibicao'] - $b['tempo_exibicao'], 2) +
                pow($a['num_votos'] - $b['num_votos'], 2));
}

/**
 * Função KNN (K-Nearest Neighbors) para selecionar os 'k' filmes mais próximos ao novo filme baseado nas distâncias calculadas.
 *
 * @param array $dados Array com os dados dos filmes.
 * @param array $novo_filme Novo filme para o qual se deseja prever a avaliação.
 * @param int $k Número de vizinhos a serem considerados.
 * @return array Array com os 'k' vizinhos mais próximos.
 */
function knn(array $dados, array $novo_filme, int $k): array
{
    // Calcula a distância de cada filme em relação ao novo filme
    foreach ($dados as $key => $filme) {
        $dados[$key]['distancia'] = distanciaEuclidiana($filme, $novo_filme);
    }

    // Ordena os filmes pela distância em ordem crescente (menor distância primeiro)
    usort($dados, function ($a, $b) {
        return $a['distancia'] <=> $b['distancia'];
    });

    // Seleciona os 'k' filmes mais próximos
    $vizinhos = array_slice($dados, 0, $k);
    return $vizinhos; // Retorna os 'k' vizinhos mais próximos
}

/**
 * Função que faz a previsão da avaliação de um novo filme com base nos vizinhos mais próximos.
 * A previsão é a média das avaliações dos 'k' vizinhos mais próximos.
 *
 * @param array $vizinhos Array com os 'k' vizinhos mais próximos.
 * @return int Previsão da avaliação, arredondada para o valor inteiro mais próximo.
 */
function predicao(array $vizinhos): int
{
    // Soma as avaliações dos vizinhos
    $soma_avaliacoes = array_sum(array_column($vizinhos, 'avaliacao'));
    // Retorna a média das avaliações (arredondada para o inteiro mais próximo)
    return $soma_avaliacoes / count($vizinhos);
}

/**
 * Função que calcula a precisão do modelo de previsão (KNN).
 * A precisão é a porcentagem de previsões corretas em relação ao total de previsões feitas.
 *
 * @param array $dados Array com os dados dos filmes.
 * @param int $k Número de vizinhos a serem considerados na previsão.
 * @return array Array contendo a acurácia (precisão), o número de acertos e o número total de previsões.
 */
function calculaPrecisao(array $dados, int $k): array
{
    $acertos = $total = 0; // Inicializa os contadores de acertos e total de previsões
    // Para cada filme nos dados, faz a previsão e compara com a avaliação real
    foreach ($dados as $filme) {
        // Encontra os vizinhos mais próximos para o filme atual
        $vizinhos = knn($dados, $filme, $k);
        // Faz a previsão da avaliação do filme
        $predicao = predicao($vizinhos);

        // Verifica se a previsão está correta (se a avaliação arredondada é igual à real)
        if (round($predicao) == round($filme['avaliacao'])) {
            $acertos++; // Incrementa o número de acertos
        }
        $total++; // Incrementa o total de previsões feitas
    }

    // Calcula a acurácia (porcentagem de acertos)
    $acuracia = $total > 0 ? $acertos / $total : 0;
    return [$acuracia, $acertos, $total]; // Retorna a acurácia, o número de acertos e o total
}

// Processa os dados do arquivo CSV
$dados = processaDados('summer_movies.csv');
// Número de vizinhos a serem considerados para a previsão
$k = 3;
// Calcula a precisão do modelo
list($acuracia, $acertos, $total) = calculaPrecisao($dados, $k);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelo KNN</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 80%;
            margin: auto;
            overflow: hidden;
        }

        header {
            background: #333;
            color: #fff;
            padding-top: 30px;
            min-height: 70px;
            border-bottom: #77d7ff 3px solid;
        }

        header a,
        header h1 {
            color: #fff;
            text-decoration: none;
            text-transform: uppercase;
            font-size: 16px;
        }

        nav ul {
            padding: 0;
            list-style: none;
        }

        nav ul li {
            display: inline;
            margin-right: 20px;
        }

        article {
            padding: 20px;
            background: #fff;
            border: #77d7ff 1px solid;
            margin-top: 20px;
        }

        footer {
            background: #333;
            color: #fff;
            text-align: center;
            padding: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<header>
        <div class="container">
            <h1>Administração do Modelo de Precisão</h1>
            <nav>
                <ul>
                <li><a href="view_menu.php">Home</a></li>
                    <li><a href="executar_modelo.php">Executar Modelo</a></li>
                    <li><a href="arvore_decisao.php">Avaliação de Filmes</a></li>
                    <li><a href="knn_form.php">Classificação de Filmes com KNN</a></li>
                    <li><a href="knn_model.php">Avaliação Modelo KNN</a></li><br><br>
                    <li><a href="view_graficos.php">Menu graficos</a></li>
                    <li><a href="view_basedados.php">Base de dados</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="container">
        <article>
        <h2>Métricas do Modelo KNN</h2>
            <h3>Acurácia: <?php echo round($acuracia * 100, 2) . '%'; ?></h3>
            <h3>Total de Filmes Avaliados: <?php echo $total; ?></h3>
            <h3>Acertos: <?php echo $acertos; ?></h3>
        </article>
    </div>

    <footer>
        <p>Administração do Modelo &copy; 2024</p>
    </footer>

</body>
</html>
