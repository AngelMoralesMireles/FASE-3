<?php
// Conexión a la base de datos
$servername = "127.0.0.1:3308";
$username = "root";
$password = "";
$dbname = "instructorzone";

$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}

// Obtener el parámetro "documento" de la URL
if (isset($_GET["documento"])) {
    $idDocumento = $_GET["documento"];

    // Obtener el enlace del documento de la tabla "documentos"
    $sql = "SELECT ContenidoDocumento FROM documentos WHERE IdDocumento = $idDocumento";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $enlaceDocumento = $row["ContenidoDocumento"];

        // Redireccionar y mostrar el contenido utilizando la etiqueta <embed>
        echo '<embed src="' . $enlaceDocumento . '" type="text/html" width="100%" height="600px">';
    } else {
        echo "<p>Error: No se encontró el documento.</p>";
    }
} else {
    echo "<p>Error: No se proporcionó el parámetro 'documento'.</p>";
}

// Cerrar la conexión a la base de datos
$conn->close();
?>
