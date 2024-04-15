<?php
$connect = mysql_connect('localhost','root','');
$db      = mysql_select_db('revenda');
?>

<HTML>
<HEAD>
 <TITLE> Pesquisa Veiculos</TITLE>
</HEAD>
<body>
    <form name="formulario" method="post" action="pesquisar.php">
       <img src="logo.jpg" width=200 height=150 align="left">
       <a href="login.php"><img src="login.jpg" width=130 height=60 align="right"></a>
       <br><br>
       <h1>REVENDA DE CARROS</h1><br>
       <br><br>
       <h1>Pesquisa de Veículos por:</h1>
       
       <!------ pesquisar marcas -------------->
       <label for="">Marcas: </label>
        <select name="marca">
        <option value="" selected="selected">Selecione...</option>

        <?php
        $query = mysql_query("SELECT codigo, nome FROM marca");
        while($marcas = mysql_fetch_array($query))
        {?>
        <option value="<?php echo $marcas['codigo']?>">
                       <?php echo $marcas['nome']   ?></option>
        <?php }
        ?>
        </select>

       <!------ pesquisar modelos -------------->
        <label for="">Modelos: </label>
        <select name="modelo">
        <option value="" selected="selected">Selecione...</option>

        <?php
        $query = mysql_query("SELECT codigo, nome FROM modelo");
        
        while($modelos = mysql_fetch_array($query))
        {?>
        <option value="<?php echo $modelos['codigo']?>">
                       <?php echo $modelos['nome']   ?></option>
        <?php }
        ?>
        </select>

    <input  type="submit" name="pesquisar" value="Pesquisar">
    </form>
<br><br>
<?php

if (isset($_POST['pesquisar']))
{
//verifica que a opção marca e modelo foi selecionada ou não
$marca   = (empty($_POST['marca']))? 'null' : $_POST['marca'];
$modelo  = (empty($_POST['modelo']))? 'null' : $_POST['modelo'];

//---------- pesquisar os veiculos da marca escolhida ----------------

if (($marca <> 'null') and ($modelo == 'null'))
{
     $sql_veiculos       = "SELECT descricao, ano, cor, valor
                            FROM veiculo,marca,modelo
                            WHERE veiculo.codmodelo = modelo.codigo
                            and modelo.codmarca = marca.codigo
                            and marca.codigo = $marca ";
     $seleciona_veiculos = mysql_query($sql_veiculos);
}

//---------- pesquisar os veiculos do modelo escolhido ----------------
if (($marca == 'null') and ($modelo <> 'null'))
{
       $sql_veiculos     = "SELECT descricao, ano, cor, valor FROM veiculo,marca,modelo
                            WHERE veiculo.codmodelo = modelo.codigo
                            and modelo.codmarca = marca.codigo
                            and modelo.codigo = $modelo ";
       $seleciona_veiculos = mysql_query($sql_veiculos);
}

//---------- pesquisar os veiculos da marca e do modelo escolhido ----------------
if (($marca <> 'null') and ($modelo <> 'null'))
{
       $sql_veiculos     = "SELECT descricao, ano, cor, valor FROM veiculo,marca,modelo
                            WHERE veiculo.codmodelo = modelo.codigo
                            and modelo.codmarca = marca.codigo
                            and marca.codigo = $marca
                            and modelo.codigo = $modelo ";
       $seleciona_veiculos = mysql_query($sql_veiculos);
}

//---------- pesquisar os veiculos do modelo escolhido ----------------
if (($marca == 'null') and ($modelo == 'null'))
{
       $sql_veiculos     = "SELECT descricao, ano, cor, valor
                            FROM veiculo";
       $seleciona_veiculos = mysql_query($sql_veiculos);
}

//---------- mostrar as informações dos veiculos  ----------------
if(mysql_num_rows($seleciona_veiculos) == 0)
{
   echo '<h1>Desculpe, mas sua busca nao retornou resultados ... </h1>';
}
else
{
   echo "Resultado da pesquisa de Veículos: <br><br>";
   echo "<ul>";
			while($resultado = mysql_fetch_array($seleciona_veiculos))
			{
			echo "<tr><td>".utf8_encode($resultado['descricao'])."</td>
			          <td>".utf8_encode($resultado['ano'])."</td>
			          <td>".utf8_encode($resultado['cor'])."</td>
			          <td>".utf8_encode($resultado['valor'])."</td></tr><br><br>";
			}
   }
}
?>
</body>

</HTML>
