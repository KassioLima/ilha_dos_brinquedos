<?php
	session_start();
	if (isset($_SESSION['logado'])) 
	{
		unset($_SESSION['logado']);
	}
	echo "<script>window.location.href='../adm';</script>";
?>