// Mostrar/ocultar campos adicionales según el tipo de documento seleccionado
var tipoDocumentoSelect = document.getElementById("tipo_documento");
var dietaFields = document.getElementById("dieta_fields");
var entrenamientoFields = document.getElementById("entrenamiento_fields");

tipoDocumentoSelect.addEventListener("change", function() {
    if (this.value === "dieta") {
        dietaFields.style.display = "block";
        entrenamientoFields.style.display = "none";
    } else if (this.value === "entrenamiento") {
        dietaFields.style.display = "none";
        entrenamientoFields.style.display = "block";
    } else {
        dietaFields.style.display = "none";
        entrenamientoFields.style.display = "none";
    }
});