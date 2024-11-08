async function sendMessage() {
    const userInput = document.getElementById('userInput').value;

    // Crear un nuevo div contenedor para el mensaje del usuario
    const userMessageDiv = document.createElement('div');
    userMessageDiv.classList.add('chat-message', 'user');

    // Crear un div para el contenido del mensaje del usuario
    const userMessageContent = document.createElement('div');
    userMessageContent.classList.add('message-content', 'user');
    userMessageContent.textContent
        = userInput;

    // Agregar el contenido al contenedor del mensaje
    userMessageDiv.appendChild(userMessageContent);

    // Crear un nuevo div contenedor para el mensaje del bot
    const botMessageDiv = document.createElement('div');
    botMessageDiv.classList.add('chat-message', 'bot');

    // Crear un div para el contenido del mensaje del bot
    const botMessageContent = document.createElement('div');
    botMessageContent.classList.add('message-content', 'bot');
    botMessageContent.textContent
        = "Chat en construcción";

    // Agregar el contenido al contenedor del mensaje
    botMessageDiv.appendChild(botMessageContent);

    // Agregar ambas burbujas al contenedor de mensajes
    const chatMessages = document.querySelector('.chat-messages');
    chatMessages.appendChild(userMessageDiv);
    chatMessages.appendChild(botMessageDiv);

    // Limpiar el input
    document.getElementById('userInput').value = '';

    $.ajax({
        type: "POST",
        url: "/AppwebMVC/Chatbot/Index",
        data: {
            question: userInput,
        },
        success: function (response) {
            console.log(response);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            if (jqXHR.responseText) {
                let jsonResponse = JSON.parse(jqXHR.responseText);

                if (jsonResponse.msj) {
                    Swal.fire({
                        icon: 'error',
                        title: 'DENEGADO',
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
}