<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "instructorzone";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Establecer el conjunto de caracteres del servidor
$conn->set_charset("utf8mb4");

// Obtener los datos del formulario
$nombre = $_POST['nombre'];
$correo = $_POST['correo'];
$contrasena = $_POST['contrasena'];
$rol = $_POST['rol'];

// Obtener el ID del rol seleccionado
$sql = "SELECT IdRol FROM Rol WHERE NombreRol = '$rol'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $rolId = $row['IdRol'];

    // Preparar la consulta SQL para insertar los datos
    $sql = "INSERT INTO Usuarios (NombreUsuario, Correo, Contrasena, Rol) VALUES ('$nombre', '$correo', '$contrasena', '$rolId')";

    if ($conn->query($sql) === TRUE) {
        echo "Registro guardado correctamente";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
} else {
    echo "No se encontró el ID del rol seleccionado";
}

// Cerrar la conexión
$conn->close();
?>
