<?php 
include_once "../../BackEnd/gestiondocumentos.php";
$oDoc = new Documentos();
$proceso = $_POST["proceso"];

switch ($proceso){
    case "USUARIO_MENU":
        print $oDoc->usuario_menu($_POST["idUsuario"]);
        break;
    case "ESTADISTICA_POR_PADECIMIENTO":
        print $oDoc->estadisticaPorPadecimiento();
        break;
    case "ESTADISTICA_POR_SEXO":
        print $oDoc->estadisticaPorSexo();
        break;
    case "ESTADISTICA_POR_DEPARTAMENTO":
        print $oDoc->estadisticaPorDepartamento();
        break;
}
?>