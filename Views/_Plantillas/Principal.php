<?php
global $viewScripts;
global $viewStyles;

$usuario = (isset($_SESSION['usuario'])) ? $_SESSION['usuario'] : null;
/** @var ?Usuario */
$usuario = is_null($usuario) ? null : Usuario::cargar($usuario->id);
$cantNotif = 0;
if (isset($_SESSION['usuario'])) {
    foreach ($usuario->notificaciones as $notif) {
        if (!$notif->getVisto()) {
            $cantNotif++;
        }
    }
}
$chatbotStatus;

$chatbotModulos = ['sedes', 'territorios', 'agenda', 'bitacora'];

foreach ($chatbotModulos as $modulo) {
    // Evaluamos si el módulo actual coincide con la primera parte de la URI
    $chatbotStatus = strtolower($uriParts[0] ?? "") == $modulo || strtolower($uriParts[1] ?? "") == $modulo
        ? true : false;

    if ($chatbotStatus == true) {
        break;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= LOCAL_DIR ?>public/img/logo-32.png" type="image/x-icon">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/datatables/datatables.min.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/choicesjs/choices.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/lib/quill/quill.snow.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/utilities.css">
    <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/site.css">
    <?php if (!empty($viewStyles)): ?>
        <?php foreach ($viewStyles as $css): ?>
            <link rel="stylesheet" href="<?= LOCAL_DIR ?>public/css/<?= $css ?>">
        <?php endforeach ?>
    <?php endif ?>

    <?php if (!empty($title)): ?>
        <title><?= $title ?> - <?= APP_NAME ?></title>
    <?php else: ?>
        <title><?= APP_NAME ?></title>
    <?php endif ?>



</head>

<body>
    <!-- Header -->
    <header class="p-3 bg-dark text-white sticky-top" id="header">
        <div class="container-fluid">
            <div id="header-wrapper" class="d-flex flex-wrap align-items-center justify-content-between">
                <a id="logo" class="d-flex gap-2 align-items-center navbar-brand" href="<?= LOCAL_DIR ?>">
                    <img src="/AppwebMVC/public/img/logo-32.png" width="32" height="32" class="d-inline-block">
                    <span class="fs-4 fw-semibold"><?= APP_NAME ?></span>
                </a>
                <div id="header-buttons" class="text-end">
                    <button id="menu-toggle" type="button" class="btn btn-dark d-none"
                        onclick="sidebar.classList.toggle('show'); this.classList.toggle('active')">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    <div class="text-end">
                        <?php if (isset($_SESSION['usuario'])): ?>
                            <!-- chatbot -->
                            <?php if ($usuario->tieneRol('SuperUsuario') && ($chatbotStatus == true)): ?>


                                <button class="btn btn-dark me-2" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample"
                                    aria-controls="offcanvasExample">
                                    <i class="fa-solid fa-comments"></i>
                                    <span>
                                        Asistente
                                    </span>
                                </button>

                                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample"
                                    aria-labelledby="offcanvasExampleLabel">
                                    <div class="offcanvas-header">
                                        <h5 class="offcanvas-title" id="offcanvasExampleLabel">Asistente "Llamas de Fuego"</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="offcanvas-body">
                                        <div class="chat-container">
                                            <div class="chat-messages">
                                                <div class="chat-message bot">
                                                    <div class="message-content bot">¿Cómo puedo ayudarte?</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="chat-input">
                                        <input type="textarea" id="userInput" placeholder="Escribe tu mensaje" class="bg-white">
                                        <button class="btn btn-secondary" onclick="sendMessage()"><i
                                                class="fa-solid fa-paper-plane"></i></button>
                                    </div>
                                </div>

                            <?php endif ?>
                            <script>

                                function sendMessage() {
                                    const userInput = document.getElementById('userInput').value;

                                    // Crear un nuevo div contenedor para el mensaje del usuario
                                    const userMessageDiv = document.createElement('div');
                                    userMessageDiv.classList.add('chat-message', 'user');

                                    // Crear un div para el contenido del mensaje del usuario
                                    const userMessageContent = document.createElement('div');
                                    userMessageContent.classList.add('message-content', 'user');
                                    userMessageContent.textContent
                                        = userInput;

                                    // Agregar el contenido al contenedor del mensaje
                                    userMessageDiv.appendChild(userMessageContent);

                                    // Crear un nuevo div contenedor para el mensaje del bot
                                    const botMessageDiv = document.createElement('div');
                                    botMessageDiv.classList.add('chat-message', 'bot');

                                    // Crear un div para el contenido del mensaje del bot
                                    const botMessageContent = document.createElement('div');
                                    botMessageContent.classList.add('message-content', 'bot');
                                    botMessageContent.textContent
                                        = "Chat en construcción";

                                    // Agregar el contenido al contenedor del mensaje
                                    botMessageDiv.appendChild(botMessageContent);

                                    // Agregar ambas burbujas al contenedor de mensajes
                                    const chatMessages = document.querySelector('.chat-messages');
                                    chatMessages.appendChild(userMessageDiv);
                                    chatMessages.appendChild(botMessageDiv);

                                    // Limpiar el input
                                    document.getElementById('userInput').value = '';
                                }

                            
                            </script>
                            <style>
                                .chat-container {

                                    width: 100%;
                                    padding: 20px;
                                    overflow-x: hidden;
                                }

                                .chat-messages {
                                    flex-direction: column;
                                }

                                .chat-message {

                                    margin-bottom: 10px;
                                    flex-wrap: wrap;
                                    display: flex;
                                }


                                .chat-message.bot {

                                    justify-content: start;

                                }

                                .chat-message.user {

                                    justify-content: end;

                                }

                                .message-content.bot {


                                    border-radius: 10px;
                                    padding: 10px;
                                    max-width: 80%;


                                    background-color: #f67483;

                                    color: white;
                                }

                                .message-content.user {


                                    /* Color de fondo para mensajes del usuario */
                                    border-radius: 10px;
                                    padding: 10px;
                                    max-width: 80%;


                                    background-color: #96979c;

                                    color: white;
                                }

                                .chat-input {
                                    display: flex;
                                    align-items: center;
                                    padding: 10px;
                                    /* Ajusta el padding según tus preferencias */
                                }

                                .chat-input input {
                                    flex-grow: 2;
                                    /* Hace que el input ocupe todo el espacio disponible */
                                    height: 40px;
                                    /* Ajusta la altura del input */
                                    border-radius: 5px;
                                    /* Redondea ligeramente las esquinas */
                                    padding: 0 10px;
                                    border: none;
                                    /* Quita el borde por defecto */
                                    box-shadow: inset 0px 2px 4px rgba(0, 0, 0, 0.1);
                                    /* Agrega una sombra interna */
                                }

                                .offcanvas-header {
                                 
                                    border-bottom-color: black;

                                }

                                .chat-input button {
                                    width: 40px;
                                    /* Ajusta el ancho del botón */
                                    height: 40px;
                                    border-radius: 50%;
                                    margin-left: 5px;
                                    /* Hace el botón circular */
                                    background-color: #96979c;
                                    /* Color del botón */
                                    border: none;
                                    cursor: pointer;
                                    transition: background-color 0.3s ease;
                                }

                                .chat-input button:hover {
                                    background-color: #f67483;
                                    /* Cambia el color al pasar el mouse */
                                }

                                .chat-input button:focus {
                                    outline: none;
                                }
                            </style>
                            <!-- fin chatbot -->
                            <div class="dropdown-center d-inline-block">
                                <?php
                                $classBtn = $cantNotif > 0
                                    ? "btn btn-accent me-2 dropdown-toggle"
                                    : "btn btn-dark me-2 dropdown-toggle";
                                ?>
                                <button class="<?= $classBtn ?>" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                    aria-expanded="false" id="btn-notif">
                                    <i class="fa-solid fa-bell"></i>
                                    <span id="contadorNotif"><?= $cantNotif ?></span>
                                </button>
                                <div class="dropdown-menu divNotificaciones" id="notificaciones">
                                    <div class="px-3" style="font-size: 14px;">
                                        <h5>Notificaciones</h5>
                                    </div>
                                    <?php if (count($usuario->notificaciones) == 0): ?>
                                        <div class="py-4 px3 text-center">
                                            <h6>No tienes notificaciones nuevas.</h6>
                                        </div>
                                    <?php else: ?>
                                        <?php foreach ($usuario->notificaciones as $notif): ?>
                                            <div class="notificacion px-3 py-2 border-top" <?php if (!$notif->getVisto()): ?>
                                                    onclick="marcarNotificacion(<?= $notif->id ?>, this)" <?php endif ?>>
                                                <div class="d-flex justify-content-between">
                                                    <h6><?= $notif->getTitulo() ?></h6>
                                                    <?php if ($notif->getVisto()): ?>
                                                        <span class="tiempo text-secondary"
                                                            style="font-size: 14px;"><?= $notif->getTiempo() ?></span>
                                                    <?php else: ?>
                                                        <span class="tiempo text-primary fw-bold"
                                                            style="font-size: 14px;"><?= $notif->getTiempo() ?></span>
                                                    <?php endif ?>
                                                </div>
                                                <div>
                                                    <span>
                                                        <?= $notif->getMensaje() ?>
                                                    </span>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    <?php endif ?>
                                </div>
                            </div>
                            <div class="dropdown d-inline-block">
                                <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa-solid fa-user-large me-1"></i>
                                    <span id="username">
                                        <?= $usuario->getNombre() ?>
                                    </span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="<?= LOCAL_DIR ?>Usuarios/MiPerfil">
                                            <i class="fa-solid fa-user fa-fw me-2"></i>
                                            Mi Perfil
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?= LOCAL_DIR ?>Usuarios/CambiarClave">
                                            <i class="fa-solid fa-key fa-fw me-2"></i>
                                            Cambiar clave
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="https://drive.google.com/file/d/1_l92SSDTtnJq6HEciekt7trbr8C_I0pX/view?usp=sharing"
                                            target="_blank">
                                            <i class="fa-solid fa-book fa-fw me-2"></i>
                                            Manual de Usuario
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-danger" href="<?= LOCAL_DIR ?>Login/Logout">
                                            <i class="fa-solid fa-right-from-bracket fa-fw me-2"></i>
                                            Cerrar sesión
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        <?php else: ?>
                            <a href="<?= LOCAL_DIR ?>login" class="btn btn-primary">Iniciar sesión</a>
                        <?php endif ?>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Menu lateral -->
    <?php require_once "Views/_Plantillas/_MenuLateral.php" ?>

    <div id="menu-backdrop" class="position-fixed h-100"></div>

    <!-- Contenido principal -->
    <main id="main">
        <!-- Imprime alertas de exito o error -->
        <div id="alerts-section">
            <?php if (!empty($_SESSION['exitos'])): ?>
                <?php foreach ($_SESSION['exitos'] as $alerta): ?>
                    <div class="alert alert-success alert-dismissible fade show text-center" role="alert">
                        <?= $alerta ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach ?>
                <?php unset($_SESSION['exitos']) ?>
            <?php endif ?>
            <?php if (!empty($_SESSION['errores'])): ?>
                <?php foreach ($_SESSION['errores'] as $alerta): ?>
                    <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">
                        <?= $alerta ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endforeach ?>
                <?php unset($_SESSION['errores']) ?>
            <?php endif ?>
        </div>

        <!-- Imprime la vista -->
        <?= $GLOBALS['view'] ?>
    </main>

    <script src="<?= LOCAL_DIR ?>public/lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/datatables/datatables.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/sweetalert2.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/choicesjs/choices.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/chartJs/chart.umd.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/quill/quill.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/utilities.js"></script>
    <script src="<?= LOCAL_DIR ?>public/js/site.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/fullcalendar/dist/index.global.min.js"></script>
    <script src="<?= LOCAL_DIR ?>public/lib/fullcalendar/packages/core/locales/es.global.js"></script>


    <?php if (!empty($viewScripts)): ?>
        <?php foreach ($viewScripts as $script): ?>
            <script src="<?= LOCAL_DIR ?>public/js/<?= $script ?>"></script>
        <?php endforeach ?>
    <?php endif ?>
</body>

</html>