function comprobardatos(){
	if(comprobarNombreApellido() && comprobarDNI() && comprobarTelefono()){
	
	}
}



//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del nombre y apellido
function comprobarNombreApellido(){
	var nombre= document.formulario.nombre.value;
	if(soloLetras(nombre) && nombre.length!=0){
		var apellido= document.formulario.apellido.value;
		if (soloLetras(apellido) && apellido.length!=0){
			return true;}
		else{
			window.alert ("El apellido no es correcto");
			return false;}}
	else{
		window.alert ("El nombre no es correcto");
		return false;}}


function soloLetras(aux){
	const letras = ['a','b','c','d','e','f','g','h','i','j','k','l','m','ñ','o','p','q','r','s','t','u','v','w','x','y','z']		   
	for (let letra of aux.toLowerCase()){
		if (!letras.includes(letra)){
			return false;}}
	return true;}
	

	
//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del DNI introducido
function comprobarDNI() {
	//Guardamos el valor de DNI introducido por el usuario en una variable
	var DNI= document.formulario.numeroDNI.value;
	//miramos si su longitud es correcta 
	if (DNI.length==8){
		//Obtenemos el resto de dividir el numero del DNI con 23
		aux=DNI % 23;
		//llamamos a la funcion obtenerLetra que nos devolvera la letra correspondiente al numero del DNI
		letraEsperada= obtenerLetra(aux);
		//Guardamos el valor de la letra del DNI en otra variable
		letraIntroducida=document.formulario.letraDNI.value;
		//miramos que las dos letras obtenidas son la iguales(para mira tanto mayuscula como minuscula la letra del usuario la pasamos a minuscula)
		if (letraIntroducida.toLowerCase()==letraEsperada ){
			//si son iguales devolvemos un true
			return true; }
		else{
			//si no son iguales indicamos fallo en la letra del DNI
			window.alert ("La letra del DNI no es correcta"); 
			return false;}}	
	else{
		//si no son tiene la longitud esperada indicamos fallo en el numero del DNI
		window.alert ("El numero del DNI no es correcto");
		return false;}	
	
}

function obtenerLetra(num){
	//a partir del numero que llega como parametro salta al caso correspondiente
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




//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del nombre y apellido
function comprobarTelefono(){
	var tel= documento.formulario.telefono.value;
	if (tel.length==9 && todoNumeros(tel)){
		return true;}
	else{
		window.alert ("El telefono no es correcto");
		return false;}}


function todoNumeros(aux){
	const numeros=['1','2','3','4','5','6','7','8','9','0'];
	for( let num of aux){
		if (!numeros.includes(num)){
			return false;}}
	return true;}
	
