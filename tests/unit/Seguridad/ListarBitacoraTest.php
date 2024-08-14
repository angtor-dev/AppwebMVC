<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "Models/Enums/EstadoCivil.php";
require_once "config.php";
require_once "user_config.default.php";

final class ListarBitacoraTest extends TestCase
{
    public function setUp(): void
    {
    }
   
    public function test_listar(): void
    {
        $bitacoras = Bitacora::listar();

        $this->assertIsArray($bitacoras);
        $this->assertInstanceOf(Bitacora::class, $bitacoras[0]);
    }

}
// php ./tests/phpunit.phar --color tests/unit/Seguridad/ListarBitacoraTest.php 