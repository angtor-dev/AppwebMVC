<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Usuario.php";
require_once "user_config.default.php";

final class PruebaTest extends TestCase
{
    public function testString(): void 
    {
        $valorEsperado = "dubidubi";
        $valor1 = "dubidubi";
        $valor2 = "dabadaba";
        $this->assertSame($valorEsperado, $valor1); // Exito 
        $this->assertSame($valorEsperado, $valor2); // Fallo
    }
}

// Abrir terminal en la raiz del proyecto y ejecutar:
// php ./tools/phpunit.phar tests/PruebaTest.php
?>