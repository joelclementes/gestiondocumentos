<?php 
if (!isset($_SESSION)) {
  @session_start();
}
@$usuario = $_SESSION["usuario"];
@$idUsuario = $_SESSION["idUsuario"];
@$nombreUsuario = $_SESSION["nombreUsuario"];
@$p = $_GET["p"];
$pag = $p . "/index.html";

if ($p == "expediente") {
  $pag = "Expediente/index.html";
}
if ($p == "inicio" || $p=="") {
  $pag = "accInicio/index.html";
}
if ($p == "cambioContrasena") {
  $pag = "paginas/perfil.php";
}
if ($p == "atencionpersonal") {
  $pag = "AtencionPersonal/index.html";
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <title>Gestión de documentos</title>
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <?php
  include("estilos.php");
  include("scripts.php");
  ?>

</head>

<body>
  <?php
  if ($usuario == "") {
    require_once("Login/index.html");
  } else { ?>
    <div id="txtUsuario" hidden><?php echo $usuario; ?></div>
    <div id="txtIdUsuario" hidden><?php echo $idUsuario; ?></div>
    <div id="txtNombreUsuario" hidden><?php echo $nombreUsuario; ?></div>
    <?php require("Menu/index.html"); ?>
    <br><br><br>
    <main>
      <?php
      @include($pag);
      ?>
    </main>
  <?php } ?>

</body>

</html>

