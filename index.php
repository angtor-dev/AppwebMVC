<?php
require_once "config.php";
require_once "utilities.php";

$relativePath = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], "index.php"));
$requestUri = substr($_SERVER['REQUEST_URI'], strlen($relativePath));
$requestUri = substr($requestUri, 0, strpos($requestUri, '?') === false ? strlen($requestUri) : strpos($requestUri, '?'));
$uriParts = explode('/', $requestUri);
$controllerName = count($uriParts) > 1 ? $uriParts[count($uriParts) - 1] : "Index";
$controllerPath = substr($requestUri, 0, strpos($requestUri, $controllerName)) ?: $requestUri."/";

if (is_file("Controllers/".$controllerPath.$controllerName."Controller.php")) {
    require_once "Controllers/".$controllerPath.$controllerName."Controller.php";
    exit();
} else {
    $controllerPath .= $controllerName."/";
    $controllerName = "Index";
    if (is_file("Controllers/".$controllerPath.$controllerName."Controller.php")) {
        require_once "Controllers/".$controllerPath.$controllerName."Controller.php";
        exit();
    }
}

http_response_code(404);
if (DEVELOPER_MODE) {
    die("<font face=consolas><b>[Error]</b> El controlador <b>".$controllerName."</b> en <b>".$controllerPath."</b> no existe</font>");
}