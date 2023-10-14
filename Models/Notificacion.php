<?php
require_once "Models/Model.php";

class Notificacion  extends Model
{
    public int $id;
    private int $idUsuario;
    private string $titulo;
    private string $mensaje;
    private string $fecha;
    private ?string $enlace;
    private string $visto;

    public function __construct(?int $idUsuario, ?string $titulo, ?string $mensaje, ?string $enlace)
    {
        if (isset($idUsuario)) {
            $this->idUsuario = $idUsuario;
            $this->titulo = $titulo;
            $this->mensaje = $mensaje;
            $this->enlace - $enlace ?? "NULL";
        }
    }

    public function registrar() : void
    {
        $query = "INSERT INTO evento (idUsuario, titulo, mensaje, enlace)
            VALUES (:idUsuario, :titulo, :mensaje, :enlace)";

        try {
            $stmt = $this->prepare($query);
            $stmt->bindValue("idUsuario", $this->idUsuario);
            $stmt->bindValue("titulo", $this->titulo);
            $stmt->bindValue("mensaje", $this->mensaje);
            $stmt->bindValue("enlace", $this->enlace);

            $stmt->execute();
        } catch (\Throwable $th) {
            $_SESSION['errores'][] = "Ha ocurrido un error al registrar la notificacion.";
        }
    }
}
?>