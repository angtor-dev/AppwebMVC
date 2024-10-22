<?php
use PHPUnit\Framework\TestCase;

require_once "Models/Bitacora.php";
require_once "Models/Notificacion.php";
require_once "Models/Sede.php";
require_once "Models/Territorio.php";
require_once "Models/Discipulo.php";
require_once "Models/Usuario.php";
require_once "Models/Celulas.php";
require_once "user_config.default.php";

final class ActualizarTest extends TestCase
{
    private Territorio $Territorio;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Territorio = new Territorio();
    }
    public function test_actualizar(): void
    {
        $territorios = Territorio::listar();
        $territorio = $territorios[count($territorios) - 1];
        $id = $territorio->id;
        $idSede = '';
        $nombre = '';
        $idLider = '1';
        $detalles = 'no tiene';
    
        $this->Territorio->validacion_datos($idSede, $nombre, $idLider, $detalles);
        $this->Territorio->validacion_existencia($nombre, $idSede, $id);
        $this->Territorio->valida_lider($idLider, $id);
        $resultado = $this->Territorio->editar_territorio($id, $idSede, $nombre, $idLider, $detalles);
        $this->assertTrue($resultado);
    }

}
// php ./tests/phpunit.phar --color tests/unit/Territorio/ActualizarTest.php