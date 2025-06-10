
    <style>
        

       .header {
           color: #f0f0f0;            /* Texto claro */
           font-weight: 700;
           font-size: 1.8rem;
           padding: 20px 0;
           text-align: center;
           font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen,
           Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
           box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
           user-select: none;
           background-color: #2c5e42; /* Verde oscuro estilo Mac */
           
        }

        body {
            background-color: #f5f5f5;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen,
                Ubuntu, Cantarell, "Open Sans", "Helvetica Neue", sans-serif;
            margin: 0;
            padding: 0;
        }
        
        .dashboard-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 15px;
        }
        .option-card {
            background: #d9d9d9;
            border-radius: 10px;
            padding: 15px;
            flex: 1 1 calc(50% - 20px);
            box-sizing: border-box;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease;
        }
        .option-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.2);
        }
        a {
            cursor: pointer;
            padding: 8px 15px;
            border-radius: 6px;
            border: none;
            margin: 5px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: background-color 0.3s ease;
        }
        .btn-dark {
            background-color: #3a3a3a;
            color: #fff;
        }
        .btn-dark:hover {
            background-color: #2a2a2a;
        }
        .btn-light {
            background-color: #e0e0e0;
            color: #333;
        }
        .btn-light:hover {
            background-color: #cfcfcf;
        }
        .btn-warning {
            background-color: #f0ad4e;
            color: #fff;
        }
        .btn-warning:hover {
            background-color: #d18c22;
        }
        .btn-success {
            background-color: #5cb85c;
            color: #fff;
        }
        .btn-success:hover {
            background-color: #449d44;
        }
        .btn-secondary {
            background-color: #6c757d;
            color: #fff;
        }
        .btn-secondary:hover {
            background-color: #565e64;
        }
        
    </style>
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


