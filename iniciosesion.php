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

// Obtener los datos del formulario de inicio de sesión
$email = $_POST['email'];
$password = $_POST['password'];

// Consulta SQL para verificar las credenciales del usuario y obtener el rol
$sql = "SELECT * FROM usuarios WHERE Correo = '$email' AND Contraseña = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $rolUsuario = $row['Rol'];

    // Usuario autenticado correctamente
    // Establecer la cookie de sesión
    setcookie('sesion_iniciada', 'true', time() + (86400 * 30), '/'); // Caduca después de 30 días (ajusta el tiempo según tus necesidades)
    setcookie('rol_usuario', $rolUsuario, time() + (86400 * 30), '/'); // Almacena el valor del rol del usuario en una cookie

    // Redireccionar a "index.html"
    header("Location: index.html");
    exit();
} else {
    // Usuario no encontrado o credenciales incorrectas
    echo "Inicio de sesión fallido. Verifica tu correo y contraseña.";
}

// Cerrar la conexión
$conn->close();
?>
