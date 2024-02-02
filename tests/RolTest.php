<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "config.php";
require_once "user_config.default.php";

final class RolTest extends TestCase
{
    public function setUp(): void
    {
        $_SESSION['usuario'] = Usuario::cargar(1);
    }

    public function test_listar(): void 
    {
        $roles = Rol::listar(1);

        $this->assertIsArray($roles);
        $this->assertInstanceOf(Rol::class, $roles[0]);
    }

    public function test_registrar(): void
    {
        $_POST['nombre'] = 'Rol de prueba';
        $_POST['descripcion'] = 'Rol destinado a pruebas unitarias';
        $_POST['nivel'] = 1;

        $rol = new Rol();
        $rol->mapFromPost();

        $esValido = $rol->esValido();
        $resultado = $rol->registrar();

        $this->assertTrue($esValido);
        $this->assertTrue($resultado);
    }

    public function test_actualizar(): void
    {
        $roles = Rol::listar();
        $rol = $roles[count($roles) -1];

        $_POST['nombre'] = 'Rol actualizado';
        $_POST['descripcion'] = 'Descripcion de rol actualizada';

        $rol->mapFromPost();

        $esValido = $rol->esValido();
        $resultado = $rol->actualizar();

        $rolActualizado = Rol::cargar($rol->id);
        $nombreActualizado = $rolActualizado->getNombre();

        $this->assertTrue($esValido);
        $this->assertTrue($resultado);
        $this->assertSame('Rol actualizado', $nombreActualizado);
    }

    public function test_eliminar(): void
    {
        $roles = Rol::listar();
        $rol = $roles[count($roles) -1];
        $resultado = $rol->eliminar(false);
        
        $rol = Rol::cargar($rol->id);

        $this->assertTrue($resultado);
        $this->assertNull($rol);
    }
}

// php ./tools/phpunit.phar tests/RolTest.php
?>