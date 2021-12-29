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

class serviciomedico{

    const SERVER = "localhost";
    const USER = "root";
    const PWD = "";
    const DB = "serviciomedico21";
    
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

    /************************************ ESTADISTICAS ***************************************/
    public function estadisticaPorPadecimiento($fi="",$ff=""){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        if ($fi==""){
			$consulta = "SELECT cp.descripcion as descripcion, count(ex.idExpediente) AS total FROM expediente ex LEFT JOIN catpadecimientos cp ON ex.idPadecimiento = cp.idPadecimiento WHERE ex.estado = 'Atendido' AND month(fecha)= month(curdate()) GROUP BY ex.idPadecimiento ORDER BY total DESC";
		} else {
			$consulta = "SELECT cp.descripcion as descripcion, count(ex.idExpediente) AS total FROM expediente ex LEFT JOIN catpadecimientos cp ON ex.idPadecimiento = cp.idPadecimiento WHERE ex.estado = 'Atendido' AND (date(ex.fecha) >= '".$fi."' and date(ex.fecha) <= '".$ff."' ) GROUP BY ex.idPadecimiento ORDER BY total DESC";
		}
        return $ProcesosBD->tabla($consulta);
    }

    public function estadisticaPorSexo($fi="",$ff=""){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
		if ($fi==""){
			$consulta="SELECT pa.sexo as descripcion, count(ex.idExpediente) AS total FROM expediente ex LEFT JOIN paciente pa ON ex.idPaciente = pa.idPaciente WHERE ex.estado = 'Atendido' AND month(fecha)= month(curdate()) GROUP BY pa.sexo ORDER BY total desc";
		} else {
			$consulta="SELECT pa.sexo as descripcion, count(ex.idExpediente) AS total FROM expediente ex LEFT JOIN paciente pa ON ex.idPaciente = pa.idPaciente WHERE ex.estado = 'Atendido' AND (date(ex.fecha) >= '".$fi."' and date(ex.fecha) <= '".$ff."') GROUP BY pa.sexo ORDER BY total desc";
		}
        return $ProcesosBD->tabla($consulta);
    }

    public function estadisticaPorDepartamento($fi="",$ff=""){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        if ($fi==""){
			$consulta="SELECT de.nombreDepartamento as descripcion, count(ex.idExpediente) AS total FROM expediente ex LEFT JOIN paciente pa ON ex.idPaciente = pa.idPaciente LEFT JOIN catdepartamentos de ON pa.idDepartamento = de.idDepartamento WHERE ex.estado = 'Atendido' AND month(fecha)= month(curdate()) GROUP BY de.nombreDepartamento ORDER BY total desc, de.nombreDepartamento ";
		} else {
			$consulta="SELECT de.nombreDepartamento as descripcion, count(ex.idExpediente) AS total FROM expediente ex LEFT JOIN paciente pa ON ex.idPaciente = pa.idPaciente LEFT JOIN catdepartamentos de ON pa.idDepartamento = de.idDepartamento WHERE ex.estado = 'Atendido' AND (date(ex.fecha) >= '".$fi."' AND date(ex.fecha) <= '".$ff."' ) GROUP BY de.nombreDepartamento ORDER BY total desc, de.nombreDepartamento ";
		}
        return $ProcesosBD->tabla($consulta);
    }

    /****************************************  ADMINISTRACIÓN DE PACIENTES *****************************************/

    public function catdepartamentos_select(){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "select idDepartamento, nombreDepartamento from catdepartamentos order by nombreDepartamento, idDepartamento";
        return $ProcesosBD->tabla($consulta);        
    }

    public function pacientes_select_all(){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "select p.idPaciente, p.nombre, p.apPaterno, p.apMaterno, count(e.idPaciente) as consultas, date_format(p.fechaDeNacimiento,'%Y-%m-%d') as fechaDeNacimiento, TIMESTAMPDIFF(YEAR, p.fechaDeNacimiento, CURDATE()) AS edad, p.sexo, if(p.sexo='Masculino',220,226) as constanteFcm, p.alergias, p.antPatFam, p.antPatPer, p.celular, p.contacto, p.correo, p.idDepartamento , d.nombreDepartamento, p.esDiabetico, p.esHipertenso, p.esDislipidemico, p.numeroSeguroSocial from paciente p left join catdepartamentos d on p.idDepartamento = d.idDepartamento left join expediente e on p.idPaciente = e.idPaciente group by p.idPaciente order by p.nombre, p.apPaterno, p.apMaterno";
        return $ProcesosBD->tabla($consulta);
    }

