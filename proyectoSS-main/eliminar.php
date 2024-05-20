<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "enfermeros";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (isset($_GET['fecha_atencion'])) {
    $fecha_atencion = $_GET['fecha_atencion'];
    $sql = "DELETE FROM datos_paciente WHERE fecha_atencion = ?";
    $stmt = mysqli_prepare($enlace, $sql);
    mysqli_stmt_bind_param($stmt, "s", $fecha_atencion);
    mysqli_stmt_execute($stmt);

    header("Location: ver_datos.php");
    mysqli_stmt_close($stmt);
} else {
    echo "No se proporcionó una fecha de atención válida.";
}

mysqli_close($enlace);
?>
