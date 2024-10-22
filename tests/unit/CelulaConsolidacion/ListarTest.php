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

final class ListarTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'consolidacion';
    }

    public function test_listar(): void
    {
        $celulas = $this->Celulas->listar_Celula($this->Tipo);

        $this->assertIsArray($celulas);
        $this->assertArrayHasKey('id', $celulas[0]);
    }

}

// php ./tests/phpunit.phar --color tests/unit/CelulaConsolidacion/ListarTest.php