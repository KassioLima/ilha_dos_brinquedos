<?php
	session_start();
	if (!isset($_SESSION['cpf']))
		header("location: ../ilhadosbrinquedos/sair.php");

	if (!isset($_GET['id']))
		header("location: ../ilhadosbrinquedos");

	$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: ".mysqli_connect_error();
		header("location: ../ilhadosbrinquedos/sair.php");
	}
	else
	{
		$result = mysqli_query($con, "SELECT * from evento where id = $_GET[id]");

		$row = mysqli_fetch_array($result);

		$brinquedos = explode("|", $row['brinquedos']);

		for($x = 1; $x < sizeof($brinquedos); $x++)
		{
			$final = "";

			$id = intval(explode("#", $brinquedos[$x])[0]);

			$resultb = mysqli_query($con, "SELECT * from brinquedo where id = $id");
			$rowb = mysqli_fetch_array($resultb);

			if(!isset($_GET['btn']))
				$xs = explode("|", $rowb['reservado_data']);
			else
			{
				if ($row['sinal_pago'] == 0) 
					$xs = explode("|", $rowb['reservado_data']);	
				else
					$xs = explode("|", $rowb['confirmado_data']);
			}


			for($y = 1; $y < sizeof($xs); $y++)
				if($row['data'] != $xs[$y])
					$final .= "|".$xs[$y];

			if(!isset($_GET['btn']))
				mysqli_query($con, "UPDATE brinquedo set reservado_data = '$final' where id = $id");
			else
			{
				if ($row['sinal_pago'] == 0) 
					mysqli_query($con, "UPDATE brinquedo set reservado_data = '$final' where id = $id");
				else
					mysqli_query($con, "UPDATE brinquedo set confirmado_data = '$final' where id = $id");
			}
			
		}
		mysqli_query($con, "DELETE from evento where id = $row[id]");

		if(!isset($_GET['btn']))
			header("location: ../ilhadosbrinquedos/verificaeventos.php");
		else
			header("location: ../ilhadosbrinquedos/?pg=perfil"); 
	}
?>