<?php
require_once "Models/Model.php";

class Discipulo extends Model
{

    public int $idCelulaConsolidacion;

    private $expresion_nombreApellido = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ]{1,50}$/';
    private $expresion_fecha = '/^\d{4}-\d{2}-\d{2}$/';
    private $expresion_texto = '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/';
    private $expresion_estadoCivil = ["casado/a", "soltero/a", "viudo/a"];
    private $expresion_cedula = '/^[0-9]{7,8}$/';
    private $expresion_asiste = ['si', 'no'];
    private $expresion_telefono = '/^(0414|0424|0416|0426|0412)[0-9]{7}/';


    public  function registrar_discipulo(
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
            idcelulaconsolidacion,
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


    public  function listar_Consolidador()
    {

        try {
            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario WHERE usuariorol.idRol IN (1, 2, 3, 4)";

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



    public  function listar_celulas()
    {

        try {

            $sql = "SELECT * FROM celulas WHERE celulas.estatus = '1' AND celulas.tipo = 'consolidacion'";

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

    public  function listar_discipulo()
    {

        try {

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
            discipulo.idcelulaconsolidacion,
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

    public  function editar_discipulo(
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

            /** @var Discipulo **/
            $resultado = Discipulo::cargar($id);

            if ($resultado->idCelulaConsolidacion != $idCelulaConsolidacion) {

                $this->validacion_accion($id, $accion = 'actualizar');

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
            }else{
                $sql = "UPDATE discipulo SET
                        asisCrecimiento = :asisCrecimiento,
                        asisFamiliar = :asisFamiliar,
                        idConsolidador = :idConsolidador,
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
            }

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

    public  function eliminar_discipulo($id)
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
}
