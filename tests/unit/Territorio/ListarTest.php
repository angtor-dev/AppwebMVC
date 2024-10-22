<?php
use PHPUnit\Framework\TestCase;

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
    private Territorio $Territorio;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Territorio = new Territorio();
    }
    public function test_listar(): void
    {
        $territorios = $this->Territorio->listar_territorio();

        $this->assertIsArray($territorios);
        $this->assertArrayHasKey('id', $territorios[0]);
    }

}

// php ./tests/phpunit.phar --color tests/unit/Territorio/ListarTest.php