<?php 
class clsConexion{
    private $conexion;

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

    public function abrir_conexion(){
        if (!isset($this->conexion)){
            // Se crea la conexion
            // $this->conexion = mysqli_connect( DB_SERVER,DB_USER,DB_PASS) or die ("No se ha podido conectar al SERVIDOR de Base de datos");
            $this->conexion = mysqli_connect($this->SERVER,$this->USER,$this->PWD) or die ("No se ha podido conectar al SERVIDOR de Base de datos");

            // Se establece el conjunto de caracteres predeterminado del cliente
            mysqli_query($this->conexion,"SET NAMES 'utf8'");

            // Selección del a base de datos a utilizar
            // $db = mysqli_select_db($this->conexion, DB_NAME) or die ( "No se ha podido conectar a la BASE DE DATOS" );
            $db = mysqli_select_db($this->conexion, $this->DB) or die ( "No se ha podido conectar a la BASE DE DATOS" );
        }
    }

    public function cerrar_conexion(){
        if(isset($this->conexion)){
            $this->conexion=null;
        }
    }

    public function obtener_conexion(){
        return $this->conexion;
    }
}
?>