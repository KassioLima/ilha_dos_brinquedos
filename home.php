<!-- TÍTULO -->
<?php 
	$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
	$ipad = strpos($_SERVER['HTTP_USER_AGENT'],"iPad");
	$android = strpos($_SERVER['HTTP_USER_AGENT'],"Android");
	$palmpre = strpos($_SERVER['HTTP_USER_AGENT'],"webOS");
	$berry = strpos($_SERVER['HTTP_USER_AGENT'],"BlackBerry");
	$ipod = strpos($_SERVER['HTTP_USER_AGENT'],"iPod");
	$symbian = strpos($_SERVER['HTTP_USER_AGENT'],"Symbian");
	$windowsphone = strpos($_SERVER['HTTP_USER_AGENT'],"Windows Phone");

	$pc = true;
	if ($iphone || $ipad || $android || $palmpre || $ipod || $berry || $symbian || $windowsphone == true) 
		$pc = false;
?>
<div>
	<div id="slide1" class="slide">
		<center>
			<div id='title'>
				<h1>Ilha dos brinquedos</h1>
				<h6>diversão não tem segredo</h6>
			</div>
		</center>
	</div>
	<center>
		<div id="slide2" class="slide">

			<div id='carregadivlogin'></div>

			<h3 id='home'>
				Olá galera!<br>
				Quer dar aquela animada em sua festa? Temos a solução com total segurança para os seus pequenos!<br>
				Confira os nossos preços!
			</h3>
			<a href="pre_contratar.php" id='querodarumafesta'>Contratar &nbsp;&nbsp; locação</a>

			<!-- GALERIA -->

			<iframe id='galeria' src="galeria.html"></iframe>

			<br><br>
		</div> 
	</center>
</div>
