<?php
use PHPUnit\Framework\TestCase;

require_once "Models/Usuario.php";
require_once "user_config.default.php";
require_once "Models/Bitacora.php";

final class RecuperarPasswordTest extends TestCase
{
    private $objeto;

    public function setUp(): void
    {
        $this->objeto = new Usuario();
    }

    /** @test **/
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

        function encrypt($data){

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
}

// php ./tests/phpunit.phar --color tests/unit/Login/RecuperarPasswordTest.php