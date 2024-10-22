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

final class RegistrarReunionTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'consolidacion';
    }

    public function test_registrar_reunion(): void
    {

        $idCelula = 52;
        $fecha = 2;
        $tematica = trim(strtolower('Tematica de prueba'));
        $semana = 4;
        $generosidad = 21;
        $infantil = '';
        $juvenil = '';
        $adulto = '';
        $actividad = trim(strtolower('Actividad de prueba'));
        $observaciones = trim(strtolower('Observacion de prueba'));
        $arrayAsistencias = [16, 17];

        $this->Celulas->validacion_datos_reunion([$idCelula, $semana, $generosidad], [$tematica, $actividad, $observaciones], $fecha);
        $resultado = $this->Celulas->registrar_reunion($idCelula, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones, $arrayAsistencias);

        $this->assertTrue($resultado);
    }

}

// php ./tests/phpunit.phar --color tests/unit/CelulaConsolidacion/RegistrarReunionTest.php