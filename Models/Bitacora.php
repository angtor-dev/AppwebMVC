<?php
require_once "Models/Model.php";

class Bitacora extends Model
{
    public int $id;
    private ?int $idUsuario;
    private string $registro;
    private string $ruta;
    private string $fecha;

    public ?Usuario $usuario = null;

    function __construct()
    {
        parent::__construct();
        if (!empty($this->idUsuario)) {
            $this->usuario = Usuario::cargar($this->idUsuario);
        }
    }

    /**
     * Registra una accion en la bitacora
     * 
     * @param string $registro accion/actividad a registrar
     */
    public static function registrar(string $registro): bool
    {
        global $requestUri;

        
        $idUsuario = !empty($_SESSION['usuario']->id) ? $_SESSION['usuario']->id : "NULL";
        $ruta = $requestUri . "/";

        $query = "INSERT INTO bitacora(idUsuario, registro, ruta)
            VALUES($idUsuario, '$registro', '$ruta')";
        $db = Database::getInstance();
        $db->pdo()->query($query);

        return true;
    }

    // Override para impedir eliminar
    public function eliminar(bool $eliminadoLogico = true): bool
    {
        $_SESSION['errores'][] = "No se puede eliminar un registro de la bitacora";
        return false;
    }

    public function listar_Bitacora()
    {

        try {

            $sql = "SELECT CONCAT(usuario.nombre, ' ' ,usuario.apellido, '-' ,usuario.cedula) AS usuarioDatos, bitacora.registro, bitacora.ruta, bitacora.fecha
            FROM bitacora INNER JOIN usuario ON usuario.id = bitacora.idUsuario  ORDER BY bitacora.fecha DESC";

            $stmt = $this->db->pdo()->prepare($sql);

            $stmt->execute();
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado;

        } catch (Exception $e) {
            $error_data = array(
                "error_message" => $e->getMessage(),
                "error_line" => "Linea del error: " . $e->getLine()
            );

            http_response_code(422);
            echo json_encode($error_data);
            die();
        }

    }

    public function backupDatabase()
    {
        try {

             $host = DB_HOST;
             $dbname = DB_NAME;
             $user = DB_USER;
             $password = DB_PASSWORD;

             $backupDir = 'backups/'; // Ajusta la ruta
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }

        // Ruta completa para los archivos
        $salida_sql = $backupDir . 'backup_' . date('Ymd_His') . '.sql';
        $salida_zip = $backupDir . $dbname . '_' . date('Ymd_His') . '.zip';
        $xammp = 'C:\xampp\mysql\bin\mysqldump';

        // Comando mysqldump (ajusta las opciones según tus necesidades)
        $command = "$xammp --user=$user --password=$password --port=3306 --host=$host --opt $dbname > $salida_sql";
        shell_exec("$command 2>&1");
        // system("$command 2>&1", $output);

        // Crear el archivo ZIP y agregar el archivo SQL
        $zip = new ZipArchive();
        if ($zip->open($salida_zip, ZipArchive::CREATE) === true) {
            // Especifica el método de compresión (ajusta según tu preferencia)
            $zip->setCompressionName('BZIP2',ZipArchive::CM_BZIP2);

            // Agrega el archivo SQL al ZIP
            if (!$zip->addFile($salida_sql)) {
                throw new Exception("Error al agregar el archivo SQL al ZIP");
            }

            // Cierra el archivo ZIP
            $zip->close();

            // Elimina el archivo SQL temporal
            unlink($salida_sql);
            
            http_response_code(200);
                echo json_encode(['message' => 'Backup realizado correctamente']);

            // ... (tu código para enviar la respuesta)
        } else {
            throw new Exception("Error al crear el archivo ZIP");
        }

        } catch (Exception $e) {
            // Registrar el error en un log
            error_log("Error al realizar el backup: " . $e->getMessage());

            http_response_code(422);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    // Getters
    public function getRegistro(): string
    {
        return $this->registro;
    }
    public function getRuta(): string
    {
        return $this->ruta;
    }
    public function getFecha(): string
    {
        return $this->fecha;
    }
}
?>