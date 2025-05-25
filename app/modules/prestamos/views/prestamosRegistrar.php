<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h3 class="mb-4">Registrar Préstamo</h3>
    <form method="POST" action="">
        <div class="mb-3">
            <label>Fecha de Solicitud</label>
            <input type="date" name="pres_fch_slcitud" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Fecha de Reserva</label>
            <input type="date" name="pres_fch_reserva" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Fecha de Entrega</label>
            <input type="date" name="pres_fch_entrega" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Observación</label>
            <textarea name="pres_observacion" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label>Destino</label>
            <input type="text" name="pres_destino" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Estado</label>
            <input type="number" name="pres_estado" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Código de Reserva</label>
            <input type="number" name="res_cod" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Préstamo</button>
    </form>
</div>
