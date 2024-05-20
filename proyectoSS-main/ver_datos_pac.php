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

/* Estilos para los botones y enlaces de navegación */
.btn, nav a {
    display: inline-block; /* Mostrar como bloque en línea */
    padding: 8px 12px; /* Relleno interno de los botones */
    margin: 5px; /* Margen exterior de los botones */
    text-decoration: none; /* Quitar subrayado de los enlaces */
    color: #fff; /* Color del texto blanco */
    background-color: #007bff; /* Color de fondo azul */
    border: 1px solid #007bff; /* Borde de 1px azul */
    border-radius: 5px; /* Bordes redondeados */
}

/* Estilos para el botón de eliminar */
.btn-danger {
    background-color: #dc3545; /* Color de fondo rojo */
    border-color: #dc3545; /* Color del borde rojo */
}

/* Estilos para el botón primario y botón de reporte */
.btn-primary, .btn-report {
    background-color: #007bff; /* Color de fondo azul */
    border-color: #007bff; /* Color del borde azul */
}

/* Estilos para la navegación */
nav {
    text-align: center; /* Alineación del texto al centro */
    margin-top: 10px; /* Margen superior */
}

/* Estilos para el centro de los botones */
.center-button {
    text-align: center; /* Alineación del texto al centro */
    margin-top: 10px; /* Margen superior */
}

/* Estilo para el filtro */
.filter-input {
    text-align: left; /* Alineación del filtro a la izquierda */
    margin: 20px 0; /* Margen superior e inferior de 20px */
    display: flex; /* Para alinear icono y campo de búsqueda */
    align-items: center; /* Alineación vertical */
}

/* Estilos para el campo de búsqueda */
.filter-input input {
    width: 200px; /* Ancho del campo */
    padding: 8px; /* Relleno interno */
    border: 1px solid #ccc; /* Borde gris claro */
    border-radius: 4px; /* Bordes redondeados */
    transition: border 0.3s; /* Transición para el borde */
}

.filter-input input:focus {
    border-color: #007bff; /* Color del borde cuando está enfocado */
    outline: none; /* Sin borde adicional de enfoque */
}


    </style>
</head>
<body>
    <h1>Información de pacientes registrados</h1>
    <label for="filter">Buscar por palabra: </label>

    <!-- Campo de entrada para filtrar la tabla -->
    <div class="filter-input">
        <input type="text" id="filter" onkeyup="filterTable()" placeholder="Buscar en la tabla...">
    </div>

    <!-- Creación de la tabla con datos -->
    <?php
    $servidor = "localhost";
    $usuario = "root";
    $clave = "";
    $baseDeDatos = "enfermeros";

    $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

    $sql = "SELECT * FROM datos_paciente";
    $result = mysqli_query($enlace, $sql);

    
    if (mysqli_num_rows($result) > 0) {
        echo "<table id='data-table'>"; // Agrega ID a la tabla
        echo "<tr><th>Fecha de atención <input type='date' onchange='filterByDate(this)' id='filter-date'></th><th>Nombre del enfermero <input type='text' onkeyup='filterByNurse(this)' id='filter-nurse'></th><th>Nombre del paciente</th><th>Edad <input type='number' onkeyup='filterByAge(this)' id='filter-age' style='width: 50px;'></th><th>Peso <select onchange='filterByWeight(this)' id='filter-weight' style='width: 120px;'><option value=''>Seleccione una categoría</option><option value='bajo'>Bajo peso</option><option value='normal'>Peso normal</option><option value='sobrepeso'>Sobrepeso</option><option value='obesidad'>Obesidad</option></select></th><th>Altura</th><th>Presión arterial <select onchange='filterByPressure(this)' id='filter-pressure' style='width: 120px;'><option value=''>Seleccione una categoría</option><option value='hipotension'>Hipotensión</option><option value='normal'>Normal</option><option value='prehipertension'>Prehipertensión</option><option value='hipertension'>Hipertensión</option></select></th><th>Temperatura</th><th>Glucosa <select onchange='filterByGlucose(this)' id='filter-glucose' style='width: 120px;'><option value=''>Seleccione una categoría</option><option value='hipoglucemia'>Hipoglucemia</option><option value='normal'>Normal</option><option value='prediabetes'>Pre-diabetes</option><option value='diabetes'>Diabetes</option></select></th><th>Frecuencia cardíaca</th><th>Síntoma</th><th>Acciones</th></tr>";


        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row["fecha_atencion"] . "</td>";
            echo "<td>" . $row["nombre_enfermero"] . "</td>";
            echo "<td>" . $row["nombre_paciente"] . "</td>";
            echo "<td>" . $row["edad"] . "</td>";
            echo "<td>" . $row["peso"] . "</td>";
            echo "<td>" . $row["altura"] . "</td>";
            echo "<td>" . $row["presion_arterial"] . "</td>";
            echo "<td>" . $row["temperatura"] . "</td>";
            echo "<td>" . $row["glucosa"] . "</td>";
            echo "<td>" . $row["frecuencia_cardiaca"] . "</td>";
            echo "<td>" . $row["sintoma"] . "</td>";
            echo "<td><a href='eliminar.php?fecha_atencion=" . $row["fecha_atencion"] . "' class='btn btn-danger' onclick='return confirm(\"¿Estás seguro de que deseas eliminar este registro?\")'>Eliminar</a>";
            echo "<a href='editar.php?fecha_atencion=" . $row["fecha_atencion"] . "' class='btn'>Editar</a></td>";
            echo "</tr>";

        }
        echo "</table>";
    } else {
        echo "No se encontraron resultados.";
    }

    mysqli_close($enlace); // Cierra la conexión
    ?>

    <!-- Función para filtrar la tabla -->
    <script>
