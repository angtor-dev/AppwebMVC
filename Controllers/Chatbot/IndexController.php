<?php
require_once "Models/Chatbot.php";
necesitaAutenticacion();

$usuarioSesion = $_SESSION['usuario'];
$chatbot = new Chatbot();

if (isset($_POST['question'])) {

    $question = $_POST['question'];
    $result = $chatbot->getRespuesta($question);
    echo json_encode($result);

    die();
}