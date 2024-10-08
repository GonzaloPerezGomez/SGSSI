function comprobardatosAnnadir(){
    //guardamos el comienzo de la ubicacion del formulario del cual cogeremos los datos en una variable
    aux=document.item_add_form;
    //comprobamos si todos los campos son validos
	result = (comprobarTitulo(aux) && comprobarAutor(aux) && comprobarFecha(aux) && comprobarISBN(aux) && comprobarNumPaginas(aux));
	return result;
}



function comprobardatosModificar(){    
    //guardamos el comienzo de la ubicacion del formulario del cual cogeremos los datos en una variable
    aux=document.item_modify_form;
    //comprobamos si todos los campos son validos
	return (comprobarTitulo(aux) && comprobarAutor(aux) && comprobarFecha(aux) && comprobarISBN(aux) && comprobarNumPaginas(aux));
}


//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del titulo del libro
function comprobarTitulo(form){
    //obtiene el valor del campo nombre
	var titulo= form.titulo.value;
	//mira si se ha escrito algo (longitud mayor que 0) y se han escrito solo letras o numeros
	if(/^[a-zA-Z0-9\s]+$/.test(titulo) && titulo.length!=0){
		//si cumple la condicion, tanto el nombre como el apellido tienen un formato valido (devolvemos true)
		return true;}
	else{
		//si no se cumple, lo indica en una ventana de alerta
		window.alert ("El titulo no sigue el formato requerido");
		//devolvemos false
		return false;}}

	
//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del DNI introducido
function comprobarAutor(form) {
	//Guardamos el valor de DNI introducido por el usuario en una variable
	var autor = form.autor.value;
	//miramos si su longitud es superior a 0 y que el nombre este compuesto unicamente por letras y separaciones
	if (autor.length!=0 && /^[a-zA-Z\s]+$/.test(autor)){
		//cumple las condiciones devuelve true
		return true; }
	else{
		//si no cumple las condiciones devuelve false
		window.alert ("El formato del nombre del autor no es correcto"); 
		return false;}}	




//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion de la fecha de lanzamiento del libro
function comprobarFecha(form){
    //obtiene el valor introducido en el registro fechaNacimiento
    fechaCompleta= form.f_publicacion.value;
    //mira si la longitud del string es de 10= 4(anno)+1(-)+2(mes)+1(-)+2(dia)
    if (fechaCompleta.length==10){
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
    window.alert ("La fecha de publicación no es valida");
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


//Comprobacion del nombre y apellido
function comprobarISBN(form){
    //obtiene el valor del numero de telefono
	var ISBN= form.ISBN.value;
	//mira si la longitud del valor es igual a 13 y si son todo numeros(con el patron /[0-9]+$/ indicamso solo numeros)
	if (/^\d{13}$/.test(ISBN)){
	    //si cumple las condiciones devuelve true
		return true;}
	else{
	    //si no cumple alerta del fallo en el telefono
		window.alert ("El numero ISBN tienen que ser 13 numeros");
		//devuelve false
		return false;}}


      
        
        
//------------------------------------------------------------------------------------------------------------------------//


//Comprobacion del correo electronico        
function comprobarNumPaginas(form){
    //obtiene el valor del numero de telefono
	var numPaginas= form.n_paginas.value;
	//mira si son todo numeros(con el patron /[0-9]+$/ indicamso solo numeros)
	if (/^[0-9]+$/.test(numPaginas)){
	    //si cumple las condiciones devuelve true
		return true;}
	else{
	    //si no cumple alerta del fallo en el telefono
		window.alert ("El numero de paginas es un numero");
		//devuelve false
		return false;}}


