<?php
require_once "Models/Model.php";

/**
 * Clase Chatbot que extiende de Model. 
 * Esta clase permite interactuar con la base de datos para obtener respuestas a preguntas 
 * mediante la búsqueda de texto completo en la tabla 'chatbot_conocimiento'.
 */
class Chatbot extends Model
{
    private $sinonimos = [
        'realizar' => 'hacer',
    'efectuar' => 'hacer',
    'ejecutar' => 'hacer',
    'inscribir' => 'registrar',
    'anotar' => 'registrar',
    'documentar' => 'registrar',
    'borrar' => 'eliminar',
    'suprimir' => 'eliminar',
    'quitar' => 'eliminar',
    'anjhel' => 'eliminar'
    ];
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
        

        // Realizar la búsqueda inicial
    $respuesta = $this->buscarRespuestaEnBaseDeDatos($question);

    // Si se encontró una respuesta, devolverla
    if ($respuesta) {
        return $respuesta;
    } else {

    // Si no se encontró, intentar con sinónimos
    $respuesta = $this->nuevapregunta($question);
         
        return $respuesta ?: 'Lo siento, no tengo una respuesta para eso.';

     }
    // Si aún no se encontró respuesta, devolver un mensaje por defecto
    
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


    function nuevapregunta($pregunta) {
     // Accedemos a la base de conocimientos global
    

     $nuevaPregunta = $this->obtenerPreguntaConSinonimos($pregunta);
     $respuesta = $this->buscarRespuestaEnBaseDeDatos($nuevaPregunta);

     return $respuesta;
       
    }

    private function obtenerPreguntaConSinonimos($pregunta) {
        // 1. Desglosar la pregunta en un array de palabras
    $arrayPregunta = explode(' ', $pregunta);

    // 2. Inicializar la nueva pregunta
    $nuevaPregunta = '';

    // 3. Iterar sobre cada palabra de la pregunta y buscar su sinónimo
    foreach ($arrayPregunta as $palabra) {
        // Convertir la palabra a minúsculas para una comparación más robusta
        $palabraMinuscula = strtolower($palabra);

        // Buscar el sinónimo en el diccionario
        $sinonimo = $this->sinonimos[$palabraMinuscula] ?? $palabraMinuscula;

        // Agregar el sinónimo o la palabra original a la nueva pregunta
        $nuevaPregunta .= $sinonimo . ' ';
    }

    // Eliminar el último espacio sobrante
    $nuevaPregunta = trim($nuevaPregunta);

    return $nuevaPregunta;
       }


    private function buscarRespuestaEnBaseDeDatos($question)
{
    // Consulta SQL utilizando MATCH...AGAINST para búsqueda de texto completo en 'question'. Esto es mas eficiente que solo usar la consulta LIKE
        // Esto requiere que la columna 'question' tenga un índice FULLTEXT previamente creado.

        $question = "%$question%";  // Formato para búsqueda con LIKE

        $sql = "SELECT answer FROM chatbot_conocimiento WHERE MATCH(question) AGAINST (:question IN NATURAL LANGUAGE MODE)";
        $statement = $this->db->pdo()->prepare($sql);
        $statement->bindParam(':question', $question, PDO::PARAM_STR);
        $statement->execute();

        // Obtener el resultado y devolver la respuesta
        $respuesta = $statement->fetch(PDO::FETCH_ASSOC);

        return $respuesta ? $respuesta['answer'] : false;
}




}
