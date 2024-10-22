<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\SebastianBergmann\Type\VoidType;

require_once "Models/Bitacora.php";
require_once "Models/Notificacion.php";
require_once "Models/Territorio.php";
require_once "Models/Discipulo.php";
require_once "Models/Usuario.php";
require_once "Models/Celulas.php";
require_once "user_config.default.php";

final class EliminarCelulaConsolidacionTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'consolidacion';
    }

    public function test_eliminar_celulaConsolidacion(): void
    {
        $celulas = Celulas::listar();
        $celula = $celulas[count($celulas) -1];
        $id = $celula->id;
        $accion = 'eliminar';

        $this->Celulas->validacion_accion($id, $accion);
        $resultado = $this->Celulas->eliminar_Celula($id);

        $this->assertTrue($resultado);
    }

}

// php ./tests/phpunit.phar --color tests/unit/CelulaConsolidacion/EliminarCelulaConsolidacionTest.php