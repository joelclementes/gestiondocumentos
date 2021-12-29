<?php 
include_once("../../BackEnd/gestiondocumentos.php");

$oDoc = new Documentos();
$proceso = $_POST["proceso"];

switch ($proceso){
    case "CATORIGEN_SELECT":
        print $oDoc->catorigen_select();
        break;
    case "CATETIQUETAS_SELECT":
        print $oDoc->catetiquetas_select();
        break;
    case "DOCUMENTOS_GUARDA":
        print $oDoc->guardaArchivo(
            $_POST["numeroOficio"],
            $_POST["fechaOficio"],
            $_POST["asunto"],
            $_POST["firmadoPor"],
            $_POST["idOrigen"],
            $_POST["notas"],
            $_POST["etiquetasEntrada"],
            $_POST["idRecibio"],
            $_FILES["archivo"]["name"],
            $_FILES["archivo"]["size"],
            $_FILES["archivo"]["tmp_name"],
            $_FILES["archivo"]["type"]);
        break;
        case "DOCUMENTOS_GUARDA_SIN_ARCHIVO":
            print $oDoc->guardaArchivoSinArchivo(
                $_POST["numeroOficio"],
                $_POST["fechaOficio"],
                $_POST["asunto"],
                $_POST["firmadoPor"],
                $_POST["idOrigen"],
                $_POST["notas"],
                $_POST["etiquetasEntrada"],
                $_POST["idRecibio"]);
            break;
    case "DOCUMENTOS_SELECT_ALL":
        print $oDoc->documentos_select_all();
        break;
    case "DOCUMENTOS_HISTORIAL_SELECT":
        print $oDoc->documentos_historial_select($_POST["idDocumento"]);
        break;
    case "DOCUMENTOS_ACTUALIZA_HISTORIAL":
        print $oDoc->documentos_actualiza_historial(
            $_POST["idDocumento"],
            $_POST["nota"],
            $_POST["idUsuario"]);
        break;
    case "DOCUMENTOS_UPDATE_DOCUMENTO":
        print $oDoc->documentos_update_documento(
            $_POST["idDocumento"],
            $_FILES["archivo"]["name"],
            $_FILES["archivo"]["tmp_name"]);
        break;
}
?>