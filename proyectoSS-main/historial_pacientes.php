<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Configuración de caracteres y escala para dispositivos móviles -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Estilos CSS -->
    <style>
       /* Estilos para la tabla */
       table {
            width: auto;
            border-collapse: collapse; /* Colapsa los bordes de la tabla */
            margin: 20px auto 30px; /* Margen superior e inferior de 20px y 30px respectivamente, y 'auto' para centrar horizontalmente */
        }

        /* Estilos para las celdas de la tabla */
        th, td {
            padding: 10px; /* Relleno interno de las celdas */
            border: 1px solid #ddd; /* Borde de 1px con color gris */
            text-align: left; /* Alineación del texto a la izquierda */
        }

        /* Estilos para las celdas de encabezado */
        th {
            background-color: #4CAF50; /* Color de fondo verde oscuro */
            color: white; /* Color del texto blanco */
        }

        /* Estilos para las filas pares */
        tr:nth-child(even) {
            background-color: #f2f2f2; /* Color de fondo gris claro */
        }
        /* Estilos para los botones de navegación */
        .btn-primary {
            color: white;
            background-color: #007bff;
            border: none;
            padding: 8px 16px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }

        h1 {
            color: #333;
            text-align: center;
        }

        form {
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
        }

        input[type="text"] {
            padding: 8px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        button[type="submit"] {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        nav {
            margin-top: 20px;
            text-align: center;
        }

        nav a {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }

        nav a:hover {
            background-color: #45a049;
        }

        .center-button {
            text-align: center;
            margin-top: 20px;
        }

    </style>
</head>
<body>
    <h1>Historial de Modificaciones de Pacientes</h1>

    <!-- Campo de entrada para filtrar por nombre de paciente -->
    <form method="GET">
        <label for="nombre_paciente">Buscar por nombre de paciente:</label>
        <input type="text" id="nombre_paciente" name="nombre_paciente" placeholder="Nombre del paciente...">
        <button type="submit">Buscar</button>
    </form>

    <?php
    // Establecer la conexión con la base de datos
    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDeDatos = "enfermeros";
    
    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);
 // Recuperar el nombre del paciente proporcionado por el usuario
 $nombre_paciente = $_GET['nombre_paciente'];

 
 // Consultar el historial de modificaciones para el paciente seleccionado
 $sql = "SELECT * FROM historial_paciente WHERE paciente_nombre = ?";
 $stmt = mysqli_prepare($enlace, $sql);
 mysqli_stmt_bind_param($stmt, "s", $nombre_paciente);
 mysqli_stmt_execute($stmt);
 $result = mysqli_stmt_get_result($stmt);

    // Verificar si se encontraron resultados
    if (mysqli_num_rows($result) > 0) {
        echo "<table>";
        echo "<tr><th>Fecha de atención</th><th>Fecha de modificación</th><th>Edad antigua</th><th>Peso antiguo</th><th>Altura antigua</th><th>Presión arterial antigua</th><th>Temperatura antigua</th><th>Glucosa antigua</th><th>Frecuencia cardíaca antigua</th><th>Síntoma antiguo</th></tr>";
    
        // Mostrar los resultados en la tabla
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["fecha_atencion"] . "</td>";
            echo "<td>" . $row["fecha_modificacion"] . "</td>";
            echo "<td>" . $row["edad_antigua"] . "</td>";
            echo "<td>" . $row["peso_antiguo"] . "</td>";
            echo "<td>" . $row["altura_antigua"] . "</td>";
            echo "<td>" . $row["presion_arterial_antigua"] . "</td>";
            echo "<td>" . $row["temperatura_antigua"] . "</td>";
            echo "<td>" . $row["glucosa_antigua"] . "</td>";
            echo "<td>" . $row["frecuencia_cardiaca_antigua"] . "</td>";
            echo "<td>" . $row["sintoma_antiguo"] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }
    
    mysqli_close($enlace); // Cerrar la conexión
    ?>
  
</body>
</html>
<nav>
    <a href="enfermeros.php" class="btn btn-primary">Registro de Enfermeros</a> |
    <a href="pacientes.php" class="btn btn-primary">Registro de pacientes</a> 
</nav>
<div class="center-button">
    <a href="ver_datos_pac.php" class="btn btn-primary">Ver datos</a>
</div>