<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\SebastianBergmann\Type\VoidType;

require_once "Models/Bitacora.php";
require_once "Models/Notificacion.php";
require_once "Models/Territorio.php";
require_once "Models/Discipulo.php";
require_once "Models/Usuario.php";
require_once "Models/Celulas.php";
require_once "user_config.default.php";

final class ActualizarReunionTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'consolidacion';
    }

    public function test_editar_reunion(): void
    {
        $idCelula = 52;
        $id = 534444;
        $fecha = '2024-02-07';
        $tematica = 45;
        $semana = 7;
        $generosidad = 7;
        $infantil = '';
        $juvenil = '';
        $adulto = '';
        $actividad = trim(strtolower('Actividad editada'));
        $observaciones = trim(strtolower('Observaciones editadas'));

        $arrayAccion = array('id' => $id, 'idCelula' => $idCelula, 'accion' => 'actualizar');

        $this->Celulas->validacion_datos_reunion([$idCelula, $semana, $generosidad, $id], 
        [$tematica, $actividad, $observaciones], $fecha);
        $this->Celulas->validacion_accion_reunion($arrayAccion);
        $resultado = $this->Celulas->editar_reuniones($id, $idCelula, $fecha,
         $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones);

        $this->assertTrue($resultado);
    }

}

// php ./tests/phpunit.phar --color tests/unit/CelulaConsolidacion/ActualizarReunionTest.php