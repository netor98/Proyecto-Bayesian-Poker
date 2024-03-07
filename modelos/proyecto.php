
<?php
$rutaDirectorio = dirname(__FILE__);

include_once "$rutaDirectorio/conexion.php";

class Proyectos
{
    private $conexion;
    private $proyectos;

    public function __construct()
    {
        $this->conexion = new Conexion();
    }
    public function obtenerNombreProyecto($idProyecto){
        
        $nombreProyecto = $this->conexion->getConexion()->query("SELECT nombre FROM proyectos WHERE idProyecto = '$idProyecto'");
        $nombre = $nombreProyecto->fetch_assoc();
        return $nombre['nombre'];
    }

    public function obtenerProyectos()
    {
        $idUsuario = $_COOKIE['idUsuario'];
        $idProyectos = $this->conexion->getConexion()->query("SELECT idProyecto FROM integrantes WHERE idUsuario = '$idUsuario' and estatus = 'activo'");

        $ids = [];
        while ($fila = $idProyectos->fetch_assoc()) {
            $ids[] = $fila['idProyecto'];
        }
        $idString = implode(',', $ids); 
        

        if (count($ids) > 0) {
            $detallesProyectos = $this->conexion->getConexion()->query("SELECT nombre, descripcion,idProyecto,estatus FROM proyectos WHERE idProyecto IN ($idString)");
            
            $mapDatosProyectos = [];

            while ($fila = $detallesProyectos->fetch_assoc()) {
                $datos = array(
                    "idProyecto" => $fila['idProyecto'],
                    "nombre" => $fila['nombre'],
                    "descripcion" => $fila['descripcion'],
                    "estatus" => $fila['estatus']
                );
                $mapDatosProyectos[] = $datos;
            }
            return $mapDatosProyectos;

        }
        return [];

        

    }

    public function crearProyecto($nombreProyecto,$descripcionProyecto){

        $codigoProyecto = $this->generarCodigoProyecto();
        while ($this->existeCodigoProyecto($codigoProyecto)) {
            $codigoProyecto = $this->generarCodigoProyecto();
        }

        $idUsuario = $_COOKIE['idUsuario'];
        $idProyecto = $this->insertarProyectoBD($nombreProyecto, $descripcionProyecto, $codigoProyecto);
        $this->insertarIntegranteBD($idUsuario, $idProyecto,'scrum master');
        return $codigoProyecto;
        
    }
    private function generarCodigoProyecto(){
        // 6 digitos
        $codigo = rand(100000,999999);
        return $codigo;

    }
    public function existeCodigoProyecto($codigoProyecto){

        $sql = "SELECT COUNT(*) FROM proyectos WHERE codigo = '$codigoProyecto'";
        $resultado = $this->conexion->getConexion()->query($sql);
        $fila = $resultado->fetch_array();
        return $fila[0] > 0;

    }
    private function insertarProyectoBD($nombreProyecto, $descripcionProyecto, $codigoProyecto){
        $sql = "INSERT INTO proyectos (fechaCreacion, descripcion,nombre,codigo, estatus) VALUES (NOW(), ?, ?,?,'activo')";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("sss",$descripcionProyecto, $nombreProyecto, $codigoProyecto);
        $stmt->execute();
        // obtenerElidDelProyecto recien insertado
        $idProyecto = $this->conexion->getConexion()->insert_id;
        return $idProyecto;
    }
    
    public function insertarIntegranteBD($idUsuario, $idProyecto,$rol){
        $sql = "INSERT INTO integrantes (idUsuario, idProyecto, rol, estatus) VALUES (?, ?, '$rol', 'activo')";
        $stmt = $this->conexion->getConexion()->prepare($sql);
        $stmt->bind_param("ii",$idUsuario, $idProyecto);
        $stmt->execute();
    }

