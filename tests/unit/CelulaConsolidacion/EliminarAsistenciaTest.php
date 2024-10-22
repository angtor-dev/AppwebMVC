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

final class EliminarAsistenciaTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'consolidacion';
    }

    public function test_eliminar_asistencia_reunion(): void
    {
        $id = [68,69,70];
        
        foreach ($id as $key) {
            $resultado = $this->Celulas->eliminar_asistenciaReunion($key);
            $this->assertTrue($resultado);
        }
        
    }

}

// php ./tests/phpunit.phar --color tests/unit/CelulaConsolidacion/EliminarAsistenciaTest.php