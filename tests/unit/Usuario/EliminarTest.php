<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "Models/Enums/EstadoCivil.php";
require_once "config.php";
require_once "user_config.default.php";

final class EliminarTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }
   
    public function test_eliminar(): void
    {
        $usuario = Usuario::cargarPorCedula('30111222');
        $resultado = $usuario->eliminar(false);

        $usuario = Usuario::cargarPorCedula('30111222');

        $this->assertTrue($resultado);
        $this->assertNull($usuario);
    }
}

// php ./tests/phpunit.phar --color tests/unit/Usuario/EliminarTest.php
?>