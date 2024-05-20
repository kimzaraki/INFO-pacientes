<?php

$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "enfermeros";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);
$row = ['ID' => '', 'Nombre' => '', 'Correo' => '', 'Telefono' => '']; // Inicializar con valores predeterminados vacíos

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['ID'])) {
    $id = $_GET['ID'];

    $sql = "SELECT * FROM datos_enfermero WHERE ID=?";
    $stmt = mysqli_prepare($enlace, $sql);
    mysqli_stmt_bind_param($stmt, "s", $id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $id = $_POST['ID'];
    $nombre = $_POST['Nombre'];
    $correo = $_POST['Correo'];
    $telefono = $_POST['Telefono'];
    $row = ['ID' => $id, 'Nombre' => $nombre, 'Correo' => $correo, 'Telefono' => $telefono];

   
    // Validación del correo electrónico
    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo "Error: El correo electrónico no es válido.";
    } elseif (!preg_match("/^\d{10}$/", $telefono)) {
        echo "Error: El número de teléfono debe tener 10 dígitos.";
    }else {
    $actualizar = "UPDATE datos_enfermero SET Nombre=?, Correo=?, Telefono=? WHERE ID=?";
    $stmt = mysqli_prepare($enlace, $actualizar);
    mysqli_stmt_bind_param($stmt, 'sssi', $nombre, $correo, $telefono, $id);
    mysqli_stmt_execute($stmt);

    $num_rows = mysqli_stmt_affected_rows($stmt);

    if ($num_rows > 0) {
        header("Location: ver_datos_enfe.php");
    } else {
        echo "Error: No se pudo actualizar el registro.";
    }

    mysqli_stmt_close($stmt);
} }

mysqli_close($enlace);
?>

<form class="principal" action="editar_enfe.php" method="post">
    <input type="hidden" name="ID" value="<?php echo htmlspecialchars($row['ID']); ?>">
    <label for="nombre_enfermero">Nombre del enfermero:</label>
    <input type="text" name="Nombre" id="Nombre" value="<?php echo htmlspecialchars($row['Nombre']); ?>">
    <label for="nombre_paciente">Correo:</label>
    <input type="text" name="Correo" id="Correo" value="<?php echo htmlspecialchars($row['Correo']); ?>">
    <label for="edad">Telefono:</label>
    <input type="text" name="Telefono" id="Telefono" value="<?php echo htmlspecialchars($row['Telefono']); ?>">
    <input type="submit" name="actualizar" value="Actualizar">
</form>

<style>
    /* Estilos generales */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.principal {
    width: 400px;
    margin: 50px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 5px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

/* Estilos para etiquetas */
label {
    display: block;
    margin-bottom: 5px;
    color: #333;
}

/* Estilos para inputs */
input[type="text"] {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}

input[type="submit"] {
    background-color: #4caf50;
    color: white;
    padding: 10px 20px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    font-size: 16px;
}

input[type="submit"]:hover {
    background-color: #45a049;
}

/* Estilos para mensajes de error */
.error {
    color: #ff0000;
    margin-bottom: 10px;
}
</style>
