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

$sql = "SELECT Documentos.IdDocumento, Documentos.TituloDocumento, Documentos.ContenidoDocumento, Usuarios.NombreUsuario, sexo.NombreSexo, nivel.NombreNivel FROM Documentos 
        INNER JOIN Entrenamientos ON Documentos.IdDocumento = Entrenamientos.DocumentoId 
        INNER JOIN Usuarios ON Documentos.IdAutor = Usuarios.IdUsuario
        INNER JOIN Sexo ON Entrenamientos.Sexo = Sexo.IdSexo
        INNER JOIN Nivel ON Entrenamientos.Nivel = Nivel.IdNivel
        WHERE Documentos.Status <> 3 AND Documentos.Status <> 1";

// Agregar condiciones de filtro si se seleccionaron valores
if (!empty($sexo)) {
    $sql .= " AND Entrenamientos.Sexo = " . $sexo;
}

if (!empty($nivel)) {
    $sql .= " AND Entrenamientos.Nivel = " . $nivel;
}

// Agregar más condiciones de filtro si es necesario

$result = $conn->query($sql);

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Crear una tabla para mostrar los resultados
    echo '<link rel="stylesheet" href="css/index.css">';
    echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';
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
    echo '<link rel="stylesheet" href="css/index.css">';
    echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';
    echo 'No se encontraron documentos de ejercicios que coincidan con los criterios de filtro.';
}

// Cerrar la conexión
$conn->close();
?>
