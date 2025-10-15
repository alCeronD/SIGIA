<?php
// PRUEBA REALIZADA
use PHPUnit\Framework\TestCase;
use App\Config\Conection;
use App\Modules\ConfigModules\Model\ConfigModulesModel;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;

class TestEmptyData extends TestCase
{
    public string $sql = "INSERT INTO areas (ar_nombre, ar_descripcion, ar_status) VALUES (?, ?, ?)";
    public string $types = "ssi";
    public string $tableName = "areas";

    public static function validData(): array
    {
        return [
            [['Área de Producción', 'Zona con máquinas', 1]],
            [['Oficina Central', 'Área administrativa', 1]],
        ];
    }

    public static function invalidData(): array
    {
        return [
            [['Nombre válido', '   ', 1]],
            [['   ', 'Zona de almacenamiento', 0]],
        ];
    }

    #[DataProvider('validData')]
    public function testValidInsert(array $data): void
    {
        $configModules = new ConfigModulesModel();
        $response = $configModules->insert($this->sql, $this->types, $data, $this->tableName);
        $this->assertTrue($response['status']);
    }

    #[DataProvider('invalidData')]
    public function testInvalidInsert(array $data): void
    {
        $configModules = new ConfigModulesModel();
        $response = $configModules->insert($this->sql, $this->types, $data, $this->tableName);
        $this->assertFalse($response['status']);
        $this->assertStringContainsString('vacío', $response['message']);
    }
}