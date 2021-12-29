<?php 
include_once("../../BackEnd/gestiondocumentos.php");

$oAcceso = new documentos();
$metodo = $_POST["metodo"];

switch ($metodo){
    case "USUARIO_MENU":
        print $oAcceso->usuario_menu($_POST["idUsuario"]);
        break;
}
?>