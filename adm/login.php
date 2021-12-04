<?php
	session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="../css/css.css">
	<meta charset="utf-8">
</head>
<body>
	<center>
		<h1 id='login'>Login - ADM</h1>
		<div id='camposlogin'>
			<br>
			<form method="post" action="login.php">
				<input type="password" id='login' class="senha" name="senha" placeholder="Digite a senha" maxlength="5" required="">
				<input id="login" class="botao" type="submit" name="" value='Entrar'>
				<?php
					if(isset($_POST['senha'])&&(!empty($_POST['senha']))) 
					{
						if($_POST['senha'] == "00000")
						{
							$_SESSION['logado'] = true;
							header("location: ../adm");
						}
					}
				?>
			</form>
		</div>
	</center>
</body>
</html>
