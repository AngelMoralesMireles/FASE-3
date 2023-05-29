
// Verificar si el usuario ha iniciado sesión
var sesionIniciada = getCookie('sesion_iniciada');
var rolUsuario = parseInt(getCookie('rol_usuario')); // Obtener el valor del rol del usuario

if (sesionIniciada === 'true') {
    document.getElementById('crearContenidoButton').style.display = 'block';
    document.getElementById('cerrarSesionButton').style.display = 'block';
    document.getElementById('inicioSesionButton').style.display = 'none';

    if (rolUsuario === 2) {
        document.getElementById('validarContenidoButton').style.display = 'block'; // Mostrar el botón "Administrar Documentos" solo para usuarios con rol 2 (administradores)
        document.getElementById('datosInstructoresButton').style.display = 'block'; // Mostrar el botón "Datos instructores" solo para usuarios con rol 2 (administradores)

    } else {
        document.getElementById('validarContenidoButton').style.display = 'none'; // Ocultar el botón "Administrar Documentos" para usuarios no administradores
        document.getElementById('datosInstructoresButton').style.display = 'none'; // Ocultarr el botón "Datos instructores" solo para usuarios no administradores

    }
} else {
    document.getElementById('crearContenidoButton').style.display = 'none';
    document.getElementById('cerrarSesionButton').style.display = 'none';
    document.getElementById('inicioSesionButton').style.display = 'block';
    document.getElementById('validarContenidoButton').style.display = 'none';
    document.getElementById('datosInstructoresButton').style.display = 'none';
}

// Función para obtener el valor de una cookie por su nombre
function getCookie(name) {
    var cookies = document.cookie.split(';');
    for (var i = 0; i < cookies.length; i++) {
        var cookie = cookies[i].trim();
        if (cookie.indexOf(name + '=') === 0) {
            return cookie.substring(name.length + 1);
        }
    }
    return '';
}
