function padrao_evento()
{
	
}

function confirma(id)
{
	var result = confirm("Deseja realmente cancelar este evento?");

	if(result)
	{
		window.location.href = 'cancelarevento.php?id='+id+'&btn=true';
	}

}

function change()
{
	var brinquedos = document.getElementsByClassName('check_brinquedo');
	for(var x = 0; x < brinquedos.length; x++)
		brinquedos[x].checked = false;
	document.getElementById('total').innerHTML = 'R$ 00,00';
}

function somamonitores(quantidade, preco)
{
	var total = quantidade*preco;
	var brinquedos = document.getElementsByClassName('check_brinquedo');
	for(var x = 0; x < brinquedos.length; x++)
	{
		if (brinquedos[x].checked == true) 
		{
			total += parseInt(brinquedos[x].value);
		}
	}
	
	document.getElementById('total').innerHTML = "R$ "+total+",00";

	if(total == 0)
		document.getElementById('total').innerHTML = "R$ 00,00";
}

function total(confirmado, reservado, data_evento)
{
	var data_digitada = document.getElementsByName("data")[0].value;

	if(data_digitada != "")
	{
		// alert(confirmado);
		// alert(reservado);
		//alert(data_evento);
		//alert(data_digitada);

		// 1 a 12
		var brinquedo_c = confirmado.split("?");
		var brinquedo_r = reservado.split("?");

		// 0  a 11
		var brinquedos = document.getElementsByClassName('check_brinquedo');
		var total = 0;

		var contador = 0;

		for(var x = 0; x < brinquedos.length; x++)
		{
			if (brinquedos[x].checked == true) 
			{
				data_encontrada = false;

				var mensagem = "";

				var data_reservada = brinquedo_r[x+1].split("#")[1].split("|");
				//alert(data_reservada);

				for (var i = 1; i < data_reservada.length; i++) 
				{
					//alert(data_reservada[i] == data_digitada);
					if((data_reservada[i] == data_digitada) && (data_reservada[i] != data_evento))
					{
						data_encontrada = true;
						mensagem = "Este brinquedo está aguardando uma confirmação para esta data";
						break;
					}
				}

				var data_confirmada = brinquedo_c[x+1].split("#")[1].split("|");

				for (var i = 1; i < data_confirmada.length; i++) 
				{
					if((data_confirmada[i] == data_digitada) && (data_confirmada[i] != data_evento))
					{
						data_encontrada = true;
						mensagem = "Este brinquedo já está confirmado para esta data";
						break;
					}
				}

				if(!data_encontrada)
				{
					total += parseInt(brinquedos[x].value);
					contador++;
				}
				else
				{
					alert(mensagem);
					brinquedos[x].checked = false;
					//return false;
				}
			}
		}

		document.getElementsByName('monitores')[0].max = contador;
		
		document.getElementsByName('monitores')[0].value = 0;

		document.getElementById('total').innerHTML = "R$ "+total+",00";

		if(total == 0)
			document.getElementById('total').innerHTML = "R$ 00,00";

		//return true;
	}
	else
	{
		var brinquedos = document.getElementsByClassName('check_brinquedo');
		for(var x = 0; x < brinquedos.length; x++)
			brinquedos[x].checked = false;
		alert("Selecione uma data");
		//return false;
	}

}