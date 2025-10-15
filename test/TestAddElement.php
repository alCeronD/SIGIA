<?php 
// PRUEBA POR REALIZAR
use PHPUnit\Framework\TestCase;
use App\Config\Conection;
use App\Modules\Elementos\Model\ElementoModelo;

// Crear una prueba de que el elemento se agrega exitosamente.

$elementos = [
    [
        'elm_placa' => 1035433,
        'elm_serie' => '1035433-1',
        'elm_nombre' => 'Impresora HP LaserJet',
        'elm_existencia' => 1,
        'elm_sugerencia' => 'Uso exclusivo oficina',
        'elm_observacion' => null,
        'elm_uni_medida' => 1, // unidad
        'elm_cod_tp_elemento' => 1, // devolutivo
        'elm_cod_estado' => 1,
        'elm_area_cod' => 2,
        'elm_ma_cod' => 5
    ],
    [
        'elm_placa' => null,
        'elm_serie' => null,
        'elm_nombre' => 'Resma de papel tamaño carta',
        'elm_existencia' => 40,
        'elm_sugerencia' => null,
        'elm_observacion' => 'Almacenar en lugar seco',
        'elm_uni_medida' => 2, // caja
        'elm_cod_tp_elemento' => 2, // consumible
        'elm_cod_estado' => 1,
        'elm_area_cod' => 3, // área general
        'elm_ma_cod' => null
    ],
    [
        'elm_placa' => 1035434,
        'elm_serie' => '1035434-1',
        'elm_nombre' => 'Proyector Epson XGA',
        'elm_existencia' => 1,
        'elm_sugerencia' => 'Prestar con cable HDMI',
        'elm_observacion' => null,
        'elm_uni_medida' => 1,
        'elm_cod_tp_elemento' => 1,
        'elm_cod_estado' => 2,
        'elm_area_cod' => 4,
        'elm_ma_cod' => 3
    ],
    [
        'elm_placa' => null,
        'elm_serie' => null,
        'elm_nombre' => 'Marcadores permanentes',
        'elm_existencia' => 120,
        'elm_sugerencia' => null,
        'elm_observacion' => null,
        'elm_uni_medida' => 3, // unidad
        'elm_cod_tp_elemento' => 2,
        'elm_cod_estado' => 1,
        'elm_area_cod' => 3, // área general
        'elm_ma_cod' => null
    ],
    [
        'elm_placa' => 1035435,
        'elm_serie' => '1035435-1',
        'elm_nombre' => 'Scanner Canon DR-C240',
        'elm_existencia' => 1,
        'elm_sugerencia' => 'Solo para digitalización de archivos',
        'elm_observacion' => 'Revisar alimentación eléctrica',
        'elm_uni_medida' => 1,
        'elm_cod_tp_elemento' => 1,
        'elm_cod_estado' => 1,
        'elm_area_cod' => 5,
        'elm_ma_cod' => 4
    ]
];

class TestAddElement extends TestCase{
    
    public ElementoModelo $elementosModelo;
    public Conection $conn;

    public array $elements = [];

    protected function setUp(): void
    {
        $conn = new Conection();

        $this->elementosModelo = new ElementoModelo();

        $this->elements =  [
    [
        'elm_placa' => 1035433,
        'elm_serie' => '1035433-1',
        'elm_nombre' => 'Impresora HP LaserJet',
        'elm_existencia' => 1,
        'elm_sugerencia' => 'Uso exclusivo oficina',
        'elm_observacion' => null,
        'elm_uni_medida' => 1, // unidad
        'elm_cod_tp_elemento' => 1, // devolutivo
        'elm_cod_estado' => 1,
        'elm_area_cod' => 2,
        'elm_ma_cod' => 5
    ],
    [
        'elm_placa' => null,
        'elm_serie' => null,
        'elm_nombre' => 'Resma de papel tamaño carta',
        'elm_existencia' => 40,
        'elm_sugerencia' => null,
        'elm_observacion' => 'Almacenar en lugar seco',
        'elm_uni_medida' => 2, // caja
        'elm_cod_tp_elemento' => 2, // consumible
        'elm_cod_estado' => 1,
        'elm_area_cod' => 3, // área general
        'elm_ma_cod' => null
    ],
    [
        'elm_placa' => 1035434,
        'elm_serie' => '1035434-1',
        'elm_nombre' => 'Proyector Epson XGA',
        'elm_existencia' => 1,
        'elm_sugerencia' => 'Prestar con cable HDMI',
        'elm_observacion' => null,
        'elm_uni_medida' => 1,
        'elm_cod_tp_elemento' => 1,
        'elm_cod_estado' => 2,
        'elm_area_cod' => 4,
        'elm_ma_cod' => 3
    ],
    [
        'elm_placa' => null,
        'elm_serie' => null,
        'elm_nombre' => 'Marcadores permanentes',
        'elm_existencia' => 120,
        'elm_sugerencia' => null,
        'elm_observacion' => null,
        'elm_uni_medida' => 3, // unidad
        'elm_cod_tp_elemento' => 2,
        'elm_cod_estado' => 1,
        'elm_area_cod' => 3, // área general
        'elm_ma_cod' => null
    ],
    [
        'elm_placa' => 1035435,
        'elm_serie' => '1035435-1',
        'elm_nombre' => 'Scanner Canon DR-C240',
        'elm_existencia' => 1,
        'elm_sugerencia' => 'Solo para digitalización de archivos',
        'elm_observacion' => 'Revisar alimentación eléctrica',
        'elm_uni_medida' => 1,
        'elm_cod_tp_elemento' => 1,
        'elm_cod_estado' => 1,
        'elm_area_cod' => 5,
        'elm_ma_cod' => 4
    ]
    ];

    }

    public function testAddElement(){
        $response = $this->elementosModelo->insertarElemento($this->elements);

        $this->assertIsArray($response,'Arreglo correcto');
    }
}

?>