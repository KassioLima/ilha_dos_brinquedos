<?php
	session_start();
?>
 
<!DOCTYPE html>
<html lang="en">
    <head>
		<meta charset="UTF-8"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>IDB - Fotos</title>
        <meta name="description" content="Ilha dos Brinqueods, diversão não tem segredo"/>
        <meta name="keywords" content="html5, responsive, image gallery, masonry, picture, images, sizes, fluid, history api, visibility api"/>
        <meta name="author" content="Codrops"/>
        <link rel="shortcut icon" href="../favicon.ico"> 
        <link rel="stylesheet" type="text/css" href="css/style.css"/>
		<script src="js/modernizr.custom.70736.js"></script>
		<script src="../js/padrao_home.js"></script>
		<noscript><link rel="stylesheet" type="text/css" href="css/noJS.css"/></noscript>
		<!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
		<style type="text/css">
			body
			{
				background-color: transparent;
			}
			h2#perfil
			{
				color: rgba(30,110,180,1);
				margin: 5%;
				margin-left: 0;
				font-size: 180%;
				border: none;
				font-family:  Bahnschrift Light;
				margin-bottom: 0;
				margin-top: 0;
			}
			a
			{
				text-decoration: underline;
			}
		</style>

		<link rel="stylesheet" type="text/css" href="../css/css.css"/>

		<script type="text/javascript">
			window.onload = function()
			{
				var xmlhttp = new XMLHttpRequest();
				xmlhttp.onreadystatechange = function()
				{
					if (this.readyState == 4 && this.status == 200)
					{
						document.getElementById("rodape").innerHTML = this.responseText;
					}
				};
				
				xmlhttp.open("GET", "../rodape.php", true);
				xmlhttp.send();
			}
		</script>
    </head>
    <body>
        <div class="container">
        	<div>
        		<center>
					<div id='login'>
						<?php
							if(isset($_SESSION['cpf'])) 
							{
								$con = mysqli_connect("localhost","root","","ilhadosbrinquedos");;
								// Check connection
								if (mysqli_connect_errno())
								{
									echo "Falha na conexão com MySQL: " . mysqli_connect_error();
								}
								else
								{
									$result = mysqli_query($con, "SELECT nome from cliente where cpf = '$_SESSION[cpf]'");
									$row = mysqli_fetch_array($result);

									if (!empty($row))
									{
										echo "<a href='../?pg=perfil' target='_parent'>".$row['nome']."</a> - <a href='../sair.php' target='_parent'>Sair</a>";
									}
									else
									{
										unset($_SESSION['cpf']);
										echo "<a href='../?pg=login' target='_parent'>Fazer login</a> ou <a href='../?pg=cadastrar' target='_parent'>Cadastrar</a>";
									}
								}
							}
							else
							{
								echo "<a href='../?pg=login' target='_parent'>Fazer login</a> ou <a href='../?pg=cadastrar' target='_parent'>Cadastrar</a>";
							}
						?>
					</div>
				</center>
        	</div>
			
			<div class="main" style="margin-top: 0; padding-top: 0;">

				
				<h2 id='perfil'>Confira nossas fotos</h2>
					
				
				<div class="gamma-container gamma-loading" id="gamma-container">

					<ul class="gamma-gallery">
						<li>
							<div data-description="<h3>Kassio</h3>" data-max-width="1800" data-max-height="1350">
								<div data-src="../images/1.jpg"></div>
							</div>
						</li>

						<li>
							<div data-description="<h3>Kassio</h3>" data-max-width="1800" data-max-height="1350">
								<div data-src="../images/2.jpg"></div>
							</div>
						</li>

						<li>
							<div data-description="<h3>Kassio</h3>" data-max-width="1800" data-max-height="1350">
								<div data-src="../images/3.jpg"></div>
							</div>
						</li>

						<li>
							<div data-description="<h3>Kassio</h3>" data-max-width="1800" data-max-height="1350">
								<div data-src="../images/4.jpg"></div>
							</div>
						</li>

					</ul>

					<div class="gamma-overlay"></div>
				</div>

			</div><!--/main-->
			<div id='rodape'></div>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<script src="js/jquery.masonry.min.js"></script>
		<script src="js/jquery.history.js"></script>
		<script src="js/js-url.min.js"></script>
		<script src="js/jquerypp.custom.js"></script>
		<script src="js/gamma.js"></script>
		<script type="text/javascript"></script>
		<script type="text/javascript">
			
			$(function() 
			{

				var GammaSettings = 
				{
						// order is important!
						viewport : [ {
							width : 1200,
							columns : 5
						}, {
							width : 900,
							columns : 4
						}, {
							width : 500,
							columns : 3
						}, { 
							width : 320,
							columns : 2
						}, { 
							width : 0,
							columns : 2
						} ]
				};

				Gamma.init( GammaSettings, fncallback );

				// Example how to add more items (just a dummy):

				var page = 0,
					items = [""]

				function fncallback() 
				{

					$( "#loadmore" ).show().on( "click", function() {

						++page;
						var newitems = items[page-1]
						if( page <= 1 ) {
							
							Gamma.add( $( newitems ) );

						}
						if( page === 1 ) {

							$( this ).remove();

						}

					} );

				}

			});

		</script>
	</body>
</html>
