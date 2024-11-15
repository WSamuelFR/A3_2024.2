<?php

require('calcula_runtime.php'); // Importa um script adicional (assume-se que seja para medir desempenho).

// Função para calcular a impureza de Gini, usada para avaliar quão "misturados" estão os dados.
function gini_impurity($rows)
{
    // Conta quantas vezes cada valor de 'avaliacao' aparece nos dados.
    $counts = array_count_values(array_map('strval', array_column($rows, 'avaliacao')));
    $impurity = 1;

    // Para cada valor, calcula a probabilidade e ajusta a impureza de Gini.
    foreach ($counts as $count) {
        $prob_of_lbl = $count / count($rows); // Proporção do rótulo atual.
        $impurity -= $prob_of_lbl * $prob_of_lbl; // Reduz a impureza com base no quadrado da proporção.
    }
    return $impurity; // Retorna o valor final da impureza.
}

// Calcula o ganho de informação entre dois subconjuntos de dados.
function information_gain($left, $right, $current_uncertainty)
{
    $p = count($left) / (count($left) + count($right)); // Proporção do subconjunto 'left'.
    // Subtrai as impurezas ponderadas dos dois subconjuntos do valor atual.
    return $current_uncertainty - $p * gini_impurity($left) - (1 - $p) * gini_impurity($right);
}

// Encontra o melhor ponto de divisão para os dados.
function find_best_split($data)
{
    $best_gain = 0; // Inicializa o ganho máximo.
    $best_split = null; // Armazena a melhor divisão.
    $current_uncertainty = gini_impurity($data); // Calcula a incerteza inicial (impureza de Gini).

    // Itera sobre os dados para testar divisões em cada valor de 'tempo_exibicao'.
    foreach ($data as $row) {
        $threshold = $row['tempo_exibicao']; // Define o ponto de divisão atual.
        
        // Divide os dados em dois subconjuntos: acima e abaixo do threshold.
        $true_rows = array_filter($data, function ($d) use ($threshold) {
            return $d['tempo_exibicao'] > $threshold; 
        });
        $false_rows = array_filter($data, function ($d) use ($threshold) {
            return $d['tempo_exibicao'] <= $threshold; 
        });

        // Ignora divisões inválidas (sem elementos em um subconjunto).
        if (count($true_rows) == 0 || count($false_rows) == 0) {
            continue;
        }

        // Calcula o ganho de informação para a divisão atual.
        $gain = information_gain($true_rows, $false_rows, $current_uncertainty);

        // Atualiza a melhor divisão se o ganho atual for maior.
        if ($gain > $best_gain) {
            $best_gain = $gain;
            $best_split = [
                'question' => $threshold,
                'true' => $true_rows,
                'false' => $false_rows
            ];
        }
    }
    return $best_split; // Retorna os detalhes da melhor divisão encontrada.
}

// Retorna o rótulo mais frequente entre os dados fornecidos.
function most_common_label($labels)
{
    $values = array_count_values(array_map('strval', $labels)); // Conta as ocorrências de cada rótulo.
    arsort($values); // Ordena de forma decrescente.
    return array_key_first($values); // Retorna o rótulo mais frequente.
}

// Constroi uma árvore de decisão recursivamente.
function build_tree($data, $depth = 0)
{
    if (empty($data)) {
        return null; // Retorna nulo se não houver dados.
    }

    $labels = array_column($data, 'avaliacao'); // Coleta os rótulos de saída.

    if (count(array_unique($labels)) === 1) {
        return $labels[0]; // Se todos os rótulos forem iguais, retorna o rótulo.
    }

    $best_split = find_best_split($data); // Encontra a melhor divisão.
    if (!$best_split) {
        return most_common_label($labels); // Retorna o rótulo mais comum se não houver boa divisão.
    }

    // Constroi recursivamente as ramificações da árvore.
    $true_branch = build_tree($best_split['true'], $depth + 1);
    $false_branch = build_tree($best_split['false'], $depth + 1);

    // Retorna a estrutura do nó da árvore.
    return [
        'question' => $best_split['question'],
        'true_branch' => $true_branch,
        'false_branch' => $false_branch
    ];
}

// Classifica uma entrada específica navegando pela árvore.
function classify($row, $node)
{
    if (!is_array($node)) {
        return $node; // Retorna o rótulo se alcançar uma folha.
    }

    $question = $node['question']; // Pega a pergunta (threshold atual).
    $answer = $row['tempo_exibicao'] > $question ? 'true_branch' : 'false_branch'; // Decide o caminho.
    return classify($row, $node[$answer]); // Continua navegando na árvore.
}

// Calcula precisão e taxa de acertos para os dados classificados.
function calcular_precisao($labels, $predictions)
{
    $true_positives = $false_positives = $true_negatives = $false_negatives = 0;

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

    // Calcula precisão e taxa de acerto.
    $accuracy = ($true_positives + $true_negatives) / count($labels);
    $precision = $true_positives + $false_positives > 0 ? $true_positives / ($true_positives + $false_positives) : 0;

    return [$accuracy, $precision];
}

// Processa os dados e constroi a árvore de decisão.
$data = ProcessaDados(); // Assume que essa função retorna o conjunto de dados.
$tree = build_tree($data); // Constroi a árvore de decisão.

$tempo_exibicao = $_POST['tempo_exibicao'] ?? null; // Recebe uma entrada do usuário.
$result = classify(['tempo_exibicao' => $tempo_exibicao], $tree); // Classifica a entrada.

$labels = [];
$predictions = [];

// Avalia o desempenho da árvore nos dados de treinamento.
foreach ($data as $filme) {
    $labels[] = $filme['avaliacao'] > 6.0 ? 1 : 0; // Define a "verdade" baseada na avaliação.
    $predictions[] = classify(['tempo_exibicao' => $filme['tempo_exibicao']], $tree); // Prediz usando a árvore.
}

list($accuracy, $precision) = calcular_precisao($labels, $predictions); // Calcula precisão e taxa de acerto.

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Classificação</title>
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
            <h1>Avaliar Modelo</h1>
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
            <div class="container">
                <h1>Resultado da Classificação</h1>
                <p>
                    O código classifica se a avaliação de um filme será alta ou baixa com base no tempo de exibição,
                    utilizando uma árvore de decisão construída a partir dos dados de filmes no arquivo CSV.
                </p>
                <h3>A avaliação prevista para um filme com exibição de <strong><?php echo $_POST['tempo_exibicao']; ?></strong> minutos é de <strong><?php echo $result; ?> Votos</strong></h3>
                <h3>Acurácia do modelo: <?php echo round($accuracy * 100, 2); ?>%</h3>
                <h3>Precisão do modelo: <?php echo round($precision * 100, 2); ?>%</h3>
                <a href="arvore_decisao.php">Voltar</a>
            </div>
        </article>
    </div>

    <footer>
        <p>Administração do Modelo &copy; 2024</p>
    </footer>

</body>

</html>