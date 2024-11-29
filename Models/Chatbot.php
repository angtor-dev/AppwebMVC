<?php
require_once "Models/Model.php";

/**
 * Clase Chatbot que extiende de Model. 
 * Esta clase permite interactuar con la base de datos para obtener respuestas a preguntas 
 * mediante la búsqueda de texto completo en la tabla 'chatbot_conocimiento'.
 */
class Chatbot extends Model
{
    //Diccionarios de sinónimos y palabras clave
    private $palabrasClave = [
        'enumerar' => 'listar',
    'catalogar' => 'listar',
    'consultar' => 'listar',
    'ver' => 'listar',
    'renovar' => 'actualizar',
    'editar' => 'actualizar',
    'cambiar' => 'actualizar',
    'modificar' => 'actualizar',
    'revisar' => 'actualizar',
    'inscribir' => 'registrar',
    'anotar' => 'registrar',
    'documentar' => 'registrar',
    'borrar' => 'eliminar',
    'suprimir' => 'eliminar',
    'quitar' => 'eliminar',
    'calendario' => 'agenda'

    ];

    private $sinonimos = [
    'clave' => 'contrasena',
    'buscar' => 'encontrar',
    'ver' => 'observar',
    'usar' => 'emplear',
    'ayudame' => 'ayuda',
    'crear' => 'generar',
    'eliminar' => 'borrar',
    'actualizar' => 'renovar',
    'iniciar' => 'comenzar',
    'terminar' => 'finalizar',
    'guardar' => 'almacenar',
    'cuenta' => 'usuario',
    'perfil' => 'información',
    'datos' => 'información',
    'problema' => 'inconveniente',
    'ayuda' => 'asistencia',
    'configuración' => 'ajustes',
    'opción' => 'alternativa',
    'saludos' => 'hola',
    'jelou' => 'hola',
    'adiós' => 'hasta luego',
    'gracias' => 'agradezco',
    'por favor' => 'favor',
    ];

    
    private $modulos = ['sede', 'agenda', 'territorio', 'sedes', 'agendas', 'territorios', 'clave', 'contrasena'];

    private $acciones = ['listar', 'eliminar', 'actualizar', 'registrar', 'recuperar'];
    
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
         
        //Reglas implícitas
        $accion = $this->buscarAcciones($question);
        $modulo = $this->buscarModulos($question);


        if (!empty($modulo) && empty($accion)) {
            // Módulo está lleno y acción está vacía
            return "Claro que te puedo proporcionar toda la información que necesites sobre $modulo. Solo necesito que ahora especifiques qué deseas hacer, ejemplo: eliminar, editar, actualizar...";
        } elseif (empty($modulo) && !empty($accion)) {
            // Módulo está vacío y acción está llena
            return "Necesitas ser más específico sobre qué deseas $accion para poder ayudarte.";
        } else {
            // Ninguna de las condiciones anteriores se cumple

            // Realizar la búsqueda inicial
        $respuesta = $this->buscarRespuestaEnBaseDeDatos($question, $accion, $modulo);

        if ($respuesta){
         return $respuesta;
 
        } else {
 
        // Realizar seguhnda busqueda con Sinonimos
         $question = $this->buscarConSinonimos($question);
         $accion = $this->buscarAcciones($question);
         $modulo = $this->buscarModulos($question);
         $respuesta = $this->buscarRespuestaEnBaseDeDatos($question, $accion, $modulo);
 
         // Si aún no se encontró respuesta, devolver un mensaje por defecto
         return $respuesta ?: 'Lo siento, no tengo una respuesta para eso.';
        }
       
        }
     
    
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

        $pregunta = $this->obtenerPreguntaConPalabrasClave($pregunta);

        return $pregunta;
    }


    private function obtenerPreguntaConPalabrasClave($pregunta) {
 
    $arrayPregunta = explode(' ', $pregunta);

   
    $nuevaPregunta = '';

    
    foreach ($arrayPregunta as $palabra) {
        
        $palabraMinuscula = strtolower($palabra);
        $palabrasClave = $this->palabrasClave[$palabraMinuscula] ?? $palabraMinuscula;
        $nuevaPregunta .= $palabrasClave . ' ';
    }

    $nuevaPregunta = trim($nuevaPregunta);

    return $nuevaPregunta;
       }


    private function buscarRespuestaEnBaseDeDatos($question, $accion, $modulo)
{
        // Consulta SQL utilizando MATCH...AGAINST para búsqueda de texto completo en 'question'. Esto es mas eficiente que solo usar la consulta LIKE
        // Esto requiere que la columna 'question' tenga un índice FULLTEXT previamente creado.

        $question = "%$question%";  
        $modulo = $modulo ? "%$modulo%" : '';
        $accion = $accion ? "%$accion%" : '';// Formato para búsqueda con LIKE

        $sql = "SELECT answer FROM chatbot_conocimiento WHERE MATCH(question) AGAINST (:question IN NATURAL LANGUAGE MODE)";

        $sql .= (!empty($modulo))  ? " AND MATCH(modulo) AGAINST (:modulo IN NATURAL LANGUAGE MODE)" : '';
        $sql .= (!empty($accion)) ? " AND MATCH(accion) AGAINST (:accion IN NATURAL LANGUAGE MODE)" : '';

        $statement = $this->db->pdo()->prepare($sql);
        $statement->bindParam(':question', $question, PDO::PARAM_STR);
        !empty($modulo) ? $statement->bindParam(':modulo', $modulo,PDO::PARAM_STR) : '';
        !empty($accion) ? $statement->bindParam(':accion', $accion,PDO::PARAM_STR) : '';
        $statement->execute();

        // Obtener el resultado y devolver la respuesta
        $respuesta = $statement->fetch(PDO::FETCH_ASSOC);

        return $respuesta ? $respuesta['answer'] : '';
}

private function buscarAcciones($question)
{
    $arrayPregunta = explode(' ', $question);

  
    $accionesQuestion = '';

   
    foreach ($arrayPregunta as $palabra) {
        $palabraMinuscula = strtolower($palabra);

        if (in_array($palabraMinuscula, $this->acciones)) {
         
            $accionesQuestion .= $palabraMinuscula . ' ';
        }
    }

    $accionesQuestion = trim($accionesQuestion);

    return $accionesQuestion;
}

private function buscarModulos($question)
{

    $arrayPregunta = explode(' ', $question);

    $modulosQuestion = '';

  
    foreach ($arrayPregunta as $palabra) {
        $palabraMinuscula = strtolower($palabra);

        if (in_array($palabraMinuscula, $this->modulos)) {
       
            $modulosQuestion .= $palabraMinuscula . ' ';
        }
    }

    $modulosQuestion = trim($modulosQuestion);
    return $modulosQuestion;
    
}


private function buscarConSinonimos($question){


    $arrayPregunta = explode(' ', $question);

 
    $nuevaPregunta = '';


    foreach ($arrayPregunta as $palabra) {
      
        $palabraMinuscula = strtolower($palabra);

        $sinonimo = $this->sinonimos[$palabraMinuscula] ?? $palabraMinuscula;

        $nuevaPregunta .= $sinonimo . ' ';
    }

    $nuevaPregunta = trim($nuevaPregunta);

    return $nuevaPregunta;


}



}
