<?php
//iniciar sessão PHP
session_start();

//comandos de conexao com BD (localweb,usuario,senha)
$conectar = mysql_connect('localhost','root','');
//selecionar o BD revenda
$banco = mysql_select_db('loja');

//verificar se botão GRAVAR foi selecionado
if (isset($_POST['gravar']))
{
   //capturar as variaveis do HTML
   $codigo = $_POST['codigo'];
   $descricao   = $_POST['descricao'];
    //comando do SQL para GRAVAR
   $sql = "insert into categoria (codigo,descricao)
           values ('$codigo','$descricao')";
   //executar o comando no BD
   $resultado = mysql_query($sql);
    //verificar se deu certo ou erro
   if ($resultado === TRUE)
   {
      //exibir uma mensagem
      echo "Dados gravados com sucesso.";
   }
   else
      {
      echo "Erro ao gravar os dados.";
      }
}


//verificar se botão EXCLUIR foi selecionado
if (isset($_POST['excluir']))
{
   //capturar as variaveis do HTML
   $codigo      = $_POST['codigo'];
   $descricao   = $_POST['descricao'];
   
    //comando do SQL para EXCLUIR
   $sql = "delete from categoria where codigo = '$codigo'";
   
   //executar o comando no BD
   $resultado = mysql_query($sql);
   
    //verificar se deu certo ou erro
   if ($resultado === TRUE)
   {
      echo "Dados excluidos com sucesso.";
   }
   else
      {
      echo "Erro ao excluir os dados.";
      }
}
//verificar se botão ALTERAR foi selecionado
if (isset($_POST['alterar']))
{
   //capturar as variaveis do HTML
   $codigo      = $_POST['codigo'];
   $descricao   = $_POST['descricao'];

    //comando do SQL para ALTERAR
   $sql = "update categoria set descricao = '$descricao'
           where codigo = '$codigo'";

   //executar o comando no BD
   $resultado = mysql_query($sql);

    //verificar se deu certo ou erro
   if ($resultado === TRUE)
   {
      echo "Dados alterados com sucesso.";
   }
   else
      {
      echo "Erro ao alterar os dados.";
      }
}

if (isset($_POST['pesquisar']))
{
  //Seleciona todas as informacoes da tabela
   $sql = mysql_query("SELECT * FROM categoria");

   echo "<b>Marcas Cadastradas:</b><br><br>";
   
   //mostrar as informações selecionadas da tabela (vetor)
   while ($dados = mysql_fetch_object($sql))
	{
               echo "Codigo: ".$dados->codigo."  ";
               echo "Nome  : ".$dados->descricao."<br>";
	}
}

?>

