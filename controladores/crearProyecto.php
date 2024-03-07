<?php
    $rutaDirectorio = dirname(__FILE__);

include "$rutaDirectorio/../modelos/proyecto.php";
include "$rutaDirectorio/../vistas/crearProyecto.php";

if (isset($_POST["nombreProyecto"]) && isset($_POST["descripcionProyecto"])) {
    if (empty($_POST["nombreProyecto"]) || empty($_POST["descripcionProyecto"])) {
        echo "los campos no pueden estar vacios";
        return;
    }
    $nombreProyecto = $_POST["nombreProyecto"];
    $descripcionProyecto = $_POST["descripcionProyecto"];
    $proyecto = new Proyectos();
    $codigoProyecto = $proyecto->crearProyecto($nombreProyecto, $descripcionProyecto);
?>

 <div class="offcanvas offcanvas-start show" data-bs-backdrop="static" tabindex="-1" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title" id="staticBackdropLabel">Crear Proyecto</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
          <div>
            <h2>Proyecto Creado Exitosamente</h2>
            <p>Comparte el siguiente código para que los miembros puedan unirse: </p>
            <p class="codigoProyecto"><?php echo $codigoProyecto ?> </p>
            <button type="button" class="btn btn-primary" onclick="copyToClipboard(<?php echo $codigoProyecto?>)" data-bs-toggle="popover" data-bs-container="body" data-bs-placement="right" data-bs-content="Copiado!">Copiar al portapapeles</button>

          </div>
        </div>
      </div>
<?php
}

?>