<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\SebastianBergmann\Type\VoidType;

require_once "Models/Bitacora.php";
require_once "Models/Notificacion.php";
require_once "Models/Sede.php";
require_once "Models/Territorio.php";
require_once "Models/Discipulo.php";
require_once "Models/Usuario.php";
require_once "Models/Celulas.php";
require_once "user_config.default.php";

final class SedesTest extends TestCase
{
    private Sede $Sede;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Sede = new Sede();
    }

    public function test_listar(): void
    {
        $sedes = $this->Sede->listar_Sede();

        $this->assertIsArray($sedes);
        $this->assertArrayHasKey('id', $sedes[0]);
    }

    public function test_registrar(): void
    {
        $idPastor = 1;
        $nombre = 'Sede Ejemplo';
        $direccion = 'No tiene';
        $estado = 'LAR';

        $this->Sede->validacion_datos($idPastor, $nombre, $direccion, $estado);
        $this->Sede->validacion_existencia($nombre, $idSede = '');
        $this->Sede->valida_pastor($idPastor, $id = '');
        $resultado = $this->Sede->registrar_Sede($idPastor, $nombre, $direccion, $estado);

        $this->assertTrue($resultado);
    }

    public function test_editar(): void
    {
        $sedes = Sede::listar();
        $sede = $sedes[count($sedes) - 1];
        $idSede = $sede->id;
        ;
        $idPastor = 1;
        $nombre = 'Sede Prueba Edicion';
        $direccion = 'No tiene';
        $estado = 'LAR';

        $this->Sede->validacion_datos($idPastor, $nombre, $direccion, $estado);
        $this->Sede->validacion_existencia($nombre, $idSede);
        $this->Sede->validacion_editar_estado($idSede, $estado);
        $this->Sede->valida_pastor($idPastor, $idSede);
        $resultado = $this->Sede->editar_Sede($idSede, $idPastor, $nombre, $direccion, $estado);

        $this->assertTrue($resultado);
    }



    public function test_eliminar(): void
    {
        $sedes = Sede::listar();
        $sede = $sedes[count($sedes) - 1];

        $id = $sede->id;
        $this->Sede->validacion_eliminar($id);
        $resultado = $this->Sede->eliminar_Sede($id);

        $this->assertTrue($resultado);
    }
}

// php ./tests/phpunit.phar --color tests/integration/SedesTest.php