<?php
require_once "Models/Model.php";
require_once "Models/Rol.php";
require_once "Models/Sede.php";
require_once "Models/Notificacion.php";


class Usuario extends Model
{
    public int $id;
    public int $idSede;
    private ?int $idCelulaFamiliar;
    private ?int $idCelulaCrecimiento;
    private ?int $idConsolidador;
    private string $cedula;
    private ?string $correo;
    private string $clave;
    private string $nombre;
    private string $apellido;
    private string $telefono;
    private string $direccion;
    private string $estadoCivil;
    private string $fechaNacimiento;
    private string $preguntaSecurity;
    private string $respuestaSecurity;
    private ?string $fechaConversion;
    private ?string $motivo;
    private int $estatus;

    public Sede $sede;
    /** @var ?array<Rol> */
    public ?array $roles;
    /** @var ?array<Notificacion> */
    public ?array $notificaciones;

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idSede)) {
            $this->sede = Sede::cargar($this->idSede);
        }
        if (!empty($this->id)) {
            $this->roles = Rol::cargarMultiplesRelaciones($this->id, get_class($this), "UsuarioRol");
        }
        if (!empty($this->id)) {
            $this->notificaciones = Notificacion::cargarRelaciones($this->id, get_class());
        }

    }

    public function login(string $cedula, string $clave): bool
    {
        if (empty($cedula) || empty($clave)) {
            return false;
        }

        $query = "SELECT * FROM usuario WHERE cedula = :cedula LIMIT 1";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('cedula', $cedula);

            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                return false;
            }

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!password_verify($clave, $usuario['clave'])) {
                return false;
            }

            // Almacena usuario en sesion
            session_start();
            $_SESSION['usuario'] = Usuario::cargar($usuario['id']);

            return true;
        } catch (\Throwable $th) {
            http_response_code(500);
            throw $th;
        }
    }

    public function registrar(): bool|null
    {
        $sql = "INSERT INTO usuario(idSede, cedula, correo, clave, nombre, apellido, telefono, direccion, estadoCivil, fechaNacimiento)
            VALUES(:idSede, :cedula, :correo, :clave, :nombre, :apellido, :telefono, :direccion, :estadoCivil, :fechaNacimiento)";

        try {
            $this->db->pdo()->beginTransaction();

            $this->encriptarClave();

            // Registra al usuario
            $stmt = $this->prepare($sql);
            $stmt->bindValue('idSede', $this->idSede);
            $stmt->bindValue('cedula', $this->cedula);
            $stmt->bindValue('correo', $this->correo);
            $stmt->bindValue('clave', $this->clave);
            $stmt->bindValue('nombre', $this->nombre);
            $stmt->bindValue('apellido', $this->apellido);
            $stmt->bindValue('telefono', $this->telefono);
            $stmt->bindValue('direccion', $this->direccion);
            $stmt->bindValue('estadoCivil', $this->estadoCivil);
            $stmt->bindValue('fechaNacimiento', $this->fechaNacimiento);

            $stmt->execute();

            // Registra los roles del usuario
            $idUsuario = $this->db->pdo()->lastInsertId();
            $idRol = null;

            $sql = "INSERT INTO usuariorol(idUsuario, idRol)
                VALUES(:idUsuario, :idRol)";

            $stmt = $this->prepare($sql);
            $stmt->bindParam('idUsuario', $idUsuario);
            $stmt->bindParam('idRol', $idRol);

            foreach ($this->roles as $rol) {
                $idRol = $rol->id;
                $stmt->execute();
            }

            // Guarda los cambios
            $this->db->pdo()->commit();

            return true;
        } catch (\Throwable $th) {
            // Revierte los cambios en la bd
            if ($this->db->pdo()->inTransaction()) {
                $this->db->pdo()->rollBack();
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar el usuario.";
            throw $th;
        }
    }

    public function actualizar(): bool
    {
        $sql = "UPDATE usuario SET idSede = :idSede, cedula = :cedula, correo = :correo,
            nombre = :nombre, apellido = :apellido, telefono = :telefono, direccion = :direccion,
            estadoCivil = :estadoCivil, fechaNacimiento = :fechaNacimiento WHERE id = :id";

        try {
            $this->db->pdo()->beginTransaction();

            // Actualiza el usuario
            $stmt = $this->prepare($sql);
            $stmt->bindValue('idSede', $this->idSede);
            $stmt->bindValue('cedula', $this->cedula);
            $stmt->bindValue('correo', $this->correo);
            $stmt->bindValue('nombre', $this->nombre);
            $stmt->bindValue('apellido', $this->apellido);
            $stmt->bindValue('telefono', $this->telefono);
            $stmt->bindValue('direccion', $this->direccion);
            $stmt->bindValue('estadoCivil', $this->estadoCivil);
            $stmt->bindValue('fechaNacimiento', $this->fechaNacimiento);
            $stmt->bindValue('id', $this->id);

            $stmt->execute();

            // Actualiza los roles del usuario
            $this->query("DELETE FROM usuariorol WHERE idUsuario = $this->id");
            $idRol = null;

            $sql = "INSERT INTO usuariorol(idUsuario, idRol)
                VALUES(:idUsuario, :idRol)";

            $stmt = $this->prepare($sql);
            $stmt->bindParam('idUsuario', $this->id);
            $stmt->bindParam('idRol', $idRol);

            foreach ($this->roles as $rol) {
                $idRol = $rol->id;
                $stmt->execute();
            }

            // Guarda los cambios
            $this->db->pdo()->commit();

            return true;
        } catch (\Throwable $th) {
            // Revierte los cambios en la bd
            if ($this->db->pdo()->inTransaction()) {
                $this->db->pdo()->rollBack();
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar el usuario.";
            throw $th;
        }
    }

    public function actualizarClave($claveNueva): void
    {
        $clave = password_hash($claveNueva, PASSWORD_DEFAULT);
        $query = "UPDATE usuario SET clave = :clave WHERE id = :id";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue('clave', $clave);
            $stmt->bindValue('id', $this->id);

            $stmt->execute();
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                echo $th->getMessage();
                die;
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al actualizar la clave.";
            throw $th;
        }
    }

    private function encriptarClave(): void
    {
        $this->clave = password_hash($this->clave, PASSWORD_DEFAULT);
    }

    public function esValido(): bool
    {
        if (
            empty($this->cedula) || empty($this->nombre) || empty($this->apellido)
            || empty($this->estadoCivil) || empty($this->fechaNacimiento)
            || empty($this->telefono) || empty($this->direccion)
        ) {
            $_SESSION['errores'][] = "Algunos campos obligatorios estan vacios.";
            return false;
        }

        if (empty($this->roles)) {
            $_SESSION['errores'][] = "Se debe seleccionar al menos un rol.";
            return false;
        }

        if (empty($this->id) && empty($this->clave)) {
            $_SESSION['errores'][] = "Debe especificar una contraseña.";
            return false;
        }

        if (empty($this->id) && !preg_match(REG_CLAVE, $this->clave)) {
            $_SESSION['errores'][] = "La clave debe poseer al menos una letra,"
                . " un número y 6 caracteres de longitud.";
            return false;
        }

        return true;
    }

    /** Valida si el usuario tiene un rol especifico */
    public function tieneRol(string $nombreRol): bool
    {
        if (empty($this->roles)) {
            return false;
        }

        foreach ($this->roles as $rol) {
            if (strcasecmp($rol->getNombre(), $nombreRol) == 0) {
                return true;
            }
        }
        return false;
    }

    /** 
     * Valida si el usuario tiene un permiso especifico en un modulo
     * 
     * @param string $modulo El modulo a consultar (en minuscula y plural).
     * @param string $permiso El permiso a validar. Los posibles valores son 
     * consultar, registrar, actualizar y eliminar.
     */
    public function tienePermiso(string $modulo, string $permiso): bool
    {
        if (empty($this->roles)) {
            return false;
        }
        $permiso = "get" . ucfirst($permiso);

        foreach ($this->roles as $rol) {
            foreach ($rol->permisos as $p) {
                if ($p->modulo->getNombre() == $modulo && $p->$permiso()) {
                    return true;
                }
            }
        }
        return false;
    }

    public static function cargarPorCedula(string|int $cedula): null|Usuario
    {
        $bd = Database::getInstance();
        $query = "SELECT * FROM usuario WHERE cedula = :cedula AND estatus = 1 LIMIT 1";

        $stmt = $bd->pdo()->prepare($query);
        $stmt->bindValue("cedula", $cedula);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Usuario");

        if ($stmt->rowCount() == 0) {
            return null;
        }
        return $stmt->fetch();
    }

    public static function cargarPorCorreo(string $correo): null|Usuario
    {
        $bd = Database::getInstance();
        $query = "SELECT * FROM usuario WHERE correo = :correo AND estatus = 1 LIMIT 1";

        $stmt = $bd->pdo()->prepare($query);
        $stmt->bindValue("correo", $correo);

        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_CLASS, "Usuario");

        if ($stmt->rowCount() == 0) {
            return null;
        }
        return $stmt->fetch();
    }

    /** Retorna un arreglo con los usuarios que tengan alguno de los roles indicados */
    public static function listarPorRoles(string ...$roles): array
    {
        /** @var Usuario[] */
        $usuarios = Usuario::listar(1);

        foreach ($usuarios as $key => $usuario) {
            foreach ($usuario->roles as $rol) {
                if (!in_array($rol->getNombre(), $roles)) {
                    unset($usuarios[$key]);
                    break;
                }
            }
        }

        return $usuarios;
    }




    public function recovery(string $cedulaRecovery): array
    {
        try {

            $sql = "SELECT `cedula`, `preguntaSecurity`, `respuestaSecurity`, `correo` FROM `usuario` WHERE `cedula` = :cedula";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":cedula", $cedulaRecovery);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                return $resultado;
            } else {
                return array();
            }
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }

    public function resetPassword(string $cedulaRecovery, string $respuesta)
    {
        try {

            $sql = "SELECT `respuestaSecurity` FROM `usuario` WHERE `cedula` = :cedula";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":cedula", $cedulaRecovery);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($respuesta == $resultado['respuestaSecurity']) {
                $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
                $clave = '';
                for ($i = 0; $i < 10; $i++) {
                    $clave .= $caracteres[rand(0, strlen($caracteres) - 1)];
                }

                $claveEncriptada = password_hash($clave, PASSWORD_DEFAULT);
                $sql = "UPDATE usuario SET clave = :clave WHERE cedula = :cedula";
                $stmt = $this->prepare($sql);
                $stmt->bindValue(':clave', $claveEncriptada);
                $stmt->bindValue(':cedula', $cedulaRecovery);
                $stmt->execute();

                return $clave;
            } else {
                return '';
            }

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }

    public function registerUser(): bool
    {
        $sql = "INSERT INTO usuario(cedula, correo, clave, nombre, apellido, telefono, direccion, estadoCivil, fechaNacimiento, preguntaSecurity, respuestaSecurity, idSede)
        VALUES(:cedula, :correo, :clave, :nombre, :apellido, :telefono, :direccion, :estadoCivil, :fechaNacimiento, :preguntaSecurity, :respuestaSecurity, :idSede)";

        try {

            $this->encriptarClave();

            // Registra al usuario
            $stmt = $this->prepare($sql);
            $stmt->bindValue('cedula', $this->cedula);
            $stmt->bindValue('correo', $this->correo);
            $stmt->bindValue('clave', $this->clave);
            $stmt->bindValue('nombre', $this->nombre);
            $stmt->bindValue('apellido', $this->apellido);
            $stmt->bindValue('telefono', $this->telefono);
            $stmt->bindValue('direccion', $this->direccion);
            $stmt->bindValue('estadoCivil', $this->estadoCivil);
            $stmt->bindValue('fechaNacimiento', $this->fechaNacimiento);
            $stmt->bindValue('preguntaSecurity', $this->preguntaSecurity);
            $stmt->bindValue('respuestaSecurity', $this->respuestaSecurity);
            $stmt->bindValue('idSede', $this->idSede);

            $stmt->execute();

            // Registra los roles del usuario
            $idUsuario = $this->db->pdo()->lastInsertId();

            $sql = "INSERT INTO usuariorol(idUsuario, idRol)
            VALUES(:idUsuario, 3)";

            $stmt = $this->prepare($sql);
            $stmt->bindParam('idUsuario', $idUsuario);
            $stmt->execute();

            return true;

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }

    public function actualizarPerfil($opcion): bool
    {
        if ($opcion == 1) {
            $sql = "UPDATE usuario SET cedula = :cedula, correo = :correo, nombre = :nombre, apellido = :apellido, 
            telefono = :telefono, direccion = :direccion, estadoCivil = :estadoCivil, fechaNacimiento = :fechaNacimiento 
            WHERE id = :id";

            try {

                // Actualiza el usuario
                $stmt = $this->prepare($sql);

                $stmt->bindValue('cedula', $this->cedula);
                $stmt->bindValue('correo', $this->correo);
                $stmt->bindValue('nombre', $this->nombre);
                $stmt->bindValue('apellido', $this->apellido);
                $stmt->bindValue('telefono', $this->telefono);
                $stmt->bindValue('direccion', $this->direccion);
                $stmt->bindValue('estadoCivil', $this->estadoCivil);
                $stmt->bindValue('fechaNacimiento', $this->fechaNacimiento);
                $stmt->bindValue('id', $_SESSION['usuario']->id);

                $stmt->execute();

                return true;

            } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
                $error_data = array(
                    "error_message" => $e->getMessage(),
                    "error_line" => "Linea del error: " . $e->getLine()
                );
                http_response_code(422);
                echo json_encode($error_data);
                die();
            }
        } else {
            $sql = "UPDATE usuario SET preguntaSecurity = :preguntaSecurity, respuestaSecurity = :respuestaSecurity
            WHERE id = :id";

            try {

                // Actualiza el usuario
                $stmt = $this->prepare($sql);

                $stmt->bindValue('preguntaSecurity', $this->preguntaSecurity);
                $stmt->bindValue('respuestaSecurity', $this->respuestaSecurity);
                $stmt->bindValue('id', $_SESSION['usuario']->id);

                $stmt->execute();

                return true;

            } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
                $error_data = array(
                    "error_message" => $e->getMessage(),
                    "error_line" => "Linea del error: " . $e->getLine()
                );
                http_response_code(422);
                echo json_encode($error_data);
                die();
            }
        }

    }

    public function validarRegister($datos): bool
    {
        try {
            $nombre = trim(strtolower($datos["nombre"]));
            $apellido = trim(strtolower($datos["apellido"]));
            $telefono = trim($datos["telefono"]);
            $cedula = trim($datos["cedula"]);
            $estadoCivil = trim(strtoupper($datos["estadoCivil"]));
            $password = trim($datos["password"]);
            $fechaNacimiento = trim($datos["fechaNacimiento"]);
            $direccion = trim($datos["direccion"]);
            $preguntaSecurity = trim($datos["preguntaSecurity"]);
            $respuestaSecurity = trim($datos["respuestaSecurity"]);
            $correo = trim(strtolower($datos["correo"]));
            $idSede = $datos['idSede'];

            if (!preg_match('/^[A-Za-z\s]+$/', $nombre) || !preg_match('/^[A-Za-z\s]+$/', $apellido)) {
                throw new Exception("Existen caracteres especiales en algun campo. Verifique", 422);
            }

            // Validar el campo email
            if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Correo invalido", 422);
            }

            if (
                empty($nombre) || empty($apellido || empty($direccion || empty($idSede)))
                || empty($preguntaSecurity) || empty($respuestaSecurity) || empty($password || empty($correo))
            ) {
                throw new Exception("Existen datos vacios", 422);
            }

            if (!ctype_digit($telefono) || !ctype_digit($cedula)) {
                throw new Exception("Se estan enviado datos no numericos enteros", 422);
            }

            if (!preg_match('/^[SCDV]$/', $estadoCivil)) {
                throw new Exception("Estado civil invalido", 422);
            }

            // Validar el campo fechaNacimiento
            if (!strtotime($fechaNacimiento) || strtotime($datos["fechaNacimiento"]) > time()) {
                throw new Exception("Fecha de nacimiento invalida", 422);
            }

            $this->validar_correo_cedula($correo, $cedula, 1);

            $this->nombre = $nombre;
            $this->apellido = $apellido;
            $this->telefono = $telefono;
            $this->direccion = $direccion;
            $this->cedula = $cedula;
            $this->estadoCivil = $estadoCivil;
            $this->clave = $password;
            $this->fechaNacimiento = $fechaNacimiento;
            $this->preguntaSecurity = $preguntaSecurity;
            $this->respuestaSecurity = $respuestaSecurity;
            $this->correo = $correo;
            $this->idSede = $idSede;

            return true;

        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function validarActualizarPerfil($datos, $opcion): bool
    {
        try {

            if ($opcion == 1) {

                $nombre = trim(strtolower($datos["nombre"]));
                $apellido = trim(strtolower($datos["apellido"]));
                $telefono = trim($datos["telefono"]);
                $cedula = trim($datos["cedula"]);
                $estadoCivil = trim(strtoupper($datos["estadoCivil"]));
                $fechaNacimiento = trim($datos["fechaNacimiento"]);
                $direccion = trim($datos["direccion"]);
                $correo = trim(strtolower($datos["correo"]));

                if (!preg_match('/^[A-Za-z\s]+$/', $nombre) || !preg_match('/^[A-Za-z\s]+$/', $apellido)) {
                    throw new Exception("Existen caracteres especiales en algun campo. Verifique", 422);
                }

                // Validar el campo email
                if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                    throw new Exception("Correo invalido", 422);
                }

                if (
                    empty($nombre) || empty($apellido) || empty($direccion) || empty($correo)
                ) {
                    throw new Exception("Existen datos vacios", 422);
                }

                if (!ctype_digit($telefono) || !ctype_digit($cedula)) {
                    throw new Exception("Se estan enviado datos no numericos enteros", 422);
                }

                if (!preg_match('/^[SCDV]$/', $estadoCivil)) {
                    throw new Exception("Estado civil invalido", 422);
                }

                // Validar el campo fechaNacimiento
                if (!strtotime($fechaNacimiento) || strtotime($datos["fechaNacimiento"]) > time()) {
                    throw new Exception("Fecha de nacimiento invalida", 422);
                }

                $this->validar_correo_cedula($correo, $cedula, 2);

                $this->nombre = $nombre;
                $this->apellido = $apellido;
                $this->telefono = $telefono;
                $this->direccion = $direccion;
                $this->cedula = $cedula;
                $this->estadoCivil = $estadoCivil;
                $this->fechaNacimiento = $fechaNacimiento;
                $this->correo = $correo;

            } else {
                $preguntaSecurity = trim($datos["preguntaSecurity"]);
                $respuestaSecurity = trim($datos["respuestaSecurity"]);

                if (empty($preguntaSecurity) || empty($respuestaSecurity)) {
                    throw new Exception("Existen datos vacios", 422);
                }

                $this->preguntaSecurity = $preguntaSecurity;
                $this->respuestaSecurity = $respuestaSecurity;
            }

            return true;

        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function validar_correo_cedula($correo, $cedula, $opcion)
    {
        try {

            if ($opcion == 1) {
                $sql = "SELECT `correo` FROM `usuario` WHERE correo = :correo";
                $stmt = $this->prepare($sql);

                $stmt->bindValue('correo', $correo);

                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    throw new Exception("Este correo ya se encuentra en uso", 422);
                }

                
                $sql = "SELECT `cedula` FROM `usuario` WHERE cedula = :cedula";
                $stmt = $this->prepare($sql);

                $stmt->bindValue('cedula', $cedula);

                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    throw new Exception("Esta cedula ya se encuentra asociada a un usuario", 422);
                }

            }else{
                $sql = "SELECT `correo` FROM `usuario` WHERE correo = :correo AND cedula != :cedula";
                $stmt = $this->prepare($sql);

                $stmt->bindValue('correo', $correo);
                $stmt->bindValue('cedula', $this->cedula);

                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    throw new Exception("Este correo ya se encuentra en uso", 422);
                }


                $sql = "SELECT `cedula` FROM `usuario` WHERE cedula = :cedula AND cedula != ".$this->cedula;
                $stmt = $this->prepare($sql);

                $stmt->bindValue('cedula', $cedula);

                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    throw new Exception("Esta cedula ya se encuentra asociada a un usuario", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function getSedes()
    {
        $sql = "SELECT * FROM sede WHERE sede.estatus = '1'";

        try {
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            //print_r($error_data);
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }



    /** Mapea los valores de un formulario post a las propiedades del objeto */
    public function mapFromPost(): bool
    {
        if (!empty($_POST)) {
            foreach ($_POST as $key => $value) {
                if (property_exists($this, $key)) {
                    $this->$key = trim($value);
                }
            }
            return true;
        }
        return false;
    }


    public function listarEstudiantes()
    {

        try {

            $sql = "SELECT usuario.* FROM usuario
            INNER JOIN usuariorol ON usuario.id = usuariorol.idUsuario AND usuariorol.idRol = '10'
             WHERE usuario.estatus = '1'";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            //print_r($error_data);
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }

    }

    public function validarRegistrarEstudiante($cedula)
    {

        try {

            $sql = "SELECT CONCAT(nombre, ' ', apellido) AS nombreCompleto, id, cedula, aprobarUsuario FROM discipulo
             WHERE cedula = :cedula AND estatus = '1' AND aprobarUsuario NOT IN ('2')";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':cedula', $cedula);

            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($stmt->rowCount() == 1) {
                if ($resultado['aprobarUsuario'] == 1) {
                    $array2 = array("tipo" => 'discipulo');
                    $resultadoFinal = array_merge($resultado, $array2);
                    return $resultadoFinal;
                } else if ($resultado['aprobarUsuario'] == 0) {
                    throw new Exception("El Discipulo aun no cumple con las 5 Asistencias", 422);
                }

                //   falta validar si ya tiene usuario creado o si el discipulo esta marcado en '2' pero no tiene usuario

            } else {
                $usuario = Usuario::cargarPorCedula($cedula);
                if ($usuario == null) {
                    throw new Exception("Esta Cedula no corresponde a ningun Usuario ni Discipulo inscrito en el sistema", 422);
                } else {
            
                if ($usuario->tieneRol("Estudiante")) {
                    throw new Exception("Este estudiante ya esta inscrito en la escuela de Impulso y Desarrollo de la Sede" . $usuario->idSede . ".", 422);

                } else{
                    return array("nombreCompleto" => $usuario->getNombreCompleto(), "id" => $usuario->id, "cedula" => $usuario->getCedula(), "tipo" => 'usuario');
                }}
            }


        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }

    }

    public function RegistrarEstudiante($id, $tipo)
    {

        try {

            if ($tipo == 'usuario') {

                $sql2 = "UPDATE usuario SET fechaInscripcionEscuela = CURDATE() WHERE id = :id";
                
                $stmt2 = $this->db->pdo()->prepare($sql2);

                $stmt2->bindValue(':id', $id);

                $stmt2->execute();

                $sql = "INSERT INTO usuariorol(idUsuario, idRol)
                VALUES(:idUsuario, :idRol)";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idUsuario', $id);
                $stmt->bindValue(':idRol', '10');

                $stmt->execute();

                /** @var Usuario */
                $Usuario = Usuario::cargar($id);

                Bitacora::registrar("Registro de Estudiante" . $Usuario->getNombreCompleto() . " exitoso.");

                http_response_code(200);
                echo json_encode(array('msj' => 'Registro de Estudiante exitoso', 'status' => 200));
                die();

            }

            if ($tipo == 'discipulo') {
                /** @var Discipulo */
                $discipulo = Discipulo::cargar($id);
                /** @var Usuario */
                $idSede = Usuario::cargar($discipulo->getIdConsolidador());


                $sql = "INSERT INTO usuario(idSede, cedula, clave, nombre, apellido, telefono, direccion, estadoCivil, fechaNacimiento, fechaInscripcionEscuela)
            VALUES(:idSede, :cedula, :clave, :nombre, :apellido, :telefono, :direccion, :estadoCivil, :fechaNacimiento, CURDATE())";


            $clave = password_hash($discipulo->getCedula(), PASSWORD_DEFAULT);

            // Registra al usuario
            
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue('idSede', $idSede->idSede);
            $stmt->bindValue('cedula', $discipulo->getCedula());
            $stmt->bindValue('clave', $clave);
            $stmt->bindValue('nombre', $discipulo->getNombre());
            $stmt->bindValue('apellido', $discipulo->getApellido());
            $stmt->bindValue('telefono', $discipulo->getTelefono());
            $stmt->bindValue('direccion', $discipulo->getDireccion());
            $stmt->bindValue('estadoCivil', $discipulo->getEstadoCivil());
            $stmt->bindValue('fechaNacimiento', $discipulo->getFechaNacimiento());

            $stmt->execute();

            // Registra los roles del usuario
            $idUsuario = $this->db->pdo()->lastInsertId();

            $sql2 = "INSERT INTO usuariorol(idUsuario, idRol)
                VALUES(:idUsuario, :idRol)";

                $stmt2 = $this->db->pdo()->prepare($sql2);

                $stmt2->bindValue(':idUsuario', $idUsuario);
                $stmt2->bindValue(':idRol', '10');

                $stmt2->execute();

                $sql3 = "UPDATE discipulo SET aprobarUsuario = '2', estatus = '0' WHERE id = :id";
                
                $stmt3 = $this->db->pdo()->prepare($sql3);

                $stmt3->bindValue(':id', $id);

                $stmt3->execute();

                /** @var Usuario */
                $Usuario = Usuario::cargar($idUsuario);

                Bitacora::registrar("Registro de Estudiante" . $Usuario->getNombreCompleto() . " exitoso.");

                http_response_code(200);
                echo json_encode(array('msj' => 'Registro de Estudiante exitoso', 'status' => 200));
                die();

            }



        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }

    }


    // Getters
    public function getEdad(): int
    {
        if (empty($this->fechaNacimiento)) {
            return 0;
        }
        $nacimiento = new DateTime($this->fechaNacimiento);
        $edad = (new DateTime())->diff($nacimiento)->y;
        return $edad;
    }

    public function getNombreCompleto(): string
    {
        return $this->nombre . " " . $this->apellido;
    }
    public function getNombre(): string
    {
        return $this->nombre ?? "";
    }
    public function getApellido(): string
    {
        return $this->apellido ?? "";
    }
    public function getCorreo(): ?string
    {
        return $this->correo ?? null;
    }
    public function getCedula(): string
    {
        return $this->cedula ?? "";
    }
    public function getTelefono(): string
    {
        return $this->telefono ?? "";
    }
    public function getDireccion(): string
    {
        return $this->direccion ?? "";
    }
    public function getEstadoCivil(): string
    {
        return $this->estadoCivil ?? "";
    }
    public function getFechaNacimiento(): string
    {
        return $this->fechaNacimiento ?? "";
    }
    public function getClaveEncriptada(): string
    {
        return $this->clave ?? "";
    }
    public function getPreguntaSecurity(): string
    {
        return $this->preguntaSecurity ?? "";
    }
    public function getRespuestaSecurity(): string
    {
        return $this->respuestaSecurity ?? "";
    }
}
?>