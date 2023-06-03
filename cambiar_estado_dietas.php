<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Validar Contenido</title>
    <style>
    body {
        background-color: lightgray;
    }

</style>
</head>
<body>
    
</body>
</html>

<?php
$servername = "127.0.0.1:3308";
$username = "root";
$password = "";
$database = "instructorzone";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $database);

// Verificar la conexión
if ($conn->connect_error) {
    die("Error al conectar a la base de datos: " . $conn->connect_error);
}

// Obtener los parámetros del formulario
if (isset($_POST['submit'])) {
    $status = $_POST['status'];
    $documentoId = $_POST['documentoId'];

    // Actualizar el estado del documento en la base de datos
    $updateSql = "UPDATE Documentos SET Status = $status WHERE IdDocumento = $documentoId";
    if ($conn->query($updateSql) === TRUE) {
        echo '<p class="success-message">El estado del documento se ha actualizado correctamente.</p>';
    } else {
        echo '<p class="error-message">Error al actualizar el estado del documento: ' . $conn->error . '</p>';
    }
    
}

// Consulta SQL para obtener los documentos con estado 1 en orden ascendente de ID
$sql = "SELECT * FROM Documentos WHERE Status = 1 ORDER BY IdDocumento ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mostrar el formulario para cambiar el estado de los documentos
    echo '<link rel="stylesheet" href="css/validarcontenido.css">';
    echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';
    echo '<div class="container">';
    echo '<h2>Cambiar Estado de Documentos</h2>';
    echo '<h3>Atención. Los documentos se aceptan o rechazan de abajo hacia arriba.</h3>';
    echo '<form method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';

    while ($row = $result->fetch_assoc()) {
        $documentoId = $row['IdDocumento'];
        $tituloDocumento = $row['TituloDocumento'];
        $contenidoDocumento = $row['ContenidoDocumento'];

        // Consulta SQL para verificar si el IdDocumento está ligado a la tabla Entrenamientos
$sqlEntrenamientos = "SELECT * FROM Entrenamientos WHERE DocumentoId = $documentoId";
$resultEntrenamientos = $conn->query($sqlEntrenamientos);

// Consulta SQL para verificar si el IdDocumento está ligado a la tabla Dietas
$sqlDietas = "SELECT * FROM Dietas WHERE DocumentoId = $documentoId";
$resultDietas = $conn->query($sqlDietas);



echo '<div class="documento">';
if ($resultEntrenamientos->num_rows > 0) {
    echo "Tipo de documento: Entrenamiento";
} elseif ($resultDietas->num_rows > 0) {
    echo "Tipo de documento: Dieta";
} else {
    echo "El IdDocumento no está ligado ni a la tabla Entrenamientos ni a la tabla Dietas.";
}
        echo '<div class="documento">';
        echo '<label for="status">Nombre del Documento: ' . $tituloDocumento . '</label>';
        echo '<p>Contenido del documento: <a href="' . $contenidoDocumento . '">' . $contenidoDocumento . '</a></p>';



        echo '<select name="status">';
        echo '<option value="2">Aceptado</option>';
        echo '<option value="3">Rechazado</option>';

        echo '</select>';
        echo '<input type="hidden" name="documentoId" value="' . $documentoId . '">';
        echo '</div>';
    }

    echo '<input type="submit" name="submit" value="Actualizar Estado">';
    echo '</form>';
    echo '</div>';
} else {
    echo '<link rel="stylesheet" href="css/validarcontenido.css">';
    echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';
    echo '<div class="container">';
    echo 'No hay documentos con estado En espera.';
    echo '</div>';
}

// Cerrar la conexión
$conn->close();
?>
