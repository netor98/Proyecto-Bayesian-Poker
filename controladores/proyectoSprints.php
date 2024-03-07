<?php
    $rutaDirectorio = dirname(__FILE__);

include "$rutaDirectorio/../modelos/sprint.php";
include "$rutaDirectorio/../modelos/proyecto.php";
include "$rutaDirectorio/../modelos/Usuario.php";

$sprintClase = new Sprint();
$sprints = $sprintClase->obtenerSprints();
$proyectoClase = new Proyectos();
$idProyecto = $_GET['idProyecto'];
$nombreProyecto  = $proyectoClase->obtenerNombreProyecto($idProyecto);
$claseUsuario = new Usuario();

$rol = $claseUsuario->obtenerRol($idProyecto);

include "$rutaDirectorio/../vistas/proyectoSprints.php";

?>