<?php
if (!DEVELOPER_MODE) {
    redirigir("/AppwebMVC/Home");
}

// Controlador para realizar pruebas

renderView("Index");
?>