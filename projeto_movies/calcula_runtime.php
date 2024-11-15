<?php

ini_set('memory_limit', '2048M'); // Aumenta o limite de memória para o script, permitindo lidar com arquivos grandes.

function ProcessaDados(): array {
    $filename = 'summer_movies.csv'; // Nome do arquivo CSV contendo os dados.

    if (($handle = fopen($filename, 'r')) !== FALSE) { // Abre o arquivo para leitura.
        $header = fgetcsv($handle); // Lê a primeira linha do arquivo como cabeçalho (ignorado aqui).
        $dados = []; // Inicializa o array que armazenará os dados processados.

        while (($data = fgetcsv($handle)) !== FALSE) { // Lê cada linha do arquivo.
            $dados[] = [
                'titulo' => $data[2], // Obtém o título do filme (coluna 3).
                'avaliacao' => (float) $data[9], // Obtém a avaliação do filme (coluna 10).
                'tempo_exibicao' => (float) $data[5] // Obtém o tempo de exibição (coluna 6).
            ];
        }
        fclose($handle); // Fecha o arquivo.
    }

    return $dados; // Retorna os dados processados em formato de array associativo.
}

function CalculaMedia(): array {
    $dados = ProcessaDados(); // Processa os dados do CSV.
    $tempo_intervalos = []; // Inicializa um array para agrupar as avaliações por tempo de exibição.

    foreach ($dados as $filme) {
        $tempo = round($filme['tempo_exibicao']); // Arredonda o tempo de exibição para facilitar a análise.
        
        // Verifica se já existe um registro para o tempo arredondado.
        if (!isset($tempo_intervalos[$tempo])) {
            $tempo_intervalos[$tempo] = ['soma_avaliacoes' => 0, 'count' => 0]; // Inicializa o registro.
        }
        
        // Atualiza a soma das avaliações e o contador para o tempo atual.
        $tempo_intervalos[$tempo]['soma_avaliacoes'] += $filme['avaliacao'];
        $tempo_intervalos[$tempo]['count'] += 1;
    }
    
    $medias_avaliacoes = []; // Inicializa um array para armazenar as médias de avaliação.
    foreach ($tempo_intervalos as $tempo => $valores) {
        // Calcula a média de avaliação para cada tempo de exibição.
        $medias_avaliacoes[$tempo] = $valores['soma_avaliacoes'] / $valores['count'];
    }
    
    return $medias_avaliacoes; // Retorna as médias de avaliação organizadas por tempo.
}

function TempoIdealExibição() {
    $medias_avaliacoes = CalculaMedia(); // Obtém as médias de avaliação por tempo de exibição.
    
    // Encontra o tempo com a melhor avaliação (máxima média).
    $melhor_tempo = array_keys($medias_avaliacoes, max($medias_avaliacoes));
    
    return $melhor_tempo; // Retorna o(s) tempo(s) ideal(is) de exibição.
}

?>
