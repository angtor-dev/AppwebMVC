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

    public Escuela $escuela;

    //Expresiones regulares para validaciones
    private $expresion_nombre = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/';
    private $expresion_texto = '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/';
    private $expresion_id = '/^\d{1,9}$/';
    private $estados_venezuela = [
        "ANZ", "APUR", "ARA", "BAR", "BOL", "CAR", "COJ", "DELTA", "FAL", "GUA",
        "LAR", "MER", "MIR", "MON", "ESP", "POR", "SUC", "TACH", "TRU", "VAR", "YAR", "ZUL"
    ];
    private $estados_venezuela_valores = [
        "ANZ"   => "anzoategui",
        "APUR"  => "apure",
        "ARA"   => "aragua",
        "BAR"   => "barinas",
        "BOL"   => "bolivar",
        "CAR"   => "carabobo",
        "COJ"   => "cojedes",
        "DELTA" => "delta amacuro",
        "FAL"   => "falcon",
        "GUA"   => "guarico",
        "LAR"   => "lara",
        "MER"   => "merida",
        "MIR"   => "miranda",
        "MON"   => "monagas",
        "ESP"   => "nueva esparta",
        "POR"   => "portuguesa",
        "SUC"   => "sucre",
        "TACH"  => "tachira",
        "TRU"   => "trujillo",
        "VAR"   => "vargas", // Nota: El estado Vargas fue renombrado a "La Guaira"
        "YAR"   => "yaracuy",
        "ZUL"   => "zulia"
    ];


    //Funcion para obtener el nombre completo del estado
    public function getNombreEstado($estadoCodigo)
    {
        if (isset($this->estados_venezuela_valores[$estadoCodigo])) {
            return $this->estados_venezuela_valores[$estadoCodigo];
        } else {
            // Manejo del error si el código no existe en el array
            return null; // o lanzar una excepción, según tu diseño
        }
    }

    public  function registrar_Sede($idPastor, $nombre, $direccion, $estadoCodigo)
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
                $codigo = $estadoCodigo . '-' . 'S' . $id;
                $identificador = 'S' . $id;
            } else {
                $id = $datos['sedeNumero'] + 1;
                $codigo = $estadoCodigo . '-' . 'S' . $id;
                $identificador = 'S' . $id;
            }

            $nombreEstado = $this->getNombreEstado($estadoCodigo);
            //Aqui puedes declarar una variable con el nombre que quieras. Puede ser $sql, $consulta, $query. Como desees
            //Lo unico que tienes que tomar en cuenta que hay nombras que si estan predefinidos, pero relah, ya el editor te avisa
            $sql = "INSERT INTO sede (id, idpastor, codigo, identificador, nombre, estado, estadoCodigo, direccion, fechaCreacion) 
            VALUES (:id, :idPastor, :codigo, :identificador, :nombre, :estado, :estadoCodigo, :direccion, CURDATE())";

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
            $sentencia->bindValue(':estado', $nombreEstado);
            $sentencia->bindValue(':estadoCodigo', $estadoCodigo);
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
            http_response_code(422);
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
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }

    public  function editar_Sede($id, $idPastor, $nombre, $direccion, $estado)
    {

        try {
            $consulta = Sede::cargar($id);

            if ($consulta->estado === $estado) {
                $sql = "UPDATE sede SET idPastor = :idPastor, nombre = :nombre, direccion = :direccion WHERE sede.id = :id";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':idPastor', $idPastor);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':direccion', $direccion);

                $stmt->execute();
            } else {

                $nombreEstado = $this->getNombreEstado($estado);
                $codigo = $estado . '-' . $consulta->identificador;

                $sql = "UPDATE sede SET idPastor = :idPastor, nombre = :nombre, estado = :estado, estadoCodigo = :estadoCodigo, direccion = :direccion, codigo = :codigo WHERE sede.id = :id";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':idPastor', $idPastor);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':estado', $nombreEstado);
                $stmt->bindValue(':estadoCodigo', $estado);
                $stmt->bindValue(':direccion', $direccion);
                $stmt->bindValue(':codigo', $codigo);

                $stmt->execute();
            }

            http_response_code(200);
            echo json_encode(array('msj' => 'Actualizado correctamente'));
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

    public  function eliminar_Sede($id)
    {

        try {

            $sql = "UPDATE sede SET estatus = '0' WHERE sede.id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();
            http_response_code(200);
            echo json_encode(array('msj' => 'Sede eliminada correctamente', 'status' => 200));
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
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }

    public function validacion_datos(int $idPastor, string $nombre, string $direccion,  string $estado): void
    {
        try {
            // Utilizar preg_match para validar el string contra la expresión regular
            if (!preg_match($this->expresion_nombre, $nombre)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El nombre que ingresaste no cumple con los requisitos. Ingrese nuevamente", 422);
            }

            if (!preg_match($this->expresion_texto, $direccion)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("La direccion que ingresaste no cumple con los requisitos. Ingrese nuevamente", 422);
            }

            if (!preg_match($this->expresion_id, $idPastor)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El ID no cumple con los requisitos. Seleccione nuevamente", 422);
            }

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

    public function validacion_existencia($nombre, $idSede)
    {
        try {
            $sql = "SELECT * FROM sede WHERE nombre = :nombre" . (!empty($idSede) ? " AND id != $idSede" : "");
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":nombre", $nombre);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado !== false) {
                if ($resultado['nombre'] === $nombre) {
                    // Lanzar una excepción si el dato existe en la BD
                    throw new Exception("La sede llamada " . $nombre . " ya existe", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function validacion_eliminar(int $idSede): void
    {
        try {
            $sql = "SELECT * FROM territorio WHERE idSede = :idSede AND estatus = 1";
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idSede", $idSede);
            $stmt->execute();
            //$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($stmt->rowCount() > 0) {
                // Lanzar una excepción si el dato existe en la BD
                throw new Exception("Esta sede esta asociada a un territorio que esta en uso, la cual posee datos asociados", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function cargarEscuela() : Sede
    {
        $this->escuela = Escuela::cargarRelaciones($this->id, "Sede")[0];
        return $this;
    }
}
