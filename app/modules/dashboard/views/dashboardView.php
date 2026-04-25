<?php $rol_id = $_SESSION['usuario']['rol_id']; ?>
<!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"> -->

<div class="content">

    <?php if ($rol_id == 2): ?>
        <div class="admin">
            <div class="option-card z-depth-1 div1">
                <div class="icons">
                    <i class="material-icons small green-text text-darken-2 center-align">camera_alt</i>
                </div>
                <div class="modalName">
                    <h5>Consultar Elementos</h5>
                    <p>Contiene una consulta de los elementos.</p>
                </div>
                <div class="buttons">
                    <a class="btn green btnGetUrl" href="<?php echo getUrl('elementos', 'elementos', 'renderViewElements', false, 'dashboard'); ?>">Consultar</a>
                </div>
            </div>

            <div class="option-card z-depth-1 div2">
                <div class="icons">
                    <i class="material-icons small grey-text text-darken-2 center-align">assignment</i>
                </div>
                <div class="modalName">
                    <h5>Préstamos de Elementos</h5>
                    <p>Consulta los préstamos actuales.</p>
                </div>
                <div class="buttons">
                    <a class="btn grey btnGetUrl" href="<?php echo getUrl('reservaPrestamos', 'reservaPrestamos', 'consultaReservaView', false, 'dashboard'); ?>">Ver reservas</a>
                    <a class="btn green btnGetUrl" href="<?php echo getUrl('reservaPrestamos', 'reservaPrestamos', 'reservaView', false, 'dashboard'); ?>">Crear prestamo o reserva</a>
                </div>
            </div>
            <div class="option-card z-depth-1 div3">
                <div class="icons">
                    <i class="material-icons small green-text text-darken-2 center-align">person</i>
                </div>
                <div class="modalName">
                    <h5>Usuarios</h5>
                    <p>Crea o busca usuarios.</p>
                </div>
                <div class="buttons">
                    <a class="btn green btnGetUrl" href="<?php echo getUrl('usuarios', 'usuarios', 'userView', false, 'dashboard'); ?>">Crear usuario</a>
                    <a class="btn grey btnGetUrl" href="<?php echo getUrl('usuarios', 'usuarios', 'consultUser', false, 'dashboard'); ?>">Consultar usuario</a>
                </div>
            </div>
            <div class="option-card z-depth-1 div4">
                <div class="icons">
                    <i class="material-icons small grey-text text-darken-2 center-align">settings</i>
                </div>
                <div class="modalName">
                    <h5>Configuraciones</h5>
                    <p>Configuraciones poco recurrentes.</p>
                </div>
                <div class="buttons">
                    <a class="btn grey btnGetUrl" href="<?php echo getUrl('configModules', 'configModules', 'renderViewArea', false, 'dashboard'); ?>">Áreas</a>
                    <a class="btn green btnGetUrl" href="<?php echo getUrl('configModules', 'configModules', 'renderViewTp', false, 'dashboard'); ?>">Tipo documento</a>
                    <a class="btn grey btnGetUrl" href="<?php echo getUrl('roles', 'roles', 'mostrarRoles', false, 'dashboard'); ?>">Roles</a>
                    <a class="btn green btnGetUrl" href="<?php echo getUrl('configModules', 'configModules', 'renderViewMarca', false, 'dashboard'); ?>">Marcas</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($rol_id == 4 || $rol_id == 12): ?>
        <div class="instruc">
            <div class="instruc-cards">
                <div class="option-card z-depth-1">
                    <div class="icons">
                        <i class="material-icons small green-text text-darken-2 center-align">assignment</i>
                    </div>
                    <div class="modalName">
                        <h5>Solicitar Préstamo</h5>
                        <p>Realiza la solicitud de préstamo de elementos.</p>
                    </div>
                    <div class="buttons">
                        <a class="btn green btnGetUrl" href="<?php echo getUrl('solicitudPrestamos', 'solicitudPrestamos', 'registrarPrestamosView', false, 'dashboard'); ?>">Solicitar</a>
                    </div>
                </div>

                <div class="option-card z-depth-1">
                    <div class="icons">
                        <i class="material-icons small grey-text text-darken-2 center-align">visibility</i>
                    </div>
                    <div class="modalName">
                        <h5>Ver Préstamos</h5>
                        <p>Consulta el estado de tus préstamos.</p>
                    </div>
                    <div class="buttons">
                        <a class="btn grey btnGetUrl" href="<?php echo getUrl('solicitudPrestamos', 'solicitudPrestamos', 'consultarPrestamosView', false, 'dashboard'); ?>">Ver préstamos</a>
                    </div>
                </div>
            </div>
            <div class="table-content">
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
    <?php endif; ?>

    <?php if ($rol_id == 16): ?>
        <div class="coordinador">
            <div class="option-card z-depth-1">
                <div class="icons">
                    <i class="material-icons small green-text text-darken-2 center-align">assignment</i>
                </div>
                <div class="modalName">
                    <h5>Reportes</h5>
                    <p>Generar reportes</p>
                </div>
                <div class="buttons">
                    <a class="btn green btnGetUrl" href="<?php echo getUrl('reportes', 'reportes', 'genReporteView', false, 'dashboard'); ?>">Reportes</a>
                </div>
            </div>
        </div>
    <?php endif; ?>

</div>

<script type="module" src="../public/assets/js/dashboard/dashboard.js"></script>