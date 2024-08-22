<?php
use PHPUnit\Framework\TestCase;

require_once "Models/Usuario.php";
require_once "user_config.default.php";

final class IniciarSesionTest extends TestCase
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
}

// php ./tests/phpunit.phar --color tests/unit/Login/IniciarSesionTest.php