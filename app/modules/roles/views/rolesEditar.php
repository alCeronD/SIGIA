<<<<<<< HEAD

<?php
// session_start();
include_once '../proyecto_sigia/app/helpers/session.php';
include_once '../proyecto_sigia/public/partials/header.php';
if (!isset($_SESSION['usuario'])) {
    header("Location: /proyecto_sigia/index.php");
    exit();
}


?>

=======
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Editar Rol</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
>>>>>>> 751a1fa (edward_commit)
<div class="container mt-5">
    <h2 class="mb-4 text-center">Editar Rol</h2>

    <form action="index.php?action=rolesEditar" method="POST">
        <input type="hidden" name="rol_id" value="<?php echo $rol_actual['rl_id']; ?>">

        <div class="mb-3">
            <label for="rol_nombre" class="form-label">Nombre del Rol</label>
            <input type="text" class="form-control" id="rol_nombre" name="rol_nombre" value="<?php echo $rol_actual['rl_nombre']; ?>" required>
        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-warning">Actualizar</button>
        </div>
    </form>
</div>
<<<<<<< HEAD

<?php
include_once '../proyecto_sigia/public/partials/footer.php';

?>
=======
</body>
</html>
>>>>>>> 751a1fa (edward_commit)
