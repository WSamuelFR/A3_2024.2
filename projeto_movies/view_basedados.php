<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
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

        form {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 600px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="submit"],
        input[type="text"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        input[type="submit"],
        input[type="text"]:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group h3 {
            margin-bottom: 10px;
            color: #555;
            text-align: center;
        }
    </style>
</head>

<body>

    <?php
    // Abre o arquivo CSV 'summer_movies.csv' para leitura
    $arquivo = fopen('summer_movies.csv', 'r');
    ?>

    <?php if ($arquivo !== FALSE) { ?>
        <?php
        // Lê o cabeçalho do arquivo CSV (primeira linha) e armazena os nomes das colunas
        $cabecalho = fgetcsv($arquivo);
        // Procura o índice de cada coluna no cabeçalho do CSV
        $tconst = array_search('tconst', $cabecalho);
        $title_type = array_search('title_type', $cabecalho);
        $primary_title = array_search('primary_title', $cabecalho);
        $original_title = array_search('original_title', $cabecalho);
        $year = array_search('year', $cabecalho);
        $runtime_minutes = array_search('runtime_minutes', $cabecalho);
        $genres = array_search('genres', $cabecalho);
        $simple_title = array_search('simple_title', $cabecalho);
        $average_rating = array_search('average_rating', $cabecalho);
        $num_votes = array_search('num_votes', $cabecalho);
        // Variável que controla a linha atual do arquivo que está sendo lida
        $linha_atual = 1;
        ?>

        <!-- Início do cabeçalho HTML -->
        <header>
            <div class="container">
                <h1>Administração do Modelo de Precisão</h1>
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
                <center>
                    <!-- Formulário para o usuário digitar o número de linhas que deseja ler do arquivo CSV -->
                    <form action="#" method="post">
                        <div class="group-form">
                            <h1>Bem-vindo ao summer-movies project</h1>
                            <h3>Escolha quantos dados deseja ler!</h3>
                            <input type="number" name="numero_linha">
                            <input type="submit" value="search"><br><br>
                        </div>
                    </form>
                </center>

                <?php
                // Recebe o valor digitado pelo usuário para o número de linhas que deseja exibir
                $numero_linha = isset($_POST['numero_linha']) ? $_POST['numero_linha'] : '';

                // Se o número de linhas for informado
                if ($numero_linha <> null) { ?>
                    <!-- Exibe a tabela com os dados do CSV -->
                    <table border="3px">
                        <thead>
                            <tr>
                                <!-- Cabeçalhos da tabela -->
                                <td>tconst</td>
                                <td>title_type</td>
                                <td>primary_title</td>
                                <td>original_title</td>
                                <td>year</td>
                                <td>runtime_minutes</td>
                                <td>genres</td>
                                <td>simple_title</td>
                                <td>average_rating</td>
                                <td>num_votes</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Lê as linhas do CSV até o número de linhas desejado e exibe na tabela
                            while (($linha = fgetcsv($arquivo)) !== FALSE && $linha_atual < $numero_linha + 1) { ?>

                                <!-- Exibe os dados de cada linha -->
                                <?php echo '<tr>
                                <td>' . $linha[$tconst] . '</td>
                                <td>' . $linha[$title_type] . '</td>
                                <td>' . $linha[$primary_title] . '</td>
                                <td>' . $linha[$original_title] . '</td>
                                <td>' . $linha[$year] . '</td>
                                <td>' . $linha[$runtime_minutes] . '</td>
                                <td>' . $linha[$genres] . '</td>
                                <td>' . $linha[$simple_title] . '</td>
                                <td>' . $linha[$average_rating] . '</td>
                                <td>' . $linha[$num_votes] . '</td>
                            </tr>'; ?>

                            <?php
                                // Incrementa a variável de controle de linha
                                $linha_atual++;
                            } ?>
                        </tbody>
                    </table>
                <?php } ?>

                <?php
                // Fecha o arquivo após a leitura
                fclose($arquivo);
                ?>

            <?php } else { ?>
                <!-- Caso ocorra um erro ao abrir o arquivo, exibe a mensagem de erro -->
                <?php echo "Erro ao abrir o arquivo."; ?>
            <?php } ?>

        </div>

        </article>
        </div>

        <footer>
            <p>Administração do Modelo &copy; 2024</p>
        </footer>


</body>

</html>