<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["sede-registrar.js"];
?>

<h2>Registro de Sede</h2>

<div class="container-fluid">

  <form id="formulario">
    <div class="mb-3">
      <label for="sede" class="form-label fw-bold">Pastor responsable</label>
      <select class="form-select" id="idPastor" name="idPastor">
      </select>
      <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idPastor" role="alert">
        Debe seleccionar un Pastor.
      </div>
    </div>

    <div class="mb-3">
      <label for="nombre" class="form-label fw-bold">Nombre de la sede</label>
      <input type="text" class="form-control" id="nombre" maxlength="30" name="nombre">

      <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_nombre" role="alert">
        Este campo no acepta numeros y no puede estar vacio.
      </div>


    </div>
    <div class="mb-3">
      <label for="direccion" class="form-label fw-bold">Dirección</label>
      <input type="text" class="form-control" id="direccion" maxlength="100" name="direccion">

      <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_direccion" role="alert">
        Este campo no puede estar vacio.
      </div>
    </div>

    <div class="mb-3">
      <label for="estado" class="form-label fw-bold">estado</label>
      <select class="form-select" id="estado" name="estado">
        <option value="" selected>Selecciona un estado</option>
        <option value="ANZ">Anzoátegui</option>
        <option value="APUR">Apure</option>
        <option value="ARA">Aragua</option>
        <option value="BAR">Barinas</option>
        <option value="BOL">Bolívar</option>
        <option value="CAR">Carabobo</option>
        <option value="COJ">Cojedes</option>
        <option value="DELTA">Delta Amacuro</option>
        <option value="FAL">Falcón</option>
        <option value="GUA">Guárico</option>
        <option value="LAR">Lara</option>
        <option value="MER">Mérida</option>
        <option value="MIR">Miranda</option>
        <option value="MON">Monagas</option>
        <option value="ESP">Nueva Esparta</option>
        <option value="POR">Portuguesa</option>
        <option value="SUC">Sucre</option>
        <option value="TÁCH">Táchira</option>
        <option value="TRU">Trujillo</option>
        <option value="VAR">Vargas</option>
        <option value="YAR">Yaracuy</option>
        <option value="ZUL">Zulia</option>
      </select>
      <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_estado" role="alert">
        Debe seleccionar un estado.
      </div>
    </div>
    <div class="d-flex justify-content-end">
      <button type="submit" class="btn btn-primary">Registrar</button>
    </div>
  </form>

</div>