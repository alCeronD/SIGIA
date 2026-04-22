<?php

// incluyo la clase para usar el renderizado de la vista.
require_once __DIR__ . '/../model/configModulesModel.php';
require_once __DIR__ . '/../../../helpers/validatePermisos.php';

class ConfigModulesController
{
    private $configModel;

    private $render;

    private $module;
    private $file;

    public function __construct() {}

    //Me devuelve la ruta de la vista del modulo de areas.
    public function renderViewArea()
    {
        return include_once  __DIR__ . '/../areas/views/areaView.php';
    }
    public function renderViewTp()
    {

        return include_once  __DIR__ . '/../tipoDocumento/views/tpDocumentoView.php';
    }

    public function renderViewMarca()
    {

        return include_once  __DIR__ . '/../marcas/views/marcaView.php';
    }

    // Traer la información de una tabla en especifico.
    public function getData(String $tableName, String $status)
    {
        // validatePermisos('configModules', 'getData');
        $model = new ConfigModulesModel();
        //las tablas, las voy a comparar segun el area crear la consulta.
        $statusTables = ['areas', 'tipo_documento', 'roles', 'categorias', 'marcas'];
        $statusColum = '';
        if (in_array($tableName, $statusTables)) {

            switch ($tableName) {
                case 'areas':
                    $statusColum = 'ar_status';
                    break;
                case 'tipo_documento':
                    $statusColum = 'tp_status';
                    break;
                case 'roles':
                    $statusColum = 'rl_status';
                    break;
                case 'categorias':
                    $statusColum = 'ca_status';
                    break;
                case 'marcas':
                    $statusColum = 'ma_status';
                    break;
                default:
                    exit();
            }
        }
        //Traigame todos los registros de la tabla cuando su estado sea activo(1) e inactivo(0).
        $sql = "SELECT * FROM $tableName WHERE $statusColum IN (1,0)";

        return $model->select($sql);
    }

    //Función para actualizar la información de un registro en base a la tabla.
    public function updateRow(array $data = [])
    {
        validatePermisos('configModules', 'updateRow');
        $values = $data['values'];
        $keysValues = array_keys($values);
        $pk = $data['pk'];
        $tableName = $data['tableName'][0];

        $prepareValues = [];
        $types = [];
        $set = [];


        $valueToCheck = reset($values);
        $columnName = array_key_first($values);

        // Construcción del SET para la consulta UPDATE
        foreach ($keysValues as $key) {
            $set[] = "`$key` = ?";
            $val = $values[$key];

            // Detectar tipo de dato
            if (is_int($val)) {
                $types[] = 'i';
            } elseif (is_float($val) || is_double($val)) {
                $types[] = 'd';
            } else {
                $types[] = 's';
            }

            $prepareValues[] = $val;
        }

        $setClause = implode(', ', $set);


        $pkValue = reset($pk);
        $pkColumn = key($pk);
        $types[] = 'i';
        $prepareValues[] = $pkValue;

        // Armar SQL final
        $sql = "UPDATE `$tableName` SET $setClause WHERE `$pkColumn` = ?";

        // Validar si el valor ya existe (evitar duplicados en campos únicos)
        $model = new ConfigModulesModel();
        $validateResult = $model->validateUnique(
            $columnName,
            $tableName,
            $valueToCheck,
            $pkValue,
            $pkColumn
        );

        if (!$validateResult['status']) {
            return $validateResult;
        }

        // Ejecutar el update
        $result = $model->update($sql, $prepareValues, $types);
        return $result;
    }

    //Creo la consulta sql.
    public function deleteRow(array $data = [])
    {

        validatePermisos('configModules', 'deleteRow');
        //Validar en el arreglo si el dato enviado corresponde a uno de los status.
        $status = [0, 1];
        //validar si está definido.
        if (!$data) {
            exit();
        }
        //Captura nombre de la tabla.
        $tableName = (string) $data['tableName'];

        //TODO: Esto debe ser dinámico
        //Me captura el valor del estatus
        if ($tableName == 'tipo_documento') {
            $valueColumn = (int) $data['values']['tp_status'];
        }

        if ($tableName == 'areas') {
            $valueColumn = (int) $data['values']['ar_status'];
        }

        if ($tableName == 'marcas') {
            $valueColumn = (int) $data['values']['ma_status'];
        }

        //Capturar nombre de la columa
        $nameColum = (string) key($data['values']);
        //Capturar el nombre de la columna de la primary key
        $pkColumName = (string) $data['pk']['column'];
        //Capturar el valor de la primary key.
        $pkValue = (int) $data['pk']['value'];

        if ($valueColumn == 1) {
            $valueColumn = 0;
        } elseif ($valueColumn == 0) {
            $valueColumn = 1;
        }

        $sql = "UPDATE `$tableName` SET `$nameColum` = ? WHERE `$pkColumName` = ?";
        $types = "ii"; // ambos son enteros
        $values = [$valueColumn, $pkValue];
        $model = new ConfigModulesModel();
        $dataResult = $model->delete($sql, $types, $values);
        return $dataResult;
    }

    public function addRow(array $data = [])
    {

        try {

            validatePermisos('configModules', 'addRow');
            if (!$data) {
                exit();
            }
            //Nombres de las columnas de la tabla
            $keysValues = array_keys($data['values']);

            //Extraigo el nombre de la tabla, en este caso me interesa el el value de tableName, no su clave
            $tableName = $data['tableName'];
            //Los valores de las filas con sus respectivas columnas.
            $dataValues = $data['values'];

            $val = array_values($dataValues);

            $sql = "INSERT INTO `$tableName` SET ";
            //Estructura SET de consulta.
            $set = [];
            //Tipos de valores.
            foreach ($dataValues as $keys => $values) {
                $set[] = "`$keys` = ?";
            }

            //Tipos de datos
            $types = "ssi";
            $set2 = implode(', ', $set);
            $sql .= $set2;
            $model = new ConfigModulesModel();
            $data = $model->insert($sql, $types, $val, $tableName);
            if (!$data['status']) {
                throw new Exception("Errror al ejecutar el procedimiento");
            }
            return $data;
        } catch (Exception $th) {
            return [
                'status' => false,
                'message' => $th->getMessage(),
                'data' => []
            ];;
        }
    }

    public function validateUniqueItem(String $nameColum, String $tableName, String $nameValueColum)
    {
        $model = new ConfigModulesModel();
        $result = $model->validateUnique($nameColum, $tableName, $nameValueColum);
        return $result;
    }
}
