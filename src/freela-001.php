<?php
$caminhoArquivo = 'src\produtos.json';
$opcao = '';

echo "====================================\n";
echo "GESTOR FÁCIL\n";
echo "====================================\n";
echo "1 - Cadastrar Produto\n";
echo "2 - Listar Produto\n";
echo "3 - Buscar Produto\n";
echo "4 - Valor Total do Estoque\n";
echo "5 - Produto Mais Caro\n";
echo "6 - Produto Sem Estoque\n";
echo "0 - Sair\n";

function abrirArquivo(string $caminhoArquivo)
{
    if (!file_exists($caminhoArquivo)) {
        file_put_contents($caminhoArquivo, json_encode([], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    $arquivo = file_get_contents($caminhoArquivo);
    if ($arquivo == "") {
        return [];
    }
    return json_decode($arquivo, true);
}
function idProduto(array $produtos)
{
    $id = 0;
    foreach ($produtos as $prd) {
        if ($id < $prd["id"]) {
            $id = $prd["id"];
        }
    }
    return $id + 1;
}

function adicionarProduto(array $produto, string $caminhoArquivo, array $produtos)
{
    $produtos[] = $produto;
    file_put_contents(
        $caminhoArquivo,
        json_encode(
            $produtos,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        ),
        LOCK_EX
    );
}

function cadastrarProduto(string $caminhoArquivo)
{
    echo "Nome do Produto:";
    $nmProduto = trim(fgets(STDIN));

    echo "Preço do Produto:";
    $prProduto = str_replace(',', '.', trim(fgets(STDIN)));
    while (!is_numeric($prProduto)) {
        echo "Valor invalido. Digite novamente:";
        $prProduto = str_replace(',', '.', trim(fgets(STDIN)));
    }
    $prProduto = floatval($prProduto);

    echo "Quantidade de Produtos:";
    $qtdProduto = trim(fgets(STDIN));
    while (!filter_var($qtdProduto, FILTER_VALIDATE_INT)) {
        echo "Valor invalido. Digite novamente um valor inteiro:";
        $qtdProduto = trim(fgets(STDIN));
    }
    $qtdProduto = intval($qtdProduto);

    echo "Categoria do Produto:";
    $ctgProduto = trim(fgets(STDIN));

    $produtos = abrirArquivo($caminhoArquivo);
    $id = idProduto($produtos);
    $produto = array(
        'id' => $id,
        'nome' => $nmProduto,
        'preco' => $prProduto,
        'quantidade' => $qtdProduto,
        'categoria' => $ctgProduto,
    );
    adicionarProduto($produto, $caminhoArquivo, $produtos);
}

do {
    echo "Escolha: ";

    $opcao = trim(fgets(STDIN));

    switch ($opcao) {
        case '0':
            echo "saindo...";
            break;
        case '1':
            cadastrarProduto($caminhoArquivo);
            break;

        default:
            echo "Opção invalida.\n";
            break;
    }
} while ($opcao !== '0');
