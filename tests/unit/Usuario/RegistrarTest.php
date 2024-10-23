<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "Models/Enums/EstadoCivil.php";
require_once "config.php";
require_once "user_config.default.php";

final class RegistrarTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }
    public function test_registrar(): void
    {
        
        $_POST['nombre'] = 'Sujeto';
        $_POST['telefono'] = '04245301584';
        $_POST['direccion'] = 'Dirección de prueba';
        $_POST['cedula'] = '30111222';
        $_POST['estadoCivil'] = EstadoCivil::Soltero->value;
        $_POST['clave'] = 'prueba123';
        $_POST['apellido'] = 'de Prueba';
        $_POST['fechaNacimiento'] = '2000-01-01';
        $_POST['preguntaSecurity'] = 'nombre de tu perro';
        $_POST['respuestaSecurity'] = 'caramelo';
        $_POST['correo'] = 'prueba@gmail.com';
        $_POST['idSede'] = 1;


        $usuario = new Usuario();
        $usuario->mapFromPost();
        $usuario->roles[] = Rol::cargar(1);

        $esValido = $usuario->esValido();
        $resultado = $usuario->registrar();

        $this->assertTrue($esValido);
        $this->assertTrue($resultado);
    }
}

// php ./tests/phpunit.phar --color tests/unit/Usuario/RegistrarTest.php
?>