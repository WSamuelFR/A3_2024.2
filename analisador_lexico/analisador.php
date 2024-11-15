<?php
header('Content-Type: application/json');

// Verifica se a requisição é do tipo POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtém o valor do campo 'textInput' da requisição e remove espaços extras
    $textInput = trim($_POST['textInput'] ?? '');

    // Se o campo estiver vazio, retorna uma mensagem de erro
    if (empty($textInput)) {
        echo json_encode(['error' => 'Texto de entrada não pode estar vazio.']);
        exit;
    }

    // Define os tipos de tokens que serão analisados
    $TIPO_PALAVRA = 'Palavra';
    $TIPO_NUMERO = 'Número Inteiro';
    $TIPO_FLOAT = 'Número Decimal';
    $TIPO_IDENTIFICADOR = 'Identificador';
    $TIPO_PALAVRA_CHAVE = 'Palavra-chave';
    $TIPO_OPERADOR = 'Operador';
    $TIPO_PONTUACAO = 'Pontuação';
    $TIPO_SIMBOLO = 'Símbolo';
    $TIPO_OUTRO = 'Outro';

    // Lista de palavras-chave para reconhecimento
    $palavrasChave = ['if', 'else', 'for', 'while', 'return', 'function', 'var', 'const', 'let'];

    /**
     * Função que analisa o texto e separa em tokens, identificando o tipo de cada um.
     *
     * @param string $texto O texto a ser analisado.
     * @return array Lista de tokens com seus respectivos tipos e posições.
     */
    function analisarTexto(string $texto): array
    {
        // Divide o texto em tokens usando espaços como separadores
        $tokens = preg_split('/\s+/', $texto);
        $resultado = [];

        // Para cada token encontrado, determina o tipo e armazena
        foreach ($tokens as $index => $token) {
            $tipo = determinarTipoToken($token);
            $resultado[] = [
                'token' => $token,  // O token em si
                'tipo' => $tipo,    // O tipo do token
                'posicao' => $index + 1, // A posição do token no texto
            ];
        }

        return $resultado;
    }

    /**
     * Função que determina o tipo de cada token.
     *
     * @param string $token O token que será analisado.
     * @return string O tipo do token (como palavra-chave, número, operador, etc.)
     */
    function determinarTipoToken(string $token): string
    {
        global $palavrasChave, $TIPO_PALAVRA, $TIPO_NUMERO, $TIPO_FLOAT, $TIPO_IDENTIFICADOR, $TIPO_PALAVRA_CHAVE, $TIPO_OPERADOR, $TIPO_PONTUACAO, $TIPO_SIMBOLO, $TIPO_OUTRO;

        // Verifica se o token é uma palavra-chave
        if (in_array($token, $palavrasChave, true)) {
            return $TIPO_PALAVRA_CHAVE;
        }
        // Verifica se o token é um identificador válido
        elseif (preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $token)) {
            return $TIPO_IDENTIFICADOR;
        }
        // Verifica se o token é um número inteiro
        elseif (preg_match('/^[+-]?\d+$/', $token)) {
            return $TIPO_NUMERO;
        }
        // Verifica se o token é um número decimal
        elseif (preg_match('/^[+-]?\d+\.\d+$/', $token)) {
            return $TIPO_FLOAT;
        }
        // Verifica se o token é um operador (como +, -, *, /, etc.)
        elseif (preg_match('/^[+\-*\/=<>!&|]+$/', $token)) {
            return $TIPO_OPERADOR;
        }
        // Verifica se o token é um sinal de pontuação (como . , ; : ? !)
        elseif (preg_match('/^[.,;:!?]$/', $token)) {
            return $TIPO_PONTUACAO;
        }
        // Verifica se o token é um símbolo especial (como # @ % $ & * etc.)
        elseif (preg_match('/^[#@%\$&\*\(\)\[\]{}]+$/', $token)) {
            return $TIPO_SIMBOLO;
        }
        // Verifica se o token é uma palavra simples
        elseif (preg_match('/^[a-zA-Z]+$/', $token)) {
            return $TIPO_PALAVRA;
        }
        // Caso o token não se encaixe em nenhuma das categorias, ele é classificado como "Outro"
        else {
            return $TIPO_OUTRO;
        }
    }

    // Analisa o texto recebido e obtém os tokens classificados
    $tokensAnalisados = analisarTexto($textInput);

    // Retorna os tokens analisados em formato JSON
    echo json_encode($tokensAnalisados);
    exit;
} else {
    // Se o método da requisição não for POST, retorna um erro
    echo json_encode(['error' => 'Método de requisição inválido. Utilize POST.']);
    exit;
}
