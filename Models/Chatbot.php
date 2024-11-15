<?php
require_once "Models/Model.php";

/**
 * Clase Chatbot que extiende de Model. 
 * Esta clase permite interactuar con la base de datos para obtener respuestas a preguntas 
 * mediante la búsqueda de texto completo en la tabla 'chatbot_conocimiento'.
 */
class Chatbot extends Model
{
    /**
     * Obtiene una respuesta de la base de datos para una pregunta dada.
     * Utiliza una búsqueda de texto completo en la columna 'question' de la tabla 'chatbot_conocimiento'.
     * 
     * @param string $pregunta La pregunta que se desea hacer al chatbot.
     * @return string La respuesta obtenida de la base de datos o un mensaje por defecto si no se encuentra respuesta.
     */
    public function getRespuesta($pregunta): string
    {
        // Preprocesar la pregunta para normalizarla antes de buscarla en la base de datos
        $question = $this->preprocesarPregunta($pregunta);
        $question = "%$question%";  // Formato para búsqueda con LIKE

        // Consulta SQL utilizando MATCH...AGAINST para búsqueda de texto completo en 'question'
        // Esto requiere que la columna 'question' tenga un índice FULLTEXT previamente creado.
        $sql = "SELECT answer FROM chatbot_conocimiento WHERE MATCH(question) AGAINST (:question IN NATURAL LANGUAGE MODE)";
        $statement = $this->db->pdo()->prepare($sql);
        $statement->bindParam(':question', $question, PDO::PARAM_STR);
        $statement->execute();

        // Obtener el resultado y devolver la respuesta
        $respuesta = $statement->fetch(PDO::FETCH_ASSOC);

        // Si se encuentra una respuesta, se devuelve; de lo contrario, un mensaje por defecto
        return $respuesta ? $respuesta['answer'] : "Lo siento, no tengo una respuesta para eso.";
    }

    /**
     * Preprocesa la pregunta para mejorar la precisión de la búsqueda.
     * Elimina caracteres especiales, convierte a minúsculas y filtra palabras de relleno (stop words).
     * 
     * @param string $pregunta La pregunta a procesar.
     * @return string La pregunta preprocesada lista para ser consultada.
     */
    public function preprocesarPregunta($pregunta): string
    {
        // 1. Convertir la pregunta a minúsculas
        $pregunta = strtolower($pregunta);

        // 2. Eliminar caracteres especiales (todo excepto letras, números y espacios)
        $pregunta = preg_replace('/[^\p{L}\p{N}\s]/u', '', $pregunta);

        // 3. Eliminar palabras de relleno (stop words) comunes en español
        $stopWords = ["el", "la", "los", "las", "de", "en", "y", "a", "un", "una", "que", "con", "para"];
        $palabras = explode(" ", $pregunta);
        $palabrasFiltradas = array_diff($palabras, $stopWords);
        $pregunta = implode(" ", $palabrasFiltradas);

        // 4. Eliminar espacios extras al inicio y final, y reducir múltiples espacios internos a uno solo
        $pregunta = trim(preg_replace('/\s+/', ' ', $pregunta));

        return $pregunta;
    }
}
