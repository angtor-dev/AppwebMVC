<?php
use PHPUnit\Framework\TestCase;
require_once "Models/Bitacora.php";
require_once "Models/Usuario.php";
require_once "config.php";
require_once "user_config.default.php";

final class GestionarPermisosTest extends TestCase
{
    
    public function test_GestionarPermiso(): void
    {
        $roles = Rol::listar();
        $rol = $roles[count($roles) -1];

        $_POST['nombre'] = 'Rol actualizado';
        $_POST['descripcion'] = 'Descripcion de rol actualizada';

        $_POST['idRol'] = $rol->id;
        $_POST['idModulo'] = 6;
        $_POST['consultar'] = true;
        $_POST['registrar'] = true;
        $_POST['actualizar'] = true;
        $_POST['eliminar'] = false;
        
        $permiso = new Permiso(); 
        $permiso->mapFromPost();

        $resultado = $permiso->registrar();
    
        $this->assertTrue($resultado);
       
        
    }

   
}

// php ./tests/phpunit.phar --color tests/unit/Seguridad/GestionarPermisosTest.php 