function padrao_home()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{
			document.getElementById("carregadivlogin").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "divlogin.php", true);
	xmlhttp.send();
}