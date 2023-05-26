<?php
// Obtener los datos enviados desde el formulario
$nombreInstructor = $_POST['nombreInstructor2'];
$tituloDocumento = $_POST['tituloDocumento2'];
$tipoCuerpo = $_POST['tipoCuerpo2'];
$objetivo = $_POST['objetivo2'];

// Configurar la conexión a la base de datos
$host = '127.0.0.1:3308'; // Cambia esto si tu base de datos está en otro servidor
$username = 'root';
$password = '';
$dbname = 'instructorzone';

// Crear una conexión a la base de datos
$conn = new mysqli($host, $username, $password, $dbname);

// Verificar si la conexión fue exitosa
if ($conn->connect_error) {
    die("Error de conexión a la base de datos: " . $conn->connect_error);
}

// Preparar la consulta SQL para insertar los datos en la tabla correspondiente
$sql = "INSERT INTO tu_tabla (nombreInstructor, tituloDocumento, tipoCuerpo, objetivo)
        VALUES ('$nombreInstructor', '$tituloDocumento', '$tipoCuerpo', '$objetivo')";

// Ejecutar la consulta y verificar si fue exitosa
if ($conn->query($sql) === TRUE) {
    echo "Documento guardado correctamente en la base de datos.";
} else {
    echo "Error al guardar el documento: " . $conn->error;
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
