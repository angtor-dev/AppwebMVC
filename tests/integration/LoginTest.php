<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;


require_once "Models/Usuario.php";
require_once "Models/Bitacora.php";
require_once "Models/Enums/EstadoCivil.php";
require_once "config.php";
require_once "user_config.default.php";


final class LoginTest extends TestCase
{
    private $objeto;

    public function setUp(): void
    {
        $this->objeto = new Usuario();
        $_SESSION['usuario'] = Usuario::cargar(1);
    }

    #[Test]
    public function login(): void
    {
        //Init
        $cedula = 1234567;
        $clave = '1234567b';

        $data = json_encode([
            'cedula' => $cedula,
            'clave' => $clave
        ]);

        $publicKeyPem = openssl_pkey_get_public(publicKey);
        openssl_public_encrypt($data, $encrypted, $publicKeyPem);
        $encryptedData = base64_encode($encrypted);
        $respuesta = $this->objeto->login($encryptedData);

        //Assert
        $this->assertTrue($respuesta);
    }



    #[Test]
    public function Registrar(): void
    {
        //Init
        $data = [
            'apellido' => 'Prueba',
            'nombre' => 'Sujeto',
            'telefono' => '04245301584',
            'direccion' => 'dirección de prueba',
            'cedula' => '30111222',
            'estadoCivil' => EstadoCivil::Soltero->value,
            'password' => 'prueba123',
            'fechaNacimiento' => '2000-01-01',
            'preguntaSecurity' => 'nombre de tu perro',
            'respuestaSecurity' => 'caramelo',
            'correo' => 'prueba@gmail.com',
            'idSede' => 1
        ];

        //se encriptan los valores
        foreach ($data as $key => $value) {
            $publicKeyPem = openssl_pkey_get_public(publicKey);
            openssl_public_encrypt(json_encode($value), $encrypted, $publicKeyPem);
            $encryptValue = base64_encode($encrypted);
            $aja[$key] = $encryptValue;
        }

        $respuesta_1 = $this->objeto->validarRegister($aja);
        $respuesta_2 = $this->objeto->registerUser();

        //Assert
        $this->assertTrue($respuesta_1);
        $this->assertTrue($respuesta_2);
    }

    #[Test]
    public function PasswordRecovery(): void
    {
        //Init

        $cedula = json_encode([
            'cedula' => 30111222
        ]);

        $respuestaRecovery = json_encode([
            'cedulaRecovery' => 30111222,
            'respuesta' => 'caramelo'
        ]);

        function encrypt($data)
        {

            $publicKeyPem = openssl_pkey_get_public(publicKey);
            openssl_public_encrypt($data, $encrypted, $publicKeyPem);
            $encryptedData = base64_encode($encrypted);
            return $encryptedData;

        }

        $cedulaEncrypt = encrypt($cedula);
        $respuestaRecoveryEncrypt = encrypt($respuestaRecovery);


        $respuesta1 = $this->objeto->recovery($cedulaEncrypt);
        $respuesta2 = $this->objeto->resetPasswordWeb($respuestaRecoveryEncrypt);


        $this->assertIsArray($respuesta1);
        $this->assertIsArray($respuesta2);
    }

    #[Test]
    public function test_eliminar(): void
    {
        $usuario = Usuario::cargarPorCedula('30111222');
        $resultado = $usuario->eliminar(false);

           $usuario = Usuario::cargarPorCedula('30111222');

        $this->assertTrue($resultado);
        $this->assertNull($usuario);
    }

}

// php ./tests/phpunit.phar --color tests/integration/LoginTest.php