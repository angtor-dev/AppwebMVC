<?php 
global $viewStyles;
global $viewScripts;
$viewScripts = ["territorio-registrar.js"];
?>

<h2>Registro de Territorio</h2>

<div class="container-fluid">

<form id="formulario">


    <div class="mb-3">
        <label for="sede" class="form-label fw-bold">Sede</label>
                    <select class="form-select" id="idSede" name="idSede" >
                    </select>
                    <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idSede" role="alert" >
                                Debe seleccionar una Sede.
                    </div>
    </div>

    <div class="mb-3">
      <label for="nombre" class="form-label fw-bold">Nombre de el Territorio</label>
      <input type="text" class="form-control" id="nombre" maxlength="50" name="nombre" >

      <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_nombre" role="alert" >
              Este campo no acepta numeros y no puede estar vacio.
    </div>


    </div>
    <div class="mb-3">
                    <label for="idLider" class="form-label fw-bold">Lider Responsable</label>
                    <select class="form-select" id="idLider" name="idLider"  placeholder="hola">
                    </select>
                    <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idLider" role="alert" >
                                Debe seleccionar un Lider.
                    </div>
            
   </div>

    
    <div class="mb-3">
      <label for="detalles" class="form-label fw-bold">Detalles</label>
      <input type="text" class="form-control" id="detalles" maxlength="100" name="detalles" >

                <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_detalles" role="alert" >
                  Este campo no puede estar vacio.
                </div>
    </div>
    
    
    <div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
  </form>

</div>

