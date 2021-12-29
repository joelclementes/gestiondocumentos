<?php 
include_once("../../BackEnd/gestiondocumentos.php");

$oDoc = new Documentos();
$proceso = $_POST["proceso"];

switch ($proceso){
    case "USUARIOS_TODOS":
        print $oDoc->usuarios_todos();
        break;
    case "USUARIO_GUARDA":
        print $oDoc->usuario_guarda(
            $_POST["id"],
            $_POST["nombre"],
            $_POST["clave"],
            $_POST["pwd"],
            $_POST["nuevo"]
        );
        break;
    case "USUARIO_SELECT_DATOS":
        print $oDoc->usuario_select_datos($_POST["idDelUsuario"]);
        break;
    case "USUARIO_BORRA_OPCION_MENU":
        print $oDoc->usuario_borra_opcion_menu($_POST["idQueSeBorra"]);
        break;
    case "USUARIO_AGREGA_OPCION_MENU":
        print $oDoc->usuario_agrega_opcion_menu($_POST["idUsuario"],$_POST["idMenu"]);
        break;
    case "USUARIO_ELIMINA":
        print $oDoc->usuario_elimina($_POST["idUsuario"]);
        break;

}
?>