<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "config.php";
require_once "user_config.default.php";

final class RegistrarRolTest extends TestCase
{
    
    public function test_registrar(): void
    {
        $_POST['nombre'] = 'Rol de prueba';
        $_POST['descripcion'] = 'Rol destinado a pruebas unitarias';
        $_POST['nivel'] = 1;

        $rol = new Rol();
        $rol->mapFromPost();

        $esValido = $rol->esValido();
        $resultado = $rol->registrar();

        $this->assertTrue($esValido);
        $this->assertTrue($resultado);
    }

   
}

// php ./tests/phpunit.phar --color tests/unit/Seguridad/RegistrarRolTest.php 
?>