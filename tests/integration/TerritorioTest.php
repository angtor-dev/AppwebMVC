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

final class TerritorioTest extends TestCase
{
    private Territorio $Territorio;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Territorio = new Territorio();
    }

    public function test_listar(): void
    {
        $territorios = $this->Territorio->listar_territorio();

        $this->assertIsArray($territorios);
        $this->assertArrayHasKey('id', $territorios[0]);
    }

    public function test_registrar(): void
    {
        $idSede = 1;
        $nombre = 'Territorio prueba phpunit';
        $idLider = 1;
        $detalles = 'no tiene';

        $this->Territorio->validacion_datos($idSede, $nombre, $idLider, $detalles);
        $this->Territorio->validacion_existencia($nombre, $idSede, $idTerritorio = '');
        $this->Territorio->valida_lider($idLider, $id = '');
        $resultado = $this->Territorio->registrar_territorio($idSede, $nombre, $idLider, $detalles);


        $this->assertTrue($resultado);
    }

    public function test_editar(): void
    {
        $territorios = Territorio::listar();
        $territorio = $territorios[count($territorios) - 1];
        $id = $territorio->id;
        $idSede = 1;
        $nombre = 'Territorio prueba edicion phpunit';
        $idLider = '1';
        $detalles = 'no tiene';
    
        $this->Territorio->validacion_datos($idSede, $nombre, $idLider, $detalles);
        $this->Territorio->validacion_existencia($nombre, $idSede, $id);
        $this->Territorio->valida_lider($idLider, $id);
        $resultado = $this->Territorio->editar_territorio($id, $idSede, $nombre, $idLider, $detalles);
        $this->assertTrue($resultado);
    }



    public function test_eliminar(): void
    {
        $territorios = Territorio::listar();
        $territorio = $territorios[count($territorios) - 1];
        $id = $territorio->id;

        $this->Territorio->validacion_accion($id, $accion = 'eliminar');
        $resultado = $this->Territorio->eliminar_territorio($id);

        $this->assertTrue($resultado);
    }
}

// php ./tests/phpunit.phar --color tests/integration/TerritorioTest.php