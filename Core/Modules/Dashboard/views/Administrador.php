<div class="container">
    <div class="content">
        <!-- AREAS, ROLES, TIPO DOCUMENTO, USUARIOS, MARCAS -->
        <?php
        $nameModules = [];
        foreach ($_SESSION['renderMenu']['modulos'] as $key => $value) {
            $nameModules[] = $value['nombreModulo'];
        }
        ?>
        <!-- elementos -->
        <div class="cardModules">
            <div class="contentModule">
                <div class="iconModule">
                    <a class="linkIcon" href="<?php echo Router::createRoute('Elementos', 'Elementos', 'renderViewElements', false, 'dashboard'); ?>">
                        <i class="material-icons green-text text-darken-2">camera_alt
                        </i>
                    </a>
                </div>
                <div class=" nameModule">Elementos
                </div>
            </div>
        </div>
        <!-- areas -->
        <div class="cardModules">
            <div class="contentModule">
                <div class="iconModule">
                    <a class="linkIcon" href="<?php echo Router::createRoute(CR_AREAS, CR_AREAS, 'renderViewArea', false, CR_DASHBOARD_LOWER_CASE); ?>">
                        <i class="material-icons green-text text-darken-2">storage
                        </i>
                    </a>
                </div>
                <div class=" nameModule">Areas
                </div>
            </div>
        </div>
        <div class="cardModules">
            <div class="contentModule">
                <div class="iconModule">
                    <a class="linkIcon" href="<?php echo Router::createRoute('Permisos', 'Permisos', 'permisosIndexView', false, CR_DASHBOARD_LOWER_CASE); ?>">
                        <i class="material-icons green-text text-darken-2">security
                        </i>
                    </a>
                </div>
                <div class=" nameModule">Permisos
                </div>
            </div>
        </div>
        <div class="cardModules">
            <div class="contentModule">
                <div class="iconModule">
                    <a class="linkIcon" href="<?php echo Router::createRoute(CR_TIPO_DOCUMENTO_NAME_MODULE, CR_TIPO_DOCUMENTO_NAME_MODULE, 'renderViewTp', false, CR_DASHBOARD_LOWER_CASE); ?>">
                        <i class="material-icons green-text text-darken-2">badge
                        </i>
                    </a>
                </div>
                <div class=" nameModule">Tipo documento
                </div>
            </div>
        </div>
        <div class="cardModules">
            <div class="contentModule">
                <div class="iconModule">
                    <a class="linkIcon" href="<?php echo Router::createRoute(CR_MARCAS, CR_MARCAS, 'renderViewMarca', false, CR_DASHBOARD_LOWER_CASE); ?>">
                        <i class="material-icons green-text text-darken-2">branding_watermark

                        </i>

                    </a>
                </div>
                <div class=" nameModule">Marcas
                </div>
            </div>
        </div>


        <!-- <div class="cardModules">
            <div class="icon">aca va icono</div>
            <div class="nameModule">aca va name</div>
        </div>
        <div class="cardModules">
            <div class="icon">aca va icono</div>
            <div class="nameModule">aca va name</div>
        </div>
        <div class="cardModules">
            <div class="icon">aca va icono</div>
            <div class="nameModule">aca va name</div>
        </div> -->

        <!-- <div class="option-card z-depth-1 div1">
            <div class="icons">
                <i class="material-icons small green-text text-darken-2 center-align">camera_alt</i>
            </div>
            <div class="modalName">
                <h5>Consultar Elementos</h5>
                <p>Contiene una consulta de los elementos.</p>
            </div>
            <div class="buttons">
                <a class="btn green btnGetUrl" href="<?php //echo Router::createRoute('Elementos', 'Elementos', 'renderViewElements', false, 'dashboard');
                                                        ?>">Consultar</a>
            </div>
        </div> -->

        <!-- <div class="option-card z-depth-1 div2">
            <div class="icons">
                <i class="material-icons small grey-text text-darken-2 center-align">assignment</i>
            </div>
            <div class="modalName">
                <h5>Préstamos de Elementos</h5>
                <p>Consulta los préstamos actuales.</p>
            </div>
            <div class="buttons">
                <a class="btn grey btnGetUrl" href="<?php //echo Router::createRoute('ReservaPrestamos', 'ReservaPrestamos', 'consultaReservaView', false, 'dashboard');
                                                    ?>">Ver reservas</a>
                <a class="btn green btnGetUrl" href="<?php //echo Router::createRoute('ReservaPrestamos', 'ReservaPrestamos', 'reservaView', false, 'dashboard');
                                                        ?>">Crear prestamo o reserva</a>
            </div>
        </div> -->
        <!-- <div class="option-card z-depth-1 div3">
            <div class="icons">
                <a href="<?php //echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'usuariosIndexView', false, CR_DASHBOARD_LOWER_CASE);
                            ?>">
                    <i class="material-icons small green-text text-darken-2 center-align">person</i>
                </a>
            </div>
            <div class="modalName">
                <h5>Usuarios</h5>
                <p>Crea o busca usuarios.</p>
            </div>
            <div class="buttons">
                <a class="btn green btnGetUrl" href="<?php //echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'usuariosIndex', false, CR_DASHBOARD_LOWER_CASE);
                                                        ?>">Crear usuario</a>
                <a class="btn grey btnGetUrl" href="<?php //echo Router::createRoute(CR_USUARIOS, CR_USUARIOS, 'consultUser', false, CR_DASHBOARD_LOWER_CASE);
                                                    ?>">Consultar usuario</a>
            </div>
        </div> -->
        <!-- <div class="option-card z-depth-1 div4">
            <div class="icons">
                <i class="material-icons small grey-text text-darken-2 center-align">settings</i>
            </div>
            <div class="modalName">
                <h5>Configuraciones</h5>
                <p>Configuraciones poco recurrentes.</p>
            </div>
            <div class="buttons">
                <a class="btn grey btnGetUrl" href="<?php //echo Router::createRoute(CR_AREAS, CR_AREAS, 'renderViewArea', false, CR_DASHBOARD_LOWER_CASE);
                                                    ?>">Áreas</a>
                <a class="btn green btnGetUrl" href="<?php //echo Router::createRoute('Tipodocumento', 'Tipodocumento', 'renderViewTp', false, CR_DASHBOARD_LOWER_CASE);
                                                        ?>">Tipo documento</a>
                <a class="btn grey btnGetUrl" href="<?php //echo Router::createRoute(CR_ROLES, CR_ROLES, 'rolesIndex', false, CR_DASHBOARD_LOWER_CASE);
                                                    ?>">Roles</a>
                <a class="btn green btnGetUrl" href="<?php //echo Router::createRoute(CR_MARCAS, CR_MARCAS, 'renderViewMarca', false, CR_DASHBOARD_LOWER_CASE);
                                                        ?>">Marcas</a>
                <a class="btn green btnGetUrl" href="<?php //echo Router::createRoute('Generalcrud', 'Generalcrud', 'renderGeneralView', false, CR_DASHBOARD_LOWER_CASE);
                                                        ?>">GeneralCrud</a>
                <a class="btn green btnGetUrl" href="<?php //echo Router::createRoute('Permisos', 'Permisos', 'permisosIndexView', false, CR_DASHBOARD_LOWER_CASE);
                                                        ?>">Seguridad del sistema</a>

            </div>
        </div> -->

    </div>
</div>