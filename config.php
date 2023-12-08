<?php
date_default_timezone_set('America/Caracas');
define("APP_NAME", "Llamas de Fuego");
define("DEVELOPER_MODE", true);

// Expresiones regulares
define("REG_NUMERICO", "/^[0-9]+$/");
define("REG_ALFABETICO", "/^\s*[a-zA-ZáÁéÉíÍóÓúÚüÜñÑ., ]+\s*$/");
define("REG_ALFANUMERICO", "/^\s*[0-9a-zA-ZáÁéÉíÍóÓúÚüÜñÑ., ]+\s*$/");
define("REG_CLAVE", "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/");
?>