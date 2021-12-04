<?php
	session_start();
	if (!isset($_SESSION['cpf']))
	header("location: ../ilhadosbrinquedos/sair.php");
?>

<?php
	// $con = mysqli_connect("localhost","root","","ilhadosbrinquedos");;
	// // Check connection
	// if (mysqli_connect_errno())
	// {
	// 	echo "Falha na conexão com MySQL: " . mysqli_connect_error();
	// 	header("location: ../ilhadosbrinquedos/sair.php");
	// }
	// else
	// {
	// 	if (!empty(trim($_POST['senha_apagar']))) 
	// 	{
	// 		$senha = trim($_POST['senha_apagar']);

	// 		$result = mysqli_query($con, "SELECT * from cliente where cpf = '$_SESSION[cpf]'");

	// 		$row = mysqli_fetch_array($result);

	// 		if(empty($row))
	// 		{
	// 			header("location: ../ilhadosbrinquedos/sair.php");
	// 		}
	// 		else
	// 		{
	// 			$result = mysqli_query($con, "SELECT * from cliente where cpf = '$_SESSION[cpf]' and codigo = '$senha'");

	// 			if($row = mysqli_fetch_array($result))
	// 			{
	// 				$cpfnew = $_SESSION['cpf']."#";
	// 				mysqli_query($con, "UPDATE evento SET cpf='$cpfnew' WHERE cpf = '$_SESSION[cpf]'");

	// 				mysqli_query($con, "DELETE FROM cliente where cpf='$_SESSION[cpf]'");

	// 				header("location: ../ilhadosbrinquedos/sair.php");

	// 			}

	// 			else
	// 			{
	// 				header("location: ../ilhadosbrinquedos/?pg=editarperfil");
	// 			}
	// 		}
	// 	}
	// 	else
	// 	{
	// 		header("location: ../ilhadosbrinquedos/?pg=editarperfil");
	// 	}
	// }
?>