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

final class ActualizarTest extends TestCase
{
    private Sede $Sede;

    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
        $this->Sede = new Sede();
    }
   
    public function test_Actualizar(): void
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


}

// php ./tests/phpunit.phar --color tests/unit/Sedes/ActualizarTest.php
?>