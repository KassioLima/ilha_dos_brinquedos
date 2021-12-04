<?php
	session_start();
	if(!isset($_SESSION['logado']))
		header("location: login.php");

	$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");
	// Check connection
	if (mysqli_connect_errno())
	{
		echo "Falha na conexão com MySQL: " . mysqli_connect_error();
		header("location: ../ilhadosbrinquedos/sair.php");
	}
?>
<div <?php if(isset($_GET['cadastrar']) || isset($_GET['monitor'])) echo "style='display: none;'"; ?>>
	<center>
		<table id='admbrinquedos' cellspacing="0">
			<tr>
				<td>
					<h2 id='perfil'>Cadastrar brinquedo</h2>
					<form method='post' action='brinquedos.php?cadastrar=true'>
						<div id='campo'>
							<input type='button' value='Nome' id='desc' disabled>
							<input type='text' id='login' class='contrato' name='nome_c' placeholder='Nome' value='' required>
						</div>
						<br>
						<div id='campo'>
							<input type='button' value='Preço em R$' id='desc' style="width: 60%" disabled>
							<input type='number' id='login' class='contrato' name='preco_c' placeholder='Preço' value='' style="width: calc(40% - 2vw)" required>
						</div>
						<br>
						<input type='submit' id='login' class='botao' value='Cadastrar' style='width: 50%;'>
					</form>
				</td>
				<td style="text-align: right;">
					<h2 id='perfil' style="margin-right: 0px;">Monitores</h2>
					<form method='post' action='brinquedos.php?monitor=true'>
						<div id='campo' style="margin-right: -2px;">
							<input type='button' value='Preço em R$' id='desc' disabled style=" width: 60%;">
							<input type='number' id='login' class='contrato' name='precomon' style="width: calc(40% - 2vw);" placeholder='Preço' value = <?php 
							echo "'"; 
							$result = mysqli_query($con, "SELECT * from monitor");
							$row = mysqli_fetch_array($result);
							echo $row['preco'];
							echo "'"; 
							?>
							required>
						</div>
						<br>
						<input type='submit' id='login' class='botao' value='Salvar alteração' style='width: 55%;'>
					</form>
				</td>
			</tr>
		</table>
		<br>
	</center>


	<div style='margin-left: 5%;'>
		<br>
		<a href="?pg=agenda" target="_blank" id='contrato'>Agenda dos brinquedos</a>
		<h2 id='perfil'>Seus brinquedos</h2>
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
					<tr>
					<td onclick=".$aspa."window.location.href = '?pg=editarbrinquedo&id=".$row['id']."'".$aspa.">
						".++$cont."
					</td>
					<td onclick=".$aspa."window.location.href = '?pg=editarbrinquedo&id=".$row['id']."'".$aspa.">
						".$row['nome']."
					</td>
					<td onclick=".$aspa."window.location.href = '?pg=editarbrinquedo&id=".$row['id']."'".$aspa.">
						R$ ".$row['preco'].",00
					</td>

				</tr>";
			}

			if(mysqli_num_rows($result) == 0)
				echo "<h3>Você ainda não tem nenhum brinquedo!</h3>";
			else
				echo "</table>";

			if(isset($_GET['cadastrar']))
			{
				mysqli_query($con, "INSERT INTO brinquedo (nome, preco) VALUES ('$_POST[nome_c]', $_POST[preco_c]	)");

				echo "
				<script>
					alert('Brinquedo cadastrado com sucesso');
					window.location.href = '../adm/?pg=brinquedos';
				</script>";
			}
			else if(isset($_GET['monitor']))
			{
				mysqli_query($con, "UPDATE monitor SET preco=$_POST[precomon]");

				echo "
				<script>
					alert('Preço alterado com sucesso');
					window.location.href = '../adm/?pg=brinquedos';
				</script>";
			}
		?>
	</center>
	<br><br>
</div>
