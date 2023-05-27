<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = $_POST["nombre"];
    $correo = $_POST["correo"];
    $contraseña = $_POST["contraseña"];
    $rol = $_POST["rol"];

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
        echo "Registro exitoso";
    } else {
        echo "Error al registrar: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
