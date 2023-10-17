<?php
/** @var Clase $clase */
/** @var ?Contenido $contenido */
$title = "Nuevo Contenido";
$estaActualizando = (isset($contenido) && !empty($contenido->id));

$titulo = $estaActualizando ? "Actualizar contenido" : "Registrar contenido nuevo";
?>

<h2 class="fw-bold"><?= $clase->getTitulo() ?> - <?= $clase->grupo->getNombre() ?></h2>
<h5><?= $titulo ?></h5>

<div class="card mt-4">
    <div class="card-body">
        <form action="/AppwebMVC/Clases/Contenidos/<?= $estaActualizando ? "Actualizar" : "Registrar" ?>" method="post" id="form-contenido">
            <input type="hidden" name="idClase" value="<?= $clase->id ?>">
            <?php if (isset($contenido)): ?>
                <input type="hidden" name="id" id="id" value="<?= $contenido->id ?>">
            <?php endif ?>
            <input type="hidden" name="contenido" id="input-contenido">
            <label class="d-flex align-items-center gap-3 pb-3 border-bottom">
                <b>Titulo</b>
                <input type="text" name="titulo" id="titulo" class="form-control flex-grow-1" required minlength="3"
                    value="<?= isset($contenido) ? $contenido->getTitulo() : "" ?>">
            </label>
            <div class="mt-3 w-100">
                <b>Contenido</b>
                <div id="editor"><?php if (isset($contenido)): ?><?= $contenido->getContenido() ?><?php else: ?>
                        <p>Escribe aquí el contenido para esta clase</p>
                    <?php endif ?>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="d-flex justify-content-end gap-2 mt-3">
    <a href="/AppwebMVC/Clases/Contenidos?id=<?= $clase->id ?>" class="btn btn btn-secondary">Cancelar</a>
    <button class="btn btn btn-accent" type="submit" form="form-contenido">Guardar</button>
</div>

<script>
    var toolbarOptions = [
        [{ 'font': [] }, { 'size': [] }],
        [ 'bold', 'italic', 'underline', 'strike' ],
        [{ 'color': [] }, { 'background': [] }],
        [{ 'script': 'super' }, { 'script': 'sub' }],
        [{ 'header': '1' }, { 'header': '2' }, 'blockquote', 'code-block' ],
        [{ 'list': 'ordered' }, { 'list': 'bullet'}, { 'indent': '-1' }, { 'indent': '+1' }],
        [ 'direction', { 'align': [] }],
        [ 'link', 'image', 'video', 'formula' ],
        [ 'clean' ]
    ]

    document.addEventListener('DOMContentLoaded', () => {
        // Inicializa editor quill.js
        editor = new Quill('#editor', {
            theme: 'snow',
            modules: {
                toolbar: toolbarOptions
            }
        })
        document.querySelector('.ql-toolbar').classList.add('bg-body-secondary')
    })

    document.getElementById('form-contenido').addEventListener('submit', e => {
        const inputContenido = document.getElementById('input-contenido')
        inputContenido.value = editor.root.innerHTML
    })
</script>