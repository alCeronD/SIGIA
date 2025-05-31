<?php

include_once __DIR__ . '/../../../helpers/renderView.php';
//include_once __DIR__ . '/../model/getElements.php';

class SolicitudPrestamoController
{

    /**
     * Summary of prestamos
     * Función que me permitirá renderizar la vista de prestamos.
     * @return void
     */
    public static function prestamos(String $vista = '')
    {

        /**
         * 
         * Clase que permite buscar la ruta y devolverla para renderizarla en base al controlador.
         */
        $render = new RenderView();
        if (!isset($render)) return;

        //var_dump($render);
        //Este renderizado de vista es exclusivo del modulo de PRESTAMOS
        switch ($vista) {
            case 'solicitud':
                require_once $render->renderView('solicitudPrestamos', 'solicitudPrestamosView.php');
                //Renderizar también el footer.
                //var_dump($render->renderView('solicitudPrestamos', 'solicitudPrestamosView.php'));

                break;
            case 'consulta':
                require_once $render->renderView('solicitudPrestamos', 'consultarSolicitudView.php');
                break;
            default:
                // en caso de que no se envie algun parámetro, lo ideal sería mostrar el index principal.
                break;
        }

    }

    public function solicitudPrestamosView(){


        return include_once __DIR__ . '/../views/solicitudPrestamosView.php';
    }
    public function consultarPrestamoViews(){

        return include_once __DIR__ . '/../views/consultarSolicitudView.php';
    }

    
}
