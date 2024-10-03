function comprobardatosRegistro(){
    //comprobamos si todos los campos son validos
    aux=document.register_form;
	result = (comprobarNombreApellido(aux) && comprobarDNI(aux) && comprobarTelefono(aux) && comprobarFecha(aux) &&  comprobarCorreo(aux) && comprobarUsuario(aux));
	return result;
}



function comprobardatosModificar(){
    aux=document.______________________;
	return (comprobarNombreApellido(aux) && comprobarDNI(aux) && comprobarTelefono(aux) && comprobarFecha(aux) &&  comprobarCorreo(aux) && comprobarUsuario(aux));
}


//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del nombre y apellido
function comprobarNombreApellido(form){
    //obtiene el valor del campo nombre
	var nombre= form.nombre.value;
	//mira si se ha escrito algo (longitud mayor que 0) y se han escrito solo letras
	if(/^[a-zA-Z\s]+$/.test(nombre) && nombre.length!=0){
	    //si se cumple la condicion comprueba el apellido
	    //obtiene el valor de campo apellido
		var apellido= form.apellido.value;
		//mira si se ha escrito algo (longitud mayor que 0) y se han escrito solo letras
		if (/^[a-zA-Z\s]+$/.test(apellido) && apellido.length!=0){
		    //si cumple la condicion, tanto el nombre como el apellido tienen un formato valido (devolvemos true)
			return true;}
		else{
		    //si no se cumple, lo indica en una ventana de alerta
			window.alert ("El apellido no es correcto");
			//devuelve false
			return false;}}
	else{
	     //si no se cumple, lo indica en una ventana de alerta
		window.alert ("El nombre no es correcto");
		//devolvemos false
		return false;}}

	
//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del DNI introducido
function comprobarDNI(form) {
	//Guardamos el valor de DNI introducido por el usuario en una variable
	var DNI= form.numeroDNI.value;
	//miramos si su longitud es correcta 
	if (DNI.length==8 && /^[0-9]+$/.test(DNI)){
		//Obtiene el resto de dividir el numero del DNI con 23
		aux=DNI % 23;
		//llama a la funcion obtenerLetra que nos devuelve la letra correspondiente al numero del DNI
		letraEsperada= obtenerLetra(aux);
		//Guarda el valor de la letra del DNI en otra variable
		letraIntroducida=form.letraDNI.value;
		//mira que las dos letras obtenidas son la iguales(para mira tanto mayuscula como minuscula la letra del usuario la pasamos a minuscula)
		if (letraIntroducida.toLowerCase()==letraEsperada ){
			//si son iguales devuelve un true
			return true; }
		else{
			//si no son iguales indica fallo en la letra del DNI
			window.alert ("La letra del DNI no es correcta"); 
			return false;}}	
	else{
		//si no son tiene la longitud esperada indica fallo en el numero del DNI
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
function comprobarTelefono(form){
    //obtiene el valor del numero de telefono
	var tel= form.telefono.value;
	//mira si la longitud del valor es diferente a 9 y si son todo numeros(con el patron /[0-9]+$/ indicamso solo numeros)
	if (tel.length==9 && /^[0-9]+$/.test(tel)){
	    //si cumple las condiciones devuelve true
		return true;}
	else{
	    //si no cumple alerta del fallo en el telefono
		window.alert ("El telefono no es correcto");
		//devuelve false
		return false;}}



//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion de la fecha de nacimiento
function comprobarFecha(form){
    //obtiene el valor introducido en el registro fechaNacimiento
    fechaCompleta= form.fechaNacimiento.value;
    //mira si la longitud del string es de 10= 4(anno)+1(-)+2(mes)+1(-)+2(dia)
    if (fechaCompleta.length=10){
        // si la longitud es correcta dividimos la fecha en base al separador -
        fecha= fechaCompleta.split('-');
        //mira si las longitudes de los valores año, mes y dia son correctos 4,2,2
        if (fecha[0].length==4 &&  fecha[1].length==2 && fecha[2].length==2){
            //pasa el anno a un integer
            anno= parseInt(fecha[0]);
            //pasa el mes a un integer
            mes=parseInt(fecha[1]);
            //pasa el dia a un integer
            dia=parseInt(fecha[2]);
            //Crea una variable de tipo date con la fecha introducida
            fechaIndicada= new Date(anno,mes-1,dia);
            //Crea una variable de tipo date con la fecha actual
            fechaActual=new Date();
            //mira si la fecha es valida y si no hemos introducido una fecha superior a la actual
            if (fechaValida(anno,mes,dia) && fechaIndicada<=fechaActual){
                //si la fecha es valida e inferior a la actual devuelve un true
                return true;}}}
    //si el programa no ha terminado, es decir, no se han cumplido todos los terminos de validacion salta una alarma de alerta
    window.alert ("La fecha de nacimiento no es valida");
    //devuelve un false
    return false;}
    


function fechaValida(anno, mes, dia){
    //mira si los meses son correctos rango de 1-12 y si el dia es mayor o igual a 1
    if (mes >= 1 && mes <= 12 && dia>=1){
        //si cumple mira si el dia no se excede con lo permitido en su mes
        switch(mes){
		    case 1: return dia<=31;
		    case 2: return esBisiesto(anno, dia);
		    case 3: return dia<=31;
		    case 4: return dia<=30;
		    case 5: return dia<=31;
		    case 6: return dia<=30; 
	    	case 7: return dia<=31;
	    	case 8: return dia<=31;
		    case 9: return dia<=30;
		    case 10: return dia<=31;
		    case 11: return dia<=30;
		    case 12: return dia<=31 }}
	else{
	    //si no se cumple devuelve false
	    return false}}



function esBisiesto(anno, dia){
    //mira si ele año es bisiesto
    if ((anno % 4==0 && anno % 100!=0) || anno % 400==0){
        //devuelve el resulatado de la expresion dia<=29, es decir si dia es > devuelve false y al contrario
        return dia<=29;}
    else{
        //devuelve el resulatado de la expresion dia<=28, es decir si dia es > devuelve false y al contrario
        return dia<=28;}}
        
        
        
        
//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del correo electronico        
function comprobarCorreo(form){
    //establece el patron requerido para el correo electronico 
    correoBase = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
    //guarda en una variable el valor introducido en el recuadro de correo 
    var correo= form.correo.value;
    //mira si el valor introdicido sigue el patron del correo electronico( es decir xxxxx@xx.xx)
    if (correoBase.test(correo)){
        //si sigue el patron, devuelve true
        return true;}
       
    else{
        //si no cumple el patron avisa del fallo de formato en el correo
        window.alert ("El correo electronico no es correcto");
        //devuelve false
        return false;}      
        
}

//------------------------------------------------------------------------------------------------------------------------//

function comprobarUsuario(form){
	//hay q comprobar q no haya un usuario en la bd con el mismo nombre de usuario
}

//------------------------------------------------------------------------------------------------------------------------//


