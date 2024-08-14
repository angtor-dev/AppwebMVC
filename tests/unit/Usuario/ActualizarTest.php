<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "Models/Enums/EstadoCivil.php";
require_once "config.php";
require_once "user_config.default.php";

final class ActualizarTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }
   
    public function test_actualizar(): void
    {
        $usuario = Usuario::cargarPorCedula('30111222');

        $_POST['nombre'] = 'Nuevo';
        $_POST['apellido'] = 'Sujeto';
        $_POST['telefono'] = '02514456741';
        $_POST['direccion'] = 'Direccion actualizada';
        $_POST['estadoCivil'] = EstadoCivil::Casado->value;

        $usuario->mapFromPost();

        $esValido = $usuario->esValido();
        $resultado = $usuario->actualizar();

        $usuarioActualizado = Usuario::cargarPorCedula('30111222');
        $nombreActualizado = $usuarioActualizado->getNombre();

        $this->assertTrue($esValido);
        $this->assertTrue($resultado);
        $this->assertSame('Nuevo', $nombreActualizado);
    }
}

// php ./tests/phpunit.phar --color tests/unit/Usuario/ActualizarTest.php
?>