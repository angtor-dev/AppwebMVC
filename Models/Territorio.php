<?php
require_once "Models/Model.php";

class Territorio extends Model
{
    public int $id;
    public int $idSede;
    public int $idLider;
    public int $idTerritorio;
    public string $codigo;
    public string $nombre;
    public string $detalles;
    public int $estatus;

    public Sede $sede;
    public Usuario $lider;

    //Expresiones regulares
    private $expresion_nombre = '/^[a-zA-Z\s]{1,30}$/';
    private $expresion_detalles = '/^[a-zA-Z0-9\s.,]{1,100}$/';
    private $expresion_id = '/^[1-9]\d*$/';

    public function __construct()
    {
        parent::__construct();
        if (!empty($this->idSede)) {
            $this->sede = Sede::cargar($this->idSede);
        }
        if (!empty($this->idLider)) {
            $this->lider = Usuario::cargar($this->idLider);
        }
    }

    public  function registrar_territorio($idSede, $nombre, $idLider, $detalles)
    {
        try {

            $sql = "SELECT MAX(id) AS territorioNumero FROM territorio";
            $consultaid = $this->db->pdo()->prepare($sql);
            $consultaid->execute();
            $datos = $consultaid->fetch(PDO::FETCH_ASSOC);

            $id = '';
            $codigo = '';
            $territorio = '';
            $sede = Sede::cargar($idSede);

            if ($datos['territorioNumero'] === null) {
                $id = 1;
                $territorio = 'T' . $id;
                $identificador = $sede->identificador;
                $codigo = $identificador . '-' . $territorio;
            } else {
                $territorios = Territorio::cargarRelaciones($idSede, "Sede");

                if (count($territorios) > 0) {
                    // Un array para almacenar solo los números de los identificadores
                    $numeros = [];
                    foreach ($territorios as $resultado) {
                        // Extraer el número del identificador (eliminar la "T")
                        $numero = (int) substr($resultado->identificador, 1);  // substr($resultado, 1) elimina el primer carácter ("T")
                        $numeros[] = $numero;
                    }
                    // Encontrar el número más grande en el array
                    $mayorNumero = max($numeros);

                    $contador = $mayorNumero + 1;
                    $territorio = 'T' . $contador;
                    $identificador = $sede->identificador;
                    $codigo = $identificador . '-' . $territorio;
                } else {
                    $contador = 1;
                    $territorio = 'T' . $contador;
                    $identificador = $sede->identificador;
                    $codigo = $identificador . '-' . $territorio;
                }
            }

            if ($id == 1) {
                //Aqui puedes declarar una variable con el nombre que quieras. Puede ser $sql, $consulta, $query. Como desees
                //Lo unico que tienes que tomar en cuenta que hay nombras que si estan predefinidos, pero relah, ya el editor te avisa
                $sql = "INSERT INTO territorio (id, idSede, idLider, codigo, identificador, nombre, detalles, fechaCreacion) 
                VALUES (:id, :idSede, :idLider, :codigo, :identificador, :nombre, :detalles, CURDATE())";
                //no se pueden enviar los valores por variables  parametrizacion y evita inyeccion de slq':nombrequetuquieres'
                //Todo lo que esta en VALUES() esta malo, preguntame el porque y despues quiero que escribas la respuesta aqui como comentario para que nunca se te olvide

                //Preparamos aqui la consulta sql que hicimos ahi arriba, es decir, preparamos la variable $sql
                $stmt = $this->db->pdo()->prepare($sql);

                //Ahora empezamos a ingresar los valores en la consulta sql. Es decir, ingresamos los valores parametrizados
                //Esto con la finalidad de evitar inyecciones SQL
                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':idSede', $idSede);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':identificador', $territorio);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':detalles', $detalles);

                //Ahora ejecutemos la consulta sql una vez ingresado todos los valores, es decir, los parametros que mencionamos arriba
                $stmt->execute();
            }else{
                //Aqui puedes declarar una variable con el nombre que quieras. Puede ser $sql, $consulta, $query. Como desees
                //Lo unico que tienes que tomar en cuenta que hay nombras que si estan predefinidos, pero relah, ya el editor te avisa
                $sql = "INSERT INTO territorio (idSede, idLider, codigo, identificador, nombre, detalles, fechaCreacion) 
                VALUES (:idSede, :idLider, :codigo, :identificador, :nombre, :detalles, CURDATE())";
                //no se pueden enviar los valores por variables  parametrizacion y evita inyeccion de slq':nombrequetuquieres'
                //Todo lo que esta en VALUES() esta malo, preguntame el porque y despues quiero que escribas la respuesta aqui como comentario para que nunca se te olvide

                //Preparamos aqui la consulta sql que hicimos ahi arriba, es decir, preparamos la variable $sql
                $stmt = $this->db->pdo()->prepare($sql);

                //Ahora empezamos a ingresar los valores en la consulta sql. Es decir, ingresamos los valores parametrizados
                //Esto con la finalidad de evitar inyecciones SQL
                $stmt->bindValue(':idSede', $idSede);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':identificador', $territorio);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':detalles', $detalles);

                //Ahora ejecutemos la consulta sql una vez ingresado todos los valores, es decir, los parametros que mencionamos arriba
                $stmt->execute();
            }

            http_response_code(200);
            echo json_encode(array('msj' => 'Territorio registrado exitosamente', 'status' => 200));
            die();

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

    public  function listar_territorio()
    {

        try {

            $sql = "SELECT usuario.id, usuario.nombre AS nombreLider, usuario.cedula, usuario.apellido, territorio.idLider, territorio.id, territorio.idSede,
             territorio.detalles, territorio.codigo, territorio.nombre, territorio.estatus 
             FROM territorio INNER JOIN usuario ON usuario.id = territorio.idLider WHERE territorio.estatus = '1'";


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


    public  function editar_territorio($id, $idSede, $nombre, $idLider, $detalles)
    {

        try {



            $sql = "UPDATE territorio SET  idSede = :idSede, nombre = :nombre, idLider = :idLider, detalles = :detalles WHERE territorio.id = :id";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':idSede', $idSede);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':idLider', $idLider);
            $stmt->bindValue(':detalles', $detalles);

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




    public  function eliminar_territorio($id)
    {

        try {

            $sql = "UPDATE territorio SET estatus = '0' WHERE territorio.id = :id";

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


    public  function listar_lideres()
    {

        try {


            $sql = "SELECT usuario.id, usuario.cedula, usuario.nombre, usuario.apellido 
            FROM usuariorol INNER JOIN usuario ON usuario.id = usuariorol.idUsuario WHERE usuariorol.idRol IN (3, 4)";

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

    public  function listar_Sedes()
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


    /////////////////// ESPACIO PARA VALIDACIONES //////////////////////
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

    public function validacion_detalles(string $detalles): void
    {
        try {
            // Utilizar preg_match para validar el string contra la expresión regular
            if (!preg_match($this->expresion_detalles, $detalles)) {
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
}
