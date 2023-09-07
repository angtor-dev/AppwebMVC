<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["CelulaConsolidacion-registrar.js"]; ?>


<h2>Registro de Celula de Consolidacion</h2>

<div class="container-fluid">

  <form id="formulario" class="row mt-4">

    <div class="row my-2">
      <div class="col-lg-4">
        <label for="nombre" class="form-label fw-bold">Nombre de la Celula</label>
        <input type="text" class="form-control" id="nombre" maxlength="20" name="nombre">

        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_nombre" role="alert">
          Este campo no acepta numeros y no puede estar vacio.
        </div>
      </div>
    </div>

    <div class="row my-2">
      <div class="col-lg-4">
        <label for="idLider" class="form-label fw-bold">Lider Responsable</label>
        <select class="form-select" id="idLider" name="idLider">
          <option selected value="">Selecciona...</option>
        </select>
        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idLider" role="alert">
          Debe seleccionar un Lider.
        </div>
      </div>
      <div class="col-lg-4">
        <label for="CoLider" class="form-label fw-bold">Co Lider Responsable</label>
        <select class="form-select" id="idCoLider" name="idCoLider">
          <option selected value="">Selecciona...</option>
        </select>
        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idCoLider" role="alert">
          Debe seleccionar un Lider.
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-lg-12">
        <label for="idTerritorio" class="form-label fw-bold">Territorio</label>
        <select class="form-select" id="idTerritorio" name="idTerritorio" placeholder="hola">
        <option selected value="">Selecciona...</option>
        </select>
        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idTerritorio" role="alert">
          Debe seleccionar un Territorio.
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-lg-12">
        <button type="submit" class="btn btn-primary">Registrar</button>
      </div>
    </div>
  </form>
</div>