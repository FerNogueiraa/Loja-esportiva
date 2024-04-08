<?php
//conectar com servidor e o banco de dados
$conectar = mysql_connect('localhost', 'root', '') ;
//escolher qual banco dados usar
$banco = mysql_select_db('loja');

//Se o botao CADASTRAR foi escolhido
if (isset($_POST['cadastrar']))
{
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $sql = mysql_query("insert into usuario (codigo,nome,login, senha) values ('$codigo', '$nome', '$login', '$senha')");

    $resultado = mysql_query($sql);

    if ($resultado)
        { echo "Falha ao gravar dados."; }
    else
        { echo "Dados cadastrados com sucesso."; }
}



if (isset($_POST['excluir']))
{
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
   //Criar o comando Gravar (DELETE) no banco de dados
   $sql = "DELETE FROM usuario WHERE codigo = '$codigo'";
        
   //Executar o comando SQL no BD
   $resultado = mysql_query($sql);
   
   //Verificar e excluiu com sucesso no BD
   if ($resultado === TRUE)
   {
    echo 'Exclusao realizada com sucesso';
   }
   else
   {
    echo 'Erro ao excluir dados';
   }
}


if (isset($_POST['alterar']))
{
    $codigo = $_POST['codigo'];
    $nome = $_POST['nome'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
   
   //Criar o comando Alterar(UPDATE) no Banco de Dados
   $sql = "UPDATE usuario SET codigo='$descricao'
        WHERE codigo = '$codigo'";
        
   //Executar o comando SQL no BD
   $resultado = mysql_query($sql);
   
   //Verificar se alterou com sucesso o BD
   if ($resultado === TRUE)
   {
    echo 'Dados alterados com sucesso';
   }
   else
   {
    echo 'Erro ao alterar dados';
   }
}


if (isset($_POST['pesquisar']))
{
  //Seleciona todas as informa��es da tabela
  $sql = mysql_query("SELECT  nome, login, senha FROM usuario");
  
  echo "<b>usuarios Cadastrados: </b><br><br>";
  while ($dados = mysql_fetch_object($sql))
  {
                
                echo "Nome: ".$dados->nome." ";
                echo "Login: ".$dados->login." ";
                echo "Senha: ".$dados->senha."<br>";
  }
}

?>