<?php
global $viewStyles;
global $viewScripts;
$viewScripts = ["listar-sede.js"];

/** @var Usuario */
$usuario = $_SESSION['usuario'];
?>

<script>
    const permisos = {
        registrar: <?php echo $usuario->tienePermiso("sedes", "registrar") ? 1 : 0 ?>,
        consultar: <?php echo $usuario->tienePermiso("sedes", "consultar") ? 1 : 0  ?>,
        actualizar: <?php echo $usuario->tienePermiso("sedes", "actualizar") ? 1 : 0  ?>,
        eliminar: <?php echo $usuario->tienePermiso("sedes", "eliminar") ? 1 : 0  ?>
    }

</script>

<div class="page-top d-flex align-items-end justify-content-between mb-2">
<h2><strong>Sedes</strong></h2>
    <div class="d-flex gap-3">
        <div class="buscador">
            <input type="text" id="search" class="form-control" placeholder="Buscar Sede">
        </div>
        <?php if ($usuarioSesion->tienePermiso("sedes", "registrar")): ?>
            <button class="btn btn-accent text-nowrap" id="registrar" data-bs-toggle="modal" data-bs-target="#modal_registrar">
                                <i class="fa-solid fa-plus"></i>
                Nueva Sede
            </button>
        <?php endif ?>
    </div>
</div>


        <div class="table-responsive">
            <table id="sedeDatatables" class="table table-bordered table-rounded table-hover" style="width:100%"> 

                <thead>

                    <tr>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Direccion</th>
                        <th class="text-center" style="width: 90px;">Opciones</th>
                    </tr>
                </thead>
                <!-- AQUI MOSTRARA LA INFORMACION -->
                </tbody>
            </table>
        </div>




<?php if ($usuario->tienePermiso("sedes", "registrar")) : ?>
<!-- MODAL PARA REGISTRAR-->

<div class="modal fade" id="modal_registrar" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registrar Sede</h5>
            </div>
            <div class="modal-body">
                <form id="formulario">
                    <div class="mb-3">
                        <label for="idPastor" class="form-label fw-bold">Pastor responsable</label>
                        <select class="form-select" id="idPastor" name="idPastor">
                        </select>
                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idPastor" role="alert">
                            Debe seleccionar un Pastor.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre" class="form-label fw-bold">Nombre de la sede</label>
                        <input type="text" class="form-control" id="nombre" maxlength="30" name="nombre" aria-describedby="msj_nombre">

                        <div class="invalid-feedback" id="msj_nombre" role="alert">
                            
                        </div>


                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-bold">Dirección</label>
                        <input type="text" class="form-control" id="direccion" maxlength="100" name="direccion" aria-describedby="msj_direccion">

                        <div class="invalid-feedback" id="msj_direccion" role="alert">
                            
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estado" class="form-label fw-bold">estado</label>
                        <select class="form-select" id="estado" name="estado">
                            <option value="x" selected>Selecciona un estado</option>
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
                            <option value="TACH">Táchira</option>
                            <option value="TRU">Trujillo</option>
                            <option value="VAR">Vargas</option>
                            <option value="YAR">Yaracuy</option>
                            <option value="ZUL">Zulia</option>
                        </select>
                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_estado" role="alert">
                            Debe seleccionar un estado.
                        </div>
                    </div>


                    <div class="d-flex justify-content-end gap-1">
                        <button type="button" id="cerrarRegistrar" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Registrar</button>

                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
<?php endif ?>


<!-- MODAL PARA VER TODOS LOS DATOS DE LA SEDE -->
<div class="modal fade" id="modal_verInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Informacion de la sede</h5>
            </div>
            <div class="modal-body">
                <!-- AQUI TE ESTA TODA LA INFORAMCION DE MANERA ESTATICA, ES DECIR, SOLO PARA MOSTRAR INFO -->
                <div class="mb-3">
                    <ul class="list-group">
                        <input type="hidden" id="id_Pastor">
                        <li class="list-group-item "><strong>Codigo:</strong>
                            <p id="inf_codigo"></p>
                        </li>
                        <li class="list-group-item "><strong>Nombre de la Sede:</strong>
                            <p id="inf_nombre"></p>
                        </li>
                        <li class="list-group-item "><strong>Pastor a cargo:</strong>
                            <p id="inf_idPastor"></p>
                        </li>
                        <li class="list-group-item "><strong>estado:</strong>
                            <p id="inf_estado"></p>
                        </li>
                        <li class="list-group-item ">
                            <h6><strong>Dirección:</strong></h6>
                            <p id="inf_direccion"></p>
                        </li>
                    </ul>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
</div>


<?php if ($usuario->tienePermiso("sedes", "actualizar")) : ?>
<!-- MODAL PARA EDITAR TODOS LOS DATOS DE LA SEDE -->

<div class="modal fade" id="modal_editarInfo" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editar datos de la Sede</h5>
            </div>
            <div class="modal-body">
                <form id="formulario2">
                    <div class="mb-3">
                        <input type="hidden" id="idSede">
                        <label for="idPastor2" class="form-label fw-bold">Pastor responsable</label>
                        <select class="form-select" id="idPastor2" name="idPastor2">
                        </select>
                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_idPastor2" role="alert">
                            Debe seleccionar un Pastor.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nombre2" class="form-label fw-bold">Nombre de la sede</label>
                        <input type="text" class="form-control" id="nombre2" maxlength="30" name="nombre2" aria-describedby="msj_nombre2">

                        <div class="invalid-feedback" id="msj_nombre2" role="alert">
                            
                        </div>


                    </div>
                    <div class="mb-3">
                        <label for="direccion2" class="form-label fw-bold">Dirección</label>
                        <input type="text" class="form-control" id="direccion2" maxlength="100" name="direccion2" aria-describedby="msj_direccion2">

                        <div class="invalid-feedback" id="msj_direccion2" role="alert">
                            
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="estado2" class="form-label fw-bold">estado</label>
                        <select class="form-select" id="estado2" name="estado2">
                            <option value="x" selected>Selecciona un estado</option>
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
                        <div class="alert alert-danger d-flex align-items-center mt-3 d-none" id="msj_estado2" role="alert">
                            Debe seleccionar un estado.
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-1">
                        <button type="button" id="cerrarEditar" class="btn btn-secondary">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Actualizar</button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif ?>

</div>