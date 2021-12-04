<?php
	session_start();
	if(!isset($_SESSION['logado']))
		header("location: login.php");
?>

<!DOCTYPE html>
<html>
	<head>
		<link rel="stylesheet" type="text/css" href="../css/css.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<?php
			if(!isset($_GET['pg'])||(empty($_GET['pg'])))
			{
				echo "<script>window.location.href='?pg=eventos&tempo=futuro&numpage=1'</script>";
			}
			echo "<script src='../js/padrao_".$_GET['pg'].".js'></script>";
		?>
		<title></title>
		<meta charset="utf-8">
		<script type="text/javascript">
		<?php

			echo 
			'
				window.onload = function()
				{
					//RODA PÉ
					// var xmlhttp = new XMLHttpRequest();
					// xmlhttp.onreadystatechange = function()
					// {
					// 	if (this.readyState == 4 && this.status == 200)
					// 	{
					// 		document.getElementById("conteudo").innerHTML = this.responseText;
					// 	}
					// };
					
					// xmlhttp.open("GET", "rodape.php", true);
					// xmlhttp.send();


					
					//CARREGA PAGINA

					var xmlhttp = new XMLHttpRequest();
					xmlhttp.onreadystatechange = function()
					{
						if (this.readyState == 4 && this.status == 200)
						{
							document.getElementById("conteudo").innerHTML = this.responseText+document.getElementById("conteudo").innerHTML;
							document.getElementById("conteudo").style="";


							//PARA NAO FICAR UM ESPAÇO EM BRANCO EM BAICO DE FOOTER
							if((document.getElementById("conteudo").scrollHeight == document.body.scrollHeight))
							{
								var height = 0;

								setInterval(function()
								{
									while(document.getElementById("conteudo").scrollHeight == document.body.scrollHeight)
									{
										document.getElementsByTagName("footer")[0].style = "height: "+height+"%";

										height += 10;
									}
									height -= 10;

									var c = document.getElementById("conteudo").scrollHeight;
									var b = document.body.scrollHeight;

									// c - 1
									// b - x%

									// c x% = b
									// x% = b/c
									// x = (b/c)*100;

									var x = (b/c)*100;

									var maior = 100 - x;

									height -= maior;

									document.getElementsByTagName("footer")[0].style = "height: "+height+"%";

									while(document.getElementById("conteudo").scrollHeight != document.body.scrollHeight)
									{
										document.getElementsByTagName("footer")[0].style = "height: "+height+"%";

										height -= 0.1;
									}

								}, 300);
								
							}

						}
					};


					//COLOCA O CONTEUDO DO ARQUIVO COM O NOME DA VIRIAVEL pg NA DIV conteudo
					//window.location.href.split("?")[1] PEGA A PARTE DO LINK DEPOIS DA INTERROGAÇÃO
					xmlhttp.open("GET", "'.$_GET["pg"].'"+".php?"+window.location.href.split("?")[1], true);

					xmlhttp.send();



					//COLOCA O NOME DA PÁGINA ATUAL NO TITLE
					document.getElementsByTagName("title")[0].innerHTML = "IDB - "+"'.$_GET["pg"].'".charAt(0).toUpperCase()+"'.$_GET["pg"].'".substr(1);

					//DESTACA NO MENU O LINK DA PAGINA ATUAL
					document.getElementsByName("'.$_GET["pg"].'")[0].className = "active";

					//DÁ UM CLICK NO BOTAO QUE RODA O SCRIPT PADRAO DA PAGINA ATUAL
					// document.getElementById("botaorodascript'.$_GET["pg"].'").click();
					// if("'.$_GET['pg'].'" != "home")
					// document.getElementById("botaorodascripthome").click();

				}
			';
		?>
		</script>
	</head>
	<body>
		<iframe src="../verificaeventos.php" style="display: none;"></iframe>
		
		<nav id='menulateral'>
			<button id='botaomenulateral1' class='' name='eventos' onclick="window.location.href='?pg=eventos&tempo=futuro&numpage=1'">Eventos</button>

			<button id='botaomenulateral2' class='' name='clientes' onclick="window.location.href='?pg=clientes&numpage=1'">Clientes</button>

			<button id='botaomenulateral3' class='' name='brinquedos' onclick="window.location.href='?pg=brinquedos'">Brinquedos</button>

			<button id='botaomenulateral4' class='' name='sair' onclick="window.location.href='sair.php'">SAIR</button>
		</nav>
		<div id='conteudo' style="display: none;">
		</div>
	</body>
</html>