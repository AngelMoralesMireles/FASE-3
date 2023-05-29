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

// Consulta SQL para obtener los entrenamientos con categorización por sexo y nivel
$sql = "SELECT Documentos.IdDocumento, Documentos.TituloDocumento, Documentos.ContenidoDocumento, Usuarios.NombreUsuario, Tipocuerpo.NombreTipoCuerpo, Objetivo.NombreObjetivo FROM Documentos 
        INNER JOIN Dietas ON Documentos.IdDocumento = Dietas.DocumentoId 
        INNER JOIN Usuarios ON Documentos.IdAutor = Usuarios.IdUsuario
        INNER JOIN Tipocuerpo ON Dietas.TipoCuerpo = Tipocuerpo.IdTipoCuerpo
        INNER JOIN Objetivo ON Dietas.Objetivo = Objetivo.IdObjetivo";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Crear una tabla para mostrar los resultados
    echo '<table>';
    echo '<tr><th>Título</th><th>Autor</th><th>Contenido</th><th>Tipo de cuerpo</th><th>Objetivo</th></tr>';

    while ($row = $result->fetch_assoc()) {
        // Mostrar los datos de cada entrenamiento o dieta en una fila de la tabla
        echo '<tr>';
        echo '<td>' . $row['TituloDocumento'] . '</td>';
        echo '<td>' . $row['NombreUsuario'] . '</td>';
        echo '<td>' . $row['ContenidoDocumento'] . '</td>';
        echo '<td>' . $row['NombreTipoCuerpo'] . '</td>';
        echo '<td>' . $row['NombreObjetivo'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo 'No se encontraron entrenamientos ni dietas.';
}

// Cerrar la conexión
$conn->close();
?>
