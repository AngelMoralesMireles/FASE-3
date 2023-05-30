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

// Función para obtener la lista de usuarios
function obtenerUsuarios($conn)
{
    $sql = "SELECT * FROM usuarios";
    $result = $conn->query($sql);

    $usuarios = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $usuarios[] = $row;
        }
    }

    return $usuarios;
}

// Función para crear un nuevo usuario
function crearUsuario($conn, $nombreUsuario, $correo, $contrasena, $rol)
{
    $nombreUsuario = $conn->real_escape_string($nombreUsuario);
    $correo = $conn->real_escape_string($correo);
    $contrasena = $conn->real_escape_string($contrasena);
    $rol = intval($rol);

    $sql = "INSERT INTO usuarios (NombreUsuario, Correo, Contraseña, Rol) VALUES ('$nombreUsuario', '$correo', '$contrasena', $rol)";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Función para obtener los detalles de un usuario
function obtenerUsuario($conn, $idUsuario)
{
    $idUsuario = intval($idUsuario);

    $sql = "SELECT * FROM usuarios WHERE IdUsuario = $idUsuario";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        return $row;
    } else {
        return false;
    }
}

// Función para actualizar un usuario existente
function actualizarUsuario($conn, $idUsuario, $nombreUsuario, $correo, $contrasena, $rol)
{
    $idUsuario = intval($idUsuario);
    $nombreUsuario = $conn->real_escape_string($nombreUsuario);
    $correo = $conn->real_escape_string($correo);
    $contrasena = $conn->real_escape_string($contrasena);
    $rol = intval($rol);

    $sql = "UPDATE usuarios SET NombreUsuario = '$nombreUsuario', Correo = '$correo', Contraseña = '$contrasena', Rol = $rol WHERE IdUsuario = $idUsuario";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Función para eliminar un usuario
function eliminarUsuario($conn, $idUsuario)
{
    $idUsuario = intval($idUsuario);

    $sql = "DELETE FROM usuarios WHERE IdUsuario = $idUsuario";

    if ($conn->query($sql) === TRUE) {
        return true;
    } else {
        return false;
    }
}

// Obtener la lista de usuarios
$usuarios = obtenerUsuarios($conn);

// Procesar los formularios
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Crear.
    if (isset($_POST["crear"])) {
        $nombreUsuario = $_POST["nombreUsuario"];
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];
        $rol = $_POST["rol"];

        if (crearUsuario($conn, $nombreUsuario, $correo, $contrasena, $rol)) {
            // Usuario creado exitosamente
            $exitoCrear = "Usuario creado exitosamente.";
        } else {
            // Error al crear el usuario
            $errorCrear = "Error al crear el usuario.";
        }
    }

    // Editar.
    if (isset($_POST["editar"])) {
        $idUsuario = $_POST["idUsuario"];
        $nombreUsuario = $_POST["nombreUsuario"];
        $correo = $_POST["correo"];
        $contrasena = $_POST["contrasena"];
        $rol = $_POST["rol"];

        if (actualizarUsuario($conn, $idUsuario, $nombreUsuario, $correo, $contrasena, $rol)) {
            // Usuario actualizado exitosamente
            $exitoEditar = "Usuario actualizado exitosamente.";
        } else {
            // Error al actualizar el usuario
            $errorEditar = "Error al actualizar el usuario.";
        }
    }
    // Eliminar.
    if (isset($_POST["eliminar"])) {
        $idUsuario = $_POST["idUsuario"];

        if (eliminarUsuario($conn, $idUsuario)) {
            // Usuario eliminado exitosamente
            $exitoEliminar = "Usuario eliminado exitosamente.";
        } else {
            // Error al eliminar el usuario
            $errorEliminar = "Error al eliminar el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CRUD de Usuarios</title>
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
    <h1>CRUD de Usuarios</h1>

    <!-- Formulario para crear un nuevo usuario -->
    <h2>Crear Usuario</h2>
    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($exitoCrear)) { echo "<p style='color: green;'>$exitoCrear</p>"; } ?>
    <?php if (isset($errorCrear)) { echo "<p style='color: red;'>$errorCrear</p>"; } ?>
    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    
        <label for="nombreUsuario">Nombre de Usuario:</label>
        <input type="text" name="nombreUsuario" required><br>

        <label for="correo">Correo Electrónico:</label>
        <input type="email" name="correo" required><br>

        <label for="contrasena">Contraseña:</label>
        <input type="password" name="contrasena" required><br>

        <label for="rol">Rol:</label>
        <select name="rol">
            <option value="1">Administrador</option>
            <option value="2">Usuario</option>
        </select><br>

        <input type="submit" name="crear" value="Crear">
    </form>

    <?php if (isset($errorCrear)) { echo "<p>$errorCrear</p>"; } ?>

    <!-- Lista de usuarios -->
    <h2>Lista de Usuarios</h2>
    <!-- Mostrar mensaje de éxito o error -->
    <?php if (isset($exitoEditar)) { echo "<p style='color: green;'>$exitoEditar</p>"; } ?>
    <?php if (isset($errorEditar)) { echo "<p style='color: red;'>$errorEditar</p>"; } ?>
    <?php if (isset($exitoEliminar)) { echo "<p style='color: green;'>$exitoEliminar</p>"; } ?>
    <?php if (isset($errorEliminar)) { echo "<p style='color: red;'>$errorEliminar</p>"; } ?>
    <table>
        <tr>
            <th>ID</th>
            <th>Nombre de Usuario</th>
            <th>Correo Electrónico</th>
            <th>Rol</th>
            <th>Acciones</th>
        </tr>
        <?php foreach ($usuarios as $usuario) { ?>
            <tr>
                <td><?php echo $usuario["IdUsuario"]; ?></td>
                <td><?php echo $usuario["NombreUsuario"]; ?></td>
                <td><?php echo $usuario["Correo"]; ?></td>
                <td><?php echo $usuario["Rol"]; ?></td>
                <td>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <input type="hidden" name="idUsuario" value="<?php echo $usuario["IdUsuario"]; ?>">
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
