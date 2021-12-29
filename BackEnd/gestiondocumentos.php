<?php
include_once 'procesosBD.php';

class jsonUsu{
	public $usuDatos="";
    public $usuPermisos="";
    public $opcDisponibles="";
}

class jsonPaciente{
    public $datPaciente="";
    public $datConsulta="";
    public $datHistorial="";
    public $datPadecimientos="";
}

class Documentos{

    const SERVER = "localhost";
    const USER = "root";
    const PWD = "";
    const DB = "gestiondocumentos";
    const FILESPATH = "documentosAdjuntos/";
    const FILESPATHSTORE = "../../documentosAdjuntos";
    
    /******************** MÉTODOS DE USUARIOS *********************/
    public function usuario_select($datosLogin){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "SELECT * FROM admusuarios WHERE clave = '".$datosLogin['claveLogin']."'";
        return $ProcesosBD->registro($consulta);
    }

    public function usuario_menu($idUsuario){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "SELECT * FROM admmenu m LEFT JOIN admusuariomenu um ON m.idMenu = um.idMenu WHERE um.idUsuario = ".$idUsuario." order by tituloMenu";
        return $ProcesosBD->tabla($consulta);
    }

    public function usuarios_todos(){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "SELECT * FROM admusuarios ORDER BY nombreUsuario";
        return $ProcesosBD->tabla($consulta);
    }

    public function usuario_select_datos($idUsuario){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        
        $consulta = "SELECT idUsuario, nombreUsuario, clave FROM admusuarios WHERE idUsuario = ".$idUsuario." ORDER BY nombreUsuario";
        $consultaPermisos = "SELECT m.tituloMenu, um.id, um.idUsuario, um.idMenu  FROM admusuariomenu um LEFT JOIN admmenu m ON um.idMenu = m.idMenu WHERE um.idUsuario = ".$idUsuario." order by m.tituloMenu" ;
        $consultaOpciones = "SELECT * FROM admmenu ORDER BY tituloMenu";

        $usu = $ProcesosBD->registro($consulta);
        $per = $ProcesosBD->tabla($consultaPermisos);
        $opc = $ProcesosBD->tabla($consultaOpciones);

        $jsonUsu = new jsonUsu();
        $jsonUsu->usuDatos = $usu;
        $jsonUsu->usuPermisos = $per;
        $jsonUsu->opcionesDisponibles = $opc;

        return json_encode($jsonUsu);
    }
    
    public function usuario_borra_opcion_menu($id){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "DELETE FROM admusuariomenu WHERE id = ".$id;
        return $ProcesosBD->ejecutaSentencia($consulta);
    }

    public function usuario_agrega_opcion_menu($idUsuario,$idMenu){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);

