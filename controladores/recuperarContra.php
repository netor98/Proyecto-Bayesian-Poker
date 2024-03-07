<?php 
    $rutaDirectorio = dirname(__FILE__);

include "$rutaDirectorio/../vistas/recuperarContra.html";
include "$rutaDirectorio/../modelos/registrarCodigo.php";
include "$rutaDirectorio/../modelos/PHPMailer/enviarCorreos.php";

if (isset($_POST['email'])) {
    $email = $_POST['email'];
    $codigo = rand(100000, 999999);

    $regCod = new RegistrarCodigo();
    $codigoRegistrado = $regCod->registrarCodigo($codigo, $email);
    
    if($codigoRegistrado){
        // 
        $destinatario = $email;
        $subject = "Recuperar contraseña";
        $baseURL = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $body = "Para recuperar su contraseña haga click en el siguiente enlace: <a href='$baseURL/recuperarContra2.php?correo=$email&codigo=$codigo'>recuperar contra<a/>";
        
        $respuesta = enviarCorreo($email,$subject,$body);
       
        if($respuesta){
            echo "<div class='mensaje-exito'>Se ha enviado el correo exitosamente</div>";

        }else{
            echo "<div class='mensaje-error'>El correo no se pudo enviar</div>";
        }
    }else{
        echo "<div class='mensaje-exito'>Se ha enviado el correo exitosamente</div>";
   
    }
    
    


  
}
?>