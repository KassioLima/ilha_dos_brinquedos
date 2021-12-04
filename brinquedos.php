<?php
	session_start();

	$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: " . mysqli_connect_error();
		header("location: ../ilhadosbrinquedos/");
	}
?>
<div id='carregadivlogin'></div>
<div style='margin-left: 5%;'>
	<br><br>
	<a href="?pg=agenda" target="_blank" id='contrato'>Agenda dos brinquedos</a>
	<h2 id='perfil'>Nossos brinquedos</h2>
</div>
<center>
	<?php

		$result = mysqli_query($con, "SELECT * from brinquedo");

		if(mysqli_num_rows($result) > 0)
			echo "<table id='evento' cellspacing='0'>";

		$aspa = '"';

		$cont = 0;

		while ($row = mysqli_fetch_array($result)) 
		{

			echo "
				<tr style='cursor: default;'>
				<td>
					".++$cont."
				</td>
				<td>
					".$row['nome']."
				</td>
				<td>
					R$ ".$row['preco'].",00
				</td>

			</tr>";
		}

		if(mysqli_num_rows($result) == 0)
			echo "<h3>Ainda não tem nenhum brinquedo!</h3>";
		else
			echo "</table>";
	?>
</center>
<br><br>
