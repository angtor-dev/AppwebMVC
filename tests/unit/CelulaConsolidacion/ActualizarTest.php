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

final class ActualizarTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'consolidacion';
    }

    public function test_editar(): void
    {
        $celulas = Celulas::listar();
        $celula = $celulas[500];
        $id = $celula->id;
        $tipo = trim(strtolower($this->Tipo));
        $nombre = 1;
        $idLider = 3;
        $idCoLider = 4;
        $idTerritorio = 1;
        $accion = 'actualiza';

        $this->Celulas->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
        $this->Celulas->validacion_existencia($nombre, $id, $tipo, $idTerritorio);
        $this->Celulas->validacion_accion($id, $accion);
        $this->Celulas->valida_celulascantidad($idLider, $tipo, $id);
        $resultado = $this->Celulas->editar_Celula($id, $tipo, $nombre, $idLider, $idCoLider, $idTerritorio);

        $this->assertTrue($resultado);
    }

}

// php ./tests/phpunit.phar --color tests/unit/CelulaConsolidacion/ActualizarTest.php