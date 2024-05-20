<?php
// Iniciar sesión para mantener errores
session_start();
$errores = [];

$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "enfermeros";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (!$enlace) {
    die("Error: No se pudo conectar." . mysqli_connect_error());
}

$sql_enfermeros = "SELECT Nombre FROM datos_enfermero";
$result_enfermeros = mysqli_query($enlace, $sql_enfermeros);
$enfermeros = mysqli_fetch_all($result_enfermeros, MYSQLI_ASSOC);

$row = [
    "fecha_atencion" => "",
    "nombre_enfermero" => "",
    "nombre_paciente" => "",
    "edad" => "",
    "peso" => "",
    "altura" => "",
    "presion_arterial" => "",
    "temperatura" => "",
    "glucosa" => "",
    "frecuencia_cardiaca" => "",
    "sintoma" => ""
];
function validar_presion_arterial($presion) {
    if (!preg_match("/^\d{2,3}\/\d{2,3}$/", $presion)) {
        return "Formato de presión arterial inválido.";
    }

    list($sistolica, $diastolica) = explode("/", $presion);

    if ($sistolica < 90 || $sistolica > 180) {
        return "Presión sistólica fuera de rango (90-180).";
    }

    if ($diastolica < 60 || $diastolica > 120) {
        return "Presión diastólica fuera de rango (60-120).";
    }

    return null; // Sin errores
}
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['fecha_atencion'])) {
    $fecha_atencion = $_GET['fecha_atencion'];

    $sql = "SELECT * FROM datos_paciente WHERE fecha_atencion=?";
    $stmt = mysqli_prepare($enlace, $sql);
    mysqli_stmt_bind_param($stmt, "s", $fecha_atencion);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result) ?: [];
}
// Verificar si se ha enviado el formulario de actualización
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    // Recoger los valores del formulario
    $fecha_atencion = $_POST['fecha_atencion'];
    $nombre_enfermero = $_POST['nombre_enfermero'];
    $nombre_paciente = $_POST['nombre_paciente'];
    $edad = $_POST['edad'];
    $peso = $_POST['peso'];
    $altura = $_POST['altura'];
    $presion_arterial = $_POST['presion_arterial'];
    $temperatura = $_POST['temperatura'];
    $glucosa = $_POST['glucosa'];
    $frecuencia_cardiaca = $_POST['frecuencia_cardiaca'];
    $sintoma = $_POST['sintoma'];

    // Consulta para actualizar los datos del paciente
    $sql_actualizar = "UPDATE datos_paciente SET nombre_enfermero=?, nombre_paciente=?, edad=?, peso=?, altura=?, presion_arterial=?, temperatura=?, glucosa=?, frecuencia_cardiaca=?, sintoma=? WHERE fecha_atencion=?";
    $stmt_actualizar = mysqli_prepare($enlace, $sql_actualizar);
    mysqli_stmt_bind_param($stmt_actualizar, "sssssssssss", $nombre_enfermero, $nombre_paciente, $edad, $peso, $altura, $presion_arterial, $temperatura, $glucosa, $frecuencia_cardiaca, $sintoma, $fecha_atencion);

    
    // Redireccionar a historial_pacientes
    header("Location: historial_paciente.php");

// Aquí para calcular el IMC con el peso y altura 
function obtener_categoria_imc($imc) {
    if ($imc < 18.5) {
        return "Bajo peso. Por favor, considere consultar a un especialista.";
    } elseif ($imc < 24.9) {
        return "Peso normal. ¡Sigue manteniendo un estilo de vida saludable!";
    } elseif ($imc < 29.9) {
        return "Sobrepeso. Se recomienda hacer ejercicio y seguir una dieta equilibrada.";
    } else {
        return "Obesidad. Por favor, consulte a un especialista para un plan de salud personalizado.";
    }
}

   
    // Validaciones
    if ($edad < 0 || $edad > 120) {
        $errores[] = "Edad fuera de rango (0-120).";
    }

    if ($peso < 10 || $peso > 300) {
        $errores[] = "Peso fuera de rango (1-300 kg).";
    }

    if ($altura < 1.00 || $altura > 2.00) {
        $errores[] = "Altura fuera de rango (1.00-2.00 cm).";
    }

    $error_presion = validar_presion_arterial($presion_arterial);
    if ($error_presion) {
        $errores[] = $error_presion;
    }

    if ($temperatura < 35 || $temperatura > 42) {
        $errores[] = "Temperatura fuera de rango (35-42 grados Celsius).";
    }

    if ($glucosa < 70 || $glucosa > 200) {
        $errores[] = "Glucosa fuera de rango (70-200 mg/dL).";
    }

    if ($frecuencia_cardiaca < 40 || $frecuencia_cardiaca > 180) {
        $errores[] = "Frecuencia cardiaca fuera de rango (40-180 latidos por minuto).";
    }

   // Si hay errores, mostrarlos y detener la ejecución
