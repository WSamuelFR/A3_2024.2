<?php
// Função para obter os títulos simples dos filmes com tempo de exibição maior que 100 minutos.
function simple_titleMA()
{
    $filename = 'summer_movies.csv'; // Nome do arquivo CSV.
    $numero_da_linha = 100; // Limita a leitura às primeiras 100 linhas.
    $linha_atual = 0; // Contador para a linha atual.

    // Abre o arquivo CSV para leitura.
    if (($handle = fopen($filename, 'r')) !== FALSE) {
        $header = fgetcsv($handle); // Lê a primeira linha do CSV como cabeçalho.

        // Obtém os índices das colunas "runtime_minutes" e "simple_title" no cabeçalho.
        $indexTempo_exibicao = array_search('runtime_minutes', $header);
        $simple_title = array_search('simple_title', $header);

        $filteredRows = []; // Array para armazenar os títulos filtrados.

        // Lê cada linha do CSV até o limite definido por `$numero_da_linha`.
        while (($data = fgetcsv($handle)) !== FALSE && $linha_atual < $numero_da_linha) {
            $Tempo_exibicao = (float) $data[$indexTempo_exibicao]; // Obtém o valor de "runtime_minutes".

            // Verifica se o tempo de exibição é maior que 100 minutos.
            if ($Tempo_exibicao > 100) { 
                $filteredRows[] = $data[$simple_title]; // Adiciona o título simples ao array filtrado.
            }
            $linha_atual++; // Incrementa o contador de linha.
        }

        fclose($handle); // Fecha o arquivo CSV.

        return ($filteredRows); // Retorna os títulos filtrados.
    }
}

// Função para obter os tempos de exibição dos filmes com runtime maior que 100 minutos.
function runtime_minutesMA()
{
    $filename = 'summer_movies.csv'; // Nome do arquivo CSV.
    $numero_da_linha = 100; // Limita a leitura às primeiras 100 linhas.
    $linha_atual = 0; // Contador para a linha atual.

    if (($handle = fopen($filename, 'r')) !== FALSE) {
        $header = fgetcsv($handle); // Lê o cabeçalho do arquivo CSV.

        // Obtém os índices das colunas "runtime_minutes" no cabeçalho.
        $indexTempo_exibicao = array_search('runtime_minutes', $header);
        $runtime_minutes = array_search('runtime_minutes', $header);

        $filteredRows = []; // Array para armazenar os tempos de exibição filtrados.

        while (($data = fgetcsv($handle)) !== FALSE && $linha_atual < $numero_da_linha) {
            $Tempo_exibicao = (float) $data[$indexTempo_exibicao]; // Obtém o valor de "runtime_minutes".

            if ($Tempo_exibicao > 100) { 
                $filteredRows[] = $data[$runtime_minutes]; // Adiciona o tempo de exibição ao array filtrado.
            }
            $linha_atual++; // Incrementa o contador de linha.
        }

        fclose($handle); // Fecha o arquivo CSV.

        return ($filteredRows); // Retorna os tempos de exibição filtrados.
    }
}

// Função para obter os votos dos filmes com runtime maior que 100 minutos.
function num_votesMA()
{
    $filename = 'summer_movies.csv'; // Nome do arquivo CSV.
    $numero_da_linha = 100; // Limita a leitura às primeiras 100 linhas.
    $linha_atual = 0; // Contador para a linha atual.

    if (($handle = fopen($filename, 'r')) !== FALSE) {
        $header = fgetcsv($handle); // Lê o cabeçalho do arquivo CSV.

        // Obtém os índices das colunas "runtime_minutes" e "num_votes".
        $indexTempo_exibicao = array_search('runtime_minutes', $header);
        $num_votes = array_search('num_votes', $header);

        $filteredRows = []; // Array para armazenar os números de votos filtrados.

        while (($data = fgetcsv($handle)) !== FALSE && $linha_atual < $numero_da_linha) {
            $Tempo_exibicao = (float) $data[$indexTempo_exibicao]; // Obtém o valor de "runtime_minutes".

            if ($Tempo_exibicao > 100) { 
                $filteredRows[] = $data[$num_votes]; // Adiciona o número de votos ao array filtrado.
            }
            $linha_atual++; // Incrementa o contador de linha.
        }

        fclose($handle); // Fecha o arquivo CSV.

        return ($filteredRows); // Retorna os números de votos filtrados.
    }
}

// Função para obter as avaliações médias dos filmes com runtime maior que 100 minutos.
function average_ratingMA()
{
    $filename = 'summer_movies.csv'; // Nome do arquivo CSV.
    $numero_da_linha = 100; // Limita a leitura às primeiras 100 linhas.
    $linha_atual = 0; // Contador para a linha atual.

    if (($handle = fopen($filename, 'r')) !== FALSE) {
        $header = fgetcsv($handle); // Lê o cabeçalho do arquivo CSV.

        // Obtém os índices das colunas "runtime_minutes" e "average_rating".
        $indexTempo_exibicao = array_search('runtime_minutes', $header);
        $average_rating = array_search('average_rating', $header);

        $filteredRows = []; // Array para armazenar as avaliações médias filtradas.

        while (($data = fgetcsv($handle)) !== FALSE && $linha_atual < $numero_da_linha) {
            $Tempo_exibicao = (float) $data[$indexTempo_exibicao]; // Obtém o valor de "runtime_minutes".

            if ($Tempo_exibicao > 100) { 
                $filteredRows[] = $data[$average_rating]; // Adiciona a avaliação média ao array filtrado.
            }
            $linha_atual++; // Incrementa o contador de linha.
        }

        fclose($handle); // Fecha o arquivo CSV.

        return ($filteredRows); // Retorna as avaliações médias filtradas.
    }
}
?>
