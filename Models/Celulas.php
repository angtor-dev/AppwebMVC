<?php
require_once "Models/Model.php";
require_once "Models/Discipulo.php";

class Celulas extends Model
{
    public int $id;
    private string $tipo;
    private int $idLider;
    private int $idColider;
    private int $idTerritorio;
    private string $identificador;
    private string $codigo;
    private string $nombre;
    private int $estatus;

    //Expresiones regulares
    private $expresion_nombre = '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,50}$/';
    private $expresion_texto = '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/';
    private $expresion_id = '/^[1-9]\d*$/';
    private $expresion_fecha = '/^\d{4}-\d{2}-\d{2}$/';


    public function registrar_Celula($tipo, $nombre, $idLider, $idCoLider, $idTerritorio)
    {
        try {

            $sql = "SELECT MAX(id) AS celulaNumero FROM celulas";
            $consultaid = $this->db->pdo()->prepare($sql);
            $consultaid->execute();
            $datos = $consultaid->fetch(PDO::FETCH_ASSOC);

            $id = '';
            $codigo = '';
            /**  @var Territorio **/
            $territorio = Territorio::cargar($idTerritorio);

            if ($datos['celulaNumero'] == null) {

                $id = 1;
                $identificador = '';

                switch ($tipo) {
                    case 'consolidacion':
                        $identificador = 'CCO' . $id;
                        break;

                    case 'crecimiento':
                        $identificador = 'CCR' . $id;
                        break;

                    case 'familiar':
                        $identificador = 'CFA' . $id;
                        break;
                }

                $codigo = $territorio->getCodigo() . '-' . $identificador;
            } else {


                $sentencia = "SELECT * FROM celulas WHERE idTerritorio = :idTerritorio AND tipo = :tipo";
                $statement = $this->db->pdo()->prepare($sentencia);
                $statement->bindValue(':idTerritorio', $idTerritorio);
                $statement->bindValue(':tipo', $tipo);
                $statement->execute();

                $celulas = $statement->fetchAll();

                if (count($celulas) > 0) {
                    // Un array para almacenar solo los números de los identificadores
                    $numeros = [];

                    foreach ($celulas as $resultado) {
                        // Extraer el número del identificador (eliminar la "CFA")
                        // Extraer el número del identificador (eliminar la "CCO")
                        // Extraer el número del identificador (eliminar la "CCR")
                        $numero = (int) substr($resultado['identificador'], 3);  // substr($resultado, 1) elimina el primer carácter ("T")
                        $numeros[] = $numero;
                    }
                    // Encontrar el número más grande en el array
                    $mayorNumero = max($numeros);

                    $contador = $mayorNumero + 1;

                    switch ($tipo) {
                        case 'consolidacion':
                            $identificador = 'CCO' . $contador;
                            break;

                        case 'crecimiento':
                            $identificador = 'CCR' . $contador;
                            break;

                        case 'familiar':
                            $identificador = 'CFA' . $contador;
                            break;
                    }

                    $codigo = $territorio->getCodigo() . '-' . $identificador;
                } else {
                    $contador = 1;

                    switch ($tipo) {
                        case 'consolidacion':
                            $identificador = 'CCO' . $contador;
                            break;

                        case 'crecimiento':
                            $identificador = 'CCR' . $contador;
                            break;

                        case 'familiar':
                            $identificador = 'CFA' . $contador;
                            break;
                    }

                    $codigo = $territorio->getCodigo() . '-' . $identificador;
                }
            }

            if ($id == 1) {

                $sql = "INSERT INTO celulas (id, nombre, codigo, identificador, tipo, idLider, idCoLider, idTerritorio, fechaCreacion) 
                VALUES (:id, :nombre, :codigo, :identificador, :tipo, :idLider, :idCoLider, :idTerritorio, CURDATE())";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':identificador', $identificador);
                $stmt->bindValue(':tipo', $tipo);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);
                $stmt->bindValue(':idTerritorio', $idTerritorio);



                $stmt->execute();
            } else {
                $sql = "INSERT INTO celulas (nombre, codigo, identificador, tipo, idLider, idCoLider, idTerritorio, fechaCreacion) 
                VALUES (:nombre, :codigo, :identificador, :tipo, :idLider, :idCoLider, :idTerritorio, CURDATE())";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':identificador', $identificador);
                $stmt->bindValue(':tipo', $tipo);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);
                $stmt->bindValue(':idTerritorio', $idTerritorio);
                $stmt->bindValue(':idTerritorio', $idTerritorio);

                $stmt->execute();
            }

