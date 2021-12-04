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
	<h1 id='login'>Login</h1>
	<div id='camposlogin'>
		<form method="post" action="login.php">
			<input type="text" id='login' name="cpf" placeholder="Digite seu cpf" maxlength="11" required>
			<input type="password" id='login' class="senha" name="codigo" placeholder="Digite seu código" maxlength="6" required="">
			<input id="login" class="botao" type="submit" name="" value='Entrar'>
			<?php
				if(isset($_POST['cpf'])&&(!empty($_POST['cpf']))) 
				{
					// $host = "localhost";
					// $user = "id7800295_idb";
					// $senha = "12345678";
					// $nome = "id7800295_ilhadosbrinquedos";

					// $con = mysqli_connect($host,$user,$senha,$nome);

					$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");

					// Check connection
					if (mysqli_connect_errno())
					{
						// echo "Falha na conexão com MySQL: " . mysqli_connect_error();
						// echo "<script>window.location.href='../ilhadosbrinquedos/?pg=login';</script>";
					}
					else
					{
						$result = mysqli_query($con, "SELECT nome, cpf from cliente where cpf = '$_POST[cpf]' and codigo = '$_POST[codigo]'");

						$row = mysqli_fetch_array($result);

						if (!empty($row) && ($_POST['cpf']) == $row['cpf'])
						{
							$_SESSION['cpf'] = $row['cpf'];
							if(isset($_SESSION['contratar']))
							{
								echo "<script>window.location.href='../ilhadosbrinquedos/?pg=contratar';</script>";
								unset($_SESSION['contratar']);
							}
							else
								echo "<script>window.location.href='../ilhadosbrinquedos/?pg=perfil';</script>";
						}
						else
						{
							echo "<script>window.location.href='../ilhadosbrinquedos/?pg=login&emailerrado=true';</script>";
						}
					}
				}
			?>
		</form>
		<a href="?pg=esqueci" id='login_c' class="esqueci">Esqueci minha senha</a>
	</div>
	Não tem uma conta?<br><br>
	<a href="?pg=cadastrar" id='login_c'>Criar conta</a>
</center>
<br><br>