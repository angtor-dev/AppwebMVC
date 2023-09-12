<?php
require_once "Models/Model.php";

class CelulaFamiliar extends Model
{
    public int $id;
    public int $idLider;
    public int $idColider;
    public int $idTerritorio;
    public string $codigo;
    public string $nombre;
    public int $estatus;

    //Expresiones regulares
    private $expresion_nombre = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/';
    private $expresion_texto = '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/';
    private $expresion_id = '/^[1-9]\d*$/';
    private $expresion_fecha = '/^\d{4}-\d{2}-\d{2}$/';


    public  function registrar_CelulaFamiliar($nombre, $idLider, $idCoLider, $idTerritorio)
    {
        try {

            $sql = "SELECT MAX(id) AS celulaNumero FROM celulafamiliar";
            $consultaid = $this->db->pdo()->prepare($sql);
            $consultaid->execute();
            $datos = $consultaid->fetch(PDO::FETCH_ASSOC);

            $id = '';
            $codigo = '';
            $territorio = Territorio::cargar($idTerritorio);

            if ($datos['celulaNumero'] == null) {
                $id = 1;
                $identificador = 'CFA' . $id;
                $codigo = $territorio->codigo . '-' . $identificador;
            } else {
                $celulas = CelulaFamiliar::cargarRelaciones($idTerritorio, "Territorio");

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
                    $identificador = 'CFA' . $contador;
                    $codigo = $territorio->codigo . '-' . $identificador;
                } else {
                    $contador = 1;
                    $identificador = 'CFA' . $contador;
                    $codigo = $territorio->codigo . '-' . $identificador;
                }
            }

            if ($id == 1) {

                $sql = "INSERT INTO celulafamiliar (id, nombre, codigo, identificador, idLider, idCoLider, idTerritorio, fechaCreacion) 
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
                $sql = "INSERT INTO celulafamiliar (nombre, codigo, identificador, idLider, idCoLider, idTerritorio, fechaCreacion) 
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
            celulafamiliar.id,
            celulafamiliar.idLider,
            celulafamiliar.idCoLider,
            celulafamiliar.idTerritorio,
            celulafamiliar.codigo,
            celulafamiliar.nombre,
            celulafamiliar.estatus
            FROM celulafamiliar
            INNER JOIN usuario AS Lider ON celulafamiliar.idLider = Lider.id
            INNER JOIN usuario AS CoLider ON celulafamiliar.idCoLider = CoLider.id
            WHERE celulafamiliar.estatus = '1'";


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
            print_r($error_data);
            echo json_encode($error_data);
            die();
        }
    }






    public  function editar_CelulaFamiliar($id, $nombre, $idLider, $idCoLider, $idTerritorio)
    {
        try {
            $consulta = CelulaFamiliar::cargar($id);

            if ($consulta->idTerritorio == $idTerritorio) {

                $sql = "UPDATE celulafamiliar SET  nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider WHERE celulafamiliar.id = :id";
                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);

                $stmt->execute();
            } else {

                $territorio = Territorio::cargar($idTerritorio);
                $celulas = CelulaFamiliar::cargarRelaciones($idTerritorio, "Territorio");

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
                    $identificador = 'CFA' . $contador;
                    $codigo = $territorio->codigo . '-' . $identificador;
                } else {
                    $contador = 1;
                    $identificador = 'CFA' . $contador;
                    $codigo = $territorio->codigo . '-' . $identificador;
                }

                $sql = "UPDATE celulafamiliar SET  nombre = :nombre, idLider = :idLider, idCoLider = :idCoLider, codigo = :codigo, identificador = :identificador WHERE celulafamiliar.id = :id";
                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idCoLider', $idCoLider);
                $stmt->bindValue(':identificador', $identificador);
                $stmt->bindValue(':codigo', $codigo);

                $stmt->execute();
            }

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




    public  function eliminar_CelulaFamiliar($id)
    {

        try {

            $sql = "UPDATE celulafamiliar SET estatus = '0' WHERE celulafamiliar.id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

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

    public function registrar_reunion($idCelulaFamiliar, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones)
    {

        try {

            $sql = "INSERT INTO reunionfamiliar (idCelulaFamiliar, fecha, tematica, semana, generosidad, infantil, juvenil, adulto, actividad, observaciones) 
        VALUES (:idCelulaFamiliar, :fecha, :tematica, :semana, :generosidad, :infantil, :juvenil, :adulto, :actividad, :observaciones)";

            $stmt = $this->db->pdo()->prepare($sql);


            $stmt->bindValue(':idCelulaFamiliar', $idCelulaFamiliar);
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
                    reunionfamiliar.id,
                    reunionfamiliar.fecha,
                    reunionfamiliar.tematica,
                    reunionfamiliar.semana,
                    reunionfamiliar.generosidad,
                    reunionfamiliar.infantil,
                    reunionfamiliar.juvenil,
                    reunionfamiliar.adulto,
                    reunionfamiliar.actividad,
                    reunionfamiliar.observaciones,
                    celulafamiliar.codigo,
                    celulafamiliar.nombre,
                    celulafamiliar.id AS idCelulaFamiliar
                FROM reunionfamiliar
                INNER JOIN celulafamiliar
                ON reunionfamiliar.idcelulafamiliar = celulafamiliar.id
                ORDER BY reunionfamiliar.fecha DESC;";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            print_r($error_data);
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }


    public function editar_reuniones($id, $idCelulaFamiliar, $fecha, $tematica, $semana, $generosidad, $infantil, $juvenil, $adulto, $actividad, $observaciones)
    {

        try {

            $sql = "UPDATE reunionfamiliar
                SET
                idCelulaFamiliar = :idCelulaFamiliar,
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
            $stmt->bindValue(':idCelulaFamiliar', $idCelulaFamiliar);
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

            $sql = "DELETE FROM reunionfamiliar WHERE id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

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

            $sql = "SELECT * FROM celulafamiliar WHERE celulafamiliar.estatus = '1'";

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
            $sql = "SELECT * FROM celulafamiliar WHERE nombre = :nombre" . (!empty($id) ? " AND id != $id" : "");
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

            $sql = "SELECT * FROM reunionfamiliar WHERE idCelulaFamiliar= :idCelulaFamiliar AND estatus = 1";

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