    public function pacientes_select_pendientes(){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "SELECT 
                        p.idPaciente, p.nombre, p.apPaterno, p.apMaterno, count(e.idPaciente) AS consultas, date_format(p.fechaDeNacimiento,'%Y-%m-%d') AS fechaDeNacimiento, TIMESTAMPDIFF(YEAR, p.fechaDeNacimiento, CURDATE()) AS edad, p.sexo, p.alergias, p.antPatFam, p.antPatPer, p.celular, p.contacto, p.correo, p.idDepartamento , d.nombreDepartamento, p.esDiabetico, p.esHipertenso, p.esDislipidemico, p.numeroSeguroSocial, e.idExpediente, e.motivoDeConsulta, date_format(e.fecha,'%d/%m/%Y %l:%i %p') as fechaDeRegistro
                    FROM 
                        paciente p LEFT JOIN catdepartamentos d ON p.idDepartamento = d.idDepartamento LEFT JOIN 
                        expediente e ON p.idPaciente = e.idPaciente 
                    WHERE 
                        e.estado='Pendiente' 
                    GROUP BY p.idPaciente 
                    ORDER BY p.nombre, p.apPaterno, p.apMaterno";
        return $ProcesosBD->tabla($consulta);
    }

    public function paciente_guarda($idPaciente, $nombre, $apPaterno,$apMaterno,$fechaDeNacimiento,$sexo,$alergias,$antPatFam,$antPatPer,$celular,$idDepartamento,$esDiabetico,$esHipertenso,$esDislipidemico,$numeroSeguroSocial){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        if($idPaciente!=0){
            $sentencia = "update paciente set nombre='".$nombre."', apPaterno = '".$apPaterno."', apMaterno = '".$apMaterno."', sexo = '".$sexo."', fechaDeNacimiento = '".$fechaDeNacimiento."', alergias = '".$alergias."', antPatFam = '".$antPatFam."', antPatPer = '".$antPatPer."', celular = '".$celular."', idDepartamento = ".$idDepartamento.", esDiabetico = ".$esDiabetico.", esHipertenso = ".$esHipertenso.", esDislipidemico = ".$esDislipidemico.", numeroSeguroSocial='".$numeroSeguroSocial."'  where idPaciente = ".$idPaciente;
        } else {
            $sentencia = "insert into paciente (nombre,apPaterno,apMaterno,fechaDeNacimiento,sexo,alergias,antPatFam,antPatPer,celular,idDepartamento,esDiabetico,esHipertenso,esDislipidemico,numeroSeguroSocial) values ('".$nombre."','".$apPaterno."','".$apMaterno."','".$fechaDeNacimiento."','".$sexo."','".$alergias."','".$antPatFam."','".$antPatPer."','".$celular."',".$idDepartamento.",".$esDiabetico.",".$esHipertenso.",".$esDislipidemico.",'".$numeroSeguroSocial."')";
            // echo $sentencia;
            if($ProcesosBD->existeRegistro("select * from paciente where nombre ='".$nombre."' and apPaterno = '".$apPat."' and apMaterno='".$apMat."'")==1){
                return;
            }
        }
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }
    
/******************************************** CONSULTAS **********************************************/

    public function consulta_guarda($p){
        // $p es un arreglo que contiene los parámetros enviados por proceso.php
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        if($this->puedeHacerCita($p['idPaciente'],$ProcesosBD)){
            $sentencia = "INSERT INTO expediente 
            (motivoDeConsulta,fecha,idPaciente,estado,ta,fc,temp,glucosa,peso,talla,imc,porcFcm,fcm) 
            VALUES ('".$p['motivoDeConsulta']."', now(), ".$p['idPaciente'].",'Pendiente','".$p['ta']."','".$p['fc']."','".$p['temp']."','".$p['glucosa']."',".$p['peso'].",".$p['talla'].",".$p['imc'].",".$p['porcFcm'].",".$p['fcm'].")";
            // echo $sentencia;
            return $ProcesosBD->ejecutaSentencia($sentencia);
        } else {
            return "No puede hacer cita, aún no ha transcurrido el tiempo establecido: </br> (60 minutos)";
        }
    }
    
