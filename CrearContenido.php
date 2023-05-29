<?php
session_start(); // Inicia la sesión (debe colocarse antes de cualquier salida HTML)

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener el ID del usuario autenticado de la sesión
    if (isset($_SESSION['id_usuario'])) {
        $id_autor = $_SESSION['id_usuario'];

        // Obtener los demás datos del formulario
        $tipo_documento = $_POST['tipo_documento'];
        $titulo_documento = $_POST['titulo_documento'];
        $contenido_documento = $_POST['contenido_documento'];

        // Otros datos necesarios
        $status = 1; // Valor predeterminado para el status

        // Conexión a la base de datos
        $servername = "127.0.0.1:3308";
        $username = "root";
        $password = "";
        $database = "instructorzone";

        $conn = new mysqli($servername, $username, $password, $database);

        if ($conn->connect_error) {
            die("Error de conexión: " . $conn->connect_error);
        }

        // Consulta para insertar el documento en la tabla Documentos
        $documento_query = "INSERT INTO Documentos (TituloDocumento, IdAutor, Status, ContenidoDocumento) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($documento_query);
        $stmt->bind_param("ssis", $titulo_documento, $id_autor, $status, $contenido_documento);
        $stmt->execute();

        // Obtener el ID del documento insertado
        $documento_id = $stmt->insert_id;

        // Dependiendo del tipo de documento, realizar la inserción correspondiente
        if ($tipo_documento == "dieta") {
            $tipo_cuerpo = $_POST['tipo_cuerpo'];
            $objetivo = $_POST['objetivo'];

            // Consulta para insertar la dieta en la tabla Dietas
            $dieta_query = "INSERT INTO Dietas (TipoCuerpo, Objetivo, DocumentoId) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($dieta_query);
            $stmt->bind_param("iii", $tipo_cuerpo, $objetivo, $documento_id);
            $stmt->execute();

            // Mostrar mensaje de éxito o error después de la inserción
            if ($stmt->affected_rows > 0) {
                echo "Registro de dieta exitoso";
            } else {
                echo "Error al registrar la dieta";
            }
        } elseif ($tipo_documento == "entrenamiento") {
            $sexo = $_POST['sexo'];
            $nivel = $_POST['nivel'];

            // Consulta para insertar el entrenamiento en la tabla Entrenamientos
            $entrenamiento_query = "INSERT INTO Entrenamientos (Sexo, Nivel, DocumentoId) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($entrenamiento_query);
            $stmt->bind_param("iii", $sexo, $nivel, $documento_id);
            $stmt->execute();

            // Mostrar mensaje de éxito o error después de la inserción
            if ($stmt->affected_rows > 0) {
                echo "Registro de entrenamiento exitoso";
            } else {
                echo "Error al registrar el entrenamiento";
            }
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Usuario no autenticado";
    }
}
?>
