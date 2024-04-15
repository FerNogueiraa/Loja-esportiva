<?php
// conectar com servidor e o banco de dados
$conectar = mysql_connect('localhost','root','') ;
$banco    = mysql_select_db("loja");

if (isset($_POST['conectar']))
{
$login   = $_POST['login'];
$senha   = $_POST['senha'];

// Verifica se existe usuario com o login e a senha digitada
$sql = mysql_query("select * FROM usuario
                    where login = '$login' and senha = '$senha'");

$resultado = mysql_num_rows($sql);

//Verifica se n�o existe o login e a senha digitado
if ($resultado == 0)
{
   echo "Login ou senha inv�lido...";
}
else
{
   //Cria a sess�o e manda para pagina menu de cadastros
   session_start();
   $_SESSION['login'] = $login;
   header("Location:menu.html");
}

}

?>