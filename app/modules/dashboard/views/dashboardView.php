<?php $rol_id = $_SESSION['usuario']['rol_id']; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

<div class="content">

    <?php if ($rol_id == 2): ?>
        <div class="admin">
            <div class="option-card z-depth-1 div1">
                <i class="material-icons large green-text text-darken-2 center-align">search</i>
                <h5>Consultar Elementos</h5>
                <p>Contiene una consulta de los elementos.</p>
                <a class="btn green btnGetUrl" href="<?php echo getUrl('elementos', 'Elementos', 'renderViewElements', false, 'dashboard'); ?>">Consultar</a>
            </div>
            <div class="option-card z-depth-1 div2">
                <i class="material-icons large grey-text text-darken-2 center-align">settings</i>
                <h5>Configuraciones</h5>
                <p>Configuraciones poco recurrentes.</p>
                <a class="btn grey btnGetUrl" href="<?php echo getUrl('configModules', 'configModules', 'renderViewArea', false, 'dashboard'); ?>">Áreas</a>
                <a class="btn grey btnGetUrl" href="<?php echo getUrl('configModules', 'configModules', 'renderViewTp', false, 'dashboard'); ?>">Tipo documento</a>
                <a class="btn green btnGetUrl" href="<?php echo getUrl('Roles', 'roles', 'mostrarRoles', false, 'dashboard'); ?>">Roles</a>
            </div>
            <div class="option-card z-depth-1 div3">
                <i class="material-icons large grey-text text-darken-2 center-align">assignment</i>
                <h5>Préstamos de Elementos</h5>
                <p>Consulta los préstamos actuales.</p>
                <a class="btn grey btnGetUrl" href="<?php echo getUrl('reservaPrestamos', 'reservaPrestamos', 'consultaReservaView', false, 'dashboard'); ?>">Ver reservas</a>
            </div>
            <div class="option-card z-depth-1 div4">
                <i class="material-icons large green-text text-darken-2 center-align">person</i>
                <h5>Usuarios</h5>
                <p>Crea o busca usuarios.</p>
                <a class="btn green btnGetUrl" href="<?php echo getUrl('usuarios', 'usuarios', 'userView', false, 'dashboard'); ?>">Crear usuario</a>
                <a class="btn green btnGetUrl" href="<?php echo getUrl('usuarios', 'usuarios', 'consultUser', false, 'dashboard'); ?>">Consultar usuario</a>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($rol_id == 4): ?>
        <div class="instruc">
            <div class="instruc-grid">
                <div class="option-card z-depth-1">
                    <i class="material-icons large green-text text-darken-2 center-align">assignment</i>
                    <h5>Solicitar Préstamo</h5>
                    <p>Realiza la solicitud de préstamo de elementos.</p>
                    <a class="btn green btnGetUrl" href="<?php echo getUrl('solicitudPrestamos', 'solicitudPrestamos', 'registrarPrestamosView', false, 'dashboard'); ?>">Solicitar préstamo</a>
                </div>

                <div class="option-card z-depth-1">
                    <i class="material-icons large grey-text text-darken-2 center-align">visibility</i>
                    <h5>Ver Préstamos</h5>
                    <p>Consulta el estado de tus préstamos.</p>
                    <a class="btn grey btnGetUrl" href="<?php echo getUrl('solicitudPrestamos', 'solicitudPrestamos', 'consultarPrestamosView', false, 'dashboard'); ?>">Ver préstamos</a>
                </div>
            </div>
            <div class="table-content">
                <div class="section">
                    <div class="card">
                        <div class="card-content">
                            <span class="card-title">Prestamos solicitados</span>
                            <table class="highlight responsive-table">
                                <thead>
                                    <tr>
                                        <th>Código de Solicitud</th>
                                        <th>Solicitante</th>
                                        <th>Fecha de Reserva</th>
                                        <th>Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($prestamos)): ?>
                                        <?php foreach ($prestamos as $prestamo): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($prestamo['codigoSolicitud']) ?></td>
                                                <td><?= htmlspecialchars($nombreCompleto) ?></td>
                                                <td><?= htmlspecialchars($prestamo['fechaReserva']) ?></td>
                                                <td><?= htmlspecialchars($prestamo['estadoNombre']) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="4" class="center-align grey-text text-darken-2">
                                                No hay préstamos registrados.
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php  if ($rol_id == 16): ?>
        <div class="coordinador">
            <div class="option-card z-depth-1">
                <i class="material-icons large green-text text-darken-2 center-align">assignment</i>
                <h5>Reportes</h5>
                <p>Generar reportes</p>
                <a class="btn green btnGetUrl" href="<?php echo getUrl('reportes', 'reportes', 'genReporteView', false, 'dashboard'); ?>">Reportes</a>
            </div>

        </div>
    <?php endif; ?>

</div>

<script type="module" src="../public/assets/js/dashboard/dashboard.js"></script>