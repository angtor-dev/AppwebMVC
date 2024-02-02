<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "config.php";
require_once "user_config.default.php";

final class BitacoraTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }

    public function test_listar(): void
    {
        $bitacoras = Bitacora::listar();

        $this->assertIsArray($bitacoras);
        $this->assertInstanceOf(Bitacora::class, $bitacoras[0]);
    }

    public function test_registrar(): void
    {
        $resultado = Bitacora::registrar('Ha realizado prueba unitaria');

        $this->assertTrue($resultado);
    }

    public function test_eliminar(): void
    {
        $bitacoras = Bitacora::listar();
        $bitacora = $bitacoras[count($bitacoras) -1];
        $resultado = $bitacora->eliminar(false);
        
        $bitacora = Bitacora::cargar($bitacora->id);

        // resultado deber ser false, pues una bitacora no se puede eliminar
        $this->assertFalse($resultado);
        $this->assertInstanceOf(Bitacora::class, $bitacora);
    }
}

// php ./tools/phpunit.phar tests/BitacoraTest.php
?>