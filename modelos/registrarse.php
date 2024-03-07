<?php 
$rutaDirectorio = dirname(__FILE__);

include "$rutaDirectorio/conexion.php";

class Registrarse{
    private $conexion;
    public function __construct(){
        $this->conexion = new Conexion();
    }
    public function registrarUsuario($nombre, $apellido, $edad, $genero, $usuario, $contrasena, $email,$codigo){
        $emailValidado = "0";
        $stmt = $this->conexion->getConexion()->prepare("INSERT INTO usuarios(nombre, apellido, edad, sexo, correo,usuario ,contrasena,codigoEmail,emailValidado) VALUES (?, ?, ?, ?, ?, ?, ?,?,?)");
        $stmt->bind_param("ssissssss", $nombre, $apellido, $edad, $genero, $email, $usuario, $contrasena,$codigo,$emailValidado);
        $stmt->execute();
        if($stmt->affected_rows == -1){
            $stmt->close();
            return "0";

        }

        $stmt->close();
        
        return "1";

    }

}
?>