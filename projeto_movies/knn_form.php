<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KNN Classificação de Filmes</title>
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

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
        }

        button {
            padding: 10px 20px;
            background-color: #007bff;
            border: none;
            color: white;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .result {
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
                <h1>Classificação de Filmes com KNN</h1>
                <p>
                    Calcular Distância: Usa a fórmula da distância euclidiana para calcular a distância entre dois
                    pontos.
                    KNN: Implementa o algoritmo KNN, calculando distâncias, ordenando os pontos mais próximos, e
                    determinando a classe majoritária com base nos K vizinhos mais próximos.
                    Teste: Classifica um ponto novo com base nos dados existentes e exibe a classe prevista.
                </p>
                <form action="knn_classificacao.php" method="POST">
                    <div class="form-group">
                        <label for="tempo_exibicao">Tempo de Exibição (minutos):</label>
                        <input type="number" id="tempo_exibicao" name="tempo_exibicao" required>
                    </div>
                    <div class="form-group">
                        <label for="k">Número de Vizinhos (K):</label>
                        <input type="number" id="k" name="k" value="3" required>
                    </div>
                    <button type="submit">Classificar</button>
                </form>

                <div class="result">
                    <?php
                    if (isset($_GET['resultado'])) {
                        echo "<h2>Resultado da Classificação</h2>";
                        echo "<h2>A avaliação prevista para o novo ponto é: <strong>{$_GET['resultado']}</strong></h2>";
                    }

                    if (isset($_GET['erro'])) {
                        echo "<h2>Erro</h2>";
                        echo "<h2><strong>{$_GET['erro']}</strong></h2>";
                    }
                    ?>
                </div>
            </div>


        </article>
    </div>

    <footer>
        <p>Administração do Modelo &copy; 2024</p>
    </footer>

</body>

</html>