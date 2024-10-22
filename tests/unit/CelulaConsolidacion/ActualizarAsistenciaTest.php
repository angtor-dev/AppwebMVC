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

final class ActualizarAsistenciaTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'consolidacion';
    }

    public function test_editar_asistencia_reunion(): void
    {
        $idReunion = 53;
        $discipulos = ['18'];

        $resultado = $this->Celulas->actualizar_asistenciaReunion($idReunion, $discipulos);

        $this->assertTrue($resultado);
    }

}

// php ./tests/phpunit.phar --color tests/unit/CelulaConsolidacion/ActualizarAsistenciaTest.php