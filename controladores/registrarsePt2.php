<?php
    $rutaDirectorio = dirname(__FILE__);

    include "$rutaDirectorio/../modelos/registrarse.php";
    include "$rutaDirectorio/../vistas/registrarsePt2.html";
    include "$rutaDirectorio/../modelos/PHPMailer/enviarCorreos.php";

if (isset($_POST["nombre"]) && isset($_POST["apellido"]) && isset($_POST["edad"]) && isset($_POST["genero"])) {

    // if($_POST['edad'])

    $nombre = $_POST["nombre"];
    $apellido = $_POST["apellido"];
    $edad = $_POST["edad"];
    $genero = $_POST["genero"];
    session_start();

    $_SESSION["nombre"] = $nombre;
    $_SESSION["apellido"] = $apellido;
    $_SESSION["edad"] = $edad;
    $_SESSION["genero"] = $genero;

}
   
if(isset($_POST["usuario"]) && isset($_POST["contrasena"])  && isset($_POST["email"])){
    $usuario = $_POST["usuario"];
    $contrasena = $_POST["contrasena"];
    $email = $_POST["email"];

    session_start();
    $nombre = $_SESSION["nombre"];
    $apellido = $_SESSION["apellido"];
    $edad = $_SESSION["edad"];
    $genero = $_SESSION["genero"];
    
    $_SESSION['usuario'] = $usuario;
    
        $codigo = rand(1000, 9999);

    $registrarse = new Registrarse();
    $exitoRegistro = $registrarse->registrarUsuario($nombre, $apellido, $edad, $genero, $usuario, $contrasena, $email,$codigo);
    
    if($exitoRegistro == "0"){
        echo "<div class='mensaje-error'>El nombre de usuario o el correo electronico ya esta en uso</div>";
    }
    if($exitoRegistro == "1"){
        header("Location: alertaEmail.php");
        $destinatario = $email;
        $subject = "Validar email";
        // Para validar tu correo electrónico, por favor ingresa el siguiente código:
        $body = "Para validar tu correo electrónico, por favor ingresa el siguiente código: $codigo";
        enviarCorreo($email,$subject,$body);


    }






}

    
?>