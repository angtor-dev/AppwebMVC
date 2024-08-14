<?php
use PHPUnit\Framework\TestCase;

require_once "Models/Usuario.php";
require_once "user_config.default.php";
require_once "Models/Enums/EstadoCivil.php";

final class RegistrarseTest extends TestCase
{
    private $objeto;

    public function setUp(): void
    {
        $this->objeto = new Usuario();
    }

    /** @test **/
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
}

// php ./tests/phpunit.phar --color tests/unit/Login/RegistrarseTest.php