            Bitacora::registrar("Registro de celula familiar");

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





    public function listar_CelulaFamiliar()
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
            WHERE celulas.estatus = '1' AND celulas.tipo = 'familiar'";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            Bitacora::registrar("Consulta de celula familiar");

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


    public function listar_CelulaCrecimiento()
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
            WHERE celulas.estatus = '1' AND celulas.tipo = 'crecimiento'";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            /** @var Bitacora **/
            Bitacora::registrar("Consulta de celula crecimiento");

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


    public function listar_CelulaConsolidacion()
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
            /** @var Bitacora **/
            Bitacora::registrar("Consulta de celula consolidacion");

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




    public function editar_Celula($id, $tipo, $nombre, $idLider, $idCoLider, $idTerritorio)
    {
        try {

            /** @var Celulas**/
            $consulta = Celulas::cargar($id);

            if ($consulta->idTerritorio == $idTerritorio) {

                $sql = "UPDATE celulas SET  nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider WHERE id = :id";
                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);

                $stmt->execute();
            } else {

                /** @var Territorio **/
                $territorio = Territorio::cargar($idTerritorio);

                $sentencia = "SELECT * FROM celulas WHERE idTerritorio = :idTerritorio AND tipo = :tipo";
                $statement = $this->db->pdo()->prepare($sentencia);
                $statement->bindValue(':idTerritorio', $idTerritorio);
                $statement->bindValue(':tipo', $tipo);
                $statement->execute();

                $celulas = $statement->fetchAll();

                $identificador = '';
                $codigo = '';

                if (count($celulas) > 0) {
                    // Un array para almacenar solo los números de los identificadores
                    $numeros = [];
                    foreach ($celulas as $resultado) {
                        // Extraer el número del identificador (eliminar la "CFA")
                        // Extraer el número del identificador (eliminar la "CCO")
                        // Extraer el número del identificador (eliminar la "CCR")
                        $numero = (int) substr($resultado['identificador'], 3);
                        $numeros[] = $numero;
                    }
                    // Encontrar el número más grande en el array
                    $mayorNumero = max($numeros);

                    $contador = $mayorNumero + 1;

                    switch ($tipo) {
                        case 'consolidacion':
                            $identificador = 'CCO' . $contador;
                            break;

                        case 'crecimiento':
                            $identificador = 'CCR' . $contador;
                            break;

                        case 'familiar':
                            $identificador = 'CFA' . $contador;
                            break;
                    }

                    $codigo = $territorio->getCodigo() . '-' . $identificador;
                } else {
                    $contador = 1;

                    switch ($tipo) {
                        case 'consolidacion':
                            $identificador = 'CCO' . $contador;
                            break;

                        case 'crecimiento':
                            $identificador = 'CCR' . $contador;
                            break;

                        case 'familiar':
                            $identificador = 'CFA' . $contador;
                            break;
                    }

                    $codigo = $territorio->getCodigo() . '-' . $identificador;
                }

                $sql = "UPDATE celulas SET nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider, codigo = :codigo, identificador = :identificador, idTerritorio = :idTerritorio WHERE id = :id";
                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);
                $stmt->bindValue(':identificador', $identificador);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':idTerritorio', $idTerritorio);

