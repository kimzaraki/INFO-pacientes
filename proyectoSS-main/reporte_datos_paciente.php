</head>
<h1>Reporte de pacientes registrados</h1>
<body>
<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "enfermeros";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

$sql = "SELECT * FROM datos_paciente";
$result = mysqli_query($enlace, $sql);

echo "<table><tr><th>Fecha de atención</th><th>Enfermero</th><th>Paciente</th><th>Edad</th><th>Peso</th><th>Altura</th><th>Presión Arterial</th><th>Temperatura</th><th>Glucosa</th><th>Frecuencia Cardiaca</th><th>Síntoma</th></tr>";

while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr><td>" . $row["fecha_atencion"] . "</td><td>" . $row["nombre_enfermero"] . "</td><td>" . $row["nombre_paciente"] . "</td><td>" . $row["edad"] . "</td><td>" . $row["peso"] . "</td><td>" . $row["altura"] . "</td><td>" . $row["presion_arterial"] . "</td><td>" . $row["temperatura"] . "</td><td>" . $row["glucosa"] . "</td><td>" . $row["frecuencia_cardiaca"] . "</td><td>" . $row["sintoma"] . "</td></tr>";
}

echo "</table>";
?>

<style>
   /* Estilos para la tabla */
table {
    width: auto;
    border-collapse: collapse;
    margin: 20px auto 30px; /* Margen superior de 20px, inferior de 30px y centrado horizontalmente */
}

/* Estilos para las celdas de la tabla */
th, td {
    padding: 10px;
    border: 1px solid #ddd;
    text-align: left;
}

/* Estilos para las celdas de encabezado */
th {
    background-color: #4CAF50;
    color: white;
}

/* Estilos para las filas pares */
tr:nth-child(even) {
    background-color: #f2f2f2;
}

    h1 {
        text-align: center;
        margin-bottom: 20px;
    }

    /* Estilos para el botón y los enlaces */
    button, nav a {
        background-color: #4CAF50;
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        font-size: 16px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    button:hover, nav a:hover {
        background-color: #45a049;
    }

    nav {
        text-align: center;
        margin-bottom: 20px; /* Aumentar el margen inferior si es necesario */
        margin-top: 30px; /* Espacio adicional arriba del menú de navegación */
    }

    nav a {
        margin: 0 10px;
    }

    /* Centrar el botón de imprimir */
    .center-button {
        display: flex;
        justify-content: center;
        margin-top: 20px; /* Ajustar según la necesidad para más espacio */
    }
</style>


<!-- Menú de Navegación -->
<nav>
    <a href="enfermeros.php">Registro de Enfermeros</a> |
    <a href="pacientes.php">Registro de pacientes</a>
</nav>

<div class="center-button">
    <button onclick="imprimirPagina()">Imprimir esta página</button>
</div>

<script>
function imprimirPagina() {
    window.print();
}
</script>

