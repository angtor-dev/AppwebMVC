<?php
require_once "Models/Model.php";

class Sede extends Model
{
    public int $id;
    private ?int $idPastor;
    private ?string $codigo;
    private string $identificador;
    private string $nombre;
    private string $estadoCodigo;
    private string $estado;
    private string $direccion;
    private int $estatus;

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

            if ($datos['sedeNumero'] == null) {
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

            /** @var Sede **/
            $consulta = Sede::cargar($id);

            if ($consulta->estado == $estado) {
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





    /////////////////////// ESPACIO PARA VALIDACIONES //////////////////////////

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

            if ($resultado != false) {
                if ($resultado['nombre'] == $nombre) {
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

    public function validacion_editar_estado(int $idSede, string $estado): void
    {
        try {

            /** @var Sede **/
            $resultado = Sede::cargar($idSede);

            if ($resultado->estadoCodigo != $estado) {
                $sql = "SELECT * FROM territorio WHERE idSede = :idSede AND estatus = 1";
                $stmt = $this->db->pdo()->prepare($sql);
                $stmt->bindValue(":idSede", $idSede);
                $stmt->execute();
                //$resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($stmt->rowCount() > 0) {
                    // Lanzar una excepción si el dato existe en la BD
                    throw new Exception("No puedes cambiar el estado de esta Sede. Recuerde que su codigo esta asociado a todos los datos relacionados con el mismo.", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }




    ////////////////////////// APARTADO DE REPORTES ESTADISTICOS ///////////////////////////

    public function cantidad_celulas_sedes()
    {
        try {
            $sql = "SELECT sede.nombre AS nombreSede, COALESCE(COUNT(celulas.id), 0) AS cantidadCelulas FROM sede 
            LEFT JOIN territorio ON territorio.idSede = sede.id 
            LEFT JOIN celulas ON celulas.idTerritorio = territorio.id 
            GROUP BY sede.nombre";

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

    public function cantidad_territorios_sedes()
    {
        try {
            $sql = "SELECT sede.nombre AS nombreSede, COALESCE(COUNT(territorio.id), 0) AS cantidadTerritorios FROM sede 
            LEFT JOIN territorio ON territorio.idSede = sede.id 
            GROUP BY sede.nombre";

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

    public function cantidad_sedes_fecha()
    {
        try {
            $sql = "SELECT 
            SUM(CASE WHEN MONTH(fechaCreacion) = 1 THEN 1 ELSE 0 END) AS Enero,
            SUM(CASE WHEN MONTH(fechaCreacion) = 2 THEN 1 ELSE 0 END) AS Febrero,
            SUM(CASE WHEN MONTH(fechaCreacion) = 3 THEN 1 ELSE 0 END) AS Marzo,
            SUM(CASE WHEN MONTH(fechaCreacion) = 4 THEN 1 ELSE 0 END) AS Abril,
            SUM(CASE WHEN MONTH(fechaCreacion) = 5 THEN 1 ELSE 0 END) AS Mayo,
            SUM(CASE WHEN MONTH(fechaCreacion) = 6 THEN 1 ELSE 0 END) AS Junio,
            SUM(CASE WHEN MONTH(fechaCreacion) = 7 THEN 1 ELSE 0 END) AS Julio,
            SUM(CASE WHEN MONTH(fechaCreacion) = 8 THEN 1 ELSE 0 END) AS Agosto,
            SUM(CASE WHEN MONTH(fechaCreacion) = 9 THEN 1 ELSE 0 END) AS Septiembre,
            SUM(CASE WHEN MONTH(fechaCreacion) = 10 THEN 1 ELSE 0 END) AS Octubre,
            SUM(CASE WHEN MONTH(fechaCreacion) = 11 THEN 1 ELSE 0 END) AS Noviembre,
            SUM(CASE WHEN MONTH(fechaCreacion) = 12 THEN 1 ELSE 0 END) AS Diciembre
            FROM 
            sede
            WHERE 
            YEAR(fechaCreacion) = YEAR(CURDATE())";

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








    public function getEscuela(): Escuela
    {
        return Escuela::cargarRelaciones($this->id, "Sede")[0];
    }

    public function getCodigo(): ?string
    {
        return $this->codigo;
    }
    public function getIdentificador(): string
    {
        return $this->identificador;
    }
    public function getNombre(): string
    {
        return $this->nombre;
    }
    public function getEstadoCodigo(): string
    {
        return $this->estadoCodigo;
    }
    public function getEstado(): string
    {
        return $this->estado;
    }
    public function getDireccion(): string
    {
        return $this->direccion;
    }
}