    private function puedeHacerCita($idPaciente,$ProcesosBD){
        $consulta = "SELECT idExpediente,fecha,idPaciente,timestampdiff(minute,fecha,now()) as minutos_transcurridos FROM expediente WHERE idPaciente = $idPaciente ORDER BY idExpediente DESC LIMIT 1";
        $existe = $ProcesosBD->existeRegistro($consulta);
        if($existe==0){
            return true;
        } else {
            $registro = $ProcesosBD->registro($consulta);
            // Obtenemos un json como este:
            // {"idExpediente":"12960","fecha":"2021-10-06 13:13:13","idPaciente":"1","diferencia":"83429"}

            // decodificamos para obtener un array y así poder acceder a un campo específico
            $registrodecodificado = json_decode($registro,true);

            // Obtenemos los minutos transcurridos desde la cita anterior
            $minutosTranscurridos = $registrodecodificado['minutos_transcurridos'];

            /**
             * La regla en Servicio Médico es que deben transcurrir más de 60 minutos
             * para que pueda generarse una nueva consulta.
             */
            if($minutosTranscurridos>60){
				return true;
			} else {
                return false;
            }
        }
    }

    public function datosConsulta($p){
        // $p es un arreglo que contiene los parámetros enviados por proceso.php
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $jsonDatos = new jsonPaciente();
        
        // Datos del paciente
        $jsonDatos->datPaciente = $this->paciente_select($p["idPaciente"],$ProcesosBD);

        // Datos de la consulta
        $jsonDatos->datConsulta = $this->consulta_select($p["idExpediente"],$ProcesosBD);

        // Historial del paciente
        $jsonDatos->datHistorial = $this->historial_select($p["idPaciente"],$ProcesosBD);

        // Catálogo de padecimientos
        $jsonDatos->datPadecimientos = $this->catpadecimientos_select();

        return json_encode($jsonDatos);
    }

    private function paciente_select($idPaciente,$ProcesosBD){
        $consulta = 
        "SELECT 
            p.idPaciente, 
            concat(p.nombre,' ',p.apPaterno,' ',p.apMaterno) as nombre, 
            p.numeroSeguroSocial,
            d.nombreDepartamento, 
            if(p.sexo='Masculino',220,226) as constanteFcm, 
            TIMESTAMPDIFF(YEAR, p.fechaDeNacimiento, CURDATE()) AS edad,
            p.sexo,
            p.alergias, 
            p.antPatFam,
            p.antPatPer,
            p.esDiabetico,
            p.esHipertenso,
            p.esDislipidemico
        FROM 
            paciente p LEFT JOIN catdepartamentos d on p.idDepartamento = d.idDepartamento 
        WHERE 
            p.idPaciente = $idPaciente";
        $paciente = $ProcesosBD->registro($consulta);
        return $paciente;
    }
    
    private function consulta_select($idExpediente,$ProcesosBD){
        $consulta = 
        "SELECT 
            e.idExpediente,
            e.motivoDeConsulta,
            e.fecha,
            e.ta,
            e.fc,
            e.temp,
            e.peso,
            e.talla,
            e.imc,
            e.porcFcm,
            e.fcm,
            e.glucosa,
            e.dx,
            e.tratamiento,
            p.idPadecimiento, 
            p.descripcion AS padecimiento,
            e.nota
        FROM 
            expediente e LEFT JOIN catpadecimientos p ON e.idPadecimiento = p.idPadecimiento 
        WHERE 
            e.idExpediente = $idExpediente";
        $expediente = $ProcesosBD->registro($consulta);
        return $expediente;
    }
    
    private function historial_select($idPaciente,$ProcesosBD){
        $consulta =
        "SELECT 
            e.idExpediente, 
            p.descripcion AS padecimiento, 
            date_format(e.fecha,'%d/%m/%Y %l:%i %p') AS fecha, 
            convert(e.motivoDeConsulta,char(250)) AS motivoDeConsulta, 
            e.dx, 
            e.ta, 
            e.fc, 
            e.fcm, 
            e.porcFcm, 
            e.temp, 
            e.peso, 
            e.talla, 
            e.imc, 
            e.glucosa,
            e.tratamiento, 
            e.nota, 
            e.atendidoPor 
        FROM expediente e LEFT JOIN catpadecimientos p ON e.idPadecimiento = p.idPadecimiento 
        WHERE e.estado = 'Atendido' AND e.idPaciente = $idPaciente 
        ORDER BY e.fecha DESC";
        // echo $consulta;
        $historial = $ProcesosBD->tabla($consulta);
        return $historial;
    }

