<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "config.php";
require_once "user_config.default.php";

final class ListarRolesTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }
    public function test_listar(): void 
    {
        $roles = Rol::listar(1);

        $this->assertIsArray($roles);
        $this->assertInstanceOf(Rol::class, $roles[0]);
    }

   
}

// php ./tests/phpunit.phar --color tests/unit/Seguridad/ListarRolesTest.php 