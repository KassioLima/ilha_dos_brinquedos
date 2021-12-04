<?php
	$con=mysqli_connect("localhost","root","","ilhadosbrinquedos");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: ".mysqli_connect_error();
		header("location: ../ilhadosbrinquedos/sair.php");
	}
	else
	{
		$result = mysqli_query($con, "SELECT * from evento where passado = 0");


		while($row = mysqli_fetch_array($result))
		{
			date_default_timezone_set('America/Sao_Paulo');
			$hoje = strtotime(date("Y-m-d")."");

			$data_s = explode("/", explode("_", $row['abertura'])[0]);
			$data_j = $data_s[2]."-".$data_s[1]."-".$data_s[0];

			$prazo_pagamento = strtotime($data_j);

			$final = ($prazo_pagamento - $hoje)/86400;

			if($final < -6 && $row['sinal_pago'] == 0) //6 pq começa do 0(hoje conta)
			{
				header("location: cancelarevento.php?id=".$row['id']);
			}
			else if(($final < -6) && ($row['sinal_pago'] != 0))
			{
				mysqli_query($con, "UPDATE evento set passado = 1 where id = $row[id]");
			}
		}
	}
?>
