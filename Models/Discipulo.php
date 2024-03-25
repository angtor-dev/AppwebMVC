<?php
require_once "Models/Model.php";

class Discipulo extends Model
{
    public int $id;
    private ?int $idConsolidador;
    private int $idCelulaConsolidacion;
    private ?string $asisFamiliar;
    private ?string $asisCrecimiento;
    private string $cedula;
    private string $nombre;
    private string $apellido;
    private string $telefono;
    private string $direccion;
    private string $estadoCivil;
    private string $fechaNacimiento;
    private string $motivo;
    private string $fechaConvercion;
    private int $aprobarUsuario;
    private int $estatus;

    private $expresion_nombreApellido = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ]{1,50}$/';
    private $expresion_fecha = '/^\d{4}-\d{2}-\d{2}$/';
    private $expresion_texto = '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/';
    private $expresion_estadoCivil = ["casado/a", "soltero/a", "viudo/a"];
    private $expresion_cedula = '/^[0-9]{7,8}$/';
    private $expresion_asiste = ['si', 'no'];
    private $expresion_telefono = '/^(0414|0424|0416|0426|0412)[0-9]{7}/';


    public function registrar_discipulo(
        $asisCrecimiento,
        $asisFamiliar,
        $idConsolidador,
        $idcelulaconsolidacion,
        $cedula,
        $nombre,
        $apellido,
        $telefono,
        $direccion,
        $estadoCivil,
        $motivo,
        $fechaNacimiento,
        $fechaConvercion
    ) {
        try {

            $sql = "INSERT INTO discipulo (
            asisCrecimiento,
            asisFamiliar,
            idConsolidador,
            idCelulaConsolidacion,
            cedula,
            nombre,
            apellido,
            telefono,
            direccion,
            estadoCivil,
            motivo,
            fechaNacimiento,
            fechaConvercion
          ) VALUES (
            :asisCrecimiento,
            :asisFamiliar,
            :idConsolidador,
            :idcelulaconsolidacion,
            :cedula,
            :nombre,
            :apellido,
            :telefono,
            :direccion,
            :estadoCivil,
            :motivo,
            :fechaNacimiento,
            :fechaConvercion
          )";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':asisCrecimiento', $asisCrecimiento);
            $stmt->bindValue(':asisFamiliar', $asisFamiliar);
            $stmt->bindValue(':idConsolidador', $idConsolidador);
            $stmt->bindValue(':idcelulaconsolidacion', $idcelulaconsolidacion);
            $stmt->bindValue(':cedula', $cedula);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':apellido', $apellido);
            $stmt->bindValue(':telefono', $telefono);
            $stmt->bindValue(':direccion', $direccion);
            $stmt->bindValue(':estadoCivil', $estadoCivil);
            $stmt->bindValue(':motivo', $motivo);
            $stmt->bindValue(':fechaNacimiento', $fechaNacimiento);
            $stmt->bindValue(':fechaConvercion', $fechaConvercion);

            //Ahora ejecutemos la consulta sql una vez ingresado todos los valores, es decir, los parametros que mencionamos arriba
            $stmt->execute();

            Bitacora::registrar("Registro de discipulo");

            http_response_code(200);
            echo json_encode(array('msj' => 'Discipulo registrado correctamente', 'status' => 200));
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


    public function listar_Consolidador()
    {

        try {
            /** @var Usuario */
            $usuario = $_SESSION['usuario'];

            if ($usuario->tieneRol('SuperUsuario')) {
                $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
             FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario WHERE usuario.estatus = '1' AND usuariorol.idRol IN (1, 2, 3, 4)";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }

            if ($usuario->tieneRol('Pastor') || $usuario->tieneRol('LiderTerritorio')) {

                $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
             FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario WHERE usuario.idSede = :idSede AND usuario.estatus = '1' AND usuariorol.idRol IN (1, 2, 3, 4)";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idSede', $usuario->idSede);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
            }

            if ($usuario->tieneRol('Lider')) {

                $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
             FROM usuariorol INNER JOIN usuario ON usuario.idS = :idLider AND usuario.estatus = '1'";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idLider', $usuario->id);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
                return $resultado;
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



    public function listar_celulas()
    {

        try {

            /** @var Usuario */
            $usuario = $_SESSION['usuario'];

            if ($usuario->tieneRol('SuperUsuario')) {
                $sql = "SELECT Lider.id AS idLider,
            CoLider.id AS idCoLider,
            Lider.nombre AS nombreLider,
            CoLider.nombre AS nombreCoLider,
            Lider.apellido AS apellidoLider,
            CoLider.apellido AS apellidoCoLider,
            Lider.cedula AS cedulaLider,
            CoLider.cedula AS cedulaCoLider,
            celulas.id,
            celulas.idLider,
            celulas.idCoLider,
            celulas.idTerritorio,
            celulas.codigo,
            celulas.nombre,
            celulas.estatus
            FROM celulas
            INNER JOIN usuario AS Lider ON celulas.idLider = Lider.id
            INNER JOIN usuario AS CoLider ON celulas.idCoLider = CoLider.id
            WHERE celulas.estatus = '1' AND celulas.tipo = 'consolidacion'";


                $stmt = $this->db->pdo()->prepare($sql);


                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);


                return $resultado;
            }

            if ($usuario->tieneRol('Pastor')) {
                $sql = "SELECT Lider.id AS idLider,
                CoLider.id AS idCoLider,
                Lider.nombre AS nombreLider,
                CoLider.nombre AS nombreCoLider,
                Lider.apellido AS apellidoLider,
                CoLider.apellido AS apellidoCoLider,
                Lider.cedula AS cedulaLider,
                CoLider.cedula AS cedulaCoLider,
                celulas.id,
                celulas.idLider,
                celulas.idCoLider,
                celulas.idTerritorio,
                celulas.codigo,
                celulas.nombre,
                celulas.estatus
                FROM celulas
                INNER JOIN usuario AS Lider ON celulas.idLider = Lider.id 
                INNER JOIN usuario AS CoLider ON celulas.idCoLider = CoLider.id
                INNER JOIN territorio ON territorio.id = celulas.idterritorio AND territorio.idSede = :idSede
                WHERE celulas.estatus = '1' AND celulas.tipo = 'consolidacion'";


                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idSede', $usuario->idSede);


                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);


                return $resultado;
            }

            if ($usuario->tieneRol('LiderTerritorio')) {
                $sql = "SELECT Lider.id AS idLider,
                    CoLider.id AS idCoLider,
                    Lider.nombre AS nombreLider,
                    CoLider.nombre AS nombreCoLider,
                    Lider.apellido AS apellidoLider,
                    CoLider.apellido AS apellidoCoLider,
                    Lider.cedula AS cedulaLider,
                    CoLider.cedula AS cedulaCoLider,
                    celulas.id,
                    celulas.idLider,
                    celulas.idCoLider,
                    celulas.idTerritorio,
                    celulas.codigo,
                    celulas.nombre,
                    celulas.estatus
                    FROM celulas
                    INNER JOIN usuario AS Lider ON celulas.idLider = Lider.id 
                    INNER JOIN usuario AS CoLider ON celulas.idCoLider = CoLider.id
                    INNER JOIN territorio ON territorio.id = celulas.idterritorio AND territorio.idLider = :idLider
                    WHERE celulas.estatus = '1' AND celulas.tipo = 'consolidacion'";


                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idLider', $usuario->id);


                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);


                return $resultado;
            }

            if ($usuario->tieneRol('Lider')) {
                $sql = "SELECT Lider.id AS idLider,
                        CoLider.id AS idCoLider,
                        Lider.nombre AS nombreLider,
                        CoLider.nombre AS nombreCoLider,
                        Lider.apellido AS apellidoLider,
                        CoLider.apellido AS apellidoCoLider,
                        Lider.cedula AS cedulaLider,
                        CoLider.cedula AS cedulaCoLider,
                        celulas.id,
                        celulas.idLider,
                        celulas.idCoLider,
                        celulas.idTerritorio,
                        celulas.codigo,
                        celulas.nombre,
                        celulas.estatus
                        FROM celulas
                        INNER JOIN usuario AS Lider ON celulas.idLider = Lider.id 
                        INNER JOIN usuario AS CoLider ON celulas.idCoLider = CoLider.id
                        WHERE celulas.idLider = :idLider AND celulas.estatus = '1' AND celulas.tipo = :tipo";


                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idLider', $usuario->id);


                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                return $resultado;
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

    public function listar_discipulo()
    {

        try {

            /** @var Usuario */
            $usuario = $_SESSION['usuario'];

            if ($usuario->tieneRol("Superusuario")) {
                $sql = "SELECT 
            Consolidador.id AS idConsolidador,
            Consolidador.nombre AS nombreConsolidador,
            Consolidador.apellido AS apellidoConsolidador,
            Consolidador.cedula AS cedulaConsolidador,
            celulas.id AS idCelulaConsolidacion,
            celulas.codigo,
            discipulo.id,
            discipulo.asisCrecimiento,
            discipulo.asisFamiliar,
            discipulo.idConsolidador,
            discipulo.idCelulaConsolidacion,
            discipulo.cedula,
            discipulo.nombre,
            discipulo.apellido,
            discipulo.telefono,
            discipulo.direccion,
            discipulo.estadoCivil,
            discipulo.motivo,
            discipulo.fechaNacimiento,
            discipulo.fechaConvercion,
            COALESCE(COUNT(asistencia.id), 0) AS asistencias
        FROM
            discipulo
            INNER JOIN usuario AS Consolidador ON discipulo.idConsolidador = Consolidador.id
            LEFT JOIN asistencia ON discipulo.id = asistencia.idDiscipulo
            INNER JOIN celulas ON discipulo.idCelulaConsolidacion = celulas.id
        GROUP BY
            discipulo.id";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                Bitacora::registrar("Consulta de discipulo");

                return $resultado;


            } 
            
           if ($usuario->tieneRol("Pastor")) {
                $sql = "SELECT 
            Consolidador.id AS idConsolidador,
            Consolidador.nombre AS nombreConsolidador,
            Consolidador.apellido AS apellidoConsolidador,
            Consolidador.cedula AS cedulaConsolidador,
            celulas.id AS idCelulaConsolidacion,
            celulas.codigo,
            discipulo.id,
            discipulo.asisCrecimiento,
            discipulo.asisFamiliar,
            discipulo.idConsolidador,
            discipulo.idCelulaConsolidacion,
            discipulo.cedula,
            discipulo.nombre,
            discipulo.apellido,
            discipulo.telefono,
            discipulo.direccion,
            discipulo.estadoCivil,
            discipulo.motivo,
            discipulo.fechaNacimiento,
            discipulo.fechaConvercion,
            COALESCE(COUNT(asistencia.id), 0) AS asistencias
        FROM
            discipulo
            INNER JOIN usuario AS Consolidador ON discipulo.idConsolidador = Consolidador.id
            LEFT JOIN asistencia ON discipulo.id = asistencia.idDiscipulo
            INNER JOIN celulas ON discipulo.idCelulaConsolidacion = celulas.id
            INNER JOIN territorio ON territorio.id = celulas.idterritorio AND territorio.idSede = :idSede GROUP BY discipulo.id";
            

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idSede', $usuario->idSede);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                Bitacora::registrar("Consulta de discipulo");

                return $resultado;
            }

            if ($usuario->tieneRol("LiderTerritorio")) {
                $sql = "SELECT 
            Consolidador.id AS idConsolidador,
            Consolidador.nombre AS nombreConsolidador,
            Consolidador.apellido AS apellidoConsolidador,
            Consolidador.cedula AS cedulaConsolidador,
            celulas.id AS idCelulaConsolidacion,
            celulas.codigo,
            discipulo.id,
            discipulo.asisCrecimiento,
            discipulo.asisFamiliar,
            discipulo.idConsolidador,
            discipulo.idCelulaConsolidacion,
            discipulo.cedula,
            discipulo.nombre,
            discipulo.apellido,
            discipulo.telefono,
            discipulo.direccion,
            discipulo.estadoCivil,
            discipulo.motivo,
            discipulo.fechaNacimiento,
            discipulo.fechaConvercion,
            COALESCE(COUNT(asistencia.id), 0) AS asistencias
        FROM
            discipulo
            INNER JOIN usuario AS Consolidador ON discipulo.idConsolidador = Consolidador.id
            LEFT JOIN asistencia ON discipulo.id = asistencia.idDiscipulo
            INNER JOIN celulas ON discipulo.idCelulaConsolidacion = celulas.id
            INNER JOIN territorio ON territorio.id = celulas.idterritorio AND territorio.idLider = :idLider GROUP BY discipulo.id";
            

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idLider', $usuario->id);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                Bitacora::registrar("Consulta de discipulo");

                return $resultado;
            }

            if ($usuario->tieneRol("Pastor")) {
                $sql = "SELECT 
            Consolidador.id AS idConsolidador,
            Consolidador.nombre AS nombreConsolidador,
            Consolidador.apellido AS apellidoConsolidador,
            Consolidador.cedula AS cedulaConsolidador,
            celulas.id AS idCelulaConsolidacion,
            celulas.codigo,
            discipulo.id,
            discipulo.asisCrecimiento,
            discipulo.asisFamiliar,
            discipulo.idConsolidador,
            discipulo.idCelulaConsolidacion,
            discipulo.cedula,
            discipulo.nombre,
            discipulo.apellido,
            discipulo.telefono,
            discipulo.direccion,
            discipulo.estadoCivil,
            discipulo.motivo,
            discipulo.fechaNacimiento,
            discipulo.fechaConvercion,
            COALESCE(COUNT(asistencia.id), 0) AS asistencias
        FROM
            discipulo
            INNER JOIN usuario AS Consolidador ON discipulo.idConsolidador = Consolidador.id
            LEFT JOIN asistencia ON discipulo.id = asistencia.idDiscipulo
            INNER JOIN celulas ON discipulo.idCelulaConsolidacion = celulas.id
            INNER JOIN territorio ON territorio.id = celulas.idterritorio AND territorio.idSede = :idSede GROUP BY discipulo.id";
            

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idSede', $usuario->idSede);

                $stmt->execute();
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

                Bitacora::registrar("Consulta de discipulo");

                return $resultado;
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

    public function editar_discipulo(
        $id,
        $asisCrecimiento,
        $asisFamiliar,
        $idConsolidador,
        $idCelulaConsolidacion,
        $cedula,
        $nombre,
        $apellido,
        $telefono,
        $direccion,
        $estadoCivil,
        $motivo,
        $fechaNacimiento,
        $fechaConvercion
    ) {

        try {

            $sql = "UPDATE discipulo SET
                        asisCrecimiento = :asisCrecimiento,
                        asisFamiliar = :asisFamiliar,
                        idConsolidador = :idConsolidador,
                        idCelulaConsolidacion = :idCelulaConsolidacion,
                        cedula = :cedula,
                        nombre = :nombre,
                        apellido = :apellido,
                        telefono = :telefono,
                        direccion = :direccion,
                        estadoCivil = :estadoCivil,
                        motivo = :motivo,
                        fechaNacimiento = :fechaNacimiento,
                        fechaConvercion = :fechaConvercion
                        WHERE
                        id = :id";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':asisCrecimiento', $asisCrecimiento);
            $stmt->bindValue(':asisFamiliar', $asisFamiliar);
            $stmt->bindValue(':idConsolidador', $idConsolidador);
            $stmt->bindValue(':idCelulaConsolidacion', $idCelulaConsolidacion);
            $stmt->bindValue(':cedula', $cedula);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':apellido', $apellido);
            $stmt->bindValue(':telefono', $telefono);
            $stmt->bindValue(':direccion', $direccion);
            $stmt->bindValue(':estadoCivil', $estadoCivil);
            $stmt->bindValue(':motivo', $motivo);
            $stmt->bindValue(':fechaNacimiento', $fechaNacimiento);
            $stmt->bindValue(':fechaConvercion', $fechaConvercion);

            $stmt->execute();


            Bitacora::registrar("Actualizacion de datos de discipulo");

            http_response_code(200);
            echo json_encode(array('msj' => 'Discipulo actualizado correctamente', 'status' => 200));
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

    public function eliminar_discipulo($id)
    {

        try {

            $sql = "DELETE FROM discipulo WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            Bitacora::registrar("Eliminacion de discipulo");

            http_response_code(200);
            echo json_encode(array('msj' => 'Discipulo eliminado correctamente', 'status' => 200));
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







    ////////////////////////////////// ESPACIO PARA VALIDACIONES //////////////////////////////////

    //Validacion de datos registro o actualizacion
    public function validacion_datos($arrayNumeros, $arrayTexto, $arrayNombreApellido, $arrayFechas, $fechaNacimiento, $arrayAsiste, $cedula, $estadoCivil, $telefono)
    {
        try {
            foreach ($arrayNumeros as $valor) {
                if (!is_numeric($valor)) {
                    throw new Exception("Los datos numericos que has enviado son invalidos. Ingrese nuevamente", 422);
                }
            }

            foreach ($arrayTexto as $valor) {
                if (!preg_match($this->expresion_texto, $valor)) {
                    // Lanzar una excepción si el string no es válido
                    throw new Exception("Has ingresado datos invalidos en direccion o motivo. Ingrese nuevamente", 422);
                }
            }

            foreach ($arrayNombreApellido as $valor) {
                if (!preg_match($this->expresion_nombreApellido, $valor)) {
                    // Lanzar una excepción si el string no es válido
                    throw new Exception("Has ingresado datos invalidos en el nombre o el apellido. Ingrese nuevamente", 422);
                }
            }

            foreach ($arrayFechas as $valor) {
                if (!preg_match($this->expresion_fecha, $valor) || !checkdate(substr($valor, 5, 2), substr($valor, 8, 2), substr($valor, 0, 4))) {
                    throw new Exception("La fecha no tiene el formato correcto o no es válida.", 422);
                }
            }

            //Validando que sea mayor de edad
            $hoy = new DateTime();
            $fecha = DateTime::createFromFormat('Y-m-d', $fechaNacimiento);
            $fecha->modify('+18 years');

            if ($hoy < $fecha) {
                throw new Exception("La fecha que nacimiento es de una persona menor de 18 años. Ingrese una fecha valida", 422);
            }

            foreach ($arrayAsiste as $valor) {
                if (!in_array($valor, $this->expresion_asiste)) {
                    // Lanzar una excepción si el string no es válido
                    throw new Exception("Dato desconocido para seleccion de asistencia a Celula Familiar o Celula de Crecimiento. Seleccione nuevamente", 422);
                }
            }

            if (!preg_match($this->expresion_cedula, $cedula)) {
                throw new Exception("Cedula invalida. Ingrese nuevamente", 422);
            }

            if (!in_array($estadoCivil, $this->expresion_estadoCivil)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("Estado civil invalido. Seleccione nuevamente", 422);
            }

            if (!preg_match($this->expresion_telefono, $telefono)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("Numero de telefono invalido, compruebe que cumple con los requisitos. Ingrese nuevamente", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    // Validacion cedula 

    public function valida_cedula_existencia($cedula, $id)
    {

        try {

            $sql = "SELECT cedula FROM discipulo WHERE cedula = :cedula AND estatus = '1' AND id NOT IN (:id)";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':cedula', $cedula);
            $stmt->bindValue(':id', $id);

            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $stmt->rowCount();

            if ($result > 0) {

                //     $sql2 = "SELECT codigo FROM celulas
                //      INNER JOIN discipulo ON celulas.id = discipulo.idCelulaConsolidacion AND discipulo.cedula = :cedula";

                //    $stmt2 = $this->db->pdo()->prepare($sql2);
                //    $stmt2->bindValue(':cedula', $cedula);
                //    $stmt2->execute();
                //    $result2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);


                return true;
            } else {
                return false;
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


    //Validando existencia de la cedula enviada
    public function validacion_existencia($cedula, $id)
    {
        try {
            $sql = "SELECT * FROM discipulo WHERE cedula = :cedula" . (!empty($id) ? " AND id != $id" : "");

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":cedula", $cedula);

            $stmt->execute();

            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado != false) {
                if ($resultado['cedula'] == $cedula) {
                    // Lanzar una excepción si el dato existe en la BD
                    throw new Exception("La cedula " . $cedula . " ya existe en el sistema", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }


    // VALIDAR ANTES DE ELIMINAR O EDITAR
    public function validacion_accion(int $id, string $accion): void
    {
        try {

            $sql = "SELECT * FROM asistencia WHERE idDiscipulo= :idDiscipulo";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idDiscipulo", $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Lanzar una excepción si el dato existe en la BD
                if ($accion == 'eliminar') {
                    throw new Exception("No puedes eliminar ese discipulo, ya que este se encuentra registrado en asistencias de reuniones. Esto podria corromper la integridad de los datos.", 422);
                }
                if ($accion == 'actualizar') {
                    throw new Exception("No puedes cambiar la celula de consolidacion de este discipulo, ya que este se encuentra registrado en asistencias de reuniones de la celula definida. Esto podria corromper la integridad de los datos.", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }


    ////////////////////////// APARTADO DE REPORTES ESTADISTICOS ///////////////////////////

    public function discipulos_consolidados_fecha()
    {
        try {
            $sql = "SELECT COUNT(discipulo.id) AS cantidad_discipulos, discipulo.fechaConvercion FROM `discipulo` GROUP BY fechaConvercion";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);

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

    // Getters
    public function getIdConsolidador(): int
    {
        return $this->idConsolidador;
    }
    public function getIdCelulaConsolidacion(): int
    {
        return $this->idCelulaConsolidacion;
    }
    public function getAsisFamiliar(): string
    {
        return $this->asisFamiliar;
    }
    public function getAsisCrecimiento(): string
    {
        return $this->asisCrecimiento;
    }
    public function getCedula(): string
    {
        return $this->cedula;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getApellido(): string
    {
        return $this->apellido;
    }
    public function getTelefono(): string
    {
        return $this->telefono;
    }
    public function getDireccion(): string
    {
        return $this->direccion;
    }
    public function getEstadoCivil(): string
    {
        return $this->estadoCivil;
    }
    public function getFechaNacimiento(): string
    {
        return $this->fechaNacimiento;
    }
    public function getMotivo(): string
    {
        return $this->motivo;
    }
    public function getFechaConvercion(): string
    {
        return $this->fechaConvercion;
    }
    public function getAprobarUsuario(): int
    {
        return $this->aprobarUsuario;
    }

    public function getEstatus(): int
    {
        return $this->estatus;
    }
}
