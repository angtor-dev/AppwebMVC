<?php
$_layout = "passwordRecovery";
$token = $_GET['token'] ?? '';
?>

<style>
    body {
      background-color: #f2f2f2; /* Gris de fondo */
     
    }

    .card {
      width: 40%;
      border-radius: 30px;
      padding: 15px;
      position: absolute;
      top: 30%;
      left: 50%;
      transform: translate(-50%, -50%);
      
    }

    .btn-secondary { 
      background-color: #868e96;
      border-color: #868e96;
      color: white; 

    }


  </style>

<div class="card mt-5">
    <div class="card-body">
      <h5 class="card-title">Cambiar contraseña</h5>

      <form action="<?= LOCAL_DIR ?>PasswordRecovery" method="post" id="form1">
        <div class="mb-3">
          <label for="nuevaPassword" class="form-label">Nueva contraseña</label>
          <input type="password" class="form-control" name="nuevaPassword" id="nuevaPassword" aria-describedby="msjNuevaPassword" required>
          <div id="msjNuevaPassword" class="invalid-feedback"></div>
        </div>

        <div class="mb-3">
          <label for="repetirPassword" class="form-label">Repetir contraseña</label>
          <input type="password" class="form-control" name="repetirPassword" id="repetirPassword" aria-describedby="msjRepetirPassword" required>
          <div id="msjRepetirPassword" class="invalid-feedback"></div>
          
          <input type="hidden" name="token" id="token">
        </div>
        
         <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-secondary">Cambiar contraseña</button>
    </div>
      </form>
    </div>
  </div>

  <script>
   let token = <?php echo json_encode($token); ?>;
   document.getElementById('token').value = token;
</script>