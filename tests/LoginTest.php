<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Usuario.php";
require_once "user_config.default.php";

final class LoginTest extends TestCase
{
    private $objeto;

    public function setUp(): void
    {
        $this->objeto = new Usuario();
    }

    /** @test **/
    public function login(): void 
    {
        //Init
        $cedula = 123456;
        $password = 123456;

        //Act
        $respuesta = $this->objeto->login($cedula, $password);

        //Assert
        $this->assertTrue($respuesta);
    }
}

// Abrir terminal en la raiz del proyecto y ejecutar:
// php ./tools/phpunit.phar tests/PruebaTest.php