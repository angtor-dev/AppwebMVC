<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "config.php";
require_once "user_config.default.php";

final class EliminarRolTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }
    public function test_eliminar(): void
    {
        $roles = Rol::listar();
        $rol = $roles[count($roles) -1];
        $resultado = $rol->eliminar(false);
        
        $rol = Rol::cargar($rol->id);

        $this->assertTrue($resultado);
        $this->assertNull($rol);
    }

   
}

// php ./tests/phpunit.phar --color tests/unit/Seguridad/EliminarRolTest.php 