<?php
    $rutaDirectorio = dirname(__FILE__);

include "$rutaDirectorio/../modelos/Usuario.php";
include "$rutaDirectorio/../modelos/historia.php";

$usuario = new Usuario();
$idProyecto = $_GET['idProyecto'];
$idHistoria = $_GET['idHistoria'];
$rolUsuario = $usuario->obtenerRol($idProyecto);
$historia= new Historia();

if(isset($_POST['decisionScrumMaster'])){
    if(!$historia->todosVotaron($idHistoria)){
        echo "Aun faltan miembros por votar";
    }else{
        $valor = $_POST['valorVotoScrumMaster'];
        $historia->votarHistoriaScrumMaster($idHistoria,$valor);
        $idSprint = $_GET['idSprint'];
        $notificacion = new Notificaciones();
        $notificacion->historiaAceptada($idProyecto,$idSprint,$idHistoria);    
    }
    
    header("Location: votar.php?idProyecto=$idProyecto&idSprint=$idSprint&idHistoria=$idHistoria");
}

if(isset($_POST['agregarRonda'])){
    $idSprint = $_GET['idSprint'];
    if($historia->todosVotaron($idHistoria)){
        $historia->crearRonda($idProyecto,$idSprint,$idHistoria);
        header("Location: verSprint.php?idProyecto=$idProyecto&idSprint=$idSprint&estatus=activo");
        exit();
    }else{
            echo "<div class='mensaje-error'>Aun faltan miembros por votar</div>";
    }
}
if(isset($_POST['asignarPromedio'])){
    if(!$historia->todosVotaron($idHistoria)){
        echo "<div class='mensaje-error'>Aun faltan miembros por votar</div>";

    }else{
        $historia->asignarPromedioDeTodasLasVotaciones($idHistoria);
        $notificacion = new Notificaciones();
        $idSprint = $_GET['idSprint'];
        $notificacion->historiaAceptada($idProyecto,$idSprint,$idHistoria);
        $idSprint = $_GET['idSprint'];    
    
        header("Location: verSprint.php?idProyecto=$idProyecto&idSprint=$idSprint&estatus=activo");
        exit();
    }

}
// guardarHistoria
if(isset($_POST['guardarHistoria'])){
    $nombre = $_POST['nombreHistoria'];
    $descripcion = $_POST['descripcionHistoria'];
    $historia->actualizarHistoria($idHistoria,$nombre,$descripcion);
    
    header("Location: votar.php?idProyecto=" . $_GET['idProyecto'] . "&idSprint=" . $_GET['idSprint'] . "&idHistoria=" . $_GET['idHistoria']);
}

if($historia->esHistoriaAceptada($idHistoria)){
    $datosHistoria = $historia->obtenerHistoria($idHistoria);
    $valorHistoria = $historia->obtenerValorHistoria($idHistoria);
    include "$rutaDirectorio/../vistas/votacionTerminada.php";
}
else if($rolUsuario == 'scrum master'){


    include "$rutaDirectorio/../vistas/votarScrumMaster.php";
    
}else{
    if(isset($_COOKIE['puntaje']) && isset($_POST['motivoHistoria'])){

        $puntaje = $_COOKIE['puntaje'];
        $motivoHistoria = $_POST['motivoHistoria'];
        // convertir a entero
        $puntaje = intval($puntaje);
        $historia->votarHistoria($idHistoria,$puntaje,$motivoHistoria);
        setcookie("puntaje", "", time() - 3600);
        

    }
    

    include "$rutaDirectorio/../vistas/votarMiembro.php";
    
    
}

?>