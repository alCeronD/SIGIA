<?php 
// Archivo para ejecutar las tareas automáticas con relación al modulo de reservas.
require_once __DIR__ . '/../model/reservaModel.php';

/**
 * Summary of ServicesReservas - Clase que me permite ejecutar tareas programadas con relación al modulo de reserva, este proceso aplico el valor final para indicar que esta clase no debe ser heredada
 */
final class ServicesReservas {

    private ReservaModel $ModeloReserva;
    public function __construct() {
        
        $this->ModeloReserva = new ReservaModel();
    }

    /**
     * Summary of callTask - Función para ejecutar las táreas programadas
     * @return void
     */
    public function callTask(){
        $this->ModeloReserva->cancelarPrestamosFecha();
    }
}


?>