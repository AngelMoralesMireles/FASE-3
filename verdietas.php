<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dietas</title>
    <style>
  body {
    background-color: #f9f9f9;
    color: #333333;
    font-family: Arial, sans-serif;
  }

  table {
  width: 100%;
  border-collapse: collapse;
  margin-bottom: 20px;
  background-color: #ffffff; /* White background color */
  border: 2px solid black; /* Add this line to set black borders */

}

  th {
    background-color: #e74c3c;
    color: #ffffff;
    padding: 10px;
    text-align: center;
  }

  td {
    border: 1px solid #ebebeb;
    padding: 10px;
  }
  th,
td {
  /* Existing table cell styles */
  text-align: center;
  border: 1px solid black; /* Add this line to set black borders */
}

  tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  .btn-ver-contenido {
  display: inline-block;
  padding: 8px 16px;
  background-color: #e74c3c;
  color: #ffffff;
  text-decoration: none;
  border-radius: 4px;
  border: 1px solid #000000; /* Add this line to set black borders */

}

.btn-ver-contenido:hover {
  background-color: #c0392b;
}

.btn-ver-contenido:active {
  background-color: #a93226;
}
</style>
</head>
<body>
    
</body>
</html>

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
$tipoCuerpo = $_POST['tipoCuerpo'];
$objetivo = $_POST['objetivo'];

$sql = "SELECT Documentos.IdDocumento, Documentos.TituloDocumento, Documentos.ContenidoDocumento, Usuarios.NombreUsuario, tipocuerpo.NombreTipoCuerpo, objetivo.NombreObjetivo FROM Documentos 
        INNER JOIN Dietas ON Documentos.IdDocumento = Dietas.DocumentoId 
        INNER JOIN Usuarios ON Documentos.IdAutor = Usuarios.IdUsuario
        INNER JOIN Tipocuerpo ON Dietas.TipoCuerpo = Tipocuerpo.IdTipoCuerpo
        INNER JOIN Objetivo ON Dietas.Objetivo = Objetivo.IdObjetivo
        WHERE Documentos.Status <> 3 AND Documentos.Status <> 1";

// Agregar condiciones de filtro si se seleccionaron valores
if (!empty($tipoCuerpo)) {
    $sql .= " AND Dietas.TipoCuerpo = " . $tipoCuerpo;
}

if (!empty($objetivo)) {
    $sql .= " AND Dietas.Objetivo = " . $objetivo;
}

// Agregar más condiciones de filtro si es necesario

$result = $conn->query($sql);


if ($result->num_rows > 0) {
    // Crear una tabla para mostrar los resultados
    echo '<link rel="stylesheet" href="css/index.css">';
    echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li> <li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';
    echo '<table style="margin-top: 30px;">';
    echo '<tr><th>Título</th><th>Autor</th><th>Contenido</th><th>Tipo de cuerpo</th><th>Objetivo</th></tr>';

    while ($row = $result->fetch_assoc()) {
        // Mostrar los datos de cada documento de dieta en una fila de la tabla
        echo '<tr>';
        echo '<td>' . $row['TituloDocumento'] . '</td>';
        echo '<td>' . $row['NombreUsuario'] . '</td>';
        echo '<td><a href="' . $row['ContenidoDocumento'] . '" class="btn-ver-contenido">Ver Contenido</a></td>';
        echo '<td>' . $row['NombreTipoCuerpo'] . '</td>';
        echo '<td>' . $row['NombreObjetivo'] . '</td>';
        echo '</tr>';
    }

    echo '</table>';
} else {
    echo '<link rel="stylesheet" href="css/index.css">';
    echo '<ul class="menu"> <li><a  href="index.html">Inicio</a></li><li><a  href="verentrenamientos.html">Ejercicios</a></li> <li><a  href="verdietas.html">Dietas</a></li> </ul>';
    echo 'No se encontraron documentos de dietas que coincidan con los criterios de filtro.';
}

// Cerrar la conexión
$conn->close();
?>
