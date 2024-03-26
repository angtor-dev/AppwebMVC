<?php
require_once "Models/Model.php";

class Grupo extends Model
{
    public int $id;
    private string $codigo;

    private int $idNivel;
    private int $identificador;
    private int $idMentor;
    private string $fechaInicio;
    private ?string $fechaFin;
    private int $estado;
    private int $estatus;


    public function registrarGrupo($idNivel, $idMentor)
    {

        try {

            /** @var Nivel **/
            $Nivel = Nivel::cargar($idNivel);

            /** @var Usuario */
            $usuario = $_SESSION['usuario'];

            /** @var Sede **/
            $Sede = Sede::cargar($usuario->idSede);

            $query = "SELECT MAX(identificador) AS conteo FROM grupo INNER JOIN usuario 
            ON usuario.id = grupo.idMentor AND usuario.idSede = :idSede WHERE grupo.estatus = '1' ORDER  BY identificador ASC";
            $consultanivel = $this->db->pdo()->prepare($query);

            $consultanivel->bindValue(':idSede', $usuario->idSede);
            $consultanivel->execute();
            $datos = $consultanivel->fetch(PDO::FETCH_ASSOC);

            $identificador = '';
            $codigo = '';


            if ($datos['conteo'] == null) {
                $identificador = 1;
                $codigo = $Sede->getCodigo() . '-' . $Nivel->getCodigo() . '-G' . $identificador;
            } else {
                $identificador = $datos['conteo'] + 1;
                $codigo = $Sede->getCodigo() . '-' . $Nivel->getCodigo() . '-G' . $identificador;
            }

            $sql = "INSERT INTO grupo (codigo, identificador, idNivel, idMentor, fechaInicio) VALUES (:codigo, :identificador, :idNivel, :idMentor, CURDATE())";
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':codigo', $codigo);
            $stmt->bindValue(':identificador', $identificador);
            $stmt->bindValue(':idNivel', $idNivel);
            $stmt->bindValue(':idMentor', $idMentor);

            $stmt->execute();


            Bitacora::registrar("Registro de grupo" . $codigo . "");

            http_response_code(200);
            echo json_encode(array('msj' => 'El grupo  ' . $codigo . '  se ha registrado exitosamente.', 'status' => 200));
            die();

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }

    }



    public function listarGrupos($tipo)
    {

        try {

            /** @var Usuario */
            $usuario = $_SESSION['usuario'];

            if ($usuario->tieneRol('Estudiante') || $usuario->tieneRol('Mentor')) {

                if ($usuario->tieneRol('Estudiante')) {

                    $sql = "SELECT usuario.nombre, usuario.apellido, grupo.* 
                FROM grupo
                INNER JOIN usuario ON usuario.id = grupo.idMentor
                INNER JOIN matricula ON matricula.idGrupo = grupo.id AND matricula.idEstudiante = :idEstudiante
                WHERE grupo.estado = :tipo AND grupo.estatus = '1'";

                    $stmt = $this->db->pdo()->prepare($sql);

                    $stmt->bindValue(':tipo ', $tipo);
                    $stmt->bindValue(':idEstudiante', $usuario->id);

                    $stmt->execute();
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $resultado;


                }


                if ($usuario->tieneRol('Mentor')) {

                    $sql = "SELECT usuario.nombre, usuario.apellido, grupo.* 
                FROM grupo
                INNER JOIN usuario ON usuario.id = grupo.idMentor
                WHERE idMentor = :idMentor AND grupo.estado = :tipo AND grupo.estatus = '1'";

                    $stmt = $this->db->pdo()->prepare($sql);

                    $stmt->bindValue(':tipo', $tipo);
                    $stmt->bindValue(':idMentor', $usuario->id);

                    $stmt->execute();
                    $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    return $resultado;

                }
            } else {

                $sql = "SELECT COUNT(matricula.idEstudiante) AS estudiantes,
                 CONCAT (usuario.cedula, ' ', usuario.nombre, ' ', usuario.apellido) AS infoMentor, grupo.*, 
            eid.id AS idEid FROM grupo
            INNER JOIN usuario ON usuario.id = grupo.idMentor
            LEFT JOIN matricula ON matricula.idGrupo = grupo.id
            INNER JOIN nivel ON nivel.id = grupo.idNivel
            INNER JOIN moduloeid ON moduloeid.id = nivel.idModuloEid
            INNER JOIN eid ON eid.id = moduloeid.idEid
            WHERE grupo.estado = :tipo AND grupo.estatus = '1' GROUP BY grupo.id";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':tipo', $tipo);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }

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

    public function listarHistorialGrupos($idEstudiante)
    {

        try {



            $sql = "SELECT usuario.nombre, usuario.apellido, grupo.*, matricula.notaTotal,
                CASE WHEN matricula.estado = '1' THEN 'Cursando'
                WHEN matricula.estado = '2' THEN 'Aprobado'
                WHEN matricula.estado = '3' THEN 'Reprobado'
                END AS estadoMatricula FROM grupo
                INNER JOIN usuario ON usuario.id = grupo.idMentor
                INNER JOIN matricula ON matricula.idGrupo = grupo.id AND matricula.idEstudiante = :idEstudiante
                 AND estatus = '1'";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idEstudiante', $idEstudiante);

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



    public function editarGrupo($id, $idNivel, $idMentor)
    {

        try {
            /** @var Grupo**/
            $consulta = Grupo::cargar($id);

            /** @var Nivel **/
            $Nivel = Nivel::cargar($idNivel);

            /** @var Usuario */
            $usuario = Usuario::cargar($idMentor);

            /** @var Sede **/
            $Sede = Sede::cargar($usuario->idSede);
          
            $codigo = '';


            if ($consulta->getidNivel() != $idNivel) {

                $this->existenciaMatricula($id);

                    $codigo = $Sede->getCodigo() . $Nivel->getCodigo() . '-G' . $consulta->getIdentificador();
                }else{

                    $codigo = $consulta->getCodigo();
                }
            

            $identificador = $consulta->getIdentificador();
       

            $sql = "UPDATE grupo 
            SET codigo = :codigo, identificador = :identificador, idNivel = :idNivel, idMentor = :idMentor
            WHERE id = :id";
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':codigo', $codigo);
            $stmt->bindValue(':identificador', $identificador);
            $stmt->bindValue(':idNivel', $idNivel);
            $stmt->bindValue(':idMentor', $idMentor);

            $stmt->execute();


            Bitacora::registrar("Actualizacion de grupo " . $codigo . ".");

            http_response_code(200);
            echo json_encode(array('msj' => 'El grupo  ' . $codigo . '  ha sido actualizado exitosamente.', 'status' => 200));
            die();

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }


    public function eliminarGrupo($id)
    {

        try {

            $this->existenciaClases($id);
            $this->existenciaMatricula($id);

            $query = "UPDATE grupo SET estatus = '0' WHERE id = :id";
            $stmt = $this->db->pdo()->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();

            http_response_code(200);
            echo json_encode(array('msj' => 'Grupo eliminado correctamente', 'status' => 200));
            die();

        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }


    private function existenciaMatricula($id)
    {

        try {

            $query = "SELECT grupo.id FROM grupo INNER JOIN matricula
            ON matricula.idGrupo = :id";

            $stmt = $this->db->pdo()->prepare($query);

            $stmt->bindValue(':id', $id);

            $stmt->execute();
            $stmt->fetch(PDO::FETCH_ASSOC);


            if ($stmt->rowCount() >= 1) {
                throw new Exception("Este grupo no puede ser editado ni eliminado ya que posee matricula de estudiantes vinculados", 422);
            }

        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    private function existenciaClases($id)
    {

        try {

            $query = "SELECT grupo.id FROM grupo INNER JOIN clase
            ON clase.idGrupo = :id";

            $stmt = $this->db->pdo()->prepare($query);

            $stmt->bindValue(':id', $id);

            $stmt->execute();
           $stmt->fetch(PDO::FETCH_ASSOC);


            if ($stmt->rowCount() >= 1) {
                throw new Exception("Este grupo no puede ser editado ni eliminado ya que posee Clases vinculadas", 422);
            }

        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }


    public function registrarMatricula($cedula, $idGrupo)
    {

        try {
            /** @var Usuario */
            $estudiante = Usuario::cargarPorCedula($cedula);

            /** @var Grupo */
            $Grupo = Grupo::cargar($idGrupo);

            $this->validarExistenciaEstudianteGrupo($cedula);
            $this->validarEstudianteAprobado($cedula, $Grupo->getidNivel());

            $sql = "INSERT INTO matricula (idGrupo, idEstudiante) VALUES (:idGrupo, :idEstudiante)";
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idGrupo', $idGrupo);
            $stmt->bindValue(':idEstudiante', $estudiante->id);


            $stmt->execute();


            Bitacora::registrar("Estudiante " . $estudiante->getNombreCompleto() . "matriculado en el grupo " . $Grupo->getCodigo() . ".");

            http_response_code(200);
            echo json_encode(array('msj' => 'Estudiante ' . $estudiante->getNombreCompleto() . 'matriculado en el grupo ' . $Grupo->getCodigo() . ' exitosamente.', 'status' => 200));
            die();



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

    public function eliminarMatricula($idEstudiante, $idGrupo)
    {

        try {
            /** @var Usuario */
            $estudiante = Usuario::cargar($idEstudiante);

            /** @var Grupo */
            $Grupo = Grupo::cargar($idGrupo);

            $sql = "SELECT id FROM grupo WHERE id = :idGrupo AND estado = '1'";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':idGrupo', $idGrupo);
            $stmt->execute();
            $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() == 1) {

                $sql = "DELETE FROM matricula WHERE idGrupo = :idGrupo AND idEstudiante = :idEstudiante";
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(':idGrupo', $idGrupo);
                $stmt->bindValue(':idEstudiante', $idEstudiante);
                $stmt->execute();

                Bitacora::registrar("Se elimino el estudiante " . $estudiante->getNombreCompleto() . "de el grupo " . $Grupo->getCodigo() . ".");

                http_response_code(200);
                echo json_encode(array('msj' => 'Estudiante ' . $estudiante->getNombreCompleto() . ' eliminado con exito.', 'status' => 200));
                die();

            } else {

                throw new Exception("Solo se pueden eliminar estudiantes de grupos Abiertos", 422);

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

    public function listarMatricula($idGrupo)
    {

        try {
            $sql = "SELECT usuario.id, usuario.cedula,CONCAT(usuario.nombre, ' ', usuario.apellido) AS nombres,  matricula.notaTotal,
                CASE WHEN matricula.estado = '1' THEN 'Cursando'
                WHEN matricula.estado = '2' THEN 'Aprobado'
                WHEN matricula.estado = '3' THEN 'Reprobado'
                END AS estado FROM matricula
                LEFT JOIN usuario ON usuario.id = matricula.idEstudiante
                WHERE matricula.idGrupo = :idGrupo";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idGrupo', $idGrupo);

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

    public function listarMentores()
    {

        try {
             
            /** @var Usuario */
        $usuario = $_SESSION['usuario'];

        if ($usuario->tieneRol('SuperUsuario') ){

            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
                FROM usuariorol 
                INNER JOIN usuario ON usuario.id = usuariorol.idUsuario 
                WHERE usuario.estatus = '1' AND usuariorol.idRol = '11'";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } else {

            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol 
            INNER JOIN usuario ON usuario.id = usuariorol.idUsuario 
            WHERE usuario.estatus = '1' AND usuario.idSede :idSede AND usuariorol.idRol = '11'";

        $stmt = $this->db->pdo()->prepare($sql);
        $stmt->bindValue(':idSede', $usuario->idSede);

        $stmt->execute();
        $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $resultado;


        }


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

    private function validarExistenciaEstudianteGrupo($cedula)
    {

        try {
            /** @var Usuario */
            $estudiante = Usuario::cargarPorCedula($cedula);
            
            if ($estudiante != null){

            if ($estudiante->tieneRol('Estudiante')) {

                /** @var Usuario */
                $usuario = $_SESSION['usuario'];

                /** @var Sede **/
                $Sede = Sede::cargar($estudiante->idSede);


                if ($estudiante->idSede == $usuario->idSede) {


                    $query = "SELECT grupo.codigo, CASE
                WHEN grupo.estado = '1' THEN 'Abierto'
                WHEN grupo.estado = '2' THEN 'Activo'
                END AS estadoGrupo FROM grupo INNER JOIN matricula 
                ON matricula.idEstudiante = :idEstudiante AND matricula.idGrupo = grupo.id 
                WHERE (grupo.estatus = '1') AND (grupo.estado = '1' OR grupo.estado = '2')";

                    $stmt = $this->db->pdo()->prepare($query);

                    $stmt->bindValue(':idEstudiante', $estudiante->id);
                    $stmt->execute();
                    $consulta = $stmt->fetch(PDO::FETCH_ASSOC);


                    if ($stmt->rowCount() >= 1) {
                        throw new Exception("Este Estudiante no puede ser inscrito ya que actualmente pertenece a el grupo " . $consulta['codigo'] . " en estado: " . $consulta['estadoGrupo'] . ".", 422);
                    }

                } else {
                    throw new Exception("Este Estudiante no puede ser inscrito a este grupo porque pertenece a la sede" . $Sede->getCodigo() . " " . $Sede->getNombre() .
                        ".", 422);
                }
            } else {
                throw new Exception("Esta Cedula no corresponde a ningun Estudiante inscrito en la EID", 422);
            } }else {
                throw new Exception("Esta cedula no corresponde a ningun usuario resgistrado en el sistema", 422);
            }

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }

    }


    private function validarEstudianteAprobado($cedula, $idNivel)
    {

        try {
            /** @var Usuario */
            $estudiante = Usuario::cargarPorCedula($cedula);


            /** @var Nivel **/
            $Nivel = Nivel::cargar($idNivel);

            $query1 = "SELECT * FROM grupo
                INNER JOIN matricula 
                ON matricula.idEstudiante = :idEstudiante AND matricula.idGrupo = grupo.id AND matricula.estado = '2' 
                WHERE grupo.idNivel = :idNivel";

            $stmt1 = $this->db->pdo()->prepare($query1);

            $stmt1->bindValue(':idEstudiante', $estudiante->id);
            $stmt1->bindValue(':idNivel', $idNivel);

            $stmt1->execute();
            $consulta1 = $stmt1->fetch(PDO::FETCH_ASSOC);

            if ($stmt1->rowCount() == 0) {
                if ($Nivel->getNivel() != 1) {

                    $nivelAnterior = $Nivel->getNivel() - 1;
                    /** @var Nivel **/
                    $nivelRequerido = Nivel::cargarpornivelymodulo($Nivel->getIdModuloEid(), $nivelAnterior);

                    $query = "SELECT * FROM grupo
                INNER JOIN matricula 
                ON matricula.idEstudiante = :idEstudiante AND matricula.idGrupo = grupo.id AND matricula.estado = '2' 
                WHERE grupo.idNivel = :idNivel";

                    $stmt = $this->db->pdo()->prepare($query);

                    $stmt->bindValue(':idEstudiante', $estudiante->id);
                    $stmt->bindValue(':idNivel', $nivelRequerido->id);

                    $stmt->execute();
                    $consulta = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($stmt->rowCount() == 0) {
                        throw new Exception("No se puede inscribir a el grupo porque el nivel requerido ''" . $nivelRequerido->getCodigo() . "'' no ha sido aprobado por el estudiante", 422);
                    }

                } else {

                    /** @var Moduloeid **/
                    $Moduloeid = Moduloeid::cargar($Nivel->getIdModuloEid());

                    if ($Moduloeid->getNivel() != 1) {

                        /** @var Moduloeid **/
                        $ModuloEidAnterior = Moduloeid::cargarModuloAnterior($Moduloeid->id);

                        $query = "SELECT * FROM grupo
                    INNER JOIN matricula 
                    ON matricula.idEstudiante = :idEstudiante AND matricula.idGrupo = grupo.id AND matricula.estado = '2' 
                    WHERE grupo.idNivel = :idNivel";

                        $stmt = $this->db->pdo()->prepare($query);

                        $stmt->bindValue(':idEstudiante', $estudiante->id);
                        $stmt->bindValue(':idNivel', $ModuloEidAnterior->getUltimoNivel());

                        $stmt->execute();
                        $consulta = $stmt->fetch(PDO::FETCH_ASSOC);


                        if ($stmt->rowCount() == 0) {
                            throw new Exception("No se puede inscribir a el grupo porque el estudiante no ha aprobado el modulo ''" . $ModuloEidAnterior->getCodigo() . "''.", 422);
                        }

                    } else {

                        /** @var Eid **/
                        $Eid = Eid::cargar($Moduloeid->getIdEid());

                        $Eid->getValidarRolesRequeridos($estudiante->id);
                        $Eid->getValidarEidsRequerido($estudiante->id);

                    }
                }
            } else {
                throw new Exception("Este Estudiante no se puede inscribir porque ya ha aprobado este nivel.", 422);
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

    public function activarGrupo($idGrupo)
    {

        try {

            $query = "SELECT grupo.id FROM grupo INNER JOIN matricula
            ON matricula.idGrupo = grupo.id WHERE grupo.id = :id";

            $stmt = $this->db->pdo()->prepare($query);

            $stmt->bindValue(':id', $idGrupo);

            $stmt->execute();
            $stmt->fetch(PDO::FETCH_ASSOC);


            if ($stmt->rowCount() == 0) {
                
                throw new Exception("Este grupo no puede pasar a estra activo porque aun no tiene matricula", 422);
               

            } else {

                /** @var Grupo */
                $Grupo = Grupo::cargar($idGrupo);

                $sql = "UPDATE grupo SET estado = '2' WHERE id = :idGrupo";
                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idGrupo', $idGrupo);

                $stmt->execute();

                Bitacora::registrar("El grupo " . $Grupo->getCodigo() . " ahora es un grupo Activo");

                http_response_code(200);
                echo json_encode(array('msj' => 'El grupo ya es visible para sus Estudiantes y Profesor correspondiente', 'status' => 200));
                die();

            }

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function cerrarGrupo($idGrupo)
    {

        try {

            $this->validarNotasGrupo($idGrupo); 
            $this->validarNotasEstudiantes($idGrupo);


            /** @var Grupo */
            $Grupo = Grupo::cargar($idGrupo);

            $sql = "UPDATE grupo SET estado = '3', fechaFin = CURDATE() WHERE id = :idGrupo";
            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idGrupo', $idGrupo);

            $stmt->execute();

            Bitacora::registrar("El grupo " . $Grupo->getCodigo() . " ahora es un grupo Cerrado");

            http_response_code(200);
            echo json_encode(array('msj' => 'El grupo ya no es visible para Mentores ni estudiantes', 'status' => 200));
            die();


        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }


    public function validarAsignarRolesAdqr($idGrupo, $idEid)
    {
        try {



            $sql1 = "SELECT rol.id, rol.nombre FROM rol 
            INNER JOIN controleid ON rol.id = controleid.idRolAdquirido AND controleid.idEid = :id";
            $stmt1 = $this->db->pdo()->prepare($sql1);
            $stmt1->bindValue(":id", $idEid);
            $stmt1->execute();

            $stmt1->fetchAll(PDO::FETCH_ASSOC);

            if ($stmt1->rowCount() == 0) {

                return false;

            } else {

                $sql2 = "SELECT id FROM grupo WHERE id = :id AND asignacionRoles = '2'";
                $stmt2 = $this->db->pdo()->prepare($sql2);
                $stmt2->bindValue(":id", $idGrupo);
                $stmt2->execute();
                $stmt2->fetchAll(PDO::FETCH_ASSOC);

                if ($stmt2->rowCount() == 0) {

                    return '1';

                } else {

                    return true;
                }
            }


        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function asignarRolesAdqr($idGrupo, $idEid)
    {
        try {

            $sql = "SELECT matricula.idEstudiante FROM matricula
        WHERE matricula.idGrupo = :idGrupo AND matricula.estado = '2'";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":id", $this->id);
            $stmt->execute();

            $Estudiantes = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sql1 = "SELECT rol.id, rol.nombre FROM rol 
            INNER JOIN controleid ON rol.id = controleid.idRolAdquirido AND controleid.idEid = :id";
            $stmt1 = $this->db->pdo()->prepare($sql1);
            $stmt1->bindValue(":id", $idEid);
            $stmt1->execute();

            $roles = $stmt->fetchAll(PDO::FETCH_ASSOC);


            foreach ($roles as $rol) {
                foreach ($Estudiantes as $Estudiante) {
                    /** @var Usuario */
                    $Usuario = Usuario::cargar($Estudiante['idEstudiante']);

                    if (empty($Usuario->tieneRol($rol['nombre']))) {

                        $sql = "INSERT INTO usuariorol(idUsuario, idRol)
                VALUES(:idUsuario, :idRol)";

                        $stmt = $this->db->pdo()->prepare($sql);

                        $stmt->bindValue(':idUsuario', $Estudiante['idEstudiante']);
                        $stmt->bindValue(':idRol', $rol['id']);

                        $stmt->execute();

                    }

                }
            }

            "UPDATE grupo SET asignacionRoles = '2' WHERE idGrupo = :idGrupo";


            $stmt1 = $this->db->pdo()->prepare($sql);
            $stmt1->bindValue(':idGrupo', $idGrupo);
          
            $stmt1->execute();


            /** @var Grupo */
            $Grupo = Grupo::cargar($idGrupo);

            Bitacora::registrar("Se asignaron los roles requeridos al grupo " . $Grupo->getCodigo() . " de forma exitosa.");

            http_response_code(200);
            echo json_encode(array('msj' => 'Se asignaron los roles a los Estudiantes aprobados de Manera exitosa', 'status' => 200));
            die();

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }


    }
    private function validarNotasGrupo($idGrupo)
    {
        try {

            $query = "SELECT id, SUM(ponderacion) AS ponderacionTotal 
            FROM clase WHERE idGrupo = :idGrupo AND estatus = '1' GROUP BY idGrupo";
            $stmt = $this->db->pdo()->prepare($query);

            $stmt->bindValue(':idGrupo', $idGrupo);

            $stmt->execute();
            $dato = $stmt->fetch(PDO::FETCH_ASSOC);


            if ($dato['ponderacionTotal'] != 100) {

                $cantidad = 100 - $dato['ponderacionTotal'];

                throw new Exception("El grupo no puede cerrar si no se a evaluado el 100% falta por evaluar " . $cantidad . "%.", 422);

            }

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    private function validarNotasEstudiantes($idGrupo)
    {
        try {
            $query = "SELECT idEstudiante FROM matricula WHERE idGrupo = :idGrupo";
            $stmt = $this->db->pdo()->prepare($query);

            $stmt->bindValue(':idGrupo', $idGrupo);
            $stmt->execute();
            $dato = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($dato as $id) {

                $query2 = "SELECT idEstudiante, SUM(calificacion) AS notaTotal FROM nota 
                INNER JOIN clase ON idGrupo = :idGrupo AND clase.id = nota.idClase
                WHERE nota.idEstudiante = :idEstudiante GROUP BY nota.idEstudiante";

                $stmt1 = $this->db->pdo()->prepare($query2);
                $stmt1->bindValue(':idGrupo', $idGrupo);
                $stmt1->bindValue(':idEstudiante', $id['idEstudiante']);
                $stmt1->execute();
                $dato1 = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($dato1['notaTotal'] <= 69.00) {


                    $sql = "UPDATE matricula SET notaTotal = :notaTotal, estado = '3' 
                WHERE idEstudiante = :idEstudiante AND idGrupo = :idGrupo";

                    $stmt1 = $this->db->pdo()->prepare($sql);
                    $stmt1->bindValue(':idGrupo', $idGrupo);
                    $stmt1->bindValue(':idEstudiante', $id['idEstudiante']);
                    $stmt1->bindValue(':notaTotal', $dato1['notaTotal']);
                    $stmt1->execute();



                }

                if ($dato1['notaTotal'] >= 70.00) {


                    $sql = "UPDATE matricula SET notaTotal = :notaTotal, estado = '2' 
                    WHERE idEstudiante = :idEstudiante AND idGrupo = :idGrupo";

                    $stmt1 = $this->db->pdo()->prepare($sql);
                    $stmt1->bindValue(':idGrupo', $idGrupo);
                    $stmt1->bindValue(':idEstudiante', $id['idEstudiante']);
                    $stmt1->bindValue(':notaTotal', $dato1['notaTotal']);
                    $stmt1->execute();



                }
            }


        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function listarNiveles()
    {

        try {

            $sql = "SELECT * FROM nivel WHERE estatus = '1'";

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

    public function getCodigo()
    {
        return $this->codigo;
    }

    public function getidNivel()
    {
        return $this->idNivel;
    }

    public function getIdentificador()
    {
        return $this->identificador;
    }

}
