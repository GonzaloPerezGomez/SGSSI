<html>
<head>
<title> registro </title>
<script type="text/javascript" >

function comprobardatos(){
	if(comprobarDNI()){
	
	}
}










function comprobarDNI() {
	var DNI= document.formulario.numeroDNI.value;
	
	if (DNI.length==8){
		aux=DNI % 23;
		letraEsperada= obtenerLetra(aux);
		letraIntroducida=document.formulario.letraDNI.value;
		if (letraIntroducida.toLowerCase()==letraEsperada){
			return true; }
		else{
			window.alert ("La letra del DNI no es correcta"); }}	
	else{
		window.alert ("El numero del DNI no es correcto"); }	
	
}

function obtenerLetra(num){
	switch(num){
		case 0: return 't';
		case 1: return 'r';
		case 2: return 'w';
		case 3: return 'a';
		case 4: return 'g';
		case 5: return 'm';
		case 6: return 'y';
		case 7: return 'f';
		case 8: return 'p';
		case 9: return 'd';
		case 10: return 'x';
		case 11: return 'b';
		case 12: return 'n';
		case 13: return 'j';
		case 14: return 'z';
		case 15: return 's';
		case 16: return 'q';
		case 17: return 'v';
		case 18: return 'h';
		case 19: return 'l';
		case 20: return 'c';
		case 21: return 'k';
		case 22: return 'e';
	}
}
	</script>
	</head>
	
	
	<body>
	<form name="formulario">
		Introduzca la infromación pedida a continuación:
		Nombre completo:<br>
		<input type="text" name="nombre" value="Nombre">  <input type="text" name="apellido" value="Apellido"><br>
		DNI: <br>
		<input type="text" name="numeroDNI" value="Numero del DNI"> <input type="text" name="letraDNI" value="letra del DNI"> <br>
  	
  		Teléfono:<br>
  		<input type="text" name="telefono" value="año-mes-dia"> <br>
		Fecha de Nacimiento:<br>
		<input type="text" name="fechaNacimiento" ><br>
		Email:<br>
		<input type="text" name="email" > <br>
		
		<br>
		<input type="button" value="Enviar" onclick="comprobardatos()">
	</form>
<html>


	
	
	
	
	
	
	
	
	
	
	
