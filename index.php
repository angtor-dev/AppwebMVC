<?php
require_once "config.php";

$relativePath = substr($_SERVER['PHP_SELF'], 0, strpos($_SERVER['PHP_SELF'], "index.php"));
$requestUri = substr($_SERVER['REQUEST_URI'], strlen($relativePath));
$requestUri = substr($requestUri, 0, strpos($requestUri, '?') === false ? strlen($requestUri) : strpos($requestUri, '?'));
$uriParams = explode('/', $requestUri);

$controller = !empty($uriParams[0]) ? $uriParams[0] : "Home";
$action = !empty($uriParams[1]) ? $uriParams[1] : "Index";

if (is_file("Controllers/".$controller."Controller.php")) {
    require_once "Controllers/".$controller."Controller.php";
    $controllerClassName = $controller."Controller";
    $controllerInstance = new $controllerClassName();

    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action();
    } else {
        echo "[Error] La accion <b>".$action."</b> no existe en el controllador "
            ."<b>".$controller."</b>";
        exit();
    }
} else {
    echo "[Error] El controlador <b>".$controller."</b> no existe";
    exit();
}
?>