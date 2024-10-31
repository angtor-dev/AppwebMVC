<?php

use PHPUnit\Framework\TestCase;

require_once "Models/Usuario.php";
require_once "Models/Sede.php";
require_once "Models/Bitacora.php";
require_once "user_config.default.php";


final class RegistrarTest extends TestCase{

private Sede $sede;


public function setUp(): void{

    $_SESSION['usuario'] = Usuario::cargar(1);
    $this->sede = new Sede;

}

public function test_resgistrar(): void
{

    $idPastor = 1;
    $nombre = 'Sede phpunit prueba';
    $direccion = 'no tiene';
    $estado = 'LAR';

    $this->sede->validacion_datos($idPastor, $nombre, $direccion, $estado);
    $this->sede->validacion_existencia($nombre, $idSede = '');
    $this->sede->valida_pastor($idPastor, $id = '');
    $respuesta = $this->sede->registrar_Sede($idPastor, $nombre, $direccion, $estado);

    $this->assertTrue($respuesta);


}




}

// php ./tests/phpunit.phar --color tests/unit/Sedes/RegistrarTest.php
?>