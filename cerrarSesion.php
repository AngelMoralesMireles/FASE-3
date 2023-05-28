<?php
// Eliminar la cookie de sesión
setcookie('sesion_iniciada', '', time() - 3600, '/');

// Redireccionar a "index.html"
header("Location: index.html");
exit();
?>
