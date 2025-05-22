<?php

include_once __DIR__ . '/../../../helpers/renderView.php';
//include_once __DIR__ . '/../model/getElements.php';

class solicitudController
{

    /**
     * Summary of prestamos
     * Función que me permitirá renderizar la vista de prestamos.
     * @return void
     */
    public static function prestamos(String $vista = '')
    {

        $render = new RenderView();
        if (!isset($render)) return;

        //Este renderizado de vista es exclusivo del modulo de PRESTAMOS
        switch ($vista) {
            case 'solicitud':
                $render::renderView('solicitudPrestamos', 'solicitudPrestamosView.php');
                //Renderizar también el footer.

                break;
            case 'consulta':
                $render::renderView('solicitudPrestamos', 'consultarSolicitudView.php');
                break;
            default:
                // en caso de que no se envie algun parámetro, lo ideal sería mostrar el index principal.
                break;
        }
    }

    // Función para renderizar la vista del modulo de configuración.
    public static function configModule($vista)
    {
        $render = new RenderView();
        if (!isset($render)) return;


        switch ($vista) {
            case 'solicitud':
                $render::renderView('solicitudPrestamosView.php', 'solicitudPrestamos');
                //Renderizar también el footer.

                break;
            default:
                // en caso de que no se envie algun parámetro, lo ideal sería mostrar el index principal.
                break;
        }
    }
}
