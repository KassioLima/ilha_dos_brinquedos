<?php 
	if (isset($_GET['cod'])) 
	{
		$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
		// Check connection
		if (mysqli_connect_errno())
		{
			echo "Falha na conexão com MySQL: " . mysqli_connect_error();
			header("location: ../ilhadosbrinquedos/?pg=esqueci&erro=true");
		}
		else
		{	
			$result = mysqli_query($con, "SELECT * from redefinicao_de_senha where cod = '$_GET[cod]'");
			if(mysqli_num_rows($result) > 0)
			{
				
				$cod = "";
				for($i=0; $i < 5; $i++) 
				{ 
					$cod .= "".rand(0, 9);
				}


					$result = mysqli_query($con, "SELECT * from redefinicao_de_senha where cod = '$_GET[cod]'");
					$row = mysqli_fetch_array($result);
					$result = mysqli_query($con, "SELECT email, cpf from cliente where cpf = '$row[cpf]'");
				$row = mysqli_fetch_array($result);

					$_SESSION['emailconf'] = $row['email'];
				$_SESSION['codconf'] = $cod;
				include("confirmaemailredefinicao.php");
				unset($_SESSION['emailconf'], $_SESSION['codconf']);

				
				if($verificado) 
				{
					date_default_timezone_set('America/Sao_Paulo');
						$datahora = date("d/m/Y_H:i:s");

					$result = mysqli_query($con, "UPDATE cliente set codigo = '$cod' where cpf = $row[cpf]");
					$result = mysqli_query($con, "UPDATE redefinicao_de_senha set cod = 'RESOLVIDO', resolvido_hora = '$datahora' where cod = '$_GET[cod]'");

					$aspa = '"';
					echo
					"
					<center>
						<h1 id='login'>Atenção</h1>
						<div id='camposlogin' class='cadastrado'>
							<h3>
								Seu novo código de acesso foi enviado para seu email.
								<br><br><br>
								Anote! É ele que voce vai usar para fazer login.
								<br>
								<button id='login' onclick='window.location.href = ".$aspa."?pg=login".$aspa."'>OK</button>
							</h3>
						</div>
					</center>
					";
				}
				else
				header("location: ../ilhadosbrinquedos/?pg=esqueci&erro=true");
			}

			else
			header("location: ../ilhadosbrinquedos/?pg=esqueci&erro=true");
		}
	}
	else
	header("location: ../ilhadosbrinquedos/?pg=esqueci&erro=true");
?>