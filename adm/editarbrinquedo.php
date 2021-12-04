<?php
	session_start();
	if(!isset($_SESSION['logado']))
		header("location: login.php");

	if(!isset($_GET['id']))
		header("location: ../adm/?pg=brinquedos");
?>
<div style=<?php
	echo '"margin-left: 5%; margin-top: 5%; '; 
	if(isset($_POST['nome']) || isset($_GET['delete'])) 
		echo "display: none;"; 
	echo '"';
	?>>
	<a id='voltar' href="?pg=brinquedos" title="Voltar" style="display: inline-block;">
		<img src="../imagem/voltar.png">
	</a>
	<br><br>
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
			if(!isset($_POST['nome']))
			{
				$result = mysqli_query($con, "SELECT * from brinquedo where id = $_GET[id]");
				$row = mysqli_fetch_array($result);
				echo
				"
					<form method='post' action='editarbrinquedo.php?id=".$_GET['id']."'>
						<div id='campo'>
							<input type='button' value='Nome' id='desc' disabled>
							<input type='text' id='login' class='contrato' name='nome' placeholder='Nome' value='".$row['nome']."' required>
						</div>
						<br>
						<div id='campo'>
							<input type='button' value='Preço' id='desc' disabled>
							<input type='number' id='login' class='contrato' name='preco' placeholder='Preço' value='".$row['preco']."' required>
						</div>
						<br>
						<input type='submit' id='login' class='botao' value='Salvar alteraçôes' style='width: 20%;'>
					</form>
				";
			}
			else
			{
				mysqli_query($con, "UPDATE brinquedo set nome = '$_POST[nome]', preco = $_POST[preco] where id = $_GET[id]");
				echo "<script>window.location.href='?pg=brinquedos';</script>";
			}
			if(!isset($_GET['delete']))
			{
				$aspa = '"';
				echo
				"
					<form action='editarbrinquedo.php?id=".$_GET['id']."&delete=true' method='post'>
						<input type='button' id='login' class='botao' value='Remover brinquedo' onclick=".$aspa."if(confirm('Deseja realmente apagar esse brinquedo da base de dados?'))
						{
							document.getElementById('submit').click();
						}".$aspa." style='width: 20%;'>
						<input type='submit' id='submit' style='display: none'>
					</form>
				";
			}
			else
			{
				mysqli_query($con, "DELETE from brinquedo where id = $_GET[id]");
				echo "<script>window.location.href='?pg=brinquedos';</script>";
			}
	}
	?>
</div>