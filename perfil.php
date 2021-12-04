<?php
	session_start();
	if (!isset($_SESSION['cpf']))
	header("location: ../ilhadosbrinquedos/sair.php");
?>

<div>
	<table id='perfil' cellspacing="0">
		<tr>
			<td style="color: black;">
				<?php
				$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");;
				// Check connection
				if (mysqli_connect_errno())
				{
					echo "Falha na conexão com MySQL: " . mysqli_connect_error();
					header("location: ../ilhadosbrinquedos/sair.php");
				}
				else
				{
					$result = mysqli_query($con, "SELECT * from cliente where cpf = '$_SESSION[cpf]'");

					$row = mysqli_fetch_array($result);

					if(!empty($row))
					{	
						echo "<b>Olá, ".$row['nome']."</b>";
					}
					else
					{
						header("location: ../ilhadosbrinquedos/sair.php");
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<td>
				<br>
				<a id='sair' href="sair.php">Sair</a>
			</td>
		</tr>
		<tr>
			<td>
				<br>
				<a id='sair' href="?pg=contratar">Contratar locação</a>
			</td>
		</tr>
		<tr>
			<td>
				<br>
				<a href="?pg=editarperfil" title="editar">
					<img src="imagem/editar.png">
				</a>
			</td>
		</tr>
	</table>
</div>
	
<div style='margin-left: 5%;'>
<h2 id='perfil'>Seus contratos</h2></div>
<center>
	<?php
		$result = mysqli_query($con, "SELECT * from evento where cpf='$_SESSION[cpf]' order by data");

		if(mysqli_num_rows($result) > 0)
			echo "<h3>Próximos</h3><table id='evento' cellspacing='0'>";

		$passados = "";

		while ($row = mysqli_fetch_array($result)) 
		{
			
 			$nome = $row['nome'];
			if(empty($row['nome']))
				$nome = "Evento sem nome";

			$sinal_pago = $row['sinal_pago'];
			if($sinal_pago == 0)
				$sinal_pago = "Aguardando pagamento do sinal";
			else
				$sinal_pago = "<b>Pagamento confirmado</b>";

			$aspa = '"';

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
						
			if($nao_passou) 
			{
				echo "
 				<tr ";
 				if ($eh_hoje) 
 				{
 					echo "style = 'background-color: darkgreen; color: white;'";
 				}
 				echo ">
					<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
						".$nome."
					</td>
					<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
						".$data."
					</td>
					<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
						R$ ".$row['valor'].",00
					</td>
					<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
						".$sinal_pago."
					</td>
					<td id='editar' onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."&editar=true'".$aspa.">
						editar
					</td>
				</tr>";
			}
			else
			{
				$passados = "
				<tr>
					<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
						".$nome."
					</td>
					<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
						".$data."
					</td>
					<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
						R$ ".$row['valor'].",00
					</td>
					<td onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."'".$aspa.">
						".$sinal_pago."
					</td>
					<td id='editar' onclick=".$aspa."window.location.href = '?pg=evento&id=".$row['id']."&editar=true'".$aspa.">
						editar
					</td>
				</tr>
				".$passados;
			}
		}

		if(mysqli_num_rows($result) == 0)
			echo "<h3>Você ainda não abriu nenhum contrato</h3>";
		else
			echo "</table>";


		if (!empty($passados)) 
		{
			echo "<br><br><h3>Passados</h3><table id='evento' class='passado' cellspacing='0'>".$passados."</table>";
		}
		
	?>
	<br><br><br>
</center>