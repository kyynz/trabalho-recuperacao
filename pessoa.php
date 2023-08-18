<?php 

//Comandos para mostrar os erros do PHP no navegador
ini_set('display_errors', 1);
error_reporting(E_ALL);

//Adiciona o arquivo de Conexão a esta página
require_once("Connection.php");

//Conexão a ser utiliza no acesso ao banco de dados
$conn = Connection::getConnection();
//print_r($conn);

$msgErro = "";

$nome = isset($_POST['nome']) ? trim($_POST['nome']) : null;
$sobrenome = isset($_POST['sobrenome']) ? trim($_POST['sobrenome']) : null;
$idade = isset($_POST['idade']) ? trim($_POST['idade']) : null;
$genero = isset($_POST['genero']) ? trim($_POST['genero']) : null;

                    

if(isset($_POST['submetido'])) {
    //Bloco para consultar se a pessoa já foi cadastrada
    $sql = "SELECT * FROM Pessoas WHERE nome = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$nome]);
    $pessoaComnomeRep = $stmt->fetchAll();
    
    //Validar - Campos obrigatórios
    if(! $nome) {
        $msgErro = "Informe o Nome!";
    
    } else if(! $sobrenome) {
        $msgErro = "Informe o Sobrenome!";
    
    } else if(! $genero) {
        $msgErro = "Informe o Genero!";

    } else if(! $idade) {
        $msgErro = "Informe a Idade!";
    }
    //validar entradas que podem ferir o servidor ou que excedem a capacidade humana
    else if(strlen($nome) > 40) {
        $msgErro = "O nome não pode ser maior que 40 caracteres. Se o seu nome tiver mais de 40 caracteres, abrevie.";
    
    } else if(strlen($sobrenome) > 120) {
        $msgErro = "O sobrenome não pode ser maior que 120 caracteres. Se o seu nome tiver mais de 120 caracteres, abrevie.";
    
    } else if(! $genero) {
        $msgErro = "Informe o Genero!";

    } else if($idade > 254) {
        $msgErro = "Essa idade excede a capacidade humana. Escreva uma idade possível.";
    } else if($idade < 0){
        $msgErro = "Idades negativas ou pessoas que ainda não nasceram não são aceitas.";
    }    
    
    
    
    //Validar  nome repetido
     else if(count($pessoaComnomeRep) > 0) {
        $msgErro = "A pessoa  [" . $nome . "] já foi cadastrada!";
    
    // Se passou as validações insere a pessoa no banco
    } else {
        $sql = 'INSERT INTO Pessoas (nome, genero, idade, sobrenome)' .
            ' VALUES (?, ?, ?, ?)';
        $stmt = $conn->prepare($sql);
        $stmt->execute([$nome, $genero, $idade,$sobrenome]);

        header("location: pessoa.php");
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Pessoas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Cadastro de Pessoas</h1>

    <h3>Formulário de Pessoas</h3>
    <form action="" method="POST" >

        <input type="text" name="nome" id="nome"
            placeholder="Informe o Nome"
            value="<?php echo $nome; ?>" />

        <br><br>

        <input type="text" name="sobrenome" id="sobrenome"
            placeholder="Informe o Sobrenome"
            value="<?php echo $sobrenome; ?>" />

        <br><br>

        <select name="genero" id="genero">
            <option value="">---Selecione o gênero---</option>
            <option value="M" <?php echo ($genero == 'M' ? 'selected' : ''); ?> >
                Masculino</option>
            <option value="F" <?php echo ($genero == 'F' ? 'selected' : ''); ?> >
                Feminino</option>
            <option value="O" <?php echo ($genero == 'O' ? 'selected' : ''); ?> >Outro</option>
        </select>

        <br><br>

        <input type="number" name="idade" id="idade"
            placeholder="Informe a Idade"
            value="<?php echo $idade; ?>" />

        <br><br>

        <button type="submit">Cadastrar</button>

        <input type="hidden" name="submetido" value="1" />
    </form>

    <div id="divErro" style="color: red; margin-top: 10px;" >
        <?php echo $msgErro; ?>
    </div>

    <h3>Listagem de Pessoas</h3>
    <?php 
        $sql = "SELECT * FROM Pessoas";

        //Prepara e executa o comando SQL
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        //Armazena os resultados ($result é uma matriz)
        $result = $stmt->fetchAll();
    ?>
    <table border="1">
        <tr>
            <td>Nome</td>
            <td>Sobrenome</td>
            <td>Idade</td>
            <td>Genero</td>
            <td></td>
        </tr>
        
        <?php foreach($result as $reg): ?>
            <tr>
                <td> <?php echo $reg['nome'] ?> </td>
                <td> <?= $reg['sobrenome'] ?> </td>
                <td> <?= $reg['idade'] ?> </td>
                <td> 
                <?php 
                    switch($reg['genero']) {
                        case 'M':
                            echo "Masculino";
                            break;
                        case 'F':
                            echo "Feminino";
                            break;

                        case 'O':
                            echo "Outros";
                            break;
                    }
                ?> 
                </td>
                <td><a href="Pessoas_del.php?id=<?= $reg['id']; ?>"
                        onclick="return confirm('Confirma a exclusão?');">
                        Excluir</a></td>

            </tr>
        <?php endforeach; ?>
    </table>

    <!-- Para chamar a validação em JS, chamar a função validarPessoa() 
    no atributo onsubmit do formulário -->
    <script src="validacao.js"></script>
</body>
</html>