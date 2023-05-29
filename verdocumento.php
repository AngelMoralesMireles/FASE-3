<?php
// Realizar la conexión a la base de datos
$servername = "127.0.0.1:3308";
$username = "root";
$password = "";
$database = "instructorzone";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Consulta para obtener todos los documentos
$consulta = "SELECT * FROM Documentos";
$resultado = $conn->query($consulta);

// Generar el HTML con la información de los documentos
$respuesta_html = "";

if ($resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $titulo_documento = $row['TituloDocumento'];
        $contenido_documento = $row['ContenidoDocumento'];

        $respuesta_html .= "<h2>$titulo_documento</h2>";
        $respuesta_html .= "<p>$contenido_documento</p>";
    }
} else {
    $respuesta_html = "No se encontraron documentos en la base de datos.";
}

// Cerrar la conexión a la base de datos
$conn->close();

// Imprimir la respuesta HTML
echo $respuesta_html;
?>
