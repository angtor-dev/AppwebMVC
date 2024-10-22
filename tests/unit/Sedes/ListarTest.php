<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\SebastianBergmann\Type\VoidType;

require_once "Models/Bitacora.php";
require_once "Models/Notificacion.php";
require_once "Models/Sede.php";
require_once "Models/Territorio.php";
require_once "Models/Discipulo.php";
require_once "Models/Usuario.php";
require_once "Models/Celulas.php";
require_once "user_config.default.php";

final class ListarTest extends TestCase
{
    private Sede $Sede;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Sede = new Sede();
    }
   
    public function test_listar(): void
    {
        $sedes = $this->Sede->listar_Sede();

        $this->assertIsArray($sedes);
        $this->assertArrayHasKey('id', $sedes[0]);
    }

}

// php ./tests/phpunit.phar --color tests/unit/Sedes/ListarTest.php
?>