<?php
	session_start();
	if(!isset($_SESSION['logado']))
		header("location: login.php");

	if(!isset($_GET["tempo"]) || !isset($_GET['numpage']))
		header("location: ../../ilhadosbrinquedos/adm/sair.php");
?>
	
<div style='margin-left: 5%;'>
	<h2 id='perfil'>Seus contratos</h2>
	<br><br>
	<form method="post" action="" id='formulario'>
		<div id='campo' style="width: 35%;">
			<input type="button" value='Busca' id='desc' style="width: 30%; font-size: 90%"  disabled>
			<input type="number" id='login' class='contrato' name="nome" placeholder='Digite o CPF do cliente' style="font-size: 110%;" onkeyup="document.getElementById('formulario').action='?pg=eventos&tempo=futuro&numpage=1&busca='+this.value;" style="width: calc(70% - 2vw)" value=<?php
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
		 	echo '<a href="?pg=eventos&tempo='.$_GET["tempo"].'&numpage=1" style="font-size: 110%; text-decoration: none; color: rgba(30,110,180,1)">Limpar busca</a>';
		 }
		?>
	</form>
	<br><br>
	<div id='tempo'>
		<input type="button" value='Eventos futuros' id='tempo1' onclick=<?php 
		echo '"';
		if(!isset($_GET['busca']))
		{
			echo "window.location.href = '?pg=eventos&tempo=futuro&numpage=1'";
		}
		else
		{
			echo "window.location.href = '?pg=eventos&tempo=futuro&numpage=1&busca=".$_GET['busca']."'";
		}
		echo '"';
		?> class=<?php if($_GET['tempo'] == "futuro") echo "'active'"; ?>>

		<input type="button" value='Eventos passados' id='tempo2' onclick=<?php 
		echo '"';
		if(!isset($_GET['busca']))
		{
			echo "window.location.href = '?pg=eventos&tempo=passado&numpage=1'";
		}
		else
		{
			echo "window.location.href = '?pg=eventos&tempo=passado&numpage=1&busca=".$_GET['busca']."'";
		}
		echo '"';
		?> class=<?php if($_GET['tempo'] == "passado") echo "'active'"; ?>>
	</div>
</div>
<center>
	<?php
		$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Falha na conexão com MySQL: " . mysqli_connect_error();
			header("location: ../ilhadosbrinquedos/sair.php");
		}
		else
		{
			if(isset($_GET['busca']))
			$result = mysqli_query($con,"SELECT * FROM evento where cpf LIKE '%$_GET[busca]%' OR cpf = '$_GET[busca]' order by data");
			else
			$result = mysqli_query($con, "SELECT * from evento order by data");

			if(mysqli_num_rows($result) > 0)
			{
				echo "<br><br>";
				if($_GET['tempo'] == "futuro")
					echo "<table id='evento' cellspacing='0'>";
				else if ($_GET['tempo'] == "passado") 
					echo "<table id='evento' class='passado' cellspacing='0'>";
					else
						header("location: ../../ilhadosbrinquedos/adm/sair.php");
			}
			$aspa = '"';

			$show = 1;
			$page = $_GET['numpage'];
			$porpagina = 20;
			$contador = 0;

			while ($row = mysqli_fetch_array($result)) 
			{
				$sinal_pago = $row['sinal_pago'];
				if($sinal_pago == 0)
					$sinal_pago = "Aguardando pagamento do sinal";
				else
					$sinal_pago = "<b>Pagamento confirmado</b>";

				$nao_passou = false; // FICA PREDEFINIDO COMO SE O EVENTO JÁ TIVESSE PASSADO
				$eh_hoje = false;


				date_default_timezone_set('America/Sao_Paulo');
				$hoje = strtotime(date("Y-m-d")."");

				$data = strtotime($row['data']);

				$final = ($data - $hoje)/86400;

				if($final >= 0)
				{
					$nao_passou = true;
					if($final == 0)
						$eh_hoje = true;
				}

				$data_array = explode("-", $row['data']);
				$data = $data_array[2]."/".$data_array[1]."/".$data_array[0];
	
				if($_GET['tempo'] == "futuro" && $nao_passou)
				{
					$contador++;
					if($show>(($page-1)*$porpagina)&&$show<((($porpagina)*$page))+1)
					{
						echo "
		 				<tr ";
		 				if ($eh_hoje) 
		 				{
		 					echo "style = 'background-color: darkgreen; color: white;'";
		 				}
		 				echo ">
							<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
								".$data."
							</td>
							<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
								".$row['cpf']."
							</td>
							<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
								R$ ".$row['valor'].",00
							</td>
							<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
								".$sinal_pago."
							</td>
							<!--td id='editar' onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."&editar=true'".$aspa.">
								editar
							</td-->
						</tr>";
					}
					$show++;
				}
				if($_GET['tempo'] == "passado" && !$nao_passou)
				{
					$contador++;
					if($show>(($page-1)*$porpagina)&&$show<((($porpagina)*$page))+1)
					{
						echo "
						<tr>
							<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
								".$data."
							</td>
							<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
								".$row['cpf']."
							</td>
							<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
								R$ ".$row['valor'].",00
							</td>
							<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
								".$sinal_pago."
							</td>
							<!--td id='editar' onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."&editar=true'".$aspa.">
								editar
							</td-->
						</tr>
						";
					}
					$show++;
				}					
			}
			if($contador == 0)
			{
				//nao achou nada
				if(isset($_GET['busca']))
				{
					echo '<br><br><br><h3>Não encontramos nada para "'.$_GET['busca'].'".</h3>';
				}
				else
					echo "<br><br><br><h3>Você ainda não recebeu nenhum contrato</h3>";
			}

			else
				echo "</table>";
			if($contador > 0)
			{
				$paginas=($contador/$porpagina);
				echo "<br><br><center><div id='paginas'>";
				for($numero = 1; $paginas>0; $numero++, $paginas--)
				{
				
					if($numero == $_GET['numpage'])
					echo "<button id='paginas' class='active'>".$numero."</button>";
					else
					{
						if(!isset($_GET['busca']))
						{
							echo "<button id='paginas' onclick='window.location.href = ".$aspa."?pg=eventos&tempo=".$_GET['tempo']."&numpage=".$numero.$aspa."' class='page".$numero."'>".$numero."</button>";
						}
						else
						{
							echo "<button id='paginas' onclick='window.location.href = ".$aspa."?pg=eventos&tempo=".$_GET['tempo']."&numpage=".$numero."&busca=".$_GET['busca'].$aspa."' class='page".$numero."'>".$numero."</button>";
						}
					}
				}
				echo "</div></center>";
			}
		}
	?>
	<br><br><br>
</center>