<?php 
include_once("../../BackEnd/gestiondocumentos.php");

$oAcceso = new Documentos();
$proceso = $_POST["proceso"];

switch ($proceso){
    case "USUARIO_SELECT":
        print $oAcceso->usuario_select($_POST["datosLogin"]);
        break;
    case "VARIABLES_SESION":
        $usu = $_POST["usuario"];
        @session_start();
        $_SESSION["idUsuario"] = $usu["idUsuario"];
        $_SESSION["usuario"] = $usu["clave"];
        $_SESSION["nombreUsuario"] = $usu["nombreUsuario"];
        header("Location: ../../index.php?p=inicio");
        break;
}
?>