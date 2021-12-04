<?php
	session_start();
	if (!isset($_SESSION['cpf']))
	{
		header("location: ../ilhadosbrinquedos/pre_contratar.php");
	}
	

	$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: " . mysqli_connect_error();
		header("location: ../ilhadosbrinquedos/sair.php");
	}

	$resultmonitores = mysqli_query($con, "SELECT * from monitor");
	$rowmonitores = mysqli_fetch_array($resultmonitores);

	if(((isset($_POST['lido'])) && (isset($_POST['cell'])) && (isset($_POST['end'])) && (isset($_POST['ref'])) && (isset($_POST['cep'])) && (isset($_POST['data'])) && (isset($_POST['monitores'])) && (isset($_POST['hora_i']))) && ((!empty(trim($_POST['cell']))) && (!empty(trim($_POST['end']))) && (!empty(trim($_POST['ref']))) && (!empty(trim($_POST['cep']))) && (!empty(trim($_POST['data']))) && (!empty(trim($_POST['hora_i'])))) && ($_POST['monitores'] >= 0)) 
	{
		$selecionados = "";

		date_default_timezone_set('America/Sao_Paulo');
		$datahora = date("d/m/Y_H:i:s");
		//$abertura = date("Y-m-d");


		$result = mysqli_query($con, "SELECT * from brinquedo");

		while($row = mysqli_fetch_array($result))
		{

			if(isset($_POST['campo'.$row['id']])) 
			{
				$pode_reservar = true;

				if(!empty($row['reservado_data']))
				{
					$reservas = explode("|", $row['reservado_data']);
					if(in_array($_POST['data'], $reservas))
					$pode_reservar = false;
				}
				if(!empty($row['confirmado_data']))
				{
					$confirmados = explode("|", $row['confirmado_data']);
					if(in_array($_POST['data'], $confirmados))
					$pode_reservar = false;
				}
				if($pode_reservar)
				{
					$datas_reserva = "";

					if(!empty($row['reservado_data']))
						$datas_reserva .= $row['reservado_data'];

					$datas_reserva .= "|".$_POST['data'];

					mysqli_query($con, "UPDATE brinquedo set reservado_data = '$datas_reserva' where id = '$row[id]'");

					//vai ser usado no evento
					$selecionados .= "|".$row['id']."#".$row['preco']."#campo".$row['id'];
					// if(isset($_POST['monitor'.$row['id'].''])) 
					// {
					// 	$selecionados .= "#monitor".$row['id'];
					// }
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
			$total += $rowmonitores['preco']*$_POST['monitores'];
				
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


			
			$result = mysqli_query($con, "SELECT cpf from cliente where cpf = '$_SESSION[cpf]'");
			if($row = mysqli_fetch_array($result))
			{
				mysqli_query($con, "INSERT INTO evento (cpf, cell, endereco, cep, ref, data, h_comeco, h_fim, brinquedos, monitores, termos_aceitos, abertura, valor) VALUES ('$row[cpf]', '$_POST[cell]', '$_POST[end]', '$_POST[cep]', '$_POST[ref]', '$_POST[data]', '$_POST[hora_i]', '$h_fim', '$selecionados', '$_POST[monitores]', 1, '$datahora', '$total')");

				echo 
				"
					<script>
						window.location.href='../ilhadosbrinquedos/?pg=perfil';
					</script>
				";

			}
			else
			{
				header("location: ../ilhadosbrinquedos/sair.php");
			}

		}
		else
		{
			header("location: ../ilhadosbrinquedos/?pg=contratar");
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

			header("location: ../ilhadosbrinquedos/?pg=contratar"); 
		}
	}
?>
<center style=<?php 
		echo '"';
		if(isset($_POST['lido'])) 
		{
			echo "display: none";
		}
		echo '"';
	?>>
	<div id='contrato'>
		<a id='voltar' href="?pg=perfil" title="Voltar">
			<img src="imagem/voltar.png">
		</a>
		<h2 id='contrato'>Contratar locação</h2>
		<h3 id='contrato' style="color: black">Preencha os campos abaixo</h3>
		<br>
		<center>
			<form method="post" action="contratar.php">

				<div id='campo' style="width: 20%;">
					<input type="button" value='Tell' id='desc'  disabled>
					<input type="text" id='login' class='contrato' name="cell" placeholder="Contato" required>
				</div>
		
				<div id='campo' style="width: 73%;">
					<input type="button" value='Endereço' id='desc' style="width: 20%"  disabled>
					<input type="text" id='login' class='contrato' name="end" style="width: calc(80% - 2vw)" placeholder="Endereço completo" required>
				</div>

				<br>

				<div id='campo' style="width: 50%;">
					<input type="button" value='Referência' id='desc'  disabled>
					<input type="text" id='login' class='contrato' name="ref" placeholder="Referência" required>
				</div>

				<div id='campo' style="width: 18%;">
					<input type="button" value='CEP' id='desc'  disabled>
					<input type="text" id='login' class='contrato' name="cep" placeholder="CEP" required>
				</div>

				<br>

				<div id='campo' style="width: 30%;">
					<input type="button" value='Data' id='desc'  disabled>
					<input type="date" id='login' class='contrato' name="data" placeholder="Data do evento" onchange="
					var brinquedos = document.getElementsByClassName('check_brinquedo');
					for(var x = 0; x < brinquedos.length; x++)
						brinquedos[x].checked = false;
					document.getElementById('total').innerHTML = 'R$ 00,00';" required>
				</div>

				<div id='campo' style="width: 25%;">
					<input type="button" value='Começo' id='desc' style="width: 50%; font-size: 1.5vw"  disabled>
					<input type="time" id='login' class='contrato' name="hora_i" style="width: calc(50% - 2vw)" required>
				</div>

				<div id='campo' style="width: 34%;">
					<input type="button" value='Final' id='desc' style="width: 35%; font-size: 1.5vw"  disabled>
					<input type="text" id='login' class='contrato' name="hora_f" style="width: calc(65% - 2vw)" placeholder="Hora de término" value='5 horas após o início' disabled>
				</div>
				<br><br>
				<div style='text-align: left;'>
					<h2>Brinquedos</h2>

					<a href="?pg=agenda" target="_blank" id='contrato'>Agenda dos brinquedos</a>
					<br><br>
					<table id="evento" cellspacing="0">

						<tr style="text-align: center; background-color: rgba(20,100,170,1); color: white; cursor: default;">
							<th><br>Nome<br><br></th>
							<th><br>Valor<br><br></th>
							<th><br>Selecionar<br><br></th>
						</tr>

						<!-- MOSTRAR BRINQUEDOS -->
						<?php

							$confirmado = "";
							$reservado = "";

							$result = mysqli_query($con, "SELECT * from brinquedo");

							while($row = mysqli_fetch_array($result))
							{
								$confirmado .= "?campo".$row['id']."#".$row['confirmado_data'];
								$reservado .= "?campo".$row['id']."#".$row['reservado_data'];
							}

							$result = mysqli_query($con, "SELECT * from brinquedo");
							
							$aspa = '"';

							while($row = mysqli_fetch_array($result))
							{
								echo
								"
									<tr>
										<td onclick='document.getElementsByName(".$aspa."campo".$row['id'].$aspa.")[0].click();'>
											".$row['nome']." 
										</td>
										<td onclick='document.getElementsByName(".$aspa."campo".$row['id'].$aspa.")[0].click();'>
											<b>R$ ".$row['preco'].",00</b>
										</td>
										<td>
											<input type='checkbox' name='campo".$row['id']."' id='check_brinquedo' value='".$row['preco']."' onclick='total(".'"'.$confirmado.'", "'.$reservado.'"'.")' class='check_brinquedo'>
										</td>
									</tr>
								";
							}
						?>
					</table>
					<br>
					<div id='campo' style="width: 36%;">
						<input type="button" value=<?php 
						echo "'Monitores (R$ ";
						echo $rowmonitores['preco'];
						echo ",00 cada)'";
						?> id='desc' style="width: 70%; font-size: 90%" disabled>
						<input type="number" min="0" max="0" id='login' class='contrato' name="monitores" onchange="
						somamonitores(this.value, <?php echo $rowmonitores['preco']; ?>);" 
						value='0' style="font-size: 120%; width: calc(30% - 2vw);" 
						onkeyup="
						somamonitores(this.value, <?php echo $rowmonitores['preco']; ?>);" required>
					</div>
					<h2 style="color: black">
						Total: <span id='total'>R$ 00,00</span>
					</h2>


					<h2>Como funciona</h2>
					<h3 style="font-weight: normal; color: black">
						O contrato não fecha aqui. Isso só ocorrerá <b>após</b> o pagamento de <b>50% do valor total do evento</b>. O valor deverá ser depositado na conta bancária da <b>Ilha dos Brinquedos</b> e deverá ser enviado o comprovante de depósito para o email da mesma, contendo o <b>cpf, nome, data, hora e valor do depósito.</b> Após o envio do comprovante por email, a <b>Ilha dos Brinquedos</b> confirmará o evento e disponibilizará o contrato final.
						<br><br>
						<b>Os outros 50% deverão ser pagos no momento da entrega dos brinquedos.</b>
					</h3>
					<h2>Cláusulas</h2>
					<h3 style="font-weight: normal; color: black">
						<b>Cláusula 01</b><br> A nossa empresa Ilha Dos Brinquedos adverte que, para que possamos fazer a devida locação de brinquedo exigimos <b>50% no ato do fechamento de contrato</b>, que é quando os brinquedos serão entregues. E mais no caso de haver desistência da parte do cliente a nossa empresa <b>não</b> se responsabiliza na devolução do valor já pago.
						<br><br>
						<b>Cláusula 02</b><br> No caso do cliente fazer a locação dos brinquedos sem nossos monitores a empresa <b>não</b> se responsabiliza pelo os danos causados nos brinquedos.
						<br><br>
						<b>Cláusula 03</b><br> No caso do cliente fazer a locação dos Brinquedos e haver mudança climática no tempo, o mesmo <b>não</b> terá a devolução do valor já pago e assim será feito um novo acordo para uma futura eventualidade.
						<br><br><br>
						<b>Responsável: Thais Fernandes de Oliveira.</b>
					</h3>
				</div>
				<input type="checkbox" id='check_brinquedo_lido' name='lido' required>
				<h3 onclick="document.getElementById('check_brinquedo_lido').click()" style="cursor: pointer; display: inline-block;">Li e aceito os termos</h3>
				<br> 
				<input type="submit" id='login' class="botao" value="Abrir contrato" style="width: 20%;">
			</form>
		</center>
	</div>
</center>
<br><br>