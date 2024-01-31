<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "Models/Celulas.php";
require_once "user_config.default.php";

final class CelulaConsolidacionTest extends TestCase
{
    private Celulas $Celulas;
    private string $Tipo;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Celulas = new Celulas();
        $this->Tipo = 'Consolidacion';
    }

    public function test_listar(): void 
    {
        $celulas = $this->Celulas->listar_Celula($this->Tipo);

        $this->assertIsArray($celulas);
        $this->assertArrayHasKey('id', $celulas[0]);
    }

    public function test_registrar(): void
    {
        $tipo =  strtolower($this->Tipo);
        $nombre = "Consolidacion de prueba";
        $idLider = '3';
        $idCoLider = '4';
        $idTerritorio = '1';
        
        $this->Celulas->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
        $this->Celulas->validacion_existencia($nombre, '', $tipo, $idTerritorio);
        $this->Celulas->valida_celulascantidad($idLider, $tipo, '');
        
        // $resultado = $this->Celulas->registrar_Celula($tipo, $nombre, $idLider, $idCoLider, $idTerritorio);
        // $this->assertTrue($resultado);
    }
}

// php ./tools/phpunit.phar tests/CelulaConsolidacionTest.php
?>