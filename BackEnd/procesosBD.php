<?php
include_once 'clsConexion.php';
class ProcesosBD{

    private $SERVER;
    private $USER;
    private $PWD;
    private $DB;

    public function __construct($SERVER,$USER,$PWD,$DB){
        $this->SERVER = $SERVER;
        $this->USER = $USER;
        $this->PWD = $PWD;
        $this->DB = $DB;
    }

    public function registro($consulta){
        // Devuelve un registro
        $oCon = new clsConexion($this->SERVER,$this->USER,$this->PWD,$this->DB);
        $oCon->abrir_conexion();

        $resultadoQry = mysqli_query($oCon->obtener_conexion(),$consulta);

        $registro = mysqli_fetch_assoc($resultadoQry);

        $oCon->cerrar_conexion();
        return json_encode($registro);
    }

    public function tabla($consulta){
        // Devuelve una tabla
        $oCon = new clsConexion($this->SERVER,$this->USER,$this->PWD,$this->DB);
        $oCon->abrir_conexion();

        $resultadoQry = mysqli_query($oCon->obtener_conexion(),$consulta);

        $tabla = array();
        while($opcion = mysqli_fetch_assoc($resultadoQry)){
            $tabla [] = $opcion;
        }
        $oCon->cerrar_conexion();
        return json_encode($tabla);
    }

    public function tablamultiquery($consulta){
        // Devuelve una tabla
        $oCon = new clsConexion($this->SERVER,$this->USER,$this->PWD,$this->DB);
        $oCon->abrir_conexion();

        $resultadoQry = mysqli_multi_query($oCon->obtener_conexion(),$consulta);

        $tabla = array();
        while($opcion = mysqli_fetch_assoc($resultadoQry)){
            $tabla [] = $opcion;
        }

        $oCon->cerrar_conexion();
        return json_encode($tabla);
    }

    public function existeRegistro($consulta){
        // Devuelve 1 ó 0 
        $oCon = new clsConexion($this->SERVER,$this->USER,$this->PWD,$this->DB);
        $oCon->abrir_conexion();

        $resultadoQry = mysqli_query($oCon->obtener_conexion(),$consulta);
        $filas = mysqli_num_rows($resultadoQry);
        $existe = 0;
        if ($filas==0){
            $existe = 0;
        } else {
            $existe = 1;
        }

        $oCon->cerrar_conexion();
        return $existe;
    }

    public function ejecutaSentencia($consulta){
        // Ejecuta solamente una sentencia [Insert/Update/Delete...etc]
        $oCon = new clsConexion($this->SERVER,$this->USER,$this->PWD,$this->DB);
        $oCon->abrir_conexion();

        $resultadoQry = mysqli_query($oCon->obtener_conexion(),$consulta);

        $resultado = "";
        if($resultadoQry){
            $resultado = "1";
        } else {
            $resultado = "Error al ejecutar el proceso: ".mysqli_error($oCon->obtener_conexion());
        }
        $oCon->cerrar_conexion();
        return $resultado;
    }

    public function inserta($consulta){
                // Ejecuta solamente una sentencia [Insert/Update/Delete...etc]
                $oCon = new clsConexion($this->SERVER,$this->USER,$this->PWD,$this->DB);
                $oCon->abrir_conexion();
        
                $resultadoQry = mysqli_query($oCon->obtener_conexion(),$consulta);
                $ultimoId = 0;
                $resultado = "";
                if($resultadoQry){
                    $resultado = "1";
                    $ultimoId = mysqli_insert_id($oCon->obtener_conexion());
                } else {
                    $resultado = "Error al ejecutar el proceso: ".mysqli_error($oCon->obtener_conexion());
                }
                $oCon->cerrar_conexion();
                return $ultimoId;
    }
}
?>