<?php
	session_start();
	if (!isset($_SESSION['cpf']) || !isset($_GET['id']))
	header("location: ../ilhadosbrinquedos/sair.php");
?>

<?php 

	//NAMESPACE
	use Dompdf\Dompdf;

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

			//INCLUE AUTOLOAD
			require_once 'dompdf/autoload.inc.php';

			//INSTANCIA CLASSE DOMPDF
			$dompdf = new DOMPDF();

			//DEFINE O HTML

			$conteudo = "

			<!DOCTYPE html>
				<html>
					<head>
						<link rel='stylesheet' type='text/css' href='css/css.css'>
						<style>
							*
							{
								color: black;
							}
						</style>
						<title>IDB - Contrato</title>
						<meta charset='utf-8'>
					</head>
					<body>
						<center><h2 id='perfil' style='color:black'>".$nome."</h2></center>

						<table cellspacing='0'>

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
								<tr>
									<td>
										<b>id</b>
									</td>
									<td>
										".$row['id']."
									</td>
								</tr>
							</table>
							<br><br>

							<center>
								<hr style='width: 80%'>
							</center>

							<br>
							<h2>Brinquedos</h2>
							<table cellspacing='0'>
								";

							$result_brinquedo = mysqli_query($con, "SELECT * from brinquedo");
						

							while($row_brinquedo = mysqli_fetch_array($result_brinquedo))
							{
								$conteudo .= "
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
												$conteudo .= " checked";
											}
										$conteudo .= " disabled>
										</td>
									</tr>
								";
							}

							$conteudo .= "
							</table>
							<br>
							<b>Monitores (R$ 50,00 cada):</b> ".$row['monitores']."
							<h2 style='color: black'>
								Total: <span id='total'>R$ ".$row['valor'].",00</span>
							</h2>

							<br><br>


						<h2>Como funciona</h2>
						<h3 style='font-weight: normal; color: black;'>
							O contrato não fecha aqui. Isso só ocorrerá <b>após</b> o pagamento de <b>50% do valor total do evento</b>. O valor deverá ser depositado na conta bancária da <b>Ilha dos Brinquedos</b> e deverá ser enviado o comprovante de depósito para o email da mesma, contendo o <b>cpf, nome, data, hora e valor do depósito.</b> Após o envio do comprovante por email, a <b>Ilha dos Brinquedos</b> confirmará o evento e disponibilizará o contrato final.
							<br><br>
							<b>Os outros 50% deverão ser pagos no momento da entrega dos brinquedos.</b>
						</h3>
						<h2>Cláusulas</h2>
						<h3 style='font-weight: normal; color: black'>
							<b>Cláusula 01</b><br> A nossa empresa Ilha Dos Brinquedos adverte que, para que possamos fazer a devida locação de brinquedo exigimos <b>50% no ato do fechamento de contrato</b>, que é quando os brinquedos serão entregues. E mais no caso de haver desistência da parte do cliente a nossa empresa <b>não</b> se responsabiliza na devolução do valor já pago.
							<br><br>
							<b>Cláusula 02</b><br> No caso do cliente fazer a locação dos brinquedos sem nossos monitores a empresa <b>não</b> se responsabiliza pelo os danos causados nos brinquedos.
							<br><br>
							<b>Cláusula 03</b><br> No caso do cliente fazer a locação dos Brinquedos e haver mudança climática no tempo, o mesmo <b>não</b> terá a devolução do valor já pago e assim será feito um novo acordo para uma futura eventualidade.
							<br><br><br>
							<b>Responsável: Thais Fernandes de Oliveira.</b>
						</h3>

						<br><br>
						Data 
						____/____/____
						<br><br>
						Cliente ___________________________________
						<br><br>
						<b>Local:</b> Rua Frederico De Castro Pereira, 985, Jardim Tropical, Nova Iguaçu, RJ, CEP 26015-060, Brasil.<br>
						<b>CNPJ:</b> 30.628.216/0001-67<br>
						<b>Contato:</b> (21) 98809-2467 / (21) 96697-8734 - facebook.com/ilhadosbrinquedos
						<br><br><br><br>
						<center>
						<b>Ilha dos Brinquedos, diversão não tem segredo
						</b>
						</center>
					</body>
				</html>

			";

			$dompdf -> load_html($conteudo);

			//RENDERIZA O HTML
			$dompdf->render();

			//EXIBE O PDF
			$dompdf->stream("contrato ilha dos brinquedos.pdf", array("Attachment" => false)); //TRUE PARA DOWNLOAD AUTOMÁTICO);
		}
		else
		{
			header("location: ../ilhadosbrinquedos/sair.php");
		}
	}
?>