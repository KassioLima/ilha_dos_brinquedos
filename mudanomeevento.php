<?php
	session_start();
	if (!isset($_SESSION['cpf']))
	header("location: ../ilhadosbrinquedos/sair.php");
?>
<?php
	if (isset($_GET['id']))
	{
		$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Falha na conexão com MySQL: " . mysqli_connect_error();
			echo 
				"
					<script>
						window.location.href='../ilhadosbrinquedos/sair.php';
					</script>
				";
		}
		else
		{
			$result = mysqli_query($con, "SELECT * from evento where cpf='$_SESSION[cpf]' and id='$_GET[id]'");
			if(mysqli_num_rows($result) > 0)
			{
				mysqli_query($con, "UPDATE evento set nome = '$_POST[nome]' where cpf = '$_SESSION[cpf]' and id = '$_GET[id]'");

				echo 
				"
					<script>
						window.location.href='../ilhadosbrinquedos/?pg=perfil';
					</script>
				";
			}
			else
			{
				echo 
				"
					<script>
						window.location.href='../ilhadosbrinquedos/sair.php';
					</script>
				";
			}
		}
	} 
	else 
	{
		echo 
		"
			<script>
				window.location.href='../ilhadosbrinquedos/sair.php';
			</script>
		";
	}
	
?>