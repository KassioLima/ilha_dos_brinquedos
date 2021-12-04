<?php
	session_start();

	if (!isset($_SESSION['cpf'])) //SE NAO ESTIVER LOGADO
	{
		$_SESSION['contratar'] = true;
		echo 
		"
			<script>
				window.location.href='../ilhadosbrinquedos/?pg=login';
			</script>
		";
	}
	else
	{
		echo "<script>window.location.href='../ilhadosbrinquedos/?pg=contratar';</script>";
	}
?>
