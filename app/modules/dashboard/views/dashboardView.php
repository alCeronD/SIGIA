        <div class="dashboard-grid">
            <div class="option-card">
                <h5>Consular Elementos</h5>
                <p>Contiene una Consulta de los Elementos.</p>
                <a class="btn-success" href="<?php echo getUrl("elementos", "elementos", "mostrarElementos", false, 'dashboard'); ?>">Consultar Elementos</a>
            </div>
            <div class="option-card">
                <h5>Configuraciones</h5>
                <p>Configuraciones poco recurrentes.</p>
                <a class="btn-secondary" href="<?php echo getUrl("configModules", "configModules", "renderViewArea", false, 'dashboard'); ?>">Areas</a>
                <a class="btn-secondary" href="<?php echo getUrl("configModules", "configModules", "renderViewTp", false, 'dashboard'); ?>">Tipo documento</a>
                <a class="btn-success" href="<?php echo getUrl("roles", "roles", "mostrarRoles", false, 'dashboard'); ?>" class="footer-icon text-center">Roles</a>

            </div>
            <div class="option-card">
                <h5>Prestamos de Elementos</h5>
                <p>Consulta los prestamos Actuales.</p>
              <a class="btn-secondary" href="<?php echo getUrl("reserva", "reserva", "reservaview", false, 'dashboard'); ?>">Ver prestamos</a>
            </div>
            <div class="option-card">
                <h5>Usuarios</h5>
                <p>crea o busca usuarios.</p>
              <a class="btn-success" href="<?php echo getUrl("usuarios", "usuarios", "userView", false, 'dashboard'); ?>">Crear usuario</a>
              <a class="btn-success" href="<?php echo getUrl("usuarios", "usuarios", "consultUser", false, 'dashboard'); ?>">Consultar usuario</a>
            </div>
        </div>


