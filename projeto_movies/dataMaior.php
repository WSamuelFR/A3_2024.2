<?php

// Função que retorna um array contendo informações de filmes com runtime maior que 100 minutos.
function dataMaior(): array
{
    // Inclui o arquivo 'dadosMaior.php', que contém as definições das funções `simple_titleMA`, `average_ratingMA`, `runtime_minutesMA`, e `num_votesMA`.
    require('dadosMaior.php');

    // Cria um array contendo subarrays com as informações dos filmes.
    // Cada subarray contém os seguintes dados:
    // - Título do filme (`simple_titleMA`)
    // - Avaliação média (`average_ratingMA`)
    // - Tempo de exibição (`runtime_minutesMA`)
    // - Número de votos (`num_votesMA`)
    $data = array(
        array(simple_titleMA()[0], average_ratingMA()[0], runtime_minutesMA()[0], num_votesMA()[0]),
        array(simple_titleMA()[1], average_ratingMA()[1], runtime_minutesMA()[1], num_votesMA()[1]),
        array(simple_titleMA()[2], average_ratingMA()[2], runtime_minutesMA()[2], num_votesMA()[2]),
        array(simple_titleMA()[3], average_ratingMA()[3], runtime_minutesMA()[3], num_votesMA()[3]),
        array(simple_titleMA()[4], average_ratingMA()[4], runtime_minutesMA()[4], num_votesMA()[4]),
        array(simple_titleMA()[5], average_ratingMA()[5], runtime_minutesMA()[5], num_votesMA()[5]),
        array(simple_titleMA()[6], average_ratingMA()[6], runtime_minutesMA()[6], num_votesMA()[6]),
        array(simple_titleMA()[7], average_ratingMA()[7], runtime_minutesMA()[7], num_votesMA()[7]),
        array(simple_titleMA()[8], average_ratingMA()[8], runtime_minutesMA()[8], num_votesMA()[8]),
        array(simple_titleMA()[9], average_ratingMA()[9], runtime_minutesMA()[9], num_votesMA()[9]),
        array(simple_titleMA()[10], average_ratingMA()[10], runtime_minutesMA()[10], num_votesMA()[10]),
        array(simple_titleMA()[11], average_ratingMA()[11], runtime_minutesMA()[11], num_votesMA()[11]),
        array(simple_titleMA()[12], average_ratingMA()[12], runtime_minutesMA()[12], num_votesMA()[12]),
        array(simple_titleMA()[13], average_ratingMA()[13], runtime_minutesMA()[13], num_votesMA()[13]),
        array(simple_titleMA()[14], average_ratingMA()[14], runtime_minutesMA()[14], num_votesMA()[14])
    );

    // Retorna o array contendo as informações organizadas.
    return($data);
}

?>