if (!empty($errores)) {
    $_SESSION['errores'] = $errores;
    header("Location: editar.php?fecha_atencion=" . urlencode($fecha_atencion));
    exit;
}
    // Actualizar datos en la base de datos
    $actualizar = "UPDATE datos_paciente SET nombre_enfermero=?, nombre_paciente=?, edad=?, peso=?, altura=?, presion_arterial=?, temperatura=?, glucosa=?, frecuencia_cardiaca=?, sintoma=? WHERE fecha_atencion=?";
    $stmt = mysqli_prepare($enlace, $actualizar);
    if (!$stmt) {
        echo "Error preparando la consulta: " . mysqli_error($enlace);
    } else {
        mysqli_stmt_bind_param($stmt, 'sssssssssss', $nombre_enfermero, $nombre_paciente,
            $edad, $peso, $altura, $presion_arterial, $temperatura, $glucosa,
            $frecuencia_cardiaca, $sintoma, $fecha_atencion);
        if (mysqli_stmt_execute($stmt)) {
            // Redireccionar a una página de confirmación o refrescar la página actual
            header("Location: ver_datos_pac.php");
        } else {
            echo "Error ejecutando la actualización: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
   
        // Verificar si se ha enviado el formulario de agregar al historial
        if (isset($_POST['actualizar'])) {

            if (isset($_POST['accion']) && $_POST['accion'] === 'agregar_historial') {

        // Recoger los valores del formulario
        $fecha_atencion = $_POST['fecha_atencion'];
        $nombre_paciente = $_POST['nombre_paciente'];
        $nombre_enfermero = $_POST['nombre_enfermero'];
        $edad = $_POST['edad'];
        $peso = $_POST['peso'];
        $altura = $_POST['altura'];
        $presion_arterial = $_POST['presion_arterial'];
        $temperatura = $_POST['temperatura'];
        $glucosa = $_POST['glucosa'];
        $frecuencia_cardiaca = $_POST['frecuencia_cardiaca'];
        $sintoma = $_POST['sintoma'];

        // Consulta para insertar los datos en la tabla historial_paciente
        $insertar_historial = "INSERT INTO historial_paciente (fecha_atencion, paciente_nombre, enfermero_nombre, fecha_modificacion, edad_antigua, peso_antiguo, altura_antigua, presion_arterial_antigua, temperatura_antigua, glucosa_antigua, frecuencia_cardiaca_antigua, sintoma_antiguo) 
        VALUES (?, ?, ?, CURRENT_TIMESTAMP, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insertar_historial = mysqli_prepare($enlace, $insertar_historial);
        mysqli_stmt_bind_param($stmt_insertar_historial, "sssssssssss", $fecha_atencion, $nombre_paciente, $nombre_enfermero, $edad, $peso, $altura, $presion_arterial, $temperatura, $glucosa, $frecuencia_cardiaca, $sintoma);

        // Ejecutar la inserción en historial_paciente
        if (mysqli_stmt_execute($stmt_insertar_historial)) {
            echo "Datos insertados correctamente en el historial.";
        } else {
            echo "Error al insertar datos en el historial: " . mysqli_error($enlace);
        }
    }
} }  }

mysqli_close($enlace);
?>
<?php
// Mostrar errores en la interfaz
if (!empty($_SESSION['errores'])) {
    echo '<div class="errores">';
    foreach ($_SESSION['errores'] as $error) {
        echo '<p>' . htmlspecialchars($error) . '</p>';
    }
    unset($_SESSION['errores']); // Limpiar errores después de mostrarlos
    echo '</div>';
}
?>

<script>
    function confirmarAgregarHistorial() {
        return confirm("¿Deseas agregar estos datos modificados al historial?");
    }
</script>
<form class="principal" action="editar.php" method="post">
<input type="hidden" name="accion" value="insertar">
    <input type="hidden" name="fecha_atencion" value="<?php echo htmlspecialchars($row["fecha_atencion"]); ?>">
    <label for="nombre_enfermero">Nombre del enfermero:</label>
    <select name="nombre_enfermero" id="nombre_enfermero">
        <?php foreach ($enfermeros as $enfermero): ?>
            <option value="<?php echo $enfermero['Nombre']; ?>" <?php if ($enfermero['Nombre'] == $row['nombre_enfermero']) echo 'selected'; ?>><?php echo $enfermero['Nombre']; ?></option>
        <?php endforeach; ?>
    </select>
    <label for="nombre_paciente">Nombre del paciente:</label>
    <input type="text" name="nombre_paciente" id="nombre_paciente" value="<?php echo htmlspecialchars($row["nombre_paciente"]); ?>">
    <label for="edad">Edad:</label>
    <input type="text" name="edad" id="edad" value="<?php echo htmlspecialchars($row["edad"]); ?>" pattern="\d{2,3}" title="La edad debe ser un número 2 o 3 dígitos.">

    <label for="peso">Peso:</label>
    <input type="text" name="peso" id="peso" value="<?php echo htmlspecialchars($row["peso"]); ?>" pattern="\d+(\.\d+)?" title="El peso debe ser un número (opcionalmente decimal) positivo." onchange="checkWeight(this.value)">
    <label for="altura">Altura:</label>
    <input type="text" name="altura" id="altura" value="<?php echo htmlspecialchars($row["altura"]); ?>" pattern="\d{1,3}(\.\d{1,2})?" title="La altura debe ser un número (opcionalmente decimal) positivo.">

    <label for="presion_arterial">Presión arterial:</label>
    <input type="text" name="presion_arterial" id="presion_arterial" value="<?php echo htmlspecialchars($row["presion_arterial"]); ?>" placeholder="Ejemplo: 120/80" pattern="\d{2,3}/\d{2,3}" title="Ingrese la presión arterial en el formato 'sistólica/diastólica'">
    <label for="temperatura">Temperatura:</label>
    <input type="text" name="temperatura" id="temperatura" value="<?php echo htmlspecialchars($row["temperatura"]); ?>">
    <label for="glucosa">Glucosa:</label>
    <input type="text" name="glucosa" id="glucosa" value="<?php echo htmlspecialchars($row["glucosa"]); ?>">
    <label for="frecuencia_cardiaca">Frecuencia cardiaca:</label>
    <input type="text" name="frecuencia_cardiaca" id="frecuencia_cardiaca" value="<?php echo htmlspecialchars($row["frecuencia_cardiaca"]); ?>">
    <label for="sintoma">Síntoma:</label>
    <textarea name="sintoma" id="sintoma" cols="30" rows="10"><?php echo htmlspecialchars($row["sintoma"]); ?></textarea>
    <!-- En el formulario, modifica el botón "Actualizar" -->
<input type="submit" name="actualizar" value="Actualizar" onclick="return confirmarAgregarHistorial();">

<!-- Agrega un campo oculto para indicar la acción de agregar al historial -->
<input type="hidden" name="accion" value="agregar_historial">

<!-- Agrega un nuevo botón "Agregar al Historial" -->
<input type="submit" name="agregar_historial" value="Agregar al Historial">

</form>
<style>
    /* Estilos generales */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
}

.principal {
    width: 500px;
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
input[type="text"],
textarea {
    width: 100%;
    padding: 8px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 3px;
    box-sizing: border-box;
}
form select {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
        background-color: white; /* Fondo blanco para combobox */
    }
/* Estilos para botones */
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