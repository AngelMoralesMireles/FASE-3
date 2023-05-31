<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];
    $rol = "1"; // Establecer rol como "1" (Instructor)

    $servername = "127.0.0.1:3308";
    $username = "root";
    $password = "";
    $database = "instructorzone";
    $conn = mysqli_connect($servername, $username, $password, $database);

    if (!$conn) {
        die("La conexión falló: " . mysqli_connect_error());
    }

    $sql = "INSERT INTO Usuarios (NombreUsuario, Correo, Contraseña, Rol) VALUES ('$nombre', '$correo', '$contraseña', '$rol')";
    if (mysqli_query($conn, $sql)) {
        echo '<link rel="stylesheet" href="css/index.css">';
        echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li><li><a  href="inicioSesion.html">Iniciar Sesión</a></li> </ul>';            
        echo "Registro exitoso";
    } else {
        echo '<link rel="stylesheet" href="css/index.css">';
        echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li><a  href="inicioSesion.html">Iniciar Sesión</a></li>  </ul>';            
        echo "Error al registrar: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
