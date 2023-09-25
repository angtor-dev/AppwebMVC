<?php
require_once "Models/Model.php";

class Celulas extends Model
{
    public int $id;
    public string $tipo;
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


    public  function registrar_Celula($tipo, $nombre, $idLider, $idCoLider, $idTerritorio)
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
                
                $codigo = $territorio->codigo . '-' . $identificador;
                
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
                
                    $codigo = $territorio->codigo . '-' . $identificador;

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

                    $codigo = $territorio->codigo . '-' . $identificador;
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

            /** @var Bitacora **/
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





    public  function listar_CelulaFamiliar()
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
            /** @var Bitacora **/
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


    public  function listar_CelulaCrecimiento()
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
            celulas.idCoLider
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
            Bitacora::registrar("Consulta de celula de Crecimiento");

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
            celulas.id,
            celulas.idLider,
            celulas.idCoLider
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
            Bitacora::registrar("Consulta de celula de Consolidacion");

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






    public  function editar_Celula($id, $tipo, $nombre, $idLider, $idCoLider, $idTerritorio)
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
                    
                    $codigo = $territorio->codigo . '-' . $identificador;
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
                    
                    $codigo = $territorio->codigo . '-' . $identificador;
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
            Bitacora::registrar("Actualizacion de celula familiar");

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




    public  function eliminar_Celula($id)
    {

        try {

            $sql = "UPDATE celulas SET estatus = '0' WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            /** @var Bitacora **/
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

    public function registrar_reunion($idCelula, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones)
    {

        try {

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

            /** @var Bitacora **/
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


    public  function listar_reunionesFamiliar()
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

            /** @var Bitacora **/
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


    public  function listar_reunionesCrecimiento()
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

            /** @var Bitacora **/
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

    public  function listar_reunionesConsolidacion()
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

            /** @var Bitacora **/
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

            /** @var Bitacora **/
            Bitacora::registrar("Actualizacion de reunion de Celula");

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




    public  function eliminar_reuniones($id)
    {
        try {

            $sql = "DELETE FROM reunioncelula WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            /** @var Bitacora **/
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


    public  function listar_celulas()
    {
        try {

            $sql = "SELECT * FROM celulas WHERE celulas.estatus = '1'";

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

    public function validacion_existencia(string $nombre, $id): void
    {
        try {
            $sql = "SELECT * FROM celulas WHERE nombre = :nombre" . (!empty($id) ? " AND id != $id" : "");
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


    public function listarDiscipulados_celula($idCelula)
    {
        try {

            $sql = "SELECT * FROM discipulo WHERE idCelula= :idCelula";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idCelula", $idCelula);
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

            /** @var Bitacora **/
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

            /** @var Bitacora **/
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

            /** @var Bitacora **/
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
}
