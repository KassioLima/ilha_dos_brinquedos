<?php
	session_start();
?>
<center>
	<div id='login'>
		<?php
			if(isset($_SESSION['cpf'])) 
			{
				$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");;
				// Check connection
				if (mysqli_connect_errno())
				{
					echo "Falha na conexão com MySQL: " . mysqli_connect_error();
				}
				else
				{
					$result = mysqli_query($con, "SELECT nome from cliente where cpf = '$_SESSION[cpf]'");
					$row = mysqli_fetch_array($result);

					if (!empty($row))
					{
						echo "<a href='?pg=perfil'>".$row['nome']."</a> - <a href='sair.php'>Sair</a>";
					}
					else
					{
						unset($_SESSION['cpf']);
						echo "<a href='?pg=login'>Fazer login</a> ou <a href='?pg=cadastrar'>Cadastrar</a>";
					}
				}
			}
			else
			{
				echo "<a href='?pg=login'>Fazer login</a> ou <a href='?pg=cadastrar'>Cadastrar</a>";
			}
		?>
	</div>
</center>