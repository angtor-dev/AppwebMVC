<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "Models/Enums/EstadoCivil.php";
require_once "config.php";
require_once "user_config.default.php";

final class ListarTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }
   
    public function test_listar(): void 
    {
        $usuarios = Usuario::listar();
        $this->assertIsArray($usuarios);
    }
}

// php ./tests/phpunit.phar --color tests/unit/Usuario/ListarTest.php
?>