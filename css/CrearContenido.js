function mostrarMenuSubirArchivos(menuId) {
    var menuSubirArchivos = document.getElementById("menuSubirArchivos" + menuId);
    menuSubirArchivos.style.display = "flex";
    
    var button = document.getElementById("button" + menuId);
    button.style.display = "none";
    
    if (menuId === 1) {
        var button2 = document.getElementById("button2");
        button2.style.display = "none";
        document.getElementById("pageTitle").textContent = button.textContent;
    } else if (menuId === 2) {
        var button1 = document.getElementById("button1");
        button1.style.display = "none";
        document.getElementById("pageTitle").textContent = button.textContent;
    }
}

function abrirFormulario(menuId) {
    var menuSubirArchivos = document.getElementById("menuSubirArchivos" + menuId);
    menuSubirArchivos.style.display = "none";
    
    var documentForm = document.getElementById("documentForm" + menuId);
    documentForm.style.display = "block";
}

function guardarDocumento(menuId) {
    var nombreInstructor = document.getElementById("nombreInstructor" + menuId).value;
    var tituloDocumento = document.getElementById("tituloDocumento" + menuId).value;

    var sexo = "";
    var nivel = "";

    if (menuId === 1) {
        sexo = document.getElementById("sexo1").value;
        nivel = document.getElementById("nivel1").value;
    } else if (menuId === 2) {
        var tipoCuerpo = document.getElementById("tipoCuerpo2").value;
        var objetivo = document.getElementById("objetivo2").value;
        console.log("Tipo de cuerpo:", tipoCuerpo);
        console.log("Objetivo:", objetivo);
    }

    // Aquí puedes realizar acciones adicionales con los datos del documento, como enviarlos a un servidor, guardarlos en una base de datos, etc.

    console.log("Documento guardado:");
    console.log("Nombre del instructor:", nombreInstructor);
    console.log("Título del documento:", tituloDocumento);
    console.log("Sexo:", sexo);
    console.log("Nivel:", nivel);
}