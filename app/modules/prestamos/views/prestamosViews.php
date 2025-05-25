<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="container mt-4">
    <h3 class="mb-4">Lista de Préstamos</h3>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>Código</th>
                <th>Fecha Solicitud</th>
                <th>Fecha Reserva</th>
                <th>Fecha Entrega</th>
                <th>Observación</th>
                <th>Destino</th>
                <th>Estado</th>
                <th>Código Reserva</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($prestamos)): ?>
                <?php foreach ($prestamos as $pres): ?>
                    <tr>
                        <td><?php echo $pres['pres_cod']; ?></td>
                        <td><?php echo $pres['pres_fch_slcitud']; ?></td>
                        <td><?php echo $pres['pres_fch_reserva']; ?></td>
                        <td><?php echo $pres['pres_fch_entrega']; ?></td>
                        <td><?php echo $pres['pres_observacion']; ?></td>
                        <td><?php echo $pres['pres_destino']; ?></td>
                        <td><?php echo $pres['pres_estado']; ?></td>
                        <td><?php echo $pres['res_cod']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="8" class="text-center">No hay registros de préstamos.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
