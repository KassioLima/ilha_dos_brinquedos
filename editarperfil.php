<?php
	session_start();
	if (!isset($_SESSION['cpf']))
	header("location: ../ilhadosbrinquedos/sair.php");
?>

<?php
	$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");;
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: " . mysqli_connect_error();
		header("location: ../ilhadosbrinquedos/sair.php");
	}
	else
	{
		$result = mysqli_query($con, "SELECT * from cliente where cpf = '$_SESSION[cpf]'");

		$row = mysqli_fetch_array($result);

		if(empty($row))
		{
			header("location: ../ilhadosbrinquedos/sair.php");
		}
		else
		{
			if(isset($_POST['nome']) /*&& isset($_POST['cpf'])*/ && isset($_POST['email']))
			{
				if(!empty($_POST['nome']) /*&& !empty($_POST['cpf'])*/ && !empty($_POST['email']))
				{
					$nome = trim($_POST['nome']);
					$email = trim($_POST['email']);
					// $cpf = trim($_POST['cpf']);


					// if((strlen($cpf) < 11))
					// 	header("location: ../ilhadosbrinquedos/?pg=editarperfil&cpferrado=true");
					/*else*/ if((strlen($nome) == 0))
						header("location: ../ilhadosbrinquedos/?pg=editarperfil&nomeerrado=true");
					else if((strlen($email) == 0))
						header("location: ../ilhadosbrinquedos/?pg=editarperfil&emailerrado=true");
					else
					{
						$result = mysqli_query($con, "SELECT * from cliente where cpf = '$_SESSION[cpf]'");
						$original = mysqli_fetch_array($result);

						// if(!empty($original))
						// {
						// 	$result = mysqli_query($con, "SELECT * from cliente where cpf = '$_POST[cpf]'");
						// 	$row = mysqli_fetch_array($result);

							// if(empty($row) || ($row['cpf'] == $original['cpf']))
							// {
								$result = mysqli_query($con, "SELECT * from cliente where email = '$_POST[email]'");
								$row = mysqli_fetch_array($result);

								if(empty($row) || ($row['email'] == $original['email']))
								{
									
									mysqli_query($con, "UPDATE cliente SET nome='$_POST[nome]', email='$_POST[email]' WHERE cpf = '$_SESSION[cpf]'");

									// $_SESSION['cpf'] = $_POST['cpf'];
									header("location: ../ilhadosbrinquedos/?pg=perfil");
									
								}

								else
								{
									header("location: ../ilhadosbrinquedos/?pg=editarperfil&emailerrado=true");
								}
							// }				
							// else
							// {
							// 	header("location: ../ilhadosbrinquedos/?pg=editarperfil&cpferrado=true");
							// }
						// }
						// else
						// {
						// 	header("location: ../ilhadosbrinquedos/?pg=editarperfil&cpferrado=true");
						// }
					}
				}
				else
				{
					header("location: ../ilhadosbrinquedos/sair.php");
				}
			}
		}
	}
?>

<div id='perfil_campos' style=<?php 
		echo '"';
		if(isset($_POST['nome']) || /*isset($_POST['cpf']) ||*/ isset($_POST['email']))
		{
			echo "display: none";
		}
		echo '"';
	?>>
	<a id='voltar' href="?pg=perfil" title="Voltar">
		<img src="imagem/voltar.png">
	</a>
	<h2 id='perfil'>Editar informações</h2>

	<form method="post" action="editarperfil.php">

		<div id='campo' style=<?php
			echo "'width: 50%;";
			if (isset($_GET['nomeerrado'])) 
			{
				echo " outline-color:  red; ";
			}
			echo "'";
		?>>
			<input type="button" value='nome' id='desc' style="width: 20%; font-size: 1.5vw"  disabled>
			<input type="text" id='login' class='contrato' name="nome" placeholder='Digite seu nome aqui' style="width: calc(80% - 2vw)" value=<?php
				echo "'".$row['nome']."'";
			?>
			 required>
		</div>
		<br>
		<!-- <div id='campo' style=<?php
			// echo "'width: 50%;";
			// if (isset($_GET['cpferrado'])) 
			// {
			// 	echo " outline-color:  red; ";
			// }
			// echo "'";
		?>>
			<input type="button" value='CPF' id='desc' style="width: 20%; font-size: 1.5vw"  disabled>
			<input type="text" id='login' class='contrato' name="cpf" placeholder='Digite seu CPF aqui' style="width: calc(80% - 2vw)" value=<?php
				// echo "'".$row['cpf']."'";
			?>
			 required>
		</div>
		<br> -->
		<div id='campo' style=<?php
			echo "'width: 50%;";
			if (isset($_GET['emailerrado'])) 
			{
				echo " outline-color:  red; ";
			}
			echo "'";
		?>>
			<input type="button" value='Email' id='desc' style="width: 20%; font-size: 1.5vw"  disabled>
			<input type="email" id='login' class='contrato' name="email" placeholder='Digite seu email aqui' style="width: calc(80% - 2vw)" value=<?php
				echo "'".$row['email']."'";
			?>
			 required>
		</div>
		<br>
		<input type="submit" id='login' class="botao" value="Salvar alterações" style="width: 30%;">

		<h3>
			Para alterar sua senha <a href="?pg=esqueci" style="text-decoration: none; color: rgba(30,110,180,1)">clique aqui</a>
		</h3>
	</form>
	<!-- <br>
	<hr id='perfil'>

	<h2 id='perfil'>Apagar conta</h2>
	<form method="post" action="apagarperfil.php">
		<h3>
			Ao apagar sua conta você perderá todos os eventos já criados
		</h3>
		<br>
		<div id='campo' style="width: 50%;">
			<input type="button" value='Senha' id='desc' style="width: 20%; font-size: 1.5vw"  disabled>
			<input type="password" id='login' class='contrato' name="senha_apagar" placeholder='Digite sua senha aqui' style="width: calc(80% - 2vw);"
			 required>
		</div>
		<br>
		<input type="submit" id='login' class="botao" value="Apagar conta" style="width: 30%;">
	</form> -->

</div>