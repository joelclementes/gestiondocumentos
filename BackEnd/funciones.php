<?php 

function devuelveEdad($fec){
	$fecha = time() - strtotime($fec);
	$edad = floor((($fecha / 3600) / 24) / 360);
	return $edad." años";
}

function limpiarCadena($cadena)
{

$cadena = ereg_replace("[^A-Za-z0-9@._-],.:(); ", "", $cadena);
//$cadena = preg_replace('/<(.*)/is', '', $cadena); 
return $cadena;

}

function fecha_larga($fecha){
	// El parámetro debe llevar el formato Y-m-d-N
	// YYYY-mm-dd-n
	$diaSem = substr($fecha,11,1);
	$dia = substr($fecha,8,2);
	$mes = substr($fecha,5,2);
	$anio = substr($fecha,0,4);
	

	switch ($diaSem) {
		case '1': $nombreDia = "Lunes"; break;
		case '2': $nombreDia = "Martes"; break;
		case '3': $nombreDia = "Miércoles"; break;
		case '4': $nombreDia = "Jueves"; break;
		case '5': $nombreDia = "Viernes"; break;
		case '6': $nombreDia = "Sábado"; break;
		case '7': $nombreDia = "Domingo"; break;
	}
	switch($mes)
	{
		case "01": $mesf = "Enero"; break;
		case "02": $mesf = "Febrero"; break;
		case "03": $mesf = "Marzo"; break;
		case "04": $mesf = "Abril"; break;
		case "05": $mesf = "Mayo"; break;
		case "06": $mesf = "Junio"; break;
		case "07": $mesf = "Julio"; break;
		case "08": $mesf = "Agosto"; break;
		case "09": $mesf = "Septiembre"; break;
		case "10": $mesf = "Octubre"; break;
		case "11": $mesf = "Noviembre"; break;
		case "12": $mesf = "Diciembre"; break;
	}
	return "$nombreDia $dia de $mesf de $anio";
}


function voltearFechaHora_dma_amd($fecha){
	// se volteará el formato dd/mm/aaaa hh:mm
	// a este formato         aaaa/mm/dd hh:mm
	$dia = substr($fecha,0,2);
	$mes = substr($fecha,3,2);
	$anio = substr($fecha,6,4);
	$hora = substr($fecha,11,5);
	return $anio."/".$mes."/".$dia." ".$hora;

}

function voltearFechaHora_amd_dma($fecha){
	// se volteará el formato aaaa/mm/dd hh:mm
	// a este formato         dd/mm/aaaa hh:mm
	$dia = substr($fecha,8,2);
	$mes = substr($fecha,5,2);
	$anio = substr($fecha,0,4);
	$hora = substr($fecha,11,5);
	return $dia."/".$mes."/".$anio." ".$hora;

}

function voltear_fecha_dma($vari){
	$dia = substr($vari,8,2);
	$mes = substr($vari,5,2);
	$anio = substr($vari,0,4);
	if($dia!="00"){
		return $dia."/".$mes."/".$anio;}
	else {
		return $dia."/".$mes."/".$anio;
		//return "";
	}
}

function fecha_para_bd($fechaoriginal){
	//Formato de fecha original dd/mm/aaaa
	$dia = substr($fechaoriginal,0,2);
	$mes = substr($fechaoriginal,3,2);
	$axo = substr($fechaoriginal,6,4);
	//Formato que se va a retornar aaaa-mm-dd
	return $axo."-".$mes."-".$dia;
}

function conv_fecha_let($vari)
{
	$dia = substr($vari,8,2);
	$mes = substr($vari,5,2);
	$anio = substr($vari,0,4);
	
	switch($mes)
	{
		case "01": $mesf = "enero"; break;
		case "02": $mesf = "febrero"; break;
		case "03": $mesf = "marzo"; break;
		case "04": $mesf = "abril"; break;
		case "05": $mesf = "mayo"; break;
		case "06": $mesf = "junio"; break;
		case "07": $mesf = "julio"; break;
		case "08": $mesf = "agosto"; break;
		case "09": $mesf = "septiembre"; break;
		case "10": $mesf = "octubre"; break;
		case "11": $mesf = "noviembre"; break;
		case "12": $mesf = "diciembre"; break;
	}
	
	return "$dia de $mesf de $anio";
}