                $stmt->execute();
            }
            /** @var Bitacora **/
            //Bitacora::registrar("Actualizacion de celula familiar");

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




    public function eliminar_Celula($id)
    {

        try {

            $sql = "UPDATE celulas SET estatus = '0' WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            Bitacora::registrar("Eliminacion de Celula");

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

    public function registrar_reunion($idCelula, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones, $arrayAsistencias)
    {

        try {

            //Condicional para verificar si es una reunion de Celula de Consolidacion.
            if (empty($infantil) && empty($juvenil) && empty($adulto)) {

                $sql = "INSERT INTO reunioncelula (idCelula, fecha, tematica, semana, generosidad, actividad, observaciones) 
                VALUES (:idCelula, :fecha, :tematica, :semana, :generosidad, :actividad, :observaciones)";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idCelula', $idCelula);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tematica', $tematica);
                $stmt->bindValue(':semana', $semana);
                $stmt->bindValue(':generosidad', $generosidad);
                $stmt->bindValue(':actividad', $actividad);
                $stmt->bindValue(':observaciones', $observaciones);

                $stmt->execute();

                //Registrando las asistencias
                $consulta = "SELECT id FROM reunioncelula ORDER BY id DESC LIMIT 1";
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


                        //Esta es una version solamente, se podria hacer de una forma mas dinamica (OJO)

                        /* Logica para verificar que al momento de registrar la asistencia, cuente si el discipulo
                           cuenta con la cantidad de 5 asistencias para ser aprobado su estatus de crearle un usuario */
                        $consultaSql = "SELECT reunioncelula.idCelula, reunioncelula.id, asistencia.idDiscipulo, asistencia.idReunion FROM asistencia 
                        INNER JOIN reunioncelula ON reunioncelula.idCelula = :idCelula
                        WHERE asistencia.idDiscipulo = :idDiscipulo AND asistencia.idReunion = reunioncelula.id";

                        $stmt4 = $this->db->pdo()->prepare($consultaSql);
                        $stmt4->bindValue(':idCelula', $idCelula);
                        $stmt4->bindValue(':idDiscipulo', $values);

                        $stmt4->execute();

                        // Si la cuenta de la consulta es igual a 5, entonces este sera aprobado. Del resto, no hara nada
                        if ($stmt4->rowCount() == 5) {
                            $update = "UPDATE discipulo SET aprobarUsuario = '1' WHERE discipulo.id = :idDiscipulo";
                            $stmt5 = $this->db->pdo()->prepare($update);
                            $stmt5->bindValue(':idDiscipulo', $values);

                            $stmt5->execute();

                            // Esto tal vez deba ir en el controlador pero no quiero refactorizar nada más antes de la defensa (y)
                            // Envia la notificación de que el discipulo alcanzo las 5 reuniones
                            /** @var Discipulo */
                            $discipulo = Discipulo::cargar($values);
                            /** @var Usuario[] */
                            $usuarios = Usuario::listar(1);
                            foreach ($usuarios as $usuario) {
                                if ($usuario->tieneRol("Superusuario") || $usuario->tieneRol("Administrador") || $usuario->tienePermiso("inscripciones", "consultar")) {
                                    Notificacion::registrar(
                                        $usuario->id,
                                        "Discipulo consolidado",
                                        "El discipulo " . $discipulo->getNombre() . " " . $discipulo->getApellido() . " ha alcanzado las 5 asistencias de consolidación."
                                    );
                                }
                            }
                        }
                    }
                }
            } else {


                $sql = "INSERT INTO reunioncelula (idCelula, fecha, tematica, semana, generosidad, infantil, juvenil, adulto, actividad, observaciones) 
                VALUES (:idCelula, :fecha, :tematica, :semana, :generosidad, :infantil, :juvenil, :adulto, :actividad, :observaciones)";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':idCelula', $idCelula);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tematica', $tematica);
                $stmt->bindValue(':semana', $semana);
                $stmt->bindValue(':generosidad', $generosidad);
                $stmt->bindValue(':infantil', $infantil);
                $stmt->bindValue(':juvenil', $juvenil);
                $stmt->bindValue(':adulto', $adulto);
                $stmt->bindValue(':actividad', $actividad);
                $stmt->bindValue(':observaciones', $observaciones);

                $stmt->execute();
            }

            Bitacora::registrar("Registro de reunion de celula");

            http_response_code(200);
            echo json_encode(array('msj' => 'Registro actualizado exitosamente', 'status' => 200));
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


    public function listar_lideres()
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

    public function listar_territorios()
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


    public function listar_reunionesFamiliar()
    {

        try {

            $sql = "SELECT
                    reunioncelula.id,
                    reunioncelula.fecha,
                    reunioncelula.tematica,
                    reunioncelula.semana,
                    reunioncelula.generosidad,
                    reunioncelula.infantil,
                    reunioncelula.juvenil,
                    reunioncelula.adulto,
                    reunioncelula.actividad,
                    reunioncelula.observaciones,
                    celulas.codigo,
                    celulas.nombre,
                    celulas.id AS idCelula
                FROM reunioncelula
                INNER JOIN celulas
                ON reunioncelula.idcelula = celulas.id AND celulas.tipo = 'familiar'
                ORDER BY reunioncelula.fecha DESC;";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Bitacora::registrar("Consulta de reuniones de celula familiar");

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


    public function listar_reunionesCrecimiento()
    {

        try {

            $sql = "SELECT
            reunioncelula.id,
            reunioncelula.fecha,
            reunioncelula.tematica,
            reunioncelula.semana,
            reunioncelula.generosidad,
            reunioncelula.infantil,
            reunioncelula.juvenil,
            reunioncelula.adulto,
            reunioncelula.actividad,
            reunioncelula.observaciones,
            celulas.codigo,
            celulas.nombre,
            celulas.id AS idCelula
        FROM reunioncelula
        INNER JOIN celulas
        ON reunioncelula.idcelula = celulas.id AND celulas.tipo = 'crecimiento'
        ORDER BY reunioncelula.fecha DESC;";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Bitacora::registrar("Consulta de reuniones de celula Crecimiento");

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

    public function listar_reunionesConsolidacion()
    {

        try {

            $sql = "SELECT
                    reunioncelula.id,
                    reunioncelula.fecha,
                    reunioncelula.tematica,
                    reunioncelula.semana,
                    reunioncelula.generosidad,
                    reunioncelula.actividad,
                    reunioncelula.observaciones,
                    celulas.codigo,
                    celulas.nombre,
                    celulas.id AS idCelula
                FROM reunioncelula
                INNER JOIN celulas
                ON reunioncelula.idcelula = celulas.id AND celulas.tipo = 'consolidacion'
                ORDER BY reunioncelula.fecha DESC;";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Bitacora::registrar("Consulta de reuniones de celula Consolidacion");

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




    public function editar_reuniones($id, $idCelula, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones)
    {

        try {

            if (empty($infantil) && empty($juvenil) && empty($adulto)) {

                $sql = "UPDATE reunioncelula
                SET
                idCelula = :idCelula,
                fecha = :fecha,
                tematica = :tematica,
                semana = :semana,
                generosidad = :generosidad,
                actividad = :actividad,
                observaciones = :observaciones
                WHERE id = :id";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':idCelula', $idCelula);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tematica', $tematica);
                $stmt->bindValue(':semana', $semana);
                $stmt->bindValue(':generosidad', $generosidad);
                $stmt->bindValue(':actividad', $actividad);
                $stmt->bindValue(':observaciones', $observaciones);

                $stmt->execute();
            } else {

                $sql = "UPDATE reunioncelula
                SET
                idCelula = :idCelula,
                fecha = :fecha,
                tematica = :tematica,
                semana = :semana,
                generosidad = :generosidad,
                infantil = :infantil,
                juvenil = :juvenil,
                adulto = :adulto,
                actividad = :actividad,
                observaciones = :observaciones
                WHERE id = :id";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':idCelula', $idCelula);
                $stmt->bindValue(':fecha', $fecha);
                $stmt->bindValue(':tematica', $tematica);
                $stmt->bindValue(':semana', $semana);
                $stmt->bindValue(':generosidad', $generosidad);
                $stmt->bindValue(':infantil', $infantil);
                $stmt->bindValue(':juvenil', $juvenil);
                $stmt->bindValue(':adulto', $adulto);
                $stmt->bindValue(':actividad', $actividad);
                $stmt->bindValue(':observaciones', $observaciones);

                $stmt->execute();
            }

            Bitacora::registrar("Actualización de reunion de Celula");

            http_response_code(200);
            echo json_encode(array('msj' => 'Reunion actualizada correctamente', 'status' => 200));
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




    public function eliminar_reuniones($id)
    {
        try {

            $sql = "DELETE FROM reunioncelula WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            Bitacora::registrar("Eliminacion de reunion de celula");

            http_response_code(200);
            echo json_encode(array('msj' => 'Reunion eliminada correctamente'));
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


    public function listar_celulas($tipo)
    {
        try {

            $sql = "SELECT * FROM celulas WHERE celulas.estatus = '1' AND celulas.tipo = :tipo";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":tipo", $tipo);

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

    public function listarDiscipulados_celula($idCelula)
    {
        try {

            $sql = "SELECT * FROM discipulo WHERE idCelulaConsolidacion= :idCelulaConsolidacion";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idCelulaConsolidacion", $idCelula);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
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

   
    public function valida_nombre($nombre, $id, $tipo, $idTerritorio)
    {
        /** @var Usuario */
        $usuario = $_SESSION['usuario'];

        try {

            $sql = "SELECT nombre FROM celulas WHERE nombre = :nombre AND idTerritorio = :idTerritorio
            AND tipo = :tipo AND estatus = '1' AND (id NOT IN (:id))";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':idTerritorio', $idTerritorio);


            $stmt->execute();
            $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result = $stmt->rowCount();

            if ($result > 0) {
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

    public function validacion_existencia(string $nombre, $id, $tipo, $idTerritorio): void
    {
        try {
            $sql = "SELECT * FROM celulas WHERE idTerritorio = :idTerritorio AND tipo = :tipo AND estatus = '1' AND nombre = :nombre" . (!empty($id) ? " AND id != $id" : "");
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":nombre", $nombre);
            $stmt->bindValue(":tipo", $tipo);
            $stmt->bindValue(':idTerritorio', $idTerritorio);
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
                $sql = "SELECT * FROM reunioncelula WHERE reunioncelula.id = :id";
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":id", $arrayAccion['id']);
                $stmt->execute();
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($resultado['idCelula'] !== $arrayAccion['idCelulaConsolidacion']) {
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


    // VALIDAR ANTES DE ELIMINAR O EDITAR
    public function validacion_accion(int $id, string $accion): void
    {
        try {

            $sql = "SELECT * FROM reunioncelula WHERE idCelula= :idCelula AND estatus = 1";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idCelula", $id);
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



    //Validacion de datos REUNION
    public function validacion_datos_reunion($arrayNumeros, $arrayTexto, $fecha)
    {
        try {
            foreach ($arrayNumeros as $valor) {
                if (!is_numeric($valor)) {
                    throw new Exception("Los datos numericos que has enviado son invalidos." . $valor . "Ingrese nuevamente", 422);
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






    ////////////////////////// APARTADO DE REPORTES ESTADISTICOS ///////////////////////////

    public function lideres_cantidad_celulas($tipo)
    {
        try {
            $sql = "SELECT usuario.nombre, usuario.apellido, COALESCE(COUNT(celulas.id), 0) AS cantidadCelulas FROM usuario 
            LEFT JOIN celulas ON celulas.idLider = usuario.id WHERE celulas.tipo = :tipo
            GROUP BY usuario.nombre";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":tipo", $tipo);
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

    public function asistencias_reuniones_celulas($idCelula, $tipo)
    {
        try {
            if ($tipo == 'consolidacion') {

                $sql = "SELECT celulas.nombre, COUNT(asistencia.idDiscipulo) AS cantidad_asistencia, reunioncelula.fecha FROM reunioncelula 
                INNER JOIN celulas ON celulas.id = reunioncelula.idCelula 
                INNER JOIN asistencia ON asistencia.idReunion = reunioncelula.id 
                WHERE reunioncelula.idCelula = :idCelula GROUP BY reunioncelula.fecha ORDER BY reunioncelula.fecha ASC";

                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":idCelula", $idCelula);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            } else {

                $sql = "SELECT celulas.nombre, (reunioncelula.infantil + reunioncelula.juvenil + reunioncelula.adulto) AS cantidad_asistencia, reunioncelula.fecha 
                FROM reunioncelula INNER JOIN celulas ON celulas.id = reunioncelula.idCelula WHERE reunioncelula.idCelula = :idCelula ORDER BY reunioncelula.fecha ASC";

                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":idCelula", $idCelula);
                $stmt->execute();

                return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function crecimiento_lideres($idLider, $fecha_inicio, $fecha_fin)
    {
        try {

            $sql = "SELECT celulas.codigo, COUNT(celulas.id) AS cantidad_celulas, celulas.fechaCreacion,
                usuario.nombre, usuario.apellido FROM celulas
                INNER JOIN usuario ON usuario.id = :idLider
                WHERE celulas.fechaCreacion BETWEEN :fecha_inicio AND :fecha_fin GROUP BY celulas.fechaCreacion ASC";


            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idLider", $idLider);
            $stmt->bindValue(":fecha_inicio", $fecha_inicio);
            $stmt->bindValue(":fecha_fin", $fecha_fin);
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

    public function celulasLider($idLider, $fecha_inicio, $fecha_fin)
    {
        try {

            $sql = "SELECT celulas.codigo, celulas.nombre, celulas.fechaCreacion FROM celulas
                WHERE (celulas.idLider = :idLider) AND (celulas.fechaCreacion BETWEEN :fecha_inicio AND :fecha_fin)";


            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idLider", $idLider);
            $stmt->bindValue(":fecha_inicio", $fecha_inicio);
            $stmt->bindValue(":fecha_fin", $fecha_fin);
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
}
