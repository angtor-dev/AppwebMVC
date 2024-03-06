$(document).ready(function () {

    let cedula = null;
    let respuesta = null;

    document.getElementById('enviarRecovery').addEventListener('click', () => {
        const cedulaRecovery = document.getElementById('cedulaRecovery').value;

        Swal.showLoading()
        setTimeout(() => {
            Swal.close();
            if (cedulaRecovery !== '' && cedulaRecovery !== null) {
                $.ajax({
                    type: "POST",
                    url: '/AppwebMVC/Login/Index',
                    data: {
                        recovery: 'recovery',
                        correoRecovery: cedulaRecovery
                    },
                    success: function (response) {
                        const datos = JSON.parse(response);

                        $('#modalCedulaRecovery').modal('hide');
                        $('#modalPreguntaRecovery').modal('show');

                        document.getElementById('preguntaRecovery').textContent = datos['pregunta'];
                        cedula = datos['cedula'];
                        respuesta = datos['respuesta'];

                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseText) {
                            let jsonResponse = JSON.parse(jqXHR.responseText);

                            if (jsonResponse.msj) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Denegado',
                                    text: jsonResponse.msj,
                                    showConfirmButton: true,
                                })
                            } else {
                                const respuesta = JSON.stringify(jsonResponse, null, 2)
                                Swal.fire({
                                    background: 'red',
                                    color: '#fff',
                                    title: respuesta,
                                    showConfirmButton: true,
                                })
                            }
                        } else {
                            alert('Error desconocido: ' + textStatus);
                        }
                    }
                })
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'La cedula ingresada es invalida',
                    text: 'Verifique bien sus datos antes de ser enviado',
                    showConfirmButton: true,
                })
            }
        }, 2000);
    })


    $('#enviarRecovery').on('click', () => {
        const respuestaRecovery = document.getElementById('respuestaRecovery').value;

        if (cedula !== '' && cedula !== null && respuesta !== '' && respuesta !== null) {
            if (respuestaRecovery === respuesta) {
                $.ajax({
                    type: "POST",
                    url: '/AppwebMVC/Login/Index',
                    data: {
                        sendRecoveryRespuesta: 'sendRecoveryRespuesta',
                        cedulaRecovery: cedula,
                        respuesta: respuesta
                    },
                    success: function (response) {
                        const datos = JSON.parse(response);
    
                        $('#modalPreguntaRecovery').modal('hide');
    
                        Swal.fire({
                            title: datos.msj,
                            showConfirmButton: true,
                        })
    
                        document.getElementById('preguntaRecovery').textContent = '';
                        email = null;
                        respuesta = null;
    
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        if (jqXHR.responseText) {
                            let jsonResponse = JSON.parse(jqXHR.responseText);
    
                            if (jsonResponse.msj) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Denegado',
                                    text: jsonResponse.msj,
                                    showConfirmButton: true,
                                })
                            } else {
                                const respuesta = JSON.stringify(jsonResponse, null, 2)
                                Swal.fire({
                                    background: 'red',
                                    color: '#fff',
                                    title: respuesta,
                                    showConfirmButton: true,
                                })
                            }
                        } else {
                            alert('Error desconocido: ' + textStatus);
                        }
                    }
                })
            }else{
                Swal.fire({
                    icon: 'error',
                    title: 'La respuesta enviada es incorrecta',
                    showConfirmButton: true,
                    timer: 2000,
                })
            }
            
        } else {
            Swal.fire({
                icon: 'error',
                title: 'DENEGADO',
                showConfirmButton: true,
            })
        }
    });
})

