<?php
require_once '../phplot-6.2.0/phplot.php'; // Inclui a biblioteca PHPlot para gerar gráficos.

// Inclui dois arquivos PHP responsáveis por fornecer dados.
include('dataMaior.php'); // Arquivo contendo a função `dataMaior`, que retorna um conjunto específico de dados.
include('dataMenor.php'); // Arquivo contendo a função `dataMenor`, que retorna outro conjunto de dados.

// Obtém a escolha do conjunto de dados a ser utilizado a partir do POST.
$dados = isset($_POST['dados']) ? $_POST['dados'] : ''; // Default vazio caso não seja enviado.

if ($dados == 0) { 
    $data = dataMaior(); // Se o valor for 0, usa o conjunto de dados retornado pela função `dataMaior`.

    $plot = new PHPlot(1500, 700); // Cria um novo objeto PHPlot com dimensões 1500x700 pixels.
    $plot->SetImageBorderType('plain'); // Define o tipo de borda para o gráfico como "plain" (simples).

    // Obtém o tipo de gráfico a ser gerado, enviado via POST.
    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : ''; // Default vazio caso não seja enviado.

    // Define o tipo de gráfico com base no valor de `$tipo`.
    switch ($tipo) {
        case '0':
            $tp = 'bars'; // Barras.
            break;
        case '1':
            $tp = 'lines'; // Linhas.
            break;
        case '2':
            $tp = 'linepoints'; // Pontos conectados por linhas.
            break;
        case '3':
            $tp = 'points'; // Apenas pontos.
            break;
        case '4':
            $tp = 'area'; // Área preenchida.
            break;
        case '5':
            $tp = 'pie'; // Gráfico de pizza.
            break;
        case '6':
            $tp = 'stackedarea'; // Áreas empilhadas.
            break;
        case '7':
            $tp = 'stackedbars'; // Barras empilhadas.
            break;
        case '8':
            $tp = 'thinbarline'; // Linhas finas entre barras.
            break;
        default:
            break; // Nenhuma ação caso `$tipo` não corresponda a nenhum caso.
    }

    // Configurações do gráfico.
    $plot->SetPlotType($tp); // Define o tipo de gráfico escolhido.
    $plot->SetDataType('text-data'); // Define o tipo de dados como texto e numérico.
    $plot->SetDataValues($data); // Passa os dados ao gráfico.

    // Configurações de títulos e legendas.
    $plot->SetTitle('Grafico runtime minutes'); // Título principal do gráfico.
    $plot->SetXTitle('summer movies title'); // Título do eixo X.
    $plot->SetYTitle('Values'); // Título do eixo Y.
    $plot->SetLegend(array('average_rating', 'runtime_minutes', 'num_votes')); // Legenda para as séries de dados.
    $plot->SetYDataLabelPos('plotin'); // Exibe os rótulos de dados dentro da área do gráfico.

    $plot->DrawGraph(); // Desenha o gráfico na tela.

} elseif ($dados == 1) { 
    $data = dataMenor(); // Se o valor for 1, usa o conjunto de dados retornado pela função `dataMenor`.

    $plot = new PHPlot(1500, 700); // Cria um novo gráfico com as mesmas dimensões.
    $plot->SetImageBorderType('plain'); // Define a borda simples.

    $tipo = isset($_POST['tipo']) ? $_POST['tipo'] : ''; // Obtém o tipo de gráfico enviado via POST.

    // Mesma lógica de escolha do tipo de gráfico para este conjunto de dados.
    switch ($tipo) {
        case '0':
            $tp = 'bars';
            break;
        case '1':
            $tp = 'lines';
            break;
        case '2':
            $tp = 'linepoints';
            break;
        case '3':
            $tp = 'points';
            break;
        case '4':
            $tp = 'area';
            break;
        case '5':
            $tp = 'pie';
            break;
        case '6':
            $tp = 'stackedarea';
            break;
        case '7':
            $tp = 'stackedbars';
            break;
        case '8':
            $tp = 'thinbarline';
            break;
        default:
            break;
    }

    // Configurações do gráfico (idênticas ao bloco anterior).
    $plot->SetPlotType($tp);
    $plot->SetDataType('text-data');
    $plot->SetDataValues($data);
    $plot->SetTitle('Grafico runtime minutes');
    $plot->SetXTitle('summer movies title');
    $plot->SetYTitle('Values');
    $plot->SetLegend(array('average_rating', 'runtime_minutes', 'num_votes'));
    $plot->SetYDataLabelPos('plotin');

    $plot->DrawGraph(); // Desenha o gráfico na tela.
}

?>
