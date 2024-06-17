<?php
$_layout = "passwordRecovery";
?>


<style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #f4f4f4;
        margin: 0;
        padding: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
    .container {
        text-align: center;
        background-color: #fff;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      
    }
    h1 {
        color: green;
    }
    p {
        color: #666;
    }
    .button {
        display: inline-block;
        padding: 10px 20px;
        margin-top: 20px;
        border-radius: 5px;
        background-color: #666;
        color: #fff;
        text-decoration: none;
    }
</style>
<body>
<div class="container">
    <h1>¡Contraseña Recuperada!</h1>
    <p>Tu cambio de contraseña se ha registrado de manera exitosa.</p>
    <a href="<?= LOCAL_DIR ?>Login" class="button">Ir a el Login de Llamas de Fuego</a>
</div>