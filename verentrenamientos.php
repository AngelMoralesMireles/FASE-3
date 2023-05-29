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

// Obtener los parámetros del filtro desde la solicitud POST
$sexo = $_POST['sexo'];
$nivel = $_POST['nivel'];

// Consulta SQL para obtener los documentos de dietas filtrados por tipo de cuerpo y objetivo
$sql = "SELECT Documentos.IdDocumento, Documentos.TituloDocumento, Documentos.ContenidoDocumento, Usuarios.NombreUsuario, sexo.NombreSexo, nivel.NombreNivel FROM Documentos 
        INNER JOIN Entrenamientos ON Documentos.IdDocumento = Entrenamientos.DocumentoId 
        INNER JOIN Usuarios ON Documentos.IdAutor = Usuarios.IdUsuario
        INNER JOIN Sexo ON Entrenamientos.Sexo = Sexo.IdSexo
        INNER JOIN Nivel ON Entrenamientos.Nivel = Nivel.IdNivel";

// Agregar condiciones de filtro si se seleccionaron valores
if (!empty($sexo)) {
    $sql .= " WHERE Entrenamientos.Sexo = " . $sexo;
}

if (!empty($nivel)) {
    if (empty($sexo)) {
        $sql .= " WHERE";
    } else {
        $sql .= " AND";
    }
    $sql .= " Entrenamientos.Nivel = " . $nivel;
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Crear una tabla para mostrar los resultados
    echo '<table>';
    echo '<tr><th>Título</th><th>Autor</th><th>Contenido</th><th>Sexo</th><th>Nivel</th></tr>';

    while ($row = $result->fetch_assoc()) {
        // Mostrar los datos de cada documento de dieta en una fila de la tabla
        echo '<tr>';
        echo '<td>' . $row['TituloDocumento'] . '</td>';
        echo '<td>' . $row['NombreUsuario'] . '</td>';
        echo '<td>' . $row['ContenidoDocumento'] . '</td>';
        echo '<td>' . $row['NombreSexo'] . '</td>';
        echo '<td>' . $row['NombreNivel'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo 'No se encontraron documentos de ejercicios que coincidan con los criterios de filtro.';
}

// Cerrar la conexión
$conn->close();
?>
