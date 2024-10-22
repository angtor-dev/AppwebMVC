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

final class EliminarTest extends TestCase
{
    private Territorio $Territorio;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Territorio = new Territorio();
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

// php ./tests/phpunit.phar --color tests/unit/Territorio/EliminarTest.php