        // Evaluamos si ya existe esa opción para el usuario
        $consultaExistente = "SELECT * FROM admusuariomenu WHERE idUsuario = ".$idUsuario." AND idMenu = ".$idMenu;
        $existe = $ProcesosBD->existeRegistro($consultaExistente);
        if($existe == 0){
            $consulta = "INSERT INTO admusuariomenu (idUsuario,idMenu) VALUES (".$idUsuario.",".$idMenu.")";
            return $ProcesosBD->ejecutaSentencia($consulta);
        }
    }

    public function usuario_guarda($id,$nombre,$clave,$pwd,$esNuevo){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);      
        
        // Evaluamos si existe un registro con la misma clave
        if($esNuevo==1){
            $consultaExistente = "SELECT * FROM admusuarios WHERE clave = '".$clave."'".$id;
        } else {
            $consultaExistente = "SELECT * FROM admusuarios WHERE clave = '".$clave."' AND idUsuario <> ".$id;
        }
        $existeClave = $ProcesosBD->existeRegistro($consultaExistente);
        // if($existeClave == 1 && $esNuevo == 1){
        if($existeClave == 1){
            return "Ya existe un usuario con esta clave";
        }
        $pwd2 = md5($pwd);
        if($esNuevo==1){
            $consulta = "INSERT INTO admusuarios (nombreUsuario, clave, pwd) VALUES ('".$nombre."','".$clave."','".$pwd2."')";
        } else {
            if($pwd==""){
                $consulta = "UPDATE admusuarios SET nombreUsuario = '".$nombre."', clave = '".$clave."' WHERE idUsuario = ".$id;
            } else {
                $consulta = "UPDATE admusuarios SET nombreUsuario = '".$nombre."', clave = '".$clave."', pwd = '".$pwd2."' WHERE idUsuario = ".$id;
            }
        }

        return $ProcesosBD->ejecutaSentencia($consulta);
    }
    
    public function usuario_elimina($idUsuario){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);

        $eliminaOpciones = "DELETE FROM admusuariomenu WHERE idUsuario = ".$idUsuario;
        $ProcesosBD->ejecutaSentencia($eliminaOpciones);

        $eliminaUsuario = "DELETE FROM admusuarios WHERE idUsuario = ".$idUsuario;
        return $ProcesosBD->ejecutaSentencia($eliminaUsuario);
    }

    public function usuario_update_pwd($idUsuario,$pwd){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $sentencia = "UPDATE admusuarios SET pwd='".$pwd."' WHERE idUsuario = ".$idUsuario;
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    
    //******************** */ CATÁLOGO DE ORIGEN *************************/
    public function catorigen_select(){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "SELECT idOrigen, nombre FROM catorigen ORDER BY nombre, idOrigen";
        return $ProcesosBD->tabla($consulta);        
    }

    public function catorigen_guarda($idOrigen,$nombre){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        if($idOrigen!=0){
            $sentencia = "update catorigen set nombre='".$nombre."'  where idOrigen = ".$idOrigen;
        } else {
            $sentencia = "insert into catorigen (nombre) values ('".$nombre."')";
        }
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    public function catorigen_delete($idOrigen){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $sentencia = "delete from catorigen where idOrigen = ".$idOrigen;
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    //******************** */ CATÁLOGO DE ETIQUETAS *************************/
    public function catetiquetas_select(){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "SELECT * FROM catetiquetas ORDER BY nombre";
        return $ProcesosBD->tabla($consulta);        
    }

    public function catetiquetas_guarda($idEtiqueta,$nombre){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        if($idEtiqueta!=0){
            $sentencia = "UPDATE catetiquetas SET nombre='$nombre' WHERE idEtiqueta = $idEtiqueta";
        } else {
            $sentencia = "INSERT INTO catetiquetas (nombre) VALUES ('$nombre')";
        }
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    public function catetiquetas_delete($idEtiqueta){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $sentencia = "DELETE FROM catetiquetas WHERE idEtiqueta = $idEtiqueta";
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    /***************** GUARDANDO ARCHIVO *****************/

    public function guardaArchivo($numeroOficio,$fechaOficio,$asunto,$firmadoPor,$fojas,$idOrigen,$notas,$etiquetasEntrada,$idRecibio,$nameArchivo,$sizeArchivo,$tmpArchivo,$typeArchivo){
        // $target_dir = "../../documentosAdjuntos";
        $target_dir = self::FILESPATHSTORE;
        if (!file_exists($target_dir)) {
			mkdir($target_dir, 0777, true);
		}
        $tarjet_file = $target_dir.'/'.basename($nameArchivo);
        if(move_uploaded_file($tmpArchivo,$tarjet_file)){
            $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
            $sentencia = "INSERT INTO documento (numeroOficio,fechaOficio,asunto,firmadoPor,fojas,idOrigen,archivo,notas,etiquetasEntrada,idRecibio) VALUES ('$numeroOficio','$fechaOficio','$asunto','$firmadoPor',$fojas,$idOrigen,'$nameArchivo','$notas','$etiquetasEntrada',$idRecibio)";
            return $ProcesosBD->ejecutaSentencia($sentencia);
        }
    }

    public function guardaArchivoSinArchivo($numeroOficio,$fechaOficio,$asunto,$firmadoPor,$fojas,$idOrigen,$notas,$etiquetasEntrada,$idRecibio){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $sentencia = "INSERT INTO documento (numeroOficio,fechaOficio,asunto,firmadoPor,fojas,idOrigen,archivo,notas,etiquetasEntrada,idRecibio) VALUES ('$numeroOficio','$fechaOficio','$asunto','$firmadoPor',$fojas,$idOrigen,'','$notas','$etiquetasEntrada',$idRecibio)";
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    /************************* SELECCION DE DOCUMENTOS *************************/
    public function documentos_select_all(){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = 
            "SELECT 
                d.idDocumento, 
                o.nombre AS origen,
                u.nombreUsuario AS recibio,
                d.numeroOficio,
                DATE_FORMAT(d.fecha,'%d-%m-%Y') AS fechaOficio,
                d.firmadoPor,
                d.fojas,
                d.asunto,
                d.etiquetasEntrada,
                DATE_FORMAT(d.fecha,'%d-%m-%Y') AS fechaRegistro,
                DATE_FORMAT(d.fecha,'%h-%i%p') AS hora,
                d.archivo as nombreArchivo,
                concat('".self::FILESPATH."',d.archivo) AS archivo,
                d.notas 
            FROM documento d LEFT JOIN catorigen o ON d.idOrigen = o.idOrigen LEFT JOIN 
                admusuarios u ON d.idRecibio = u.idUsuario 
            ORDER BY d.fecha DESC";
        return $ProcesosBD->tabla($consulta);
    }

    public function documentos_actualiza_historial($idDocumento,$nota){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $sentencia = "INSERT INTO documentohistorial (idDocumento,nota) VALUES ($idDocumento,'$nota')";
        $ProcesosBD->ejecutaSentencia($sentencia);
        $this->documentos_historial_select($idDocumento);
    }

    public function documentos_historial_select($idDocumento){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = 
            "SELECT 
                id, 
                idDocumento,
                nota
            FROM documentohistorial WHERE idDocumento = $idDocumento 
            ORDER BY id DESC";
        return $ProcesosBD->tabla($consulta);
    }

}
