</head>
<h1>Reporte de enfermeros registrados</h1>

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
    echo "<table><tr><th>ID</th><th>Nombre del enfermero</th><th>Correo</th><th>Telefono</th>";

    $id = 1; // Inicializamos el ID en 1

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $id . "</td>"; // Mostramos el ID generado
        echo "<td>" . $row["Nombre"] . "</td>";
        echo "<td>" . $row["Correo"] . "</td>";
        echo "<td>" . $row["Telefono"] . "</td>";
       
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

<style>
   table {
    width: auto;
    margin: 20px auto 30px; /* Se establece un margen superior e inferior de 20px y 30px respectivamente, y 'auto' para centrar horizontalmente */
    border-collapse: collapse;
}

th, td {
    padding: 8px;
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

nav {
    text-align: center;
    margin-bottom: 20px;
    margin-top: 30px;
}

nav a {
    margin: 0 10px;
}

button, nav a {
    background-color: #4CAF50;
    color: white;
    padding: 8px 16px;
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

.center-button {
    display: flex;
    justify-content: center;
    margin-top: 20px;
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
