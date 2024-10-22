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

final class ListarAsistenciaTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'consolidacion';
    }

    public function test_listar_asistencia_reunion(): void
    {
        $idReunion = 53;

        $Lista = $this->Celulas->listar_asistencia($idReunion);

        $this->assertIsArray($Lista);
        $this->assertArrayHasKey('idReunion', $Lista[0]);
    }

}

// php ./tests/phpunit.phar --color tests/unit/CelulaConsolidacion/ListarAsistenciaTest.php