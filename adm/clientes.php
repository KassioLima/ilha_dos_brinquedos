<?php
	session_start();
	if(!isset($_SESSION['logado']))
		header("location: login.php");

	if(!isset($_GET['numpage']))
		header("location: sair.php");

	$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");;
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: " . mysqli_connect_error();
		header("location: ../../ilhadosbrinquedos/adm/sair.php");
	}

	if(isset($_GET['busca']))
		$result = mysqli_query($con,"SELECT * FROM cliente where cpf LIKE '%$_GET[busca]%' OR cpf = '$_GET[busca]' order by cpf");
	else
	$result = mysqli_query($con, "SELECT * from cliente order by cpf");

?>
	
<div style='margin-left: 5%;'>
	<h2 id='perfil'>Pessoas cadastradas <?php echo "(".mysqli_num_rows($result).")"; ?> </h2>
	<br><br>
	<form method="post" action="" id='formulario'>
		<div id='campo' style="width: 35%;">
			<input type="button" value='Busca' id='desc' style="width: 30%; font-size: 1.5vw"  disabled>
			<input type="number" id='login' class='contrato' name="nome" placeholder='Digite o CPF do cliente' style="font-size: 110%;" onkeyup="document.getElementById('formulario').action='?pg=clientes&numpage=1&busca='+this.value;" style="width: calc(70% - 2vw)" value=<?php
				if(isset($_GET['busca']))
					echo "'".$_GET['busca']."'";
				else
					echo "''";
			?>
		 required>
		</div>
		<br>
		<input type="submit" id='login' class="botao" value="Buscar"
		style="width: 10%; cursor: pointer">
		<br>
		<?php 
		 if (isset($_GET['busca'])) 
		 {
		 	echo '<a href="?pg=clientes&numpage=1" style="font-size: 110%; text-decoration: none; color: rgba(30,110,180,1)">Limpar busca</a>';
		 }
		?>
	</form>
</div>
<center>
	<?php
		$aspa = '"';

		if(mysqli_num_rows($result) > 0)
		{
			if(isset($_GET['busca']))
			{
				echo '<h2 id="perfil" style="font-size: 140%; color: black; font-weight: 200">Resultados para "'.$_GET['busca'].'"</h2>';
			}
			echo 
			"
				<br>
				<table id='evento' cellspacing='0'>
					<tr style='background-color: rgba(20,100,170,1); color: white'>
						<th>
							<br>
							CPF
							<br><br>
						</th>
						<th>
							<br>
							Nome
							<br><br>
						</th>
						<th>
							<br>
							Email
							<br><br>
						</th>
						<th>
							<br>
							Código
							<br><br>
						</th>
					</tr>
			";

		}

		$show = 1;
		$page = $_GET['numpage'];
		$porpagina = 20;
		
		while ($row = mysqli_fetch_array($result)) 
		{
			if($show>(($page-1)*$porpagina)&&$show<((($porpagina)*$page))+1)
			{
				echo
				"
	 				<tr>
						<td>
							".$row['cpf']."
						</td>
						<td style='max-width: 250px;'>
							".$row['nome']."
						</td>
						<td>
							".$row['email']."
						</td>
						<td>
							".$row['codigo']."
						</td>
					</tr>
				";
			}
			$show++;
		}

		if(mysqli_num_rows($result) == 0)
		{
			//nao achou nada
			if(isset($_GET['busca']))
			{
				echo '<h3>Não encontramos nada para "'.$_GET['busca'].'".</h3>';
			}
			else
				echo "<h3>Você ainda não recebeu nenhum cliente</h3>";
		}

		else
			echo "</table>";

		if(mysqli_num_rows($result) > 0)
		{
			$paginas=(mysqli_num_rows($result)/$porpagina);
			echo "<br><br><center><div id='paginas'>";
			for($numero = 1; $paginas>0; $numero++, $paginas--)
			{
			
				if($numero == $_GET['numpage'])
				echo "<button id='paginas' class='active'>".$numero."</button>";
				else
				{
					if(!isset($_GET['busca']))
					{
						echo "<button id='paginas' onclick='window.location.href = ".$aspa."?pg=clientes&numpage=".$numero.$aspa."' class='page".$numero."'>".$numero."</button>";
					}
					else
					{
						echo "<button id='paginas' onclick='window.location.href = ".$aspa."?pg=clientes&numpage=".$numero."&busca=".$_GET['busca'].$aspa."' class='page".$numero."'>".$numero."</button>";
					}
				}
			}
			echo "</div></center>";
		}
	?>
	<br><br><br>
</center>