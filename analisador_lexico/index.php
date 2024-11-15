<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analisador Léxico</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container mt-5">

        <div class="container mt-3" style="background-color: blue; color: yellow;">
            <h1 class="text-center mb-4">Analisador Léxico</h1>

            <form id="lexicalForm">
                <div class="mb-3">
                    <label for="textInput" class="form-label">
                        <h3>Insira o texto para análise:</h3>
                    </label>
                    <textarea class="form-control" id="textInput" name="textInput" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-danger">Analisar</button>
            </form>
        </div>

        <div class="container mt-5">
            <div id="outputTable" class="mt-5" style="background-color: yellow; color: blue;">
                <h2 class="text-center mb-4">Resultado da Análise</h2>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead class="table-dark">
                            <tr>
                                <th>Token</th>
                                <th>Tipo</th>
                                <th>Posição</th>
                            </tr>
                        </thead>
                        <tbody id="outputData">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Quando o documento estiver pronto (carregado)
        $(document).ready(function() {
            // Inicialmente esconde a tabela de saída
            $('#outputTable').hide();

            // Quando o formulário de análise lexical for enviado
            $('#lexicalForm').on('submit', function(e) {
                // Impede o comportamento padrão do formulário (não recarregar a página)
                e.preventDefault();

                // Realiza uma requisição AJAX para o servidor
                $.ajax({
                    // Define a URL de destino (o arquivo PHP que processa a análise)
                    url: 'analisador.php',
                    // Define o método HTTP da requisição como POST
                    type: 'POST',
                    // Envia os dados do formulário como uma string serializada
                    data: $(this).serialize(),
                    // Define que a resposta esperada será em formato JSON
                    dataType: 'json',
                    // Quando a requisição for bem-sucedida
                    success: function(data) {
                        // Se houver um erro na resposta do servidor, exibe um alerta com a mensagem de erro
                        if (data.error) {
                            alert(data.error);
                            return; // Interrompe a execução caso haja erro
                        }

                        // Se a análise foi bem-sucedida, prepara a saída
                        let output = '';
                        // Para cada item na resposta (tokens analisados)
                        data.forEach(function(item) {
                            // Cria uma linha da tabela com os dados do token
                            output += `
                            <tr>
                                <td>${item.token}</td>
                                <td>${item.tipo}</td>
                                <td>${item.posicao}</td>
                            </tr>
                        `;
                        });

                        // Insere as linhas geradas na tabela de saída
                        $('#outputData').html(output);
                        // Mostra a tabela com os resultados
                        $('#outputTable').show();
                    },
                    // Caso ocorra um erro na requisição AJAX
                    error: function(xhr, status, error) {
                        // Exibe o erro no console
                        console.error('Erro ao processar a análise:', error);
                        // Exibe um alerta de erro para o usuário
                        alert('Ocorreu um erro ao processar a análise. Por favor, tente novamente.');
                    }
                });
            });
        });
    </script>

</body>

</html>