<?php
use PHPUnit\Framework\TestCase;
use PHPUnit\SebastianBergmann\Type\VoidType;

require_once "Models/Bitacora.php";
require_once "Models/Territorio.php";
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
        $this->Tipo = 'consolidacion';
    }

    /*public function test_listar(): void
    {
        $celulas = $this->Celulas->listar_Celula($this->Tipo);

        $this->assertIsArray($celulas);
        $this->assertArrayHasKey('id', $celulas[0]);
    }*/

    /*public function test_registrar(): void
    {
        $tipo = strtolower($this->Tipo);
        $nombre = "Consolidacion de prueba";
        $idLider = '3';
        $idCoLider = '4';
        $idTerritorio = '1';

        $this->Celulas->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
        $this->Celulas->validacion_existencia($nombre, '', $tipo, $idTerritorio);
        $this->Celulas->valida_celulascantidad($idLider, $tipo, '');

        $resultado = $this->Celulas->registrar_Celula($tipo, $nombre, $idLider, $idCoLider, $idTerritorio);
        $this->assertTrue($resultado);
    }*/

    /*public function test_editar(): void
    {
        $id = 56;
        $tipo = trim(strtolower($this->Tipo));
        $nombre = trim(strtolower('Prueba edicion'));
        $idLider = 3;
        $idCoLider = 4;
        $idTerritorio = 1;
        $accion = 'actualizar';

        $this->Celulas->validacion_datos($nombre, [$idLider, $idCoLider, $idTerritorio]);
        $this->Celulas->validacion_existencia($nombre, $id, $tipo, $idTerritorio);
        $this->Celulas->validacion_accion($id, $accion);
        $this->Celulas->valida_celulascantidad($idLider, $tipo, $id);
        $resultado = $this->Celulas->editar_Celula($id, $tipo, $nombre, $idLider, $idCoLider, $idTerritorio);

        $this->assertTrue($resultado);
    }*/

    /*public function test_registrar_reunion(): void
    {
        $idCelula = 56;
        $fecha = '2024-01-31';
        $tematica = trim(strtolower('Tematica de prueba'));
        $semana = 4;
        $generosidad = 21;
        $infantil = '';
        $juvenil = '';
        $adulto = '';
        $actividad = trim(strtolower('Actividad de prueba'));
        $observaciones = trim(strtolower('Observacion de prueba'));
        $arrayAsistencias = [16, 17];

        $this->Celulas->validacion_datos_reunion([$idCelula, $semana, $generosidad], [$tematica, $actividad, $observaciones], $fecha);
        $resultado = $this->Celulas->registrar_reunion($idCelula, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones, $arrayAsistencias);

        $this->assertTrue($resultado);
    }*/

    /*public function test_listar_reunion(): void
    {
        $reuniones = $this->Celulas->listar_reuniones($this->Tipo);

        $this->assertIsArray($reuniones);
        $this->assertArrayHasKey('id', $reuniones[0]);
    }*/


}

// php ./tools/phpunit.phar tests/CelulaConsolidacionTest.php
?>