<?php
$title = "Cambiar clave";
?>

<div class="card" style="max-width: 512px;">
    <div class="card-header">
        <h4 class="card-title mb-0">Cambiar Clave</h4>
    </div>
    <div class="card-body">
        <form action="/AppwebMVC/Usuarios/CambiarClave" method="post" id="form-clave">
            <label class="d-block mb-3">
                <h6>Clave actual</h6>
                <input type="password" class="form-control" name="claveActual" id="claveActual" required>
                <span class="error-text text-danger"></span>
            </label>
            <label class="d-block mb-3">
                <h6>Clave nueva</h6>
                <input type="password" class="form-control" name="claveNueva" id="claveNueva" required>
                <span class="error-text text-danger"></span>
            </label>
            <label class="d-block mb-3">
                <h6>Repetir clave nueva</h6>
                <input type="password" class="form-control" name="confirmacion" id="confirmacion" required>
                <span class="error-text text-danger"></span>
            </label>
            <div class="d-flex justify-content-end gap-2">
                <a href="javascript:history.back()" class="btn btn-secondary">
                    Cancelar
                </a>
                <button class="btn btn-accent" type="submit">
                    Guardar
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('claveNueva').addEventListener('blur', e => {
        const input = e.currentTarget
        const text = input.nextElementSibling
        let value = input.value.trim()

        if (value.length < 6) {
            text.textContent = "Debe poseer al menos 6 caracteres de longitud"
        } else if (!/[a-zA-Z]/.test(value)) {
            text.textContent = "Debe poseer al menos una letra"
        } else if (!/[0-9]/.test(value)) {
            text.textContent = "Debe poseer al menos un numero"
        } else {
            text.textContent = ""
        }
    })

    document.getElementById('confirmacion').addEventListener('blur', e => {
        const claveNueva = document.getElementById('claveNueva')
        const input = e.currentTarget
        const text = input.nextElementSibling
        let value = input.value.trim()

        if (claveNueva.value != input.value) {
            text.textContent = "Las claves no coinciden"
        } else {
            text.textContent = ""
        }
    })
</script>