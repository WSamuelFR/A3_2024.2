<?php
// Aumenta o limite de memória disponível para o script
ini_set('memory_limit', '2048M');

/**
 * Lê o arquivo CSV e processa os dados em um array associativo.
 * Cada elemento contém título, avaliação e tempo de exibição do filme.
 */
function processaDados(): array
{
    $filename = 'summer_movies.csv'; // Nome do arquivo CSV
    $dados = []; // Array para armazenar os dados processados

    if (($handle = fopen($filename, 'r')) !== FALSE) { 
        fgetcsv($handle); // Lê e descarta o cabeçalho do CSV
        while (($data = fgetcsv($handle)) !== FALSE) {
            $dados[] = [
                'titulo' => $data[2], // Nome do filme
                'avaliacao' => (float) $data[9], // Avaliação média
                'tempo_exibicao' => (float) $data[5] // Tempo de exibição
            ];
        }
        fclose($handle); // Fecha o arquivo
    }
    return $dados;
}

/**
 * Calcula a média das avaliações agrupadas pelo tempo de exibição arredondado.
 */
function calculaMedia(): array
{
    $dados = processaDados(); // Obtém os dados do CSV
    $tempo_intervalos = []; // Array para armazenar somas e contagens por intervalo de tempo

    foreach ($dados as $filme) {
        $tempo = round($filme['tempo_exibicao']); // Arredonda o tempo de exibição
        if (!isset($tempo_intervalos[$tempo])) {
            $tempo_intervalos[$tempo] = ['soma_avaliacoes' => 0, 'count' => 0];
        }
        $tempo_intervalos[$tempo]['soma_avaliacoes'] += $filme['avaliacao']; // Soma avaliações
        $tempo_intervalos[$tempo]['count']++; // Conta filmes no intervalo
    }

    // Calcula a média de avaliações para cada intervalo
    return array_map(function ($valores) {
        return $valores['soma_avaliacoes'] / $valores['count'];
    }, $tempo_intervalos);
}

/**
 * Identifica os tempos de exibição com a maior média de avaliações.
 */
function tempoIdealExibicao(): array
{
    $medias_avaliacoes = calculaMedia(); // Calcula as médias
    return array_keys($medias_avaliacoes, max($medias_avaliacoes)); // Retorna tempos com a maior média
}

/**
 * Faz uma predição baseada no tempo de exibição ideal.
 * Se o tempo do filme está no intervalo ideal, retorna 1; caso contrário, 0.
 */
function predicaoAvaliacao(float $tempo_exibicao, array $melhor_tempo): int
{
    return in_array(round($tempo_exibicao), $melhor_tempo) ? 1 : 0;
}

/**
 * Avalia a precisão do modelo comparando predições com os rótulos reais.
 */
function avaliarPrecisao(): float
{
    $dados = processaDados(); // Obtém os dados do CSV
    $melhor_tempo = tempoIdealExibicao(); // Obtém os tempos ideais
    $labels = []; // Rótulos reais
    $predictions = []; // Predições do modelo

    foreach ($dados as $filme) {
        $labels[] = $filme['avaliacao'] > 4000 ? 1 : 0; // Define rótulo com base na avaliação
        $predictions[] = predicaoAvaliacao($filme['tempo_exibicao'], $melhor_tempo); // Predição do modelo
    }

    return precisao($labels, $predictions); // Calcula a precisão
}

/**
 * Calcula a precisão simples como a proporção de predições corretas.
 */
function precisao(array $labels, array $predictions): float
{
    $corretos = array_sum(array_map(function ($label, $prediction) {
        return $label === $prediction ? 1 : 0; // Soma predições corretas
    }, $labels, $predictions));

    return $corretos / count($labels); // Retorna a precisão
}

/**
 * Calcula a acurácia e a precisão detalhadas do modelo.
 */
function calculaAcuraciaEPrecisao(array $dados): array
{
    $labels = [];
    $predictions = [];
    foreach ($dados as $filme) {
        $labels[] = $filme['avaliacao'] > 4000 ? 1 : 0; // Define rótulo com base na avaliação
        $predictions[] = predicaoAvaliacao($filme['tempo_exibicao'], tempoIdealExibicao()); // Predição do modelo
    }

    // Variáveis para contagem dos casos
    $true_positives = $true_negatives = $false_positives = $false_negatives = 0;

    foreach ($labels as $i => $label) {
        if ($label == 1 && $predictions[$i] == 1) {
            $true_positives++;
        } elseif ($label == 0 && $predictions[$i] == 0) {
            $true_negatives++;
        } elseif ($label == 0 && $predictions[$i] == 1) {
            $false_positives++;
        } elseif ($label == 1 && $predictions[$i] == 0) {
            $false_negatives++;
        }
    }

    // Calcula acurácia e precisão
    $accuracy = ($true_positives + $true_negatives) / count($labels);
    $precision = $true_positives ? $true_positives / ($true_positives + $false_positives) : 0;

    return [$accuracy, $precision]; // Retorna acurácia e precisão
}

// Processamento e exibição dos resultados
$dados = processaDados(); // Processa os dados do CSV
$tempo_ideal = tempoIdealExibicao(); // Obtém o tempo ideal de exibição
$precisao = round(avaliarPrecisao(), 2); // Calcula a precisão geral do modelo
list($accuracy, $precision) = calculaAcuraciaEPrecisao($dados); // Calcula acurácia e precisão detalhadas
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Executar Modelo</title>
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
            <h1>Executar Modelo</h1>
            <nav>
                <ul>
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
            <h1>Métricas do Modelo</h1>
            <h3>Tempo ideal de exibição: <?php echo implode(', ', $tempo_ideal); ?> minutos</h3>
            <h3>A precisão do modelo é: <?php echo ($precisao * 100) . "%"; ?></h3>
            <h3>Acurácia: <?php echo round($accuracy * 100, 2); ?>%</h3>
            <h2>Gráfico de Regressão Linear</h2>
            <img src="regressao_linear.php" alt="Gráfico de Regressão Linear">
        </article>
    </div>
    <footer>
        <p>Administração do Modelo &copy; 2024</p>
    </footer>
</body>
</html>
