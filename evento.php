<?php
	session_start();
	if (!isset($_SESSION['cpf']) || !isset($_GET['id']))
	header("location: ../ilhadosbrinquedos/sair.php");

	$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: " . mysqli_connect_error();
		header("location: ../ilhadosbrinquedos/sair.php");
	}

	$resultmonitores = mysqli_query($con, "SELECT * from monitor");
	$rowmonitores = mysqli_fetch_array($resultmonitores);

	if(((isset($_POST['nome'])) && (isset($_POST['cell'])) && (isset($_POST['end'])) && (isset($_POST['ref'])) && (isset($_POST['cep'])) && (isset($_POST['data'])) && (isset($_POST['hora_i'])) && (isset($_POST['monitores'])) ) && ((!empty(trim($_POST['nome']))) && (!empty(trim($_POST['cell']))) && (!empty(trim($_POST['end']))) && (!empty(trim($_POST['ref']))) && (!empty(trim($_POST['cep']))) && (!empty(trim($_POST['data']))) && (!empty(trim($_POST['hora_i'])))) && ($_POST['monitores']) >= 0) 
	{
		$selecionados = "";

		$result_evento = mysqli_query($con, "SELECT * from evento where id='$_GET[id]' and cpf = '$_SESSION[cpf]'");
		$row_evento = mysqli_fetch_array($result_evento);


		$result = mysqli_query($con, "SELECT * from brinquedo");

		while($row = mysqli_fetch_array($result))
		{
			$pode_reservar = false;

			$reservados = explode("|", $row['reservado_data']);
			$confirmados = explode("|", $row['confirmado_data']);


			if($_POST['data'] != $row_evento['data'])
			{
				if((!in_array($_POST['data'], $reservados)) && (!in_array($_POST['data'], $confirmados)))
				{
					$pode_reservar = true;
				}
			}
			else
			{
				$pode_reservar = true;
			}

			if(isset($_POST['campo'.$row['id'].''])) 
			{
				if($pode_reservar)
				{
					$reservados_final = "";

					$reservados = explode("|", $row['reservado_data']);
					for($y = 1; $y < sizeof($reservados); $y++)
						if($row_evento['data'] != $reservados[$y])
						$reservados_final .= "|".$reservados[$y];

					$reservados_final .= "|".$_POST['data'];

					mysqli_query($con, "UPDATE brinquedo set reservado_data = '$reservados_final' where id = $row[id]");


					$selecionados .= "|".$row['id']."#".$row['preco']."#campo".$row['id'];

					if(isset($_POST['monitor'.$row['id'].''])) 
					{
						$selecionados .= "#monitor".$row['id'];
					}

				}				
			}
			else
			{
				if($pode_reservar)
				{
					$reservados_final = "";

					$reservados = explode("|", $row['reservado_data']);
					for($y = 1; $y < sizeof($reservados); $y++)
						if($row_evento['data'] != $reservados[$y])
						$reservados_final .= "|".$reservados[$y];

					mysqli_query($con, "UPDATE brinquedo set reservado_data = '$reservados_final' where id = $row[id]");
				}
			}
		}


		$nome_valor = explode("|", $selecionados);

		if (sizeof($nome_valor) > 1 && $pode_reservar)
		{
			$total = 0;
			for ($i = 1; $i < sizeof($nome_valor); $i++)
			{
				$sep = explode("#", $nome_valor[$i]);
				$total += intval($sep[1]);
			}

			$total += $_POST['monitores']*$rowmonitores['preco'];

			$h_fim_array = explode(":", $_POST['hora_i']);
			$h = intval($h_fim_array[0]) + 5;
			$m = intval($h_fim_array[1]);

			if ($h >= 24)
				$h -= 24;

			$hora = "".$h;
			$minuto = "".$m;

			if ($hora == "0") 
				$hora = "00";
			if (strlen($hora) == 1)
				$hora = "0".$hora;

			if ($minuto == "0") 
				$minuto = "00";
			if (strlen($minuto) == 1)
				$minuto = "0".$minuto;

			$h_fim = $hora.":".$minuto;
			
			$result = mysqli_query($con, "SELECT * from evento where cpf='$_SESSION[cpf]' and id='$_GET[id]'");
			if(mysqli_num_rows($result) > 0)
			{
				mysqli_query($con, "UPDATE evento set nome = '$_POST[nome]', cell = '$_POST[cell]', endereco = '$_POST[end]', cep = '$_POST[cep]', ref = '$_POST[ref]', data = '$_POST[data]', h_comeco = '$_POST[hora_i]', h_fim = '$h_fim', brinquedos = '$selecionados', valor = '$total', monitores = '$_POST[monitores]' where cpf = '$_SESSION[cpf]' and id = '$_GET[id]'");

				header("location: ../ilhadosbrinquedos/?pg=perfil");
			}
			else
			{
				header("location: ../ilhadosbrinquedos/sair.php");
			}
		}
		else
		{
			header("location: ../ilhadosbrinquedos/cancelarevento.php?id=".$row_evento['id']."&btn=true");
		}
		if($_POST['monitores'] >= sizeof($nome_valor) && $pode_reservar)
		{
			$result = mysqli_query($con, "SELECT * from evento where cpf = '$_SESSION[cpf]' order by id desc");
			$row = mysqli_fetch_array($result);

			$brinquedos = explode("|", $row['brinquedos']);

			for($x = 1; $x < sizeof($brinquedos); $x++)
			{
				$final = "";

				$id = intval(explode("#", $brinquedos[$x])[0]);

				$resultb = mysqli_query($con, "SELECT * from brinquedo where id = $id");
				$rowb = mysqli_fetch_array($resultb);

				$xs = explode("|", $rowb['reservado_data']);

				for($y = 1; $y < sizeof($xs); $y++)
					if($row['data'] != $xs[$y])
						$final .= "|".$xs[$y];

				
				mysqli_query($con, "UPDATE brinquedo set reservado_data = '$final' where id = $id");
			}

			mysqli_query($con, "DELETE from evento where id = $row[id]");

			header("location: ../ilhadosbrinquedos/?pg=perfil"); 
		}
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
	<a id='voltar' href="?pg=perfil" title="Voltar" style="display: inline-block;">
		<img src="imagem/voltar.png">
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
				$result = mysqli_query($con, "SELECT * from evento where cpf='$_SESSION[cpf]' and id='$_GET[id]'");

				if(mysqli_num_rows($result) > 0)
				{
					echo "

					<a id='contrato' href='gerarpdf.php?id=".$_GET['id']."' target='_blank'>
						Contrato
					</a>

					";
					$row = mysqli_fetch_array($result);

					$brinquedos_array = explode("|", $row['brinquedos']);

					$data_array = explode("-", $row['data']);
					$data = $data_array[2]."/".$data_array[1]."/".$data_array[0];

					$nome = $row['nome'];
					if(empty($row['nome']))
						$nome = "Evento sem nome";

					$sinal_pago = $row['sinal_pago'];
					if($sinal_pago == 0)
						$sinal_pago = "Aguardando pagamento do sinal";
					else
						$sinal_pago = "<b>Pagamento confirmado<b>";

					$aspa = '"';

					if (isset($_GET['editar']) && $_GET['editar'] == true) 
					{
						if ($row['sinal_pago'] == 0) 
						{
							echo 
							"
								<br><br><br>
								<form method='post' action='evento.php?id=".$_GET['id']."'>
								<div id='campo'>
									<input type='button' value='Nome' id='desc' disabled>
									<input type='text' id='login' class='contrato' name='nome' placeholder='Nome' value='".$nome."' required>
								</div>
								<br>
								<div id='campo' style='width: 26%;'>
									<input type='button' value='Data' id='desc' disabled>
									<input type='date' id='login' class='contrato' name='data' placeholder='Data do evento' value='".$row['data']."' onchange='change();' required>
								</div>
								<br><br>
									<span style='font-weight: 200; font-size: 120%;'>
										<b>CPF:</b> ".$row['cpf']."
										<br>
										<b>Situação:</b> ".$sinal_pago."
									</span>
								<br><br><br>
								<div id='campo' style='width: 18%;'>
									<input type='button' value='Tell' id='desc' disabled>
									<input type='text' id='login' class='contrato' name='cell' placeholder='Celular' value='".$row['cell']."' required>
								</div>
								<br>
								<div id='campo' style='width: 60%;'>
									<input type='button' value='Endereço' id='desc' disabled style='width: 20%'>
									<input type='text' id='login' class='contrato' name='end' placeholder='Endereco' value='".$row['endereco']."'
									style='width: calc(80% - 2vw)' required>
								</div>
								<br>
								<div id='campo' style='width: 18%;'>
									<input type='button' value='CEP' id='desc' disabled>
									<input type='text' id='login' class='contrato' name='cep' placeholder='CEP' value='".$row['cep']."' required>
								</div>
								<br>
								<div id='campo' style='width: 35%;'>
									<input type='button' value='Referência' id='desc' disabled>
									<input type='text' id='login' class='contrato' name='ref' placeholder='Referência' value='".$row['ref']."' required>
								</div>
								<br>
								<div id='campo' style='width: 20%;'>
									<input type='button' value='Começo' id='desc' style='width: 40%' disabled>
									<input type='time' id='login' class='contrato' name='hora_i' placeholder='Hora' value='".$row['h_comeco']."' style='width: calc(56% - 2vw)' required>
								</div>
								<br>
								<div id='campo' style=width: 34%;>
									<input type=button value='Final' id='desc' style=width: 35%; font-size: 1.5vw  disabled>
									<input type=text id='login' class='contrato' name='hora_f' style=width: calc(65% - 2vw) placeholder='Hora de término' value='5 horas após o começo' disabled>
								</div>



								<h2>Brinquedos</h2>
								<a href='?pg=agenda' target='_blank' id='contrato'>Agenda dos brinquedos
								</a>
								<br><br>
								<table id='evento' cellspacing='0'>";

								$result = mysqli_query($con, "SELECT * from evento where cpf = '$_SESSION[cpf]' and id='$_GET[id]'");
								$row = mysqli_fetch_array($result);
								$data_deste_evento = $row['data'];

								$contmonitores = $row['monitores'];
								$maxmonitores = 0;

								$valor_total = $row['valor'];

								$confirmado = "";
								$reservado = "";

								$result = mysqli_query($con, "SELECT * from brinquedo");

								while($row = mysqli_fetch_array($result))
								{	
									$confirmado .= "?campo".$row['id']."#".$row['confirmado_data'];
									$reservado .= "?campo".$row['id']."#".$row['reservado_data'];
								}
								
								$result_brinquedo = mysqli_query($con, "SELECT * from brinquedo");
								while($row_brinquedo = mysqli_fetch_array($result_brinquedo))
								{
									echo
									"
										<tr>
											<td onclick='document.getElementsByName(".$aspa."campo".$row_brinquedo['id'].$aspa.")[0].click();'>
												".$row_brinquedo['nome']." 
											</td>
											<td onclick='document.getElementsByName(".$aspa."campo".$row_brinquedo['id'].$aspa.")[0].click();'>
												<b>R$ ".$row_brinquedo['preco'].",00</b>
											</td>
											<td>
												<input type='checkbox' name='campo".$row_brinquedo['id']."' id='check_brinquedo' value='".$row_brinquedo['preco']."' onclick='total(".'"'.$confirmado.'", "'.$reservado.'", "'.$data_deste_evento.'"'.")' class='check_brinquedo'";

												if (in_array($row_brinquedo['id']."#".$row_brinquedo['preco']."#campo".$row_brinquedo['id'], $brinquedos_array))
												{
													echo " checked";
													$maxmonitores++;
												}
											echo ">
											</td>
										</tr>
									";
								}

								echo"
								</table>
								<br><br>
								<div id='campo' style='width: 33%;'>
								<input type='button' value='Monitores (R$".$rowmonitores['preco'].",00 cada)'
								id='desc' style='width: 70%; font-size: 90%' disabled>
								<input type='number' min='0' max='".$maxmonitores."' id='login' class='contrato' name='monitores' onchange='
								somamonitores(this.value, ".$rowmonitores['preco'].");' 
								value='".$contmonitores."' style='font-size: 120%; width: calc(30% - 2vw);' 
								onkeyup='
								somamonitores(this.value,".$rowmonitores['preco'].");' required>
							</div>
								<h2 style='color: black'>
									Total: <span id='total'>R$ ".$valor_total.",00</span>
								</h2>
								<br><br>
								<input type='submit' id='login' class='botao' value='Salvar alterações' style='width: 30%;'>
								</form>

								<br><br><br>
								<input type='submit' id='login' class='botao' value='Cancelar evento' style='width: 30%;' onclick='confirma(".$aspa.$_GET['id'].$aspa.")'>
							";


						}
						else
						{
							$editar_ou_ver_nome = "
							<br><br><br>
							<form method='post' action='mudanomeevento.php?id=".$_GET['id']."'>
								<div id='campo'>
									<input type='button' value='Nome' id='desc' disabled>
									<input type='text' id='login' class='contrato' name='nome' placeholder='Nome' value='".$nome."' required>
								</div>
								<br>
								<input type='submit' id='login' class='botao' value='Salvar alteração' style='width: 20%;'>
							</form>
							";
						}
					}

					// APENAS VISUALIZAÇÃO
					if(!isset($_GET['editar']) || isset($editar_ou_ver_nome))
					{
						if (isset($editar_ou_ver_nome)) 
						{
							echo $editar_ou_ver_nome;
						}
						else
							echo "
							<h2 id='perfil'>
								".$nome."
							</h2>";

						echo 
						"
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
										<b>Situação</b>
									</td>
									<td>
										".$sinal_pago."
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

							<h2>Brinquedos</h2>
							<table id='evento' cellspacing='0'>";

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
					
					echo "
					<br>
					<b>Cláusula 01</b>
					<br> 
					(...)no caso de haver desistência da parte do cliente a nossa empresa <b>não</b> se responsabiliza na devolução do valor já pago.";
				}
			}
		}
	?>
	<br><br><br>
</div>