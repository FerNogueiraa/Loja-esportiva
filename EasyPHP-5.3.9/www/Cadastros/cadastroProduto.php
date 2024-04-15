<?php

//Inicia e sess�o
session_start();

//Conecta com o banco de dados (localweb, usuario, senha)
$conectar = mysql_connect('localhost','root','');

//Escolhe qual banco de dados usar
$banco = mysql_select_db('loja');


//Verifica qual bot�o foi selecionado (gravar, excluir, alterar ou pesquisar)
if (isset($_POST['gravar']))
{
   $codigo    = $_POST['codigo'];
   $descricao = $_POST['descricao'];
   $codcategoria     = $_POST['codcategoria'];
   $codclassificacao = $_POST['codclassificacao'];
   $codesporte = $_POST['codesporte'];
   $codmarca   = $_POST['codmarca'];
   $cor        = $_POST['cor'];
   $tamanho    = $_POST['tamanho'];
   $preco      = $_POST['preco'];


   // incluir arquivos fotos (endereço da pasta no computador)
   $foto1 = $_FILES['foto1'];
         
   //criar pasta computador
   $diretorio = "fotos/";
      
   //Esta função  usada para converter caracteres em string
   $extensao1 = strtolower(substr($_FILES['foto1']['name'], -4));

   //faz md5 para nao ter nomes repetidos nas fotos
   $novo_nome = md5(time()).$extensao1;

   //mover arquivo da foto para a pasta FOTOS no computador
   move_uploaded_file($_FILES['foto1']['tmp_name'], $diretorio.$novo_nome);



   //Criar o comando Gravar (INSERT) no banco de dados
   $sql = "INSERT INTO produto(codigo,descricao,codcategoria,codclassificacao,codesporte,codmarca,cor,tamanho,preco,foto1)
                       values ('$codigo','$descricao','$codcategoria','$codclassificacao','$codesporte','$codmarca',
                       '$cor','$tamanho','$preco','$novo_nome')";
 
   $resultado = mysql_query($sql);

   //Verificar se gravou com sucesso no Banco de Dados

   if($resultado === TRUE)
   {
    echo 'Cadastro realizado com sucesso';
   }
   else
   {
    echo'Erro ao gravar dados';
   }
}


if (isset($_POST['excluir']))
{
    $codigo = $_POST['codigo'];
    $descricao = $_POST['descricao'];
    $codcategoria = $_POST['codcategoria'];
    $codclassificacao = $_POST['codclassificacao'];
    $codesporte = $_POST['codesporte'];
    $codmarca = $_POST['codmarca'];
    $cor = $_POST['cor'];
    $tamanho = $_POST['tamanho'];
    $preco = $_POST['preco'];

   //Criar o comando Gravar (DELETE) no banco de dados
   $sql = "DELETE FROM produto WHERE codigo = '$codigo'";
        
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
    $descricao = $_POST['descricao'];
    $codcategoria = $_POST['codcategoria'];
    $codclassificacao = $_POST['codclassificacao'];
    $codesporte = $_POST['codesporte'];
    $codmarca = $_POST['codmarca'];
    $cor = $_POST['cor'];
    $tamanho = $_POST['tamanho'];
    $preco = $_POST['preco'];
   
   //Criar o comando Alterar(UPDATE) no Banco de Dados
   $sql = "UPDATE produto SET descricao='$descricao', codcategoria='$codcategoria', codclassificacao='$codclassificacao', codesporte='$codesporte', codmarca='$codmarca', cor='$cor', tamanho='$tamanho', preco='$preco' 
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
  $sql = mysql_query("SELECT codigo, descricao, codcategoria,codclassificacao,codesporte,codmarca,cor,tamanho,preco,foto1 FROM produto");
  
  echo "<b>Produtos Cadastrados: </b><br><br>";
  while ($dados = mysql_fetch_object($sql))
  {
   echo $dados;
                echo "Codigo: ".$dados->codigo." ";
                echo "Descricao: ".$dados->descricao." ";
                echo "Cod Categoria: ".$dados->codcategoria." ";
                echo "Cod Classificacao: ".$dados->codclassificacao." ";
                echo "Cod Esporte: ".$dados->codesporte." ";
                echo "Cod Marca: ".$dados->codmarca." ";
                echo "Cor: ".$dados->cor." ";
                echo "Tamanho: ".$dados->tamanho." ";
                echo "Preco: ".$dados->preco." ";
                echo '<img src="fotos/'.$dados->$foto1.'" height=100 width=150 />'."<br><br>";
                
  }
}

?>
