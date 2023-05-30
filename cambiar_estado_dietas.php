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
        echo "El estado del documento se ha actualizado correctamente.";
    } else {
        echo "Error al actualizar el estado del documento: " . $conn->error;
    }
}

// Consulta SQL para obtener los documentos con estado 1 en orden ascendente de ID
$sql = "SELECT * FROM Documentos WHERE Status = 1 ORDER BY IdDocumento ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mostrar el formulario para cambiar el estado de los documentos
    echo '<link rel="stylesheet" href="css/index.css">';
    echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';
    echo '<h2>Cambiar Estado de Documentos</h2>';
    echo '<h3>Atención. Los documentos se actualizan de abajo hacia arriba.</h3>';
    echo '<form method="POST" action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '">';

    while ($row = $result->fetch_assoc()) {
        $documentoId = $row['IdDocumento'];
        $tituloDocumento = $row['TituloDocumento'];

        echo '<label for="status">Estado del Documento: ' . $tituloDocumento . '</label>';
        echo '<select name="status">';
        echo '<option value="2">2</option>';
        echo '<option value="3">3</option>';
        echo '</select>';
        echo '<input type="hidden" name="documentoId" value="' . $documentoId . '">';
        echo '<br><br>';
    }

    echo '<input type="submit" name="submit" value="Actualizar Estado">';
    echo '</form>';
} else {
    echo '<link rel="stylesheet" href="css/index.css">';
    echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';
    echo 'No hay documentos con estado 1.';
}


// Cerrar la conexión
$conn->close();
?>