    public function expediente_update($p){
        // $p es un arreglo que contiene los parámetros enviados por proceso.php
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
		$sentencia = "UPDATE expediente SET 
		dx = '".$p["dx"]."', 
		ta = '".$p["ta"]."', 
		fc = '".$p["fc"]."', 
		temp = '".$p["temp"]."', 
		peso = '".$p["peso"]."', 
		talla = ".$p["talla"].",
		imc = ".$p["imc"].",
		porcFcm = ".$p["porcFcm"].",
		fcm = ".$p["fcm"].",
		glucosa = '".$p["glucosa"]."', 
		tratamiento = '".$p["rp"]."',
		estado = 'Atendido', 
		idPadecimiento = ".$p["idPadecimiento"].", 
		atendidoPor = '".$p["atendidoPor"]."',
		nota = '".$p["nota"]."' 
		WHERE idExpediente = ".$p["idExpediente"];

        // echo $sentencia;
        return $ProcesosBD->ejecutaSentencia($sentencia);

    }

//*********************************** PADECIMIENTOS **********************************/

    public function catpadecimientos_select(){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "SELECT * FROM catpadecimientos ORDER BY descripcion";
        $padecimientos = $ProcesosBD->tabla($consulta);
        return $padecimientos;
    }

    public function catpadecimientos_guarda($idPadecimiento,$descripcion){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        if($idPadecimiento!=0){
            $sentencia = "update catpadecimientos set descripcion='".$descripcion."'  where idPadecimiento = ".$idPadecimiento;
        } else {
            $sentencia = "insert into catpadecimientos (descripcion) values ('".$descripcion."')";
        }
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    public function catpadecimientos_delete($idPadecimiento){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $sentencia = "delete from catpadecimientos where idPadecimiento = ".$idPadecimiento;
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }
    //******************** */ CATÁLOGO DE DEPARTAMENTOS *************************/

    public function catdepartamentos_guarda($idDepartamento,$nombreDepartamento){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        if($idDepartamento!=0){
            $sentencia = "update catdepartamentos set nombreDepartamento='".$nombreDepartamento."'  where idDepartamento = ".$idDepartamento;
        } else {
            $sentencia = "insert into catdepartamentos (nombreDepartamento) values ('".$nombreDepartamento."')";
        }
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    public function catdepartamentos_delete($idDepartamento){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $sentencia = "delete from catdepartamentos where idDepartamento = ".$idDepartamento;
        return $ProcesosBD->ejecutaSentencia($sentencia);
    }

    //************************** REPORTES ******************************/
    public function qryReporteDeTurno($fecha){
        $strQuery = "
        SELECT
            CONCAT(p.apPaterno,' ',p.apMaterno,' ',p.nombre) AS nombre, 
            p.sexo, 
            TIMESTAMPDIFF(YEAR, p.fechaDeNacimiento, CURDATE()) AS edad, 
            d.nombreDepartamento, 
            CONCAT('TA:',e.ta,' FC:',e.fc,' Temp:',e.temp) AS datos, 
            CONVERT(e.dx,char(300)) as dx, 
            TRIM(replace(CONVERT(e.tratamiento,char(300)),'\n',', ')) as rp, 
            e.atendidoPor, 
            DATE_FORMAT(e.fecha,'%H:%i') as hora
        FROM 
            expediente e left join paciente p on e.idPaciente = p.idPaciente left join 
            catdepartamentos d on p.idDepartamento = d.idDepartamento 
        WHERE 
            e.estado = 'Atendido' AND 
            fecha LIKE '".$fecha."%' 
        ORDER BY 
            hora
    ";

    return $strQuery;
    }

    public function reporteDeTurno_select($fecha){
        $ProcesosBD = new ProcesosBD(self::SERVER,self::USER,self::PWD,self::DB);
        $consulta = "
        SELECT
            CONCAT(p.apPaterno,' ',p.apMaterno,' ',p.nombre) AS nombre, 
            p.sexo, 
            TIMESTAMPDIFF(YEAR, p.fechaDeNacimiento, CURDATE()) AS edad, 
            d.nombreDepartamento, 
            CONCAT('TA:',e.ta,' FC:',e.fc,' Temp:',e.temp) AS datos, 
            CONVERT(e.dx,char(300)) as dx, 
            TRIM(replace(CONVERT(e.tratamiento,char(300)),'\n',', ')) as rp, 
            e.atendidoPor, 
            DATE_FORMAT(e.fecha,'%H:%i') as hora
        FROM 
            expediente e left join paciente p on e.idPaciente = p.idPaciente left join 
            catdepartamentos d on p.idDepartamento = d.idDepartamento 
        WHERE 
            e.estado = 'Atendido' AND 
            fecha LIKE '".$fecha."%' 
        ORDER BY 
            hora
    ";
    $reporte = $ProcesosBD->tabla($consulta);
    return $reporte;
    }
}
