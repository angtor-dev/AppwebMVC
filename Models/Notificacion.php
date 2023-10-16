<?php
require_once "Models/Model.php";

class Notificacion extends Model
{
    public int $id;
    private int $idUsuario;
    private string $titulo;
    private string $mensaje;
    private string $fecha;
    private ?string $enlace;
    private bool $visto;
    
    public static function cargarRelaciones(int $id, string $tablaForanea, ?int $estatus = null): array
    {
        $notificaciones = parent::cargarRelaciones($id, $tablaForanea, $estatus);

        usort($notificaciones, [get_class(), "porFechaDescendente"]);

        return $notificaciones;
    }

    private static function porFechaDescendente(Notificacion $notifA, Notificacion $notifB) : int
    {
        $timeA = strtotime($notifA->fecha);
        $timeB = strtotime($notifB->fecha);

        return $timeB <=> $timeA;
    }

    public static function registrar(int $idUsuario, string $titulo, string $mensaje, string $enlace = null) : void
    {
        $db = Database::getInstance();
        $query = "INSERT INTO notificacion (idUsuario, titulo, mensaje, enlace)
            VALUES (:idUsuario, :titulo, :mensaje, :enlace)";

        try {
            $stmt = $db->pdo()->prepare($query);
            $stmt->bindValue("idUsuario", $idUsuario);
            $stmt->bindValue("titulo", $titulo);
            $stmt->bindValue("mensaje", $mensaje);
            $stmt->bindValue("enlace", $enlace);

            $stmt->execute();
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                echo $th->getMessage();
                die;
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar la notificacion.";
        }
    }

    public function marcarVisto() : void
    {
        $query = "UPDATE notificacion SET visto = 1 WHERE id = $this->id";

        try {
            $this->query($query);
        } catch (\Throwable $th) {
            if (DEVELOPER_MODE) {
                echo $th->getMessage();
                die;
            }
            $_SESSION['errores'][] = "Ha ocurrido un error al marcar la notificacion";
            throw $th;
        }
    }

    // Getters
    public function getTiempo() : string
    {
        $fecha = new DateTime($this->fecha);
        $hoy = new DateTime(date("Y-m-d H:i:s"));

        $diff = $hoy->diff($fecha);

        if ($diff->days == 0 && $diff->h == 0) {
            return $diff->format('Hace %im');
        } elseif ($diff->days == 0 && $diff->h > 0) {
            return $diff->format('Hace %hh');
        } else {
            return $fecha->format('d/m/y');
        }
    }
    public function getIdUsuario() : int {
        return $this->idUsuario;
    }
    public function getTitulo() : string {
        return $this->titulo;
    }
    public function getMensaje() : string {
        return $this->mensaje;
    }
    public function getFecha() : string {
        return $this->fecha;
    }
    public function getEnlace() : string {
        return $this->enlace;
    }
    public function getVisto() : bool {
        return $this->visto;
    }
}
?>