function filterTable() {
    var input = document.getElementById("filter");
    var filter = input.value.toLowerCase(); // Convertir a minúsculas
    var table = document.getElementById("data-table");
    var tr = table.getElementsByTagName("tr"); // Todas las filas

    // Recorre cada fila y verifica si hay coincidencia
    for (var i = 1; i < tr.length; i++) {
        var visible = false; // Bandera para saber si mostrar la fila
        var tds = tr[i].getElementsByTagName("td"); // Todas las celdas en la fila
        for (var j = 0; j < tds.length; j++) {
            if (tds[j].textContent.toLowerCase().indexOf(filter) > -1) {
                visible = true; // Se encontró coincidencia
                break; // No se necesita seguir buscando
            }
        }
        tr[i].style.display = visible ? "" : "none"; // Muestra u oculta la fila
    }
}

function filterByDate(input) {
            var date = input.value;
            var table = document.getElementById("data-table");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var dateCell = tr[i].getElementsByTagName("td")[0];
                if (dateCell) {
                    var dateValue = dateCell.textContent.trim();
                    if (dateValue === date) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        function filterByNurse(input) {
            var nurseName = input.value.toLowerCase();
            var table = document.getElementById("data-table");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var nurseCell = tr[i].getElementsByTagName("td")[1];
                if (nurseCell) {
                    var nurseValue = nurseCell.textContent.trim().toLowerCase();
                    if (nurseValue.indexOf(nurseName) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        function filterByAge(input) {
            var age = parseInt(input.value);
            var table = document.getElementById("data-table");
            var tr = table.getElementsByTagName("tr");

            for (var i = 1; i < tr.length; i++) {
                var ageCell = tr[i].getElementsByTagName("td")[3];
                if (ageCell) {
                    var ageValue = parseInt(ageCell.textContent.trim());
                    if (!isNaN(age) && ageValue === age) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
        
        function filterByWeight(input) {
            var weightCategory = input.value;
            var table = document.getElementById("data-table");
            var tr = table.getElementsByTagName("tr");

            var weightRanges = {
                'bajo': { min: 0, max: 18.5 },
                'normal': { min: 18.5, max: 24.9 },
                'sobrepeso': { min: 25, max: 29.9 },
                'obesidad': { min: 30, max: Infinity }
            };

            for (var i = 1; i < tr.length; i++) {
                var weightCell = tr[i].getElementsByTagName("td")[4];
                var heightCell = tr[i].getElementsByTagName("td")[5];
                if (weightCell && heightCell) {
                    var weightValue = parseFloat(weightCell.textContent.trim());
                    var heightValue = parseFloat(heightCell.textContent.trim());
                    var bmi = weightValue / (heightValue * heightValue);
                    var category = calculateWeightCategory(bmi);
                    if (category === weightCategory) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function calculateWeightCategory(bmi) {
            if (bmi < 18.5) {
                return 'bajo';
            } else if (bmi >= 18.5 && bmi < 25) {
                return 'normal';
            } else if (bmi >= 25 && bmi < 30) {
                return 'sobrepeso';
            } else {
                return 'obesidad';
            }
        }
        
        function filterByPressure(input) {
            var pressureCategory = input.value;
            var table = document.getElementById("data-table");
            var tr = table.getElementsByTagName("tr");

            var pressureRanges = {
                'hipotension': { min: 0, max: 90 },
                'normal': { min: 90, max: 120 },
                'prehipertension': { min: 120, max: 140 },
                'hipertension': { min: 140, max: Infinity }
            };

            for (var i = 1; i < tr.length; i++) {
                var pressureCell = tr[i].getElementsByTagName("td")[6];
                if (pressureCell) {
                    var pressureValue = parseInt(pressureCell.textContent.trim());
                    var category = calculatePressureCategory(pressureValue);
                    if (category === pressureCategory) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function calculatePressureCategory(pressure) {
            if (pressure < 90) {
                return 'hipotension';
            } else if (pressure >= 90 && pressure < 120) {
                return 'normal';
            } else if (pressure >= 120 && pressure < 140) {
                return 'prehipertension';
            } else {
                return 'hipertension';
            }
        }
        function filterByGlucose(input) {
            var glucoseCategory = input.value;
            var table = document.getElementById("data-table");
            var tr = table.getElementsByTagName("tr");

            var glucoseRanges = {
                'hipoglucemia': { min: 0, max: 70 },
                'normal': { min: 70, max: 100 },
                'prediabetes': { min: 100, max: 126 },
                'diabetes': { min: 126, max: Infinity }
            };

            for (var i = 1; i < tr.length; i++) {
                var glucoseCell = tr[i].getElementsByTagName("td")[8];
                if (glucoseCell) {
                    var glucoseValue = parseInt(glucoseCell.textContent.trim());
                    var category = calculateGlucoseCategory(glucoseValue);
                    if (category === glucoseCategory) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        //Seleccionar
        function calculateGlucoseCategory(glucose) {
            if (glucose < 70) {
                return 'hipoglucemia';
            } else if (glucose >= 70 && glucose < 100) {
                return 'normal';
            } else if (glucose >= 100 && glucose < 126) {
                return 'prediabetes';
            } else {
                return 'diabetes';
            }
        }
    </script>

    <!-- Menú de Navegación -->
    <nav>
        <a href="enfermeros.php" class='btn btn-primary'>Registro de Enfermeros</a> |
        <a href="pacientes.php" class='btn btn-primary'>Registro de pacientes</a> |
       <a href='historial_pacientes.php' class='btn'>Ver Historial</a>

    </nav>

    <!-- Botón para el reporte de datos -->
    <div class="center-button">
        <a href="reporte_datos_paciente.php" class='btn btn-primary'>Reporte de Datos</a>
    </div>

</body>
</html>
