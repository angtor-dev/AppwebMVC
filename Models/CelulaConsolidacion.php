<?php
require_once "Models/Model.php";

class CelulaConsolidacion extends Model
{
    public int $id;
    public int $idLider;
    public int $idColider;
    public int $idTerritorio;
    public string $identificador;
    public string $codigo;
    public string $nombre;
    public int $estatus;

    //Expresiones regulares
    private $expresion_nombre = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/';
    private $expresion_texto = '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/';
    private $expresion_id = '/^[1-9]\d*$/';
    private $expresion_fecha = '/^\d{4}-\d{2}-\d{2}$/';



    public  function registrar_CelulaConsolidacion($nombre, $idLider, $idCoLider, $idTerritorio)
    {
        try {

            
            

            $sql = "SELECT MAX(id) AS celulaNumero FROM celulaconsolidacion";
            $consultaid = $this->db->pdo()->prepare($sql);
            $consultaid->execute();
            $datos = $consultaid->fetch(PDO::FETCH_ASSOC);

            $id = '';
            $codigo = '';
            
            /** @var Territorio **/ 
            $territorio = Territorio::cargar($idTerritorio);

            if ($datos['celulaNumero'] == null) {
                $id = 1;
                $identificador = 'CCO' . $id;
                $codigo = $territorio->codigo . '-' . $identificador;
            } else {
                
                /** @var CelulaConsolidacion[] **/
                $celulas = CelulaConsolidacion::cargarRelaciones($idTerritorio, "Territorio");

                if (count($celulas) > 0) {
                    // Un array para almacenar solo los números de los identificadores
                    $numeros = [];
                    foreach ($celulas as $resultado) {
                        // Extraer el número del identificador (eliminar la "CFA")
                        $numero = (int) substr($resultado->identificador, 3);  // substr($resultado, 1) elimina el primer carácter ("T")
                        $numeros[] = $numero;
                    }
                    // Encontrar el número más grande en el array
                    $mayorNumero = max($numeros);

                    $contador = $mayorNumero + 1;
                    $identificador = 'CCO' . $contador;
                    $codigo = $territorio->codigo . '-' . $identificador;
                } else {
                    $contador = 1;
                    $identificador = 'CCO' . $contador;
                    $codigo = $territorio->codigo . '-' . $identificador;
                }
            }

            if ($id == 1) {

                $sql = "INSERT INTO celulaconsolidacion (id, nombre, codigo, identificador, idLider, idCoLider, idTerritorio, fechaCreacion) 
                VALUES (:id, :nombre, :codigo, :identificador, :idLider, :idCoLider, :idTerritorio, CURDATE())";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':identificador', $identificador);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);
                $stmt->bindValue(':idTerritorio', $idTerritorio);

                $stmt->execute();
            } else {
                $sql = "INSERT INTO celulaconsolidacion (nombre, codigo, identificador, idLider, idCoLider, idTerritorio, fechaCreacion) 
                VALUES (:nombre, :codigo, :identificador, :idLider, :idCoLider, :idTerritorio, CURDATE())";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':identificador', $identificador);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);
                $stmt->bindValue(':idTerritorio', $idTerritorio);
                $stmt->bindValue(':idTerritorio', $idTerritorio);

                $stmt->execute();
            }

            Bitacora::registrar("Registro de celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Celula registrada exitosamente', 'status' => 200));
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

    public  function listar_CelulaConsolidacion()
    {

        try {

            $sql = "SELECT Lider.id AS idLider,
            CoLider.id AS idCoLider,
            Lider.nombre AS nombreLider,
            CoLider.nombre AS nombreCoLider,
            Lider.apellido AS apellidoLider,
            CoLider.apellido AS apellidoCoLider,
            Lider.cedula AS cedulaLider,
            CoLider.cedula AS cedulaCoLider,
            celulaconsolidacion.id,
            celulaconsolidacion.idLider,
            celulaconsolidacion.idCoLider,
            celulaconsolidacion.idTerritorio,
            celulaconsolidacion.codigo,
            celulaconsolidacion.nombre,
            celulaconsolidacion.estatus
            FROM celulaconsolidacion
            INNER JOIN usuario AS Lider ON celulaconsolidacion.idLider = Lider.id
            INNER JOIN usuario AS CoLider ON celulaconsolidacion.idCoLider = CoLider.id
            WHERE celulaconsolidacion.estatus = '1'";


            $stmt = $this->db->pdo()->prepare($sql);

            Bitacora::registrar("Consulta de celulas de consolidacion");

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
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


    public  function editar_CelulaConsolidacion($id, $nombre, $idLider, $idCoLider, $idTerritorio)
    {
        try {

            /** @var CelulaConsolidacion **/
            $consulta = CelulaConsolidacion::cargar($id);

            if ($consulta->idTerritorio == $idTerritorio) {

                $sql = "UPDATE celulaconsolidacion SET  nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider WHERE celulaconsolidacion.id = :id";
                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);

                $stmt->execute();
            } else {
                /** @var territorio  **/
                $territorio = Territorio::cargar($idTerritorio);

                /** @var CelulaConsolidacion[]  **/
                $celulas = CelulaConsolidacion::cargarRelaciones($idTerritorio, "Territorio");

                $identificador = '';
                $codigo = '';

                if (count($celulas) > 0) {
                    // Un array para almacenar solo los números de los identificadores
                    $numeros = [];
                    foreach ($celulas as $resultado) {
                        // Extraer el número del identificador (eliminar la "CFA")
                        $numero = (int) substr($resultado->identificador, 3);  // substr($resultado, 1) elimina el primer carácter ("T")
                        $numeros[] = $numero;
                    }
                    // Encontrar el número más grande en el array
                    $mayorNumero = max($numeros);

                    $contador = $mayorNumero + 1;
                    $identificador = 'CCO' . $contador;
                    $codigo = $territorio->codigo . '-' . $identificador;
                } else {
                    $contador = 1;
                    $identificador = 'CCO' . $contador;
                    $codigo = $territorio->codigo . '-' . $identificador;
                }

                $sql = "UPDATE celulaconsolidacion SET  nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider, codigo = :codigo, identificador = :identificador WHERE celulaconsolidacion.id = :id";
                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);
                $stmt->bindValue(':identificador', $identificador);
                $stmt->bindValue(':codigo', $codigo);

                $stmt->execute();
            }

            Bitacora::registrar("Actualizacion de celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Celula actualizada exitosamente', 'status' => 200));
            die();
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

    public  function eliminar_CelulaConsolidacion($id)
    {

        try {

            $sql = "UPDATE celulaconsolidacion SET estatus = '0' WHERE celulaconsolidacion.id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            Bitacora::registrar("Eliminacion de celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Celula eliminada correctamente'));
            die();
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

    public function registrar_reunion($idCelulaConsolidacion, $fecha, $tematica, $semana, $generosidad, $actividad, $observaciones, $arrayAsistencias)
    {

        try {

            $sql = "INSERT INTO reunionconsolidacion (idCelulaConsolidacion, fecha, tematica, semana, generosidad, actividad, observaciones) 
            VALUES (:idCelulaConsolidacion, :fecha, :tematica, :semana, :generosidad, :actividad, :observaciones)";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idCelulaConsolidacion', $idCelulaConsolidacion);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tematica', $tematica);
            $stmt->bindValue(':semana', $semana);
            $stmt->bindValue(':generosidad', $generosidad);
            $stmt->bindValue(':actividad', $actividad);
            $stmt->bindValue(':observaciones', $observaciones);

            $stmt->execute();

            //Registrando las asistencias
            $consulta = "SELECT id FROM reunionconsolidacion ORDER BY id DESC LIMIT 1";
            $stmt2 = $this->db->pdo()->prepare($consulta);
            $stmt2->execute();

            if ($stmt2->rowCount() > 0) {
                $resultado = $stmt2->fetch(PDO::FETCH_ASSOC);
                $idReunion = $resultado['id'];

                foreach ($arrayAsistencias as $values) {
                    $sql2 = "INSERT INTO `asistencia` (`idReunion`, `idDiscipulo`) VALUES (:idReunion, :idDiscipulo)";
                    $stmt3 = $this->db->pdo()->prepare($sql2);

                    $stmt3->bindValue(':idReunion', $idReunion);
                    $stmt3->bindValue(':idDiscipulo', $values);

                    $stmt3->execute();
                }
            }

            Bitacora::registrar("Registro de reunion en celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Reunion registrada correctamente', 'status' => 200));
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


    public  function listar_lideres()
    {

        try {

            //Esta consulta esta sujeto a cambios hasta que se consiga un mejor logica para traer los usuarios con el nivel requerido

            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario 
            WHERE usuariorol.idRol IN (1, 2, 3, 4)";

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

    public  function listar_territorios()
    {

        try {

            $sql = "SELECT * FROM territorio WHERE territorio.estatus = '1'";

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


    public  function listar_reuniones()
    {

        try {

            $sql = "SELECT
                    reunionconsolidacion.id,
                    reunionconsolidacion.fecha,
                    reunionconsolidacion.tematica,
                    reunionconsolidacion.semana,
                    reunionconsolidacion.generosidad,
                    reunionconsolidacion.actividad,
                    reunionconsolidacion.observaciones,
                    celulaconsolidacion.codigo,
                    celulaconsolidacion.nombre,
                    celulaconsolidacion.id AS idCelulaConsolidacion
                FROM reunionconsolidacion
                INNER JOIN celulaconsolidacion
                ON reunionconsolidacion.idCelulaConsolidacion = celulaconsolidacion.id
                ORDER BY reunionconsolidacion.fecha DESC";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Bitacora::registrar("Consulta de reuniones de celula de consolidacion");

            return $resultado;
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


    public  function editar_reuniones($id, $idCelulaConsolidacion, $fecha, $tematica, $semana, $generosidad, $actividad, $observaciones)
    {

        try {

            $sql = "UPDATE reunionconsolidacion
                SET
                idCelulaConsolidacion = :idCelulaConsolidacion,
                fecha = :fecha,
                tematica = :tematica,
                semana = :semana,
                generosidad = :generosidad,
                actividad = :actividad,
                observaciones = :observaciones
                WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':idCelulaConsolidacion', $idCelulaConsolidacion);
            $stmt->bindValue(':fecha', $fecha);
            $stmt->bindValue(':tematica', $tematica);
            $stmt->bindValue(':semana', $semana);
            $stmt->bindValue(':generosidad', $generosidad);
            $stmt->bindValue(':actividad', $actividad);
            $stmt->bindValue(':observaciones', $observaciones);

            $stmt->execute();

            Bitacora::registrar("Actualizacion de reunion de celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Reunion actualizada correctamente'));
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




    public  function eliminar_reuniones($id)
    {

        try {

            $sql = "DELETE FROM reunionconsolidacion WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            Bitacora::registrar("Eliminacion de reunion en celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Reunion eliminada correctamente'));
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


    public  function listar_celulas()
    {

        try {

            $sql = "SELECT * FROM celulaconsolidacion WHERE celulaconsolidacion.estatus = '1'";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $resultado;
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


    public function listarDiscipulados_celula($idCelulaConsolidacion)
    {
        try {

            $sql = "SELECT * FROM discipulo WHERE idCelulaConsolidacion= :idCelulaConsolidacion";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idCelulaConsolidacion", $idCelulaConsolidacion);
            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
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





    public function listar_asistencia($idReunion)
    {

        try {

            $sql = "SELECT
                discipulo.id,
                discipulo.cedula, 
                discipulo.nombre,
                discipulo.apellido,
                asistencia.id AS idAsistencia,
                asistencia.idReunion,
                asistencia.idDiscipulo
            FROM asistencia
            INNER JOIN discipulo ON  asistencia.idDiscipulo = discipulo.id WHERE asistencia.idReunion = :idReunion";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':idReunion', $idReunion);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
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

    public function listarAsistencia_reunion($idCelulaConsolidacion, $idReunion)
    {
        try {

            $sql = "SELECT * FROM discipulo
            WHERE id NOT IN (SELECT idDiscipulo FROM asistencia WHERE idReunion = :idReunion) AND discipulo.idCelulaConsolidacion = 
            :idCelulaConsolidacion";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":idCelulaConsolidacion", $idCelulaConsolidacion);
            $stmt->bindValue(":idReunion", $idReunion);

            $stmt->execute();

            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Bitacora::registrar("Consulta de asistencias de reunion de celula de consolidacion");

            return $resultado;
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


    public function eliminar_asistenciaReunion($id)
    {
        try {
            $sql = "DELETE FROM asistencia WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            Bitacora::registrar("Eliminacion de asistencia en reunion de celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Asistencia eliminada correctamente'));
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


    public function actualizar_asistenciaReunion($idReunion, $discipulos)
    {
        try {

            foreach ($discipulos as $valor) {
                $sql = "INSERT INTO `asistencia` (`idReunion`, `idDiscipulo`) VALUES (:idReunion, :discipulo)";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(":idReunion", $idReunion);
                $stmt->bindValue(":discipulo", $valor);

                $stmt->execute();
            }

            Bitacora::registrar("Eliminacion de asistencia en reunion de celula de consolidacion");

            http_response_code(200);
            echo json_encode(array('msj' => 'Asistencias actualizadas correctamente'));
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









    ///////////////////////////////// ESPACIO PARA VALIDACIONES //////////////////////////////////

    public function validacion_datos(string $nombre, array $idArray): void
    {
        try {
            // Utilizar preg_match para validar el string contra la expresión regular
            if (!preg_match($this->expresion_nombre, $nombre)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El nombre que ingresaste no cumple con los requisitos. Ingrese nuevamente", 422);
            }

            foreach ($idArray as $key) {
                if (!preg_match($this->expresion_id, $key)) {
                    // Lanzar una excepción si el string no es válido
                    throw new Exception("El ID no cumple con los requisitos. Seleccione nuevamente", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }


    //Validacion de datos REUNION
    public function validacion_datos_reunion($arrayNumeros, $arrayTexto, $fecha)
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
                    throw new Exception("Has ingresado datos invalidos en algun campo textual. Ingrese nuevamente", 422);
                }
            }

            if (!preg_match($this->expresion_fecha, $fecha) || !checkdate(substr($fecha, 5, 2), substr($fecha, 8, 2), substr($fecha, 0, 4))) {
                throw new Exception("La fecha no tiene el formato correcto o no es válida.", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    //Validacion de array asistencias para registrar o actualizar
    public function validacion_array_asistencias($arrayAsistencias)
    {
        try {
            if (empty($arrayAsistencias)) {
                throw new Exception("Debes agregar discipulos para las asistencia. Ingrese nuevamente.", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    //Validacion de accion para actualizar reunion o eliminar
    public function validacion_accion_reunion($arrayAccion)
    {
        try {
            if ($arrayAccion['accion'] == 'eliminar') {
                $sql = "SELECT * FROM asistencia WHERE asistencia.idReunion = :id";
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":id", $arrayAccion['id']);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                    throw new Exception("No puedes eliminar la reunion porque ya se encuentran asistencias registradas.", 422);
                }
            }

            if ($arrayAccion['accion'] == 'actualizar') {
                $sql = "SELECT * FROM reunionconsolidacion WHERE reunionconsolidacion.id = :id";
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":id", $arrayAccion['id']);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($resultado['idCelulaConsolidacion'] != $arrayAccion['idCelulaConsolidacion']) {
                    $sql2 = "SELECT * FROM asistencia WHERE asistencia.idReunion = :id";
                    $stmt2 = $this->db->pdo()->prepare($sql2);
                    $stmt2->bindValue(":id", $arrayAccion['id']);
                    $stmt2->execute();
                    if ($stmt2->rowCount() > 0) {
                        throw new Exception("No puedes cambiar la celula de consolidacion porque ya se encuentran datos de asistencias registradas en la reunion.", 422);
                    }
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function validacion_existencia(string $nombre, $id): void
    {
        try {
            $sql = "SELECT * FROM celulaconsolidacion WHERE nombre = :nombre" . (!empty($id) ? " AND id != $id" : "");
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":nombre", $nombre);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado != false) {
                if ($resultado['nombre'] == $nombre) {
                    // Lanzar una excepción si el dato existe en la BD
                    throw new Exception("La celula llamada " . $nombre . " ya existe", 422);
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

            $sql = "SELECT * FROM reunionconsolidacion WHERE idCelulaConsolidacion= :idCelulaConsolidacion AND estatus = 1";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idCelulaFamiliar", $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Lanzar una excepción si el dato existe en la BD
                if ($accion == 'eliminar') {
                    throw new Exception("Esta celula esta asociada a reuniones y otro tipo de informacion que podria corromper la integridad de los datos.", 422);
                }
                if ($accion == 'actualizar') {
                    throw new Exception("No puedes cambiar el territorio porque la celula posee datos de reuniones e informacion adicional. Esto podria destruir la integridad de los datos", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }
}
