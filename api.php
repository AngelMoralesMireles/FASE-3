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

    // Invertir el valor del rol
    if ($rol == 1) {
        $rol = 2; // Administrador
    } else {
        $rol = 1; // Instructor
    }

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

    // Invertir el valor del rol
    if ($rol == 1) {
        $rol = 2; // Administrador
    } else {
        $rol = 1; // Instructor
    }

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

        // Obtener los detalles del usuario
        $usuario = obtenerUsuario($conn, $idUsuario);

        if ($usuario) {
            // Mostrar el formulario de edición con los datos del usuario
            ?>
            <h2>Editar Usuario</h2>
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <input type="hidden" name="idUsuario" value="<?php echo $usuario["IdUsuario"]; ?>">
                
                <label for="nombreUsuario">Nombre de Usuario:</label>
                <input type="text" name="nombreUsuario" value="<?php echo $usuario["NombreUsuario"]; ?>" required><br>

                <label for="correo">Correo Electrónico:</label>
                <input type="email" name="correo" value="<?php echo $usuario["Correo"]; ?>" required><br>

                <label for="contrasena">Contraseña:</label>
                <input type="password" name="contrasena" required><br>

                <label for="rol">Rol:</label>
                <select name="rol">
                    <option value="1" <?php if ($usuario["Rol"] == 1) { echo "selected"; } ?>>Administrador</option>
                    <option value="2" <?php if ($usuario["Rol"] == 2) { echo "selected"; } ?>>Instructor</option>
                </select><br>

                <input type="submit" name="actualizar" value="Actualizar">
            </form>
            <?php
        } else {
            // No se encontró el usuario, mostrar mensaje de error
            $errorEditar = "Error: No se encontró el usuario.";
        }
    }

    // Actualizar.
    if (isset($_POST["actualizar"])) {
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

// Cerrar la conexión a la base de datos
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Usuarios</title>
    <link rel="stylesheet" href="css/index.css">
</head>
<body>

    <h1>Usuarios</h1>

    <?php if (isset($exitoCrear)) { ?>
        <p style="color: green;"><?php echo $exitoCrear; ?></p>
    <?php } ?>

    <?php if (isset($errorCrear)) { ?>
        <p style="color: red;"><?php echo $errorCrear; ?></p>
    <?php } ?>

    <h2>Crear Usuario</h2>
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
            <option value="2">Instructor</option>
        </select><br>

        <input type="submit" name="crear" value="Crear">
    </form>

    <h2>Lista de Usuarios</h2>
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
            <td>
                <?php
                if ($usuario["Rol"] == 1) {
                    echo "Instructor";
                } elseif ($usuario["Rol"] == 2) {
                    echo "Administrador";
                } else {
                    echo "Desconocido";
                }
                ?>
            </td>
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
</html>