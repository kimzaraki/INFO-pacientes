<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        table {
    width: auto;
    margin: 20px auto 30px; /* Margen superior e inferior de 20px y 30px respectivamente, y 'auto' para centrar horizontalmente */
    border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #4CAF50;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

    

        tr:nth-child(even) {
            background-color: #f2f2f2;
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

        
        </style>
</head>
<h1>Informacion de enfermeros registrados</h1>

<body>
<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "enfermeros";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

// Consulta para obtener el número total de enfermeros
$sql_count = "SELECT COUNT(*) as total_enfermeros FROM datos_enfermero";
$result_count = mysqli_query($enlace, $sql_count);
$row_count = mysqli_fetch_assoc($result_count);
$total_enfermeros = $row_count['total_enfermeros'];

$sql = "SELECT * FROM datos_enfermero";
$result = mysqli_query($enlace, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table><tr><th>ID</th><th>Nombre del enfermero</th><th>Correo</th><th>Telefono</th><th>Acciones</th>";

    $id = 1; // Inicializamos el ID en 1

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $id . "</td>"; // Mostramos el ID generado
        echo "<td>" . $row["Nombre"] . "</td>";
        echo "<td>" . $row["Correo"] . "</td>";
        echo "<td>" . $row["Telefono"] . "</td>";
        echo "<td><a href='eliminar.php?ID=" . $row["ID"] . "' class='btn btn-danger'>Eliminar</a>";
        echo "<a href='editar_enfe.php?ID=" . $row["ID"] . "' class='btn btn-primary'>Editar</a></td>";
        echo "</tr>";
        $id++; // Incrementamos el ID para el próximo enfermero
    }
    echo "</table>";
} else {
    echo "0 results";
}

// Cerrar la conexión a la base de datos
mysqli_close($enlace);
?>

    <!-- Menú de Navegación -->
    <nav>
        <a href="enfermeros.php" class="btn btn-primary">Registro de Enfermeros</a> |
        <a href="pacientes2.php" class="btn btn-primary">Registro de pacientes</a> 
    </nav>
    <div class="center-button">
        <a href="reporte_datos_enfermero.php" class="btn btn-primary">Reporte de Datos</a>
    </div>

</body>
</html>
