<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "config.php";
require_once "user_config.default.php";

final class ActualizarRolTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }

    public function test_actualizar(): void
    {
        $roles = Rol::listar();
        $rol = $roles[count($roles) -1];

        $_POST['nombre'] = 'Rol actualizado';
        $_POST['descripcion'] = 'Descripcion de rol actualizada';

        $rol->mapFromPost();

        $esValido = $rol->esValido();
        $resultado = $rol->actualizar();    

        $rolActualizado = Rol::cargar($rol->id);
        $nombreActualizado = $rolActualizado->getNombre();

        $this->assertTrue($esValido);
        $this->assertTrue($resultado);
        $this->assertSame('Rol actualizado', $nombreActualizado);
    }

   
}

// php ./tests/phpunit.phar --color tests/unit/Seguridad/ActualizarRolTest.php 