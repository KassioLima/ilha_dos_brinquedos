<?php
	session_start();
?>

<center style=<?php 
		echo '"';
		if(isset($_POST['cpf'])&&(!empty($_POST['cpf']))) 
		{
			echo "display: none";
		}
		echo '"';
	?>>
	<h1 id='login'>Cadastrar</h1>
	<div id='camposlogin'>
		<form method="post" action="cadastrar.php">
			<input type="text" id='login' name="cpf" placeholder="Digite seu cpf" maxlength="11" required>
			<input type="email" id='login' name="email" placeholder="Digite seu email" required>
			<input type="text" id='login' name="nome" placeholder="Digite seu nome" required>
			<input id="login" class="botao" type="submit" name="" value='Cadastrar'>
			<?php
				if(isset($_POST['cpf'])&&(!empty($_POST['cpf']))) 
				{
					$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");;
					// Check connection
					if (mysqli_connect_errno())
					{
						echo "Falha na conexão com MySQL: " . mysqli_connect_error();
					}
					else
					{
						$result = mysqli_query($con, "SELECT cpf from cliente where cpf = '$_POST[cpf]' or email = '$_POST[email]'");

						$row = mysqli_fetch_array($result);

						if(empty($row))
						{	
							$codigo = "";
							for($i=0; $i < 5; $i++) 
							{ 
								$codigo .= "".rand(0, 9);
							}

							$_SESSION['emailconf'] = $_POST['email'];
							$_SESSION['codconf'] = $codigo;

							include("verificaemail.php");
							unset($_SESSION['emailconf'], $_SESSION['codconf']);

							if ($verificado) 
							{
								mysqli_query($con, "INSERT INTO cliente (nome, cpf, codigo, email) VALUES ('$_POST[nome]','$_POST[cpf]','$codigo','$_POST[email]')");

								echo "<script>window.location.href='../ilhadosbrinquedos/?pg=cadastrado';</script>";
							}
							else
							{
								echo "<script>window.location.href='../ilhadosbrinquedos/?pg=cadastrar&email_ou_cpf_errado=true';</script>";
							}

						}
						else
						{
							echo "<script>window.location.href='../ilhadosbrinquedos/?pg=cadastrar&email_ou_cpf_errado=true';</script>";
						}
					}
					//echo "<script>window.location.href='../ilhadosbrinquedos';</script>";
				}
			?>
		</form>
	</div>
	Já tem uma conta?<br><br>
	<a href="?pg=login" id='login_c'>Fazer login</a>
</center>
<br><br>