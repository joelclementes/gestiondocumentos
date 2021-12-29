<?php 
include_once("../../BackEnd/gestiondocumentos.php");

$oDoc = new Documentos();
$proceso = $_POST["proceso"];

switch ($proceso){
    case "CATORIGEN_SELECT":
        print $oDoc->catorigen_select();
        break;
    case "CATORIGEN_GUARDA":
        print $oDoc->catorigen_guarda(
            $_POST["idOrigen"],
            $_POST["nombre"]
        );
        break;
    case "CATORIGEN_DELETE":
        print $oDoc->catorigen_delete($_POST["idOrigen"]);
        break;
}
?>