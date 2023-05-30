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

// Función para obtener la lista de documentos
function obtenerDocumentos($conn)
{
    $sql = "SELECT * FROM documentos";
    $result = $conn->query($sql);

    $documentos = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $documentos[] = $row;
        }
    }

    return $documentos;
}

// Función para crear un nuevo documento
function crearDocumento($conn, $titulo, $contenido)
{
    $titulo = $conn->real_escape_string($titulo);
    $contenido = $conn->real_escape_string($contenido);

    $sql = "INSERT INTO documentos (TituloDocumento, ContenidoDocumento) VALUES ('$titulo', '$contenido')";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Función para obtener los detalles de un documento
function obtenerDocumento($conn, $idDocumento)
{
    $idDocumento = intval($idDocumento);

    $sql = "SELECT * FROM documentos WHERE IdDocumento = $idDocumento";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return false;
    }
}

// Función para actualizar un documento existente
function actualizarDocumento($conn, $idDocumento, $titulo, $contenido)
{
    $idDocumento = intval($idDocumento);
    $titulo = $conn->real_escape_string($titulo);
    $contenido = $conn->real_escape_string($contenido);

    $sql = "UPDATE documentos SET TituloDocumento = '$titulo', ContenidoDocumento = '$contenido' WHERE IdDocumento = $idDocumento";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Función para eliminar un documento
function eliminarDocumento($conn, $idDocumento)
{
    $idDocumento = intval($idDocumento);

    // Eliminar registros relacionados en la tabla "entrenamientos"
    $sqlEntrenamientos = "DELETE FROM entrenamientos WHERE DocumentoId = $idDocumento";
    $conn->query($sqlEntrenamientos);

    // Eliminar el documento
    $sqlDocumentos = "DELETE FROM documentos WHERE IdDocumento = $idDocumento";

    if ($conn->query($sqlDocumentos) === TRUE) {
        return true;
    } else {
        return false;
    }
}


// Obtener la lista de documentos
$documentos = obtenerDocumentos($conn);
// Filtrar documentos con estado 3
$documentos = array_filter($documentos, function ($documento) {
    return $documento["Status"] != 3;
});
// Procesar los formularios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear.
    if (isset($_POST["crear"])) {
        $titulo = $_POST["titulo"];
        $contenido = $_POST["contenido"];

        if (crearDocumento($conn, $titulo, $contenido)) {
            // Documento creado exitosamente
            $exitoCrear = "Documento creado exitosamente.";
        } else {
            // Error al crear el documento
            $errorCrear = "Error al crear el documento.";
        }
    }
    // Editar.
    if (isset($_POST["editar"])) {
        $idDocumento = $_POST["idDocumento"];
        $titulo = $_POST["titulo"];
        $contenido = $_POST["contenido"];

        if (actualizarDocumento($conn, $idDocumento, $titulo, $contenido)) {
            // Documento actualizado exitosamente
            $exitoEditar = "Documento actualizado exitosamente.";
        } else {
            // Error al actualizar el documento
            $errorEditar = "Error al actualizar el documento.";
        }
    }
    // Eliminar.
    if (isset($_POST["eliminar"])) {
        $idDocumento = $_POST["idDocumento"];

        if (eliminarDocumento($conn, $idDocumento)) {
            // Documento eliminado exitosamente
            $exitoEliminar = "Documento eliminado exitosamente.";
        } else {
            // Error al eliminar el documento
            $errorEliminar = "Error al eliminar el documento.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD de Documentos</title>
    <link rel="stylesheet" href="css/style01.css">
</head>
<body>
<ul class="menu">
        <li><a href="index.html">Inicio</a></li>
        <li><a href="verentrenamientos.html">Ejercicios</a></li>
        <li><a href="verdietas.html">Dietas</a></li>
        <li class="marginLeft button"><a href="Validar Contenido.html" class="specialButton" id="validarContenidoButton">Validar Contenido</a></li>
        <li class="marginLeft button"><a href="CrearContenido.html" class="specialButton" id="crearContenidoButton">Crear Contenido</a></li>
        <li class="button"><a href="cerrarSesion.php" class="specialButton" id="cerrarSesionButton" onclick="cerrarSesion()">Cerrar Sesión</a></li>

    </ul>
    <h1>CRUD de Documentos</h1>

    <!-- Formulario para crear un nuevo documento -->
    <h2>Crear Documento:</h2>
    <li class="marginLeft button"><a href="CrearContenido.html" class="specialButton" id="crearContenidoButton">Crear Contenido</a></li>

    <!-- Lista de documentos -->
    <h2>Lista de Documentos</h2>
    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($exitoEditar)) { echo "<p style='color: green;'>$exitoEditar</p>"; } ?>
    <?php if (isset($errorEditar)) { echo "<p style='color: red;'>$errorEditar</p>"; } ?>
    <?php if (isset($exitoEliminar)) { echo "<p style='color: green;'>$exitoEliminar</p>"; } ?>
    <?php if (isset($errorEliminar)) { echo "<p style='color: red;'>$errorEliminar</p>"; } ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Título</th>
            <th>Contenido</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($documentos as $documento) { ?>
            <tr>
                <td><?php echo $documento["IdDocumento"]; ?></td>
                <td><?php echo $documento["TituloDocumento"]; ?></td>
                <td><?php echo $documento["ContenidoDocumento"]; ?></td>
                <td>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input type="hidden" name="idDocumento" value="<?php echo $documento["IdDocumento"]; ?>">
    <input type="hidden" name="titulo" value="<?php echo $documento["TituloDocumento"]; ?>">
    <input type="hidden" name="contenido" value="<?php echo $documento["ContenidoDocumento"]; ?>">
    <input type="submit" name="editar" value="Editar">
    <input type="submit" name="eliminar" value="Eliminar">
</form>
                </td>
            </tr>
        <?php } ?>
    </table>

    <?php if (isset($errorEditar)) { echo "<p>$errorEditar</p>"; } ?>
    <?php if (isset($errorEliminar)) { echo "<p>$errorEliminar</p>"; } ?>

</body>
</html>
