<?php
require_once "Models/Model.php";

class Territorio extends Model
{
    public int $id;
    public int $idSede;
    public int $idLider;
    private int $idTerritorio;
    private string $identificador;
    private string $codigo;
    private string $nombre;
    private string $detalles;
    private int $estatus;

    public Sede $sede;
    public Usuario $lider;

    //Expresiones regulares
    private $expresion_nombre = '/^[a-zA-ZñÑáéíóúÁÉÍÓÚ\s]{5,50}$/';
    private $expresion_texto = '/^[a-zA-Z0-9ñÑáéíóúÁÉÍÓÚ\s.,]{5,100}$/';
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

    public function registrar_territorio($idSede, $nombre, $idLider, $detalles)
    {
        try {

            $sql = "SELECT MAX(id) AS territorioNumero FROM territorio";
            $consultaid = $this->db->pdo()->prepare($sql);
            $consultaid->execute();
            $datos = $consultaid->fetch(PDO::FETCH_ASSOC);

            $id = '';
            $codigo = '';
            $territorio = '';
            /** @var Sede */
            $sede = Sede::cargar($idSede);

            if ($datos['territorioNumero'] == null) {
                $id = 1;
                $territorio = 'T' . $id;
                $identificador = $sede->getCodigo();
                $codigo = $identificador . '-' . $territorio;
                
            } else {
                /** @var Territorio[] */
                $territorios = Territorio::cargarRelaciones($idSede, "Sede");

                if (count($territorios) > 0) {
                    // Un array para almacenar solo los números de los identificadores
                    $numeros = [];
                    foreach ($territorios as $resultado) {
                        // Extraer el número del identificador (eliminar la "T")
                        $numero = (int) substr($resultado->getIdentificador(), 1);  // substr($resultado, 1) elimina el primer carácter ("T")
                        $numeros[] = $numero;
                    }
                    // Encontrar el número más grande en el array
                    $mayorNumero = max($numeros);

                    $id = $datos['territorioNumero'] + 1;
                    $contador = $mayorNumero + 1;
                    $territorio = 'T' . $contador;
                    $identificador = $sede->getCodigo();
                    $codigo = $identificador . '-' . $territorio;
                } else {

                    $id = $datos['territorioNumero'] + 1;
                    $contador = 1;
                    $territorio = 'T' . $contador;
                    $identificador = $sede->getCodigo();
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
            } else {
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
            }

            Bitacora::registrar("Registro de Territorio");

            http_response_code(200);
            echo json_encode(array('msj' => 'Territorio registrado exitosamente', 'status' => 200));
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

    public  function listar_territorio()
    {

        try {

            $sql = "SELECT usuario.id, usuario.nombre AS nombreLider, usuario.cedula, usuario.apellido, territorio.idLider, territorio.id, territorio.idSede,
             territorio.detalles, territorio.codigo, territorio.nombre, territorio.estatus 
             FROM territorio INNER JOIN usuario ON usuario.id = territorio.idLider WHERE territorio.estatus = '1'";


            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

            Bitacora::registrar("Consulta de Territorios");

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


    public  function editar_territorio($id, $idSede, $nombre, $idLider, $detalles)
    {

        try {
            /** @var Territorio */
            $consulta = Territorio::cargar($id);

            if ($consulta->idSede == $idSede) {

                $sql = "UPDATE territorio SET nombre = :nombre, idLider = :idLider, detalles = :detalles WHERE territorio.id = :id";
                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':detalles', $detalles);

                $stmt->execute();
            } else {
                //Validando si existen celulas antes de cambiar Sede del territorio
                $this->validacion_accion($id, $accion = 'actualizar');

                /** @var Sede */
                $sede = Sede::cargar($idSede);
                /** @var Territorio[] */
                $territorios = Territorio::cargarRelaciones($idSede, "Sede");

                $identificador = '';
                $codigo = '';

                if (count($territorios) > 0) {
                    // Un array para almacenar solo los números de los identificadores
                    $numeros = [];
                    foreach ($territorios as $resultado) {
                        // Extraer el número del identificador (eliminar la "T")
                        $numero = (int) substr($resultado->getIdentificador(), 1);  // substr($resultado, 1) elimina el primer carácter ("T")
                        $numeros[] = $numero;
                    }
                    // Encontrar el número más grande en el array
                    $mayorNumero = max($numeros);

                    $contador = $mayorNumero + 1;
                    $identificador = 'T' . $contador;
                    $codigo = $sede->getCodigo() . '-' . $identificador;
                } else {
                    $contador = 1;
                    $identificador = 'T' . $contador;
                    $codigo = $sede->getCodigo() . '-' . $identificador;
                }

                $sql = "UPDATE territorio SET nombre = :nombre, idLider = :idLider, idSede = :idSede, detalles = :detalles, codigo = :codigo, identificador = :identificador WHERE territorio.id = :id";

                $stmt = $this->db->pdo()->prepare($sql);

                $stmt->bindValue(':id', $id);
                $stmt->bindValue(':nombre', $nombre);
                $stmt->bindValue(':idLider', $idLider);
                $stmt->bindValue(':idSede', $idSede);
                $stmt->bindValue(':detalles', $detalles);
                $stmt->bindValue(':codigo', $codigo);
                $stmt->bindValue(':identificador', $identificador);

                $stmt->execute();
            }

            Bitacora::registrar("Actualizacion de Territorio");

            http_response_code(200);
            echo json_encode(array('msj' => 'Territorio actualizado exitosamente', 'status' => 200));
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




    public  function eliminar_territorio($id)
    {
        try {
            $sql = "UPDATE territorio SET estatus = '0' WHERE territorio.id = :id";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->bindValue(":id", $id);

            $stmt->execute();

            Bitacora::registrar("Eliminacion de Territorio");

            http_response_code(200);
            echo json_encode(array('msj' => 'Eliminado correctamente'));
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
            http_response_code(422);
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
            http_response_code(422);
            echo json_encode($error_data);
            die();
        }
    }






    ///////////////////////////////// ESPACIO PARA VALIDACIONES //////////////////////////////////


    public function validacion_datos(int $idSede, string $nombre, int $idLider, string $detalles): void
    {
        try {
            // Utilizar preg_match para validar el string contra la expresión regular
            if (!preg_match($this->expresion_nombre, $nombre)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El nombre que ingresaste no cumple con los requisitos. Ingrese nuevamente", 422);
            }

            if (!preg_match($this->expresion_texto, $detalles)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("La direccion que ingresaste no cumple con los requisitos. Ingrese nuevamente", 422);
            }

            if (!preg_match($this->expresion_id, $idSede)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El ID no cumple con los requisitos. Seleccione nuevamente", 422);
            }

            if (!preg_match($this->expresion_id, $idLider)) {
                // Lanzar una excepción si el string no es válido
                throw new Exception("El ID no cumple con los requisitos. Seleccione nuevamente", 422);
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function valida_nombre($nombre, $id, $idSede)
    {


        try {

            $sql = "SELECT nombre FROM territorio WHERE (nombre = :nombre) AND (idSede = :idSede) AND (estatus = '1') AND (id NOT IN (:id))";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(':nombre', $nombre);
            $stmt->bindValue(':idSede', $idSede);
            $stmt->bindValue(':id', $id);

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

    public function validacion_existencia(string $nombre, $idSede, $idTerritorio): void
    {
        try {
            $sql = "SELECT * FROM territorio WHERE idSede = :idSede AND nombre = :nombre" . (!empty($idTerritorio) ? " AND id != $idTerritorio" : "");
            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":nombre", $nombre);
            $stmt->bindValue(":idSede", $idSede);
            $stmt->execute();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($resultado != false) {
                if ($resultado['nombre'] == $nombre) {
                    // Lanzar una excepción si el dato existe en la BD
                    throw new Exception("El territorio llamado " . $nombre . " ya existe", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }

    public function validacion_accion(int $idTerritorio, string $accion): void
    {
        try {
            
            /*$sql = "(SELECT 1 FROM celulafamiliar WHERE idTerritorio= :idTerritorio1 AND estatus = 1 LIMIT 1)
                    UNION
                    (SELECT 1 FROM celulaconsolidacion WHERE idTerritorio = :idTerritorio2 AND estatus = 1  LIMIT 1)
                    UNION
                    (SELECT 1 FROM celulacrecimiento WHERE idTerritorio = :idTerritorio3 AND estatus = 1  LIMIT 1)
                    LIMIT 1";*/
            $sql = "SELECT * FROM celulas WHERE idTerritorio = :idTerritorio AND estatus = 1";

            $stmt = $this->db->pdo()->prepare($sql);
            $stmt->bindValue(":idTerritorio", $idTerritorio);
            /*$stmt->bindValue(":idTerritorio1", $idTerritorio);
            $stmt->bindValue(":idTerritorio2", $idTerritorio);
            $stmt->bindValue(":idTerritorio3", $idTerritorio);*/
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // Lanzar una excepción si el dato existe en la BD
                if ($accion == 'eliminar') {
                    throw new Exception("Este territorio esta asociado a celulas que estan en uso. Estos poseen datos asociados", 422);
                }
                if($accion == 'actualizar'){
                    throw new Exception("No puedes cambiar la sede porque ya existen celulas asociadas al territorio y con codigos unicos generados. Esto podria destruir la integridad de los datos", 422);
                }
            }
        } catch (Exception $e) {
            http_response_code($e->getCode());
            echo json_encode(array("msj" => $e->getMessage(), "status" => $e->getCode()));
            die();
        }
    }






    ////////////////////////// APARTADO DE REPORTES ESTADISTICOS ///////////////////////////

    public function cantidad_celulas_territorios()
    {
        try {
            $sql = "SELECT territorio.nombre AS nombreTerritorio, COALESCE(COUNT(celulas.id), 0) AS cantidadCelulas FROM territorio 
            LEFT JOIN celulas ON celulas.idTerritorio = territorio.id 
            GROUP BY territorio.nombre";

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
    public function getIdentificador() : string {
        return $this->identificador;
    }
    public function getCodigo() : string {
        return $this->codigo;
    }
    public function getNombre() : string {
        return $this->nombre;
    }
    public function getDetalles() : string {
        return $this->detalles;
    }
}
