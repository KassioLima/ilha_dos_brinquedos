<?php
	session_start();
	if(!isset($_SESSION['logado']) || !isset($_GET['id']))
		header("location: login.php");
?>

<?php

	$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: " . mysqli_connect_error();
		header("location: ../ilhadosbrinquedos/sair.php");
	}

	$resultmonitores = mysqli_query($con, "SELECT * from monitor");
	$rowmonitores = mysqli_fetch_array($resultmonitores);
	
	if(isset($_GET['editar']))
	{
		$situacao = false;
		if($_POST['situacao'] == "Pagamento confirmado")
			$situacao = true;

		$result = mysqli_query($con, "SELECT * FROM evento where id = $_GET[id]");

		$alterada = false;

		if(mysqli_num_rows($result) > 0)
		{

			$row = mysqli_fetch_array($result);

			$brinquedos = explode("|", $row['brinquedos']);

			for($x = 1; $x < sizeof($brinquedos); $x++)
			{
				$final = "";

				$id = intval(explode("#", $brinquedos[$x])[0]);

				$resultb = mysqli_query($con, "SELECT * from brinquedo where id = $id");
				$rowb = mysqli_fetch_array($resultb);

				if ($row['sinal_pago'] == 0) 
					$xs = explode("|", $rowb['reservado_data']);	
				else
					$xs = explode("|", $rowb['confirmado_data']);


				for($y = 1; $y < sizeof($xs); $y++)
					if($row['data'] != $xs[$y])
						$final .= "|".$xs[$y];

				
				if ($situacao == false)
				{
					if ($row['sinal_pago'] == 1) 
					{
						mysqli_query($con, "UPDATE brinquedo set confirmado_data = '$final' where id = $id");

						$final = $rowb['reservado_data']."|".$row['data'];

						mysqli_query($con, "UPDATE brinquedo set reservado_data = '$final' where id = $id");

						$alterada = true;
					}
				}
				else
				{
					if ($row['sinal_pago'] == 0) 
					{
						mysqli_query($con, "UPDATE brinquedo set reservado_data = '$final' where id = $id");

						$final = $rowb['confirmado_data']."|".$row['data'];

						mysqli_query($con, "UPDATE brinquedo set confirmado_data = '$final' where id = $id");

						$alterada = true;
					}
				}
			}

			if($alterada)
				mysqli_query($con, "UPDATE evento set sinal_pago = '$situacao' where id = $_GET[id]");
		}

		header("location: ../../ilhadosbrinquedos/adm/?pg=evento&id=".$_GET['id']);
	}
?>


<div id='verevento' style=<?php 
		echo '"';
		if(isset($_POST['cep'])) 
		{
			echo "display: none";
		}
		echo '"';
	?>>
	<a id='voltar' href="?pg=eventos&tempo=futuro&numpage=1" title="Voltar" style="display: inline-block;">
		<img src="../imagem/voltar.png">
	</a>
	<br><br>
	<?php
		if(isset($_GET['id']))
		{
			$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
			// Check connection
			if (mysqli_connect_errno())
			{
				echo "Falha na conexão com MySQL: " . mysqli_connect_error();
				header("location: ../ilhadosbrinquedos/sair.php");
			}
			else
			{
				$result = mysqli_query($con, "SELECT * from evento where id='$_GET[id]'");

				if(mysqli_num_rows($result) > 0)
				{
					echo "

					<a id='contrato' href='gerarpdf.php?id=".$_GET['id']."' target='_blank'>
						Contrato
					</a>

					";
					$row = mysqli_fetch_array($result);

					$contmonitores = $row['monitores'];
					$maxmonitores = 0;

					$brinquedos_array = explode("|", $row['brinquedos']);

					$data_array = explode("-", $row['data']);
					$data = $data_array[2]."/".$data_array[1]."/".$data_array[0];

					$nome = $row['nome'];
					if(empty($row['nome']))
						$nome = "Evento sem nome";

					$aspa = '"';

					echo "
					<h2 id='perfil'>
						".$nome."
					</h2>
					<table id='visualizacao' cellspacing='0'>

						<tr>
							<td>
								<b><b>Data do evento</b></b>
							</td>
							<td>
								".$data."
							</td>
						</tr>
						<tr>
							<td>
								<b>CPF</b>
							</td>
							<td>
								".$row['cpf']."
							</td>
						</tr>
											
						<tr>
							<td>
								<b>Contato</b>
							</td>
							<td>
								".$row['cell']."
							</td>
						</tr>
						
						<tr>
							<td>
								<b>Endereço</b>
							</td>
							<td>
								".$row['endereco']."
							</td>
						</tr>
						
						<tr>
							<td>
								<b>CEP</b>
							</td>
							<td>
								".$row['cep']."
							</td>
						</tr>
						
						<tr>
							<td>
								<b>Referência</b>
							</td>
							<td>
								".$row['ref']."
							</td>
						</tr>
						
						<tr>
							<td>
								<b>Começo</b>
							</td>
							<td>
								".$row['h_comeco']."
							</td>
						</tr>
						
						<tr>
							<td>
								<b>Hora de término</b>
							</td>
							<td>
								5 horas após o começo
							</td>
						</tr>
					</table>
					<br>
					<form method='post' action='evento.php?id=".$_GET['id']."&editar=true'>
						<div id='campo' style='width: 50%'>
							<input type='button' value='Situação' id='desc' disabled>
							<select id='login' class='contrato' name='situacao'>
								<option>
									Aguardando pagamento do sinal
								</option>
								<option "; if($row['sinal_pago'] == 1) echo "selected"; echo ">
									Pagamento confirmado
								</option>
							</select>
						</div>
						<br>
						<input type='submit' id='login' class='botao' value='Alterar situação' style='width: 30%;'>
					</form>
					<br>
					<h2>Brinquedos</h2>
					<table id='evento' cellspacing='0'>

					";


					$result_brinquedo = mysqli_query($con, "SELECT * from brinquedo");
				

					while($row_brinquedo = mysqli_fetch_array($result_brinquedo))
					{
						echo
						"
							<tr>
								<td>
									".$row_brinquedo['nome']." 
								</td>
								<td>
									<b>R$ ".$row_brinquedo['preco'].",00</b>
								</td>
								<td>
									<input type='checkbox' name='campo".$row_brinquedo['id']."' id='check_brinquedo' value='".$row_brinquedo['preco']."' onclick='total()' class='check_brinquedo'";

									if (in_array($row_brinquedo['id']."#".$row_brinquedo['preco']."#campo".$row_brinquedo['id'], $brinquedos_array))
									{
										echo " checked";
										$maxmonitores++;
									}
								echo " disabled>
								</td>
							</tr>
						";
					}
					echo "
					</table>
					<br><br>
					<div id='campo' style='width: 33%;'>
						<input type='button' value='Monitores (R$".$rowmonitores['preco'].",00 cada)'
						id='desc' style='width: 70%; font-size: 90%' disabled>
						<input type='number' min='0' max='0' id='login' class='contrato' name='monitores'
						value='".$row['monitores']."' style='font-size: 120%; width: calc(30% - 2vw);' disabled>
					</div>
					<h2 style='color: black'>
						Total: <span id='total'>R$ ".$row['valor'].",00</span>
					</h2>
					<br><br><br>
					<input type='submit' id='login' class='botao' value='Cancelar evento' style='width: 30%;' onclick='confirma(".$aspa.$_GET['id'].$aspa.")'>
					";
				}
			}
		}
	?>
	<br><br><br>
</div>