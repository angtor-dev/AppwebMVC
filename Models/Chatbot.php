<?php
require_once "Models/Model.php";

class Chatbot extends Model
{

    /// Para esta funcion se ejecuto la siguiente sentencia a nivel de BD 
    /// ALTER TABLE chatbot_conocimiento ADD FULLTEXT(question);
    /// Esto permitirá realizar consultas de texto completo más eficientes.
    public function getRespuesta($pregunta): string
    {
        $question = $this->preprocesarPregunta($pregunta);
        $question = "%$question%";

        /// En lugar de usar LIKE, que es menos eficiente y no tan preciso en la búsqueda, se uso la consulta MATCH con AGAINST. 
        /// Esto te permitirá obtener resultados más relevantes y rápidos:

        $sql = "SELECT answer FROM chatbot_conocimiento WHERE MATCH(question) AGAINST (:question IN NATURAL LANGUAGE MODE)";
        $statement = $this->db->pdo()->prepare($sql);
        $statement->bindParam(':question', $question, PDO::PARAM_STR);
        $statement->execute();
        $respuesta = $statement->fetch(PDO::FETCH_ASSOC);

        return $respuesta ? $respuesta['answer'] : "Lo siento, no tengo una respuesta para eso.";
    }

    public function preprocesarPregunta($pregunta): string
    {
        // 1. Convertir a minúsculas
        $pregunta = strtolower($pregunta);

        // 2. Eliminar caracteres especiales
        $pregunta = preg_replace('/[^\p{L}\p{N}\s]/u', '', $pregunta);

        // 3. Quitar palabras de relleno (stop words)
        $stopWords = ["el", "la", "los", "las", "de", "en", "y", "a", "un", "una", "que", "con", "para"];
        $palabras = explode(" ", $pregunta);
        $palabrasFiltradas = array_diff($palabras, $stopWords);
        $pregunta = implode(" ", $palabrasFiltradas);

        // 4. Quitar espacios extras
        $pregunta = trim(preg_replace('/\s+/', ' ', $pregunta));

        return $pregunta;
    }
}