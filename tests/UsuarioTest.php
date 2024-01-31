<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "Models/Enums/EstadoCivil.php";
require_once "user_config.default.php";

final class UsuarioTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }

    public function test_listar(): void 
    {
        $usuarios = Usuario::listar();

        $this->assertIsArray($usuarios);
        $this->assertInstanceOf(Usuario::class, $usuarios[0]);
    }

    public function test_registrar(): void
    {
        $_POST['nombre'] = 'Sujeto';
        $_POST['apellido'] = 'de Prueba';
        $_POST['cedula'] = '30111222';
        $_POST['correo'] = 'prueba@gmail.com';
        $_POST['clave'] = 'prueba123';
        $_POST['telefono'] = '04161296157';
        $_POST['direccion'] = 'Dirección de prueba';
        $_POST['estadoCivil'] = EstadoCivil::Soltero->value;
        $_POST['fechaNacimiento'] = '2000-01-01';
        $_POST['idSede'] = 1;

        $usuario = new Usuario();
        $usuario->mapFromPost();

        $esValido = $usuario->esValido();
        $resultado = $usuario->registrar();

        $this->assertTrue($esValido);
        $this->assertTrue($resultado);
    }
}

// php ./tools/phpunit.phar tests/CelulaConsolidacionTest.php
?>