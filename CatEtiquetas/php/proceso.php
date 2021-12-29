<?php 
include_once("../../BackEnd/gestiondocumentos.php");

$oEtiq = new Documentos();
$proceso = $_POST["proceso"];

switch ($proceso){
    case "CATETIQUETAS_SELECT":
        print $oEtiq->catetiquetas_select();
        break;
    case "CATETIQUETAS_GUARDA":
        print $oEtiq->catetiquetas_guarda(
            $_POST["idEtiqueta"],
            $_POST["nombre"]
        );
        break;
    case "CATETIQUETAS_DELETE":
        print $oEtiq->catetiquetas_delete($_POST["idEtiqueta"]);
        break;
}
?>