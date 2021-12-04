<div style='padding-left: 5%;'>
<center><h2 id='perfil'>Agenda dos brinquedos</h2></center>
<!-- ORDENA ARRAY DE DATAS -->
<?php   
	function date_sort($a, $b) 
	{
	    return strtotime($a) - strtotime($b);
	}
?>

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
		$result = mysqli_query($con, "SELECT * from brinquedo");
		while ($row = mysqli_fetch_array($result)) 
		{
			echo 
			"
				<br>
				<h2 id='perfil' style='color: black; font-size: 120%; margin: 0'>
					".$row['nome']."
				</h2>
			";

			$vazio = true;

			$reservados = explode("|", $row['reservado_data']);
			usort($reservados, "date_sort");

			if(sizeof($reservados) > 1)
			{	
				$vazio = false;

				echo 
				"
					<br>
					<h2 id='perfil' style='color: black; font-size: 105%; margin: 0; font-weight: normal;'>
						Aguardando pagamento do sinal
					</h2>
				";
				for($x = 1; $x < sizeof($reservados); $x++)
				{
					$data_inteira = explode("-", $reservados[$x]);
					$data = $data_inteira[2]."/".$data_inteira[1]."/".$data_inteira[0];

					echo "<input type'button' id='login' value='".$data."' style='width: 15%; margin: 2.5%; margin-left: 0;'>";
					// echo "<td>R$ ".$row['preco'].",00</td></tr>";
				}
			}

			$confirmados = explode("|", $row['confirmado_data']);
			usort($confirmados, "date_sort");
			if(sizeof($confirmados) > 1)
			{
				$vazio = false;

				echo 
				"	<br><br>
					<h2 id='perfil' style='color: black; font-size: 105%; margin: 0; font-weight: normal;'>
						Confirmado
					</h2>
				";
				for($x = 1; $x < sizeof($confirmados); $x++)
				{
					$data_inteira = explode("-", $confirmados[$x]);
					$data = $data_inteira[2]."/".$data_inteira[1]."/".$data_inteira[0];

					echo "<input type'button' id='login' value='".$data."' style='width: 15%; margin: 2.5%; margin-left: 0;'>";
					// echo "<td>R$ ".$row['preco'].",00</td></tr>";
				}
			}
			if($vazio)
			{
				echo "Totalmente livre<br>";
			}
			echo "<br><br>";
		}
	}
?>
	<br><br><br>
</div>