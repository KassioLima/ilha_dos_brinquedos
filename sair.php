<?php
	session_start();
	if (isset($_SESSION['cpf'])) 
	{
		unset($_SESSION['cpf']);
	}
	echo "<script>window.location.href='../ilhadosbrinquedos';</script>";
?>