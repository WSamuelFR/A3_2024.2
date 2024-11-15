<?php
// Inclui a biblioteca PHPlot para gerar gráficos
require '../phplot-6.2.0/phplot.php';  

// Define o caminho do arquivo CSV que será processado
$csvFile = 'summer_movies.csv';
// Array para armazenar os dados extraídos do arquivo CSV
$data = [];

// Abre o arquivo CSV para leitura
if (($handle = fopen($csvFile, "r")) !== FALSE) {
    // Lê e descarta o cabeçalho do CSV
    fgetcsv($handle); 

    // Contador para limitar o número de filmes processados (50 filmes)
    $count = 0;
    // Lê as linhas do CSV, até um máximo de 50 filmes
    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE && $count < 50) {
        // Verifica se as colunas de tempo de exibição e número de votos são numéricas
        if (isset($row[5], $row[9]) && is_numeric($row[5]) && is_numeric($row[9])) {
            // Armazena as informações do filme no array de dados
            $data[] = [
                "tconst" => $row[0], // Código único do filme
                "runtime_minutes" => (int)$row[5], // Tempo de exibição (em minutos)
                "num_votes" => (int)$row[9] // Número de votos
            ];
            $count++; // Incrementa o contador de filmes processados
        }
    }
    fclose($handle); // Fecha o arquivo CSV após a leitura
}

// Array para armazenar os dados para a regressão linear
$regression_data = [];
// Prepara os dados para a regressão linear (relacionando votos e tempo de exibição)
foreach ($data as $movie) {
    $regression_data[] = [$movie["num_votes"], $movie["runtime_minutes"]];
}

// Cria um objeto PHPlot para gerar o gráfico
$plot = new PHPlot(1000, 600);
// Define o título do gráfico
$plot->SetTitle('Regressão Linear - Número de Votos vs Tempo de Exibição');
// Define o título do eixo X
$plot->SetXTitle('Número de Votos');
// Define o título do eixo Y
$plot->SetYTitle('Tempo de Execução (min)');

// Define os dados para o gráfico de regressão linear
$plot->SetDataValues($regression_data);
// Define o tipo de dados como 'data-data' (pares de dados numéricos)
$plot->SetDataType('data-data');
// Define o tipo de gráfico como linha com pontos
$plot->SetPlotType('linepoints');

// Define a cor da linha como azul
$plot->SetDataColors(['blue']);
// Define o tipo de borda do gráfico como 'full' (borda completa)
$plot->SetPlotBorderType('full');
// Define a cor da grade como cinza
$plot->SetGridColor('gray');
// Define a largura da linha do gráfico
$plot->SetLineWidths(2);
// Define a posição das legendas do eixo X como "abaixo do gráfico"
$plot->SetXTickLabelPos('plotdown');
// Define a posição das legendas do eixo Y como "à esquerda do gráfico"
$plot->SetYTickLabelPos('plotleft');
// Habilita a exibição da grade no eixo X
$plot->SetDrawXGrid(TRUE);
// Habilita a exibição da grade no eixo Y
$plot->SetDrawYGrid(TRUE);

// Gera o gráfico com base nas configurações acima
$plot->DrawGraph();
?>