function validarVacios($limite,$cadena) {
	$long = strlen($cadena);
	if($long<$limite)
	{
		return 0;	
	}
	else
	{
		return 1;
	}
	
}


function validarValues($valor) {
	if($valor==0 || $valor=="")
	{
		return 0;	
	}
	else
	{
		return 1;
	}
	
}


function comprobar_fecha($vari){

	$dia = substr($vari,0,2);
	$mes = substr($vari,3,2);
	$anio = substr($vari,6,4);
@ $v = checkdate ($mes, $dia, $anio); 	
//echo "<br>".$dia."-".$mes."-".$anio;
if ($v)
{
return 1;
}
else
{

return 0;
}

}

function comprobar_email($email){
    $mail_correcto = 0;
	
	if(trim($email)=="")
	{
		return 0;
	}
	else
	{
    //compruebo unas cosas primeras
    if ((strlen($email) >= 6) && (substr_count($email,"@") == 1) && (substr($email,0,1) != "@") && (substr($email,strlen($email)-1,1) != "@")){
       if ((!strstr($email,"'")) && (!strstr($email,"\"")) && (!strstr($email,"\\")) && (!strstr($email,"\$")) && (!strstr($email," "))) {
          //miro si tiene caracter .
          if (substr_count($email,".")>= 1){
             //obtengo la terminacion del dominio
             $term_dom = substr(strrchr ($email, '.'),1);
             //compruebo que la terminación del dominio sea correcta
             if (strlen($term_dom)>1 && strlen($term_dom)<5 && (!strstr($term_dom,"@")) ){
                //compruebo que lo de antes del dominio sea correcto
                $antes_dom = substr($email,0,strlen($email) - strlen($term_dom) - 1);
                $caracter_ult = substr($antes_dom,strlen($antes_dom)-1,1);
                if ($caracter_ult != "@" && $caracter_ult != "."){
                   $mail_correcto = 1;
                }
             }
          }
       }
	
    }
    if ($mail_correcto)
       return 1;
    else
       return 0;
	   
	   }
}

function edad($edad){
list($anio,$mes,$dia) = explode("-",$edad);
$anio_dif = date("Y") - $anio;
$mes_dif = date("m") - $mes;
$dia_dif = date("d") - $dia;
if ($dia_dif < 0 || $mes_dif < 0)
$anio_dif--;
return $anio_dif;
}

function strzero($var,$posiciones)
{
	$lenVar = strlen($var);
	for($ceros=1; $ceros<=($posiciones-$lenVar); $ceros++){
		$strCadenaCeros=$strCadenaCeros."0";
	}
	return $strCadenaCeros.$var;
}

//Funcion para subir archivos al servidor
function upload($uploaddir, $archivotmp, $archivo){
	//echo printf("Iniciando");
	//Hace una copia del archivo en el servidor.
	if (move_uploaded_file($archivotmp,$uploaddir.$archivo)){
		//print"<FONT FACE='Arial'><br><br>";
		//print"El Archivo <b>$archivo</b> fue almacenado satisfactoriamente<br><br>";
		//print"</FONT>";
	    //chown ('/usr/local/apache2/htdocs/boletines/pdfs/acrobat_link.jpg','pagina');
		$cargado='TRUE';
	}else{				
		//Si existe un error nos muesta la posible causa.
		if ($error==1){
			print"EL ARCHIVO EXEDE EL TAMAÑO PERMITIDO EN LA DIRECTIVA php.ini\n";
			$cargado='FALSE';
		}elseif ($error==2){
			print"EL ARCHIVO EXEDE EL TAMAÑO PERMITIDO EN LA DIRECTIVA ESPECIFICADA EN EL FORMULARIO\n";
			$cargado='FALSE';				
		}elseif ($error==3){
			print"EL ARCHIVO HA SIDO ALMACENADO PARCIALMENTE\n";
			$cargado='FALSE';
		}elseif ($error==4){
			print"EL ARCHIVO NO FUE ALMACENADO\n";
			$cargado='FALSE';
		}else{
			printf($error);
			$cargado=$error;
		}	
	}
	return $cargado;
}

?>