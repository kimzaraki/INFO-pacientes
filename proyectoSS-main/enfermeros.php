<?php
session_start();
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "enfermeros";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (!$enlace) {
    die("Conexion fallida: " . mysqli_connect_error());
}

$mensaje = ""; // Inicializamos la variable de mensaje
$nombre = "";
$correo = "";
$telefono = "";

if (isset($_POST['registro'])) {
    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $telefono = $_POST['telefono'];

    // Verificar que los campos no estén vacíos
    if (empty($nombre) || empty($correo) || empty($telefono)) {
        $mensaje = "Por favor, complete todos los campos.";
    } else if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $mensaje = "Por favor, ingrese un correo electrónico válido.";
    } else if (!preg_match("/^[0-9]{10}$/", $telefono)) {
        $mensaje = "Por favor, ingrese un número de teléfono válido de 10 dígitos.";
    } else {
        // Comprobar si el enfermero ya existe en la base de datos
        $consulta = "SELECT * FROM datos_enfermero WHERE nombre = ? OR correo = ?";
        $stmt = mysqli_prepare($enlace, $consulta);
        mysqli_stmt_bind_param($stmt, "ss", $nombre, $correo);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($resultado) > 0) {
            // El enfermero ya existe
            $mensaje = "El enfermero ya está registrado.";
        } else {
            // El enfermero no existe, insertar nuevo enfermero
            $insertar = "INSERT INTO datos_enfermero (nombre, correo, telefono, fecha_registro) VALUES (?, ?, ?, NOW())";
            $stmt = mysqli_prepare($enlace, $insertar);
            mysqli_stmt_bind_param($stmt, "sss", $nombre, $correo, $telefono);
            mysqli_stmt_execute($stmt);
            $mensaje = "Registro exitoso.";
            // Limpiar los campos después del registro exitoso
            $nombre = "";
            $correo = "";
            $telefono = "";
        }
    }
}

mysqli_close($enlace);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Registro de Enfermeros</title>
    <style>
        /* Estilos CSS aquí */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            flex-direction: column; /* Cambio para acomodar verticalmente */
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        form input[type="text"],
        form input[type="email"],
        form input[type="submit"],
        form input[type="reset"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
            box-sizing: border-box;
        }

        form input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #4cae4c;
        }

        form input[type="reset"] {
            background-color: #d9534f;
            color: white;
            cursor: pointer;
        }

        form input[type="reset"]:hover {
            background-color: #c9302c;
        }
       
        .btn, nav a {
            display: inline-block;
            padding: 8px 12px;
            margin: 5px;
            text-decoration: none;
            color: #fff;
            background-color: #007bff;
            border: 1px solid #007bff;
            border-radius: 5px;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-primary, .btn-report {
            background-color: #007bff;
            border-color: #007bff;
        }

        nav {
            text-align: center;
            margin-top: 10px;
        }

        .center-button {
            text-align: center;
            margin-top: 10px; /* Adjusted margin to provide more space */
        }
        .mensaje {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: red;
            color: white;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.5);
            display: <?php echo empty($mensaje) ? 'none' : 'block'; ?>;
            z-index: 9999;
        }

        .mensaje p {
            margin: 0;
        }

        .mensaje button {
            border: none;
            background-color: transparent;
            color: white;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }
        </style>
    <script>
        function cerrarMensaje() {
            document.getElementById('mensaje').style.display = 'none';
        }
    </script>
</head>
<body>
    <h1>Registro de Enfermeros</h1>
    <form action="#" name="enfermeros" method="post">
        <label for="">Nombre de enfermero: </label>
        <input type="text" name="nombre" placeholder="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
        <label for="">Correo: </label>
        <input type="email" name="correo" placeholder="correo" value="<?php echo htmlspecialchars($correo); ?>">
        <label for="">Telefono: </label>
        <input type="text" name="telefono" placeholder="telefono" value="<?php echo htmlspecialchars($telefono); ?>" onkeypress="return event.charCode >= 48 && event.charCode <= 57">
        <input type= "submit" name="registro">
        <input type="reset">
    </form>
    <div class="mensaje" id="mensaje">
        <p><?php echo $mensaje; ?></p>
        <button onclick="cerrarMensaje()">OK</button>
    </div>

    <!-- Menú de Navegación -->
    <nav>
        <a href="enfermeros.php" >Registro de Enfermeros</a> |
        <a href="pacientes2.php" >Registro de pacientes</a> 
    </nav>
    <div class="center-button">
        <a href="ver_datos_enfe.php" class="btn btn-primary">Ver datos</a>

    </div>

</body>
</html>