    public function obtenerIntegrantesProyecto($idProyecto){
        
        $sql = "SELECT idUsuario,estatus FROM integrantes WHERE idProyecto = '$idProyecto'";
        $resultado = $this->conexion->getConexion()->query($sql);
        $integrantesActivos= [];
        $integrantesInactivos= [];
        $idsUsuarios = [];
        while ($fila = $resultado->fetch_assoc()) {
        $idsUsuarios[] = [$fila['idUsuario'],$fila['estatus']];
        }
        for ($i=0; $i < count($idsUsuarios); $i++) { 
            $idUsuario = $idsUsuarios[$i][0];
            $sql = "SELECT nombre, apellido FROM usuarios WHERE idUsuario = '$idUsuario'";
            $resultado = $this->conexion->getConexion()->query($sql);
            $fila = $resultado->fetch_assoc();
            $nombre = $fila['nombre'];
            $apellido = $fila['apellido'];
            $nombreCompleto = $nombre . " " . $apellido;
            if ($idsUsuarios[$i][1] == 'activo') {
                $integrantesActivos[] = $nombreCompleto;
            }else{
                $integrantesInactivos[] = $nombreCompleto;
            }
        }
        return [$integrantesActivos,$integrantesInactivos];
    }

    public function abandonarProyecto(){
        $idUsuario = $_COOKIE['idUsuario'];
        $idProyecto = 11;
        $sql = "UPDATE integrantes SET estatus = 'inactivo' WHERE idUsuario = '$idUsuario' AND idProyecto = '$idProyecto'";
        $this->conexion->getConexion()->query($sql);
    }
    public function deshabilitarProyecto(){
        $idProyecto = $_GET['idProyecto'];
        $sql = "UPDATE proyectos set estatus='inactivo' where idProyecto='$idProyecto'";
        $this->conexion->getConexion()->query($sql);
    }

    public function obtenerProyecto(){
        $idProyecto = $_GET['idProyecto'];
        $sql = "SELECT nombre, descripcion,codigo FROM proyectos WHERE idProyecto = '$idProyecto'";
        $resultado = $this->conexion->getConexion()->query($sql);
        $fila = $resultado->fetch_assoc();
        $nombre = $fila['nombre'];
        $descripcion = $fila['descripcion'];
        $codigo = $fila['codigo'];
        $datos = array(
            "nombreProyecto" => $nombre,
            "descripcionProyecto" => $descripcion,
            "codigoProyecto" => $codigo
        );
        return $datos;

    }
    public function estaScrumMasterActivo($idProyecto){
        $sql = "SELECT COUNT(*) FROM integrantes WHERE idProyecto = '$idProyecto' AND rol = 'scrum master' AND estatus = 'activo'";
        $resultado = $this->conexion->getConexion()->query($sql);
        $fila = $resultado->fetch_array();
        return $fila[0] > 0;
    }
    public function deshabilitarRol($idUsuario,$idProyecto){
        // esta funciona lo puede usar unicamente el scrum master y lo que hace es poner el estatus del
        // scrum master a inactivo
        $sql = "UPDATE integrantes SET estatus = 'inactivo' WHERE idUsuario = '$idUsuario' AND idProyecto = '$idProyecto'";
        $this->conexion->getConexion()->query($sql);

    }
    public function editarProyecto($idProyecto,$nombre,$descripcion){
        $sql = "UPDATE proyectos SET nombre = '$nombre', descripcion = '$descripcion' WHERE idProyecto = '$idProyecto'";
        $this->conexion->getConexion()->query($sql);

    }
    public function estaProyectoActivo($codigoProyecto){
        $sql = "SELECT idProyecto,estatus FROM proyectos WHERE codigo = '$codigoProyecto'";
        $resultado = $this->conexion->getConexion()->query($sql);
        $fila = $resultado->fetch_assoc();
        $idProyecto = $fila['idProyecto'];
        $estatus = $fila['estatus'];
      
        if (!$this->estaScrumMasterActivo($idProyecto) || $estatus == 'inactivo'){
            return False;
        }
        return True;
       
    }

}
?>