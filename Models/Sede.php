<?php
require_once "Models/Model.php";

class Sede extends Model
{
    public int $id;
    public ?int $idPastor;
    public ?string $codigo;
    public string $nombre;
    public string $estado;
    public string $direccion;
    public int $estatus;

    //Expresiones regulares para validaciones
    private $expresion_nombre = '/^[a-zA-Z\s]{1,30}$/';
    private $expresion_direccion = '/^[a-zA-Z0-9\s.,]{1,100}$/';
    private $expresion_id = '/^\d{1,9}$/';
    private $estados_venezuela = [
        "ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
        "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TÁCH", "TRU", "VAR", "YAR", "ZUL"
    ];

    public  function registrar_Sede($idPastor, $nombre, $direccion, $estado)
    {
        try {

            $query = "SELECT MAX(id) AS sedeNumero FROM sede";
            $consultaid = $this->db->pdo()->prepare($query);
            $consultaid->execute();
            $datos = $consultaid->fetch(PDO::FETCH_ASSOC);

            //variables vacias para luego rellenar
            $id = '';
            $codigo = '';
            $identificador = '';

            if ($datos['sedeNumero'] === null) {
                $id = 1;
                $codigo = $estado . '-' . 'S' . $id;
                $identificador = 'S'.$id;

            } else {
                $id = $datos['sedeNumero'] + 1;
                $codigo = $estado . '-' . 'S' . $id;
                $identificador = 'S'.$id;
            }

            //Aqui puedes declarar una variable con el nombre que quieras. Puede ser $sql, $consulta, $query. Como desees
            //Lo unico que tienes que tomar en cuenta que hay nombras que si estan predefinidos, pero relah, ya el editor te avisa
            $sql = "INSERT INTO sede (id, idpastor, codigo, identificador, nombre, estado, direccion, fechaCreacion) 
                VALUES (:id, :idPastor, :codigo, :identificador, :nombre, :estado, :direccion, CURDATE())";

            //no se pueden enviar los valores por variables  parametrizacion y evita inyeccion de slq':nombrequetuquieres'
            //Todo lo que esta en VALUES() esta malo, preguntame el porque y despues quiero que escribas la respuesta aqui como comentario para que nunca se te olvide

            //Preparamos aqui la consulta sql que hicimos ahi arriba, es decir, preparamos la variable $sql
            $sentencia = $this->db->pdo()->prepare($sql);
            //Ahora empezamos a ingresar los valores en la consulta sql. Es decir, ingresamos los valores parametrizados
            //Esto con la finalidad de evitar inyecciones SQL
            $sentencia->bindValue(':id', $id);
            $sentencia->bindValue(':idPastor', $idPastor);
            $sentencia->bindValue(':codigo', $codigo);
            $sentencia->bindValue(':identificador', $identificador);
            $sentencia->bindValue(':nombre', $nombre);
            $sentencia->bindValue(':estado', $estado);
            $sentencia->bindValue(':direccion', $direccion);

            //Ahora ejecutemos la consulta sql una vez ingresado todos los valores, es decir, los parametros que mencionamos arriba
            $sentencia->execute();
            http_response_code(200);
            echo json_encode(array('msj' => 'Sede registrada exitosamente', 'status' => 200));
            die();

        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );

            echo json_encode($error_data);
            die();
        }
    }

    public  function listar_Sede()
    {

        try {

            $sql = "SELECT * FROM sede WHERE sede.estatus = '1'";

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
            echo json_encode($error_data);
            die();
        }
    }

    public  function editar_Sede($id, $idPastor, $nombre, $direccion, $estado)
    {

        try {

            $sql = "UPDATE sede SET idPastor = :idPastor, nombre = :nombre, estado = :estado, direccion = :direccion WHERE sede.id = :id";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':idPastor', $idPastor);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':estado', $estado);
            $stmt->bindValue(':direccion', $direccion);

            $stmt->execute();
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            //print_r($error_data);
            echo json_encode($error_data);
            die();
        }
    }

    public  function eliminar_Sede($id)
    {

        try {

            $sql = "UPDATE sede SET estatus = '0' WHERE sede.id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();
        } catch (Exception $e) { // Muestra el mensaje de error y detén la ejecución.
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );
            //print_r($error_data);
            echo json_encode($error_data);
            die();
        }
    }

    public  function listar_Pastores()
    {

        try {
            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario WHERE usuariorol.idRol = '4'";

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
            echo json_encode($error_data);
            die();
        }
    }

    public function validacion_nombre(string $nombre): void
    {
        try {
            // Utilizar preg_match para validar el string contra la expresión regular
            if (!preg_match($this->expresion_nombre, $nombre)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El nombre que ingresaste no cumple con los requisitos. Ingrese nuevamente", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function validacion_direccion(string $direccion): void
    {
        try {
            // Utilizar preg_match para validar el string contra la expresión regular
            if (!preg_match($this->expresion_direccion, $direccion)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("La direccion que ingresaste no cumple con los requisitos. Ingrese nuevamente", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function validacion_id(int $id): void
    {
        try {
            // Utilizar preg_match para validar el string contra la expresión regular
            if (!preg_match($this->expresion_id, $id)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El ID no cumple con los requisitos. Seleccione nuevamente", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function validacion_estado(string $estado): void
    {
        try {
            // Utilizar preg_match para validar el string contra la expresión regular
            if (!in_array($estado, $this->estados_venezuela)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El estado que ha seleccionado no existe. Seleccione nuevamente", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }
}
