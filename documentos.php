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
echo '<link rel="stylesheet" href="css/index.css">';
echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';

// Función para obtener la lista de documentos
function obtenerDocumentos($conn)
{
    $sql = "SELECT documentos.IdDocumento, usuarios.NombreUsuario, documentos.TituloDocumento, status.NombreStatus, documentos.ContenidoDocumento
            FROM documentos
            INNER JOIN usuarios ON documentos.IdAutor = usuarios.IdUsuario
            INNER JOIN status ON documentos.Status = status.IdStatus";
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
function crearDocumento($conn, $titulo, $autor, $status, $contenido)
{
    $titulo = $conn->real_escape_string($titulo);
    $contenido = $conn->real_escape_string($contenido);
    $autor = intval($autor);
    $status = intval($status);

    $sql = "INSERT INTO documentos (TituloDocumento, IdAutor, Status, ContenidoDocumento) VALUES ('$titulo', $autor, $status, '$contenido')";

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
function actualizarDocumento($conn, $idDocumento, $titulo, $autor, $status, $contenido)
{
    $idDocumento = intval($idDocumento);
    $titulo = $conn->real_escape_string($titulo);
    $contenido = $conn->real_escape_string($contenido);
    $autor = intval($autor);
    $status = intval($status);

    $sql = "UPDATE documentos SET TituloDocumento = '$titulo', IdAutor = $autor, Status = $status, ContenidoDocumento = '$contenido' WHERE IdDocumento = $idDocumento";

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

// Eliminar el documento
$sql = "DELETE FROM documentos WHERE IdDocumento = $idDocumento";
$result = $conn->query($sql);

if ($result === TRUE) {
    return true;
} else {
    return false;
}

}

// Obtener la lista de documentos
$documentos = obtenerDocumentos($conn);

// Procesar los formularios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear
    if (isset($_POST["crear"])) {
        $titulo = $_POST["titulo"];
        $autor = $_POST["autor"];
        $status = $_POST["status"];
        $contenido = $_POST["contenido"];

        if (crearDocumento($conn, $titulo, $autor, $status, $contenido)) {
            // Documento creado exitosamente
            $exitoCrear = "Documento creado exitosamente.";
        } else {
            // Error al crear el documento
            $errorCrear = "Error al crear el documento.";
        }
    }

    // Editar
    if (isset($_POST["editar"])) {
        $idDocumento = $_POST["idDocumento"];

        // Obtener los detalles del documento
        $documento = obtenerDocumento($conn, $idDocumento);

        if ($documento) {
            // Mostrar el formulario de edición con los datos del documento
            ?>
            <h2>Editar Documento</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="idDocumento" value="<?php echo $documento["IdDocumento"]; ?>">
                
                <label for="titulo">Título:</label>
                <input type="text" name="titulo" value="<?php echo $documento["TituloDocumento"]; ?>" required><br>

                <label for="autor">Autor:</label>
                <input type="text" name="autor" value="<?php echo $documento["IdAutor"]; ?>" disabled><br>

                <label for="status">Estado:</label>
<select name="status" required>
    <option value="1" <?php if ($documento["Status"] == 1) echo "selected"; ?>>En espera</option>
    <option value="2" <?php if ($documento["Status"] == 2) echo "selected"; ?>>Aceptado</option>
    <option value="3" <?php if ($documento["Status"] == 3) echo "selected"; ?>>Rechazado</option>
</select><br>

                <label for="contenido">Contenido:</label>
                <textarea name="contenido" required><?php echo $documento["ContenidoDocumento"]; ?></textarea><br>

                <input type="submit" name="actualizar" value="Actualizar">
            </form>
            <?php
        } else {
            // No se encontró el documento, mostrar mensaje de error
            $errorEditar = "Error: No se encontró el documento.";
        }
    }
// Actualizar
if (isset($_POST["actualizar"])) {
    $idDocumento = $_POST["idDocumento"];
    $titulo = $_POST["titulo"];
    $status = $_POST["status"];
    $contenido = $_POST["contenido"];

    // Obtener los detalles del documento
    $documento = obtenerDocumento($conn, $idDocumento);

    if ($documento) {
        // Mantener el valor original del autor
        $autor = $documento["IdAutor"];

        if (actualizarDocumento($conn, $idDocumento, $titulo, $autor, $status, $contenido)) {
            // Documento actualizado exitosamente
            $exitoEditar = "Documento actualizado exitosamente.";
        } else {
            // Error al actualizar el documento
            $errorEditar = "Error al actualizar el documento.";
        }
    } else {
        // No se encontró el documento, mostrar mensaje de error
        $errorEditar = "Error: No se encontró el documento.";
    }
}

// Eliminar
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

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Documentos</title>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/tabladocumentos.css">
</head>
<body>
<h1>Documentos</h1>

<?php if (isset($exitoCrear)) { ?>
    <p style="color: green;"><?php echo $exitoCrear; ?></p>
<?php } ?>

<?php if (isset($errorCrear)) { ?>
    <p style="color: red;"><?php echo $errorCrear; ?></p>
<?php } ?>

<h2>Crear Documento:</h2>
<ul class="menu">
<li><a href="CrearContenido.html">Crear</a></li>
</ul>
<h2>Lista de Documentos</h2>
<table class="documentos-table">

    <tr>
        <th>ID</th>
        <th>Título</th>
        <th>Autor</th>
        <th>Estado</th>
        <th>Contenido</th>
        <th>Acción</th>
    </tr>
    
    <?php foreach ($documentos as $documento) { ?>
    <tr>
        <td><?php echo $documento["IdDocumento"]; ?></td>
        <td><?php echo $documento["TituloDocumento"]; ?></td>
        <td><?php echo $documento["NombreUsuario"]; ?></td>
        <td><?php echo $documento["NombreStatus"]; ?></td>
        <td><a href="<?php echo $documento["ContenidoDocumento"]; ?>" target="_blank"><button>Ver Contenido</button></a></td>
        <td>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="idDocumento" value="<?php echo $documento["IdDocumento"]; ?>">
                <input type="submit" name="editar" value="Editar">
                <input type="submit" name="eliminar" value="Eliminar">
            </form>
        </td>
    </tr>
<?php } ?>
</table>

<?php if (isset($exitoEditar)) { ?>
    <p style="color: green;"><?php echo $exitoEditar; ?></p>
<?php } ?>

<?php if (isset($errorEditar)) { ?>
    <p style="color: red;"><?php echo $errorEditar; ?></p>
<?php } ?>

<?php if (isset($exitoEliminar)) { ?>
    <p style="color: green;"><?php echo $exitoEliminar; ?></p>
<?php } ?>

<?php if (isset($errorEliminar)) { ?>
    <p style="color: red;"><?php echo $errorEliminar; ?></p>
<?php } ?>
</body>