<?php
$servidor = "localhost";
$usuario = "root";
$clave = "";
$baseDeDatos = "enfermeros";

$enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

if (!$enlace) {
    echo "Error: No se pudo conectar a MySQL." . PHP_EOL;
    echo "errno de depuración: " . mysqli_connect_errno() . PHP_EOL;
    echo "error de depuración: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
// Consulta para obtener todos los enfermeros
$query_enfermeros = "SELECT nombre FROM datos_enfermero";
$resultado_enfermeros = mysqli_query($enlace, $query_enfermeros);
$enfermeros = [];
if ($resultado_enfermeros) {
    while ($fila = mysqli_fetch_assoc($resultado_enfermeros)) {
        $enfermeros[] = $fila['nombre']; // Almacenar los nombres en un array
    }
}
mysqli_free_result($resultado_enfermeros);
if (isset($_POST['registro'])) {
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

    // Verificar si el paciente ya está registrado
    $query_paciente = "SELECT * FROM datos_paciente WHERE nombre_paciente = ?";
    $stmt_paciente = mysqli_prepare($enlace, $query_paciente);
    mysqli_stmt_bind_param($stmt_paciente, 's', $nombre_paciente);
    mysqli_stmt_execute($stmt_paciente);
    $result_paciente = mysqli_stmt_get_result($stmt_paciente);

    if (mysqli_num_rows($result_paciente) > 0) {
        // Obtener datos antiguos del paciente
        $fila_paciente = mysqli_fetch_assoc($result_paciente);
        $edad_antigua = $fila_paciente['edad'];
        $peso_antiguo = $fila_paciente['peso'];
        $altura_antigua = $fila_paciente['altura'];
        $presion_arterial_antigua = $fila_paciente['presion_arterial'];
        $temperatura_antigua = $fila_paciente['temperatura'];
        $glucosa_antigua = $fila_paciente['glucosa'];
        $frecuencia_cardiaca_antigua = $fila_paciente['frecuencia_cardiaca'];
        $sintoma_antiguo = $fila_paciente['sintoma'];

        // Insertar datos antiguos en la tabla historial_paciente
        $insertar_historial = "INSERT INTO historial_paciente (fecha_atencion, paciente_nombre, edad_antigua, peso_antiguo, altura_antigua, presion_arterial_antigua, temperatura_antigua, glucosa_antigua, frecuencia_cardiaca_antigua, sintoma_antiguo) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insertar_historial = mysqli_prepare($enlace, $insertar_historial);
        mysqli_stmt_bind_param($stmt_insertar_historial, 'ssisssssss', $fecha_atencion, $nombre_paciente, $edad_antigua, $peso_antiguo, $altura_antigua, $presion_arterial_antigua, $temperatura_antigua, $glucosa_antigua, $frecuencia_cardiaca_antigua, $sintoma_antiguo);
        mysqli_stmt_execute($stmt_insertar_historial);
        
        echo "Error: El paciente ya está registrado.";
    } else {
        // Validar si el enfermero existe
        $query_enfermero = "SELECT * FROM datos_enfermero WHERE nombre = ?";
        $stmt_enfermero = mysqli_prepare($enlace, $query_enfermero);
        mysqli_stmt_bind_param($stmt_enfermero, 's', $nombre_enfermero);
        mysqli_stmt_execute($stmt_enfermero);
        $result_enfermero = mysqli_stmt_get_result($stmt_enfermero);

        if (mysqli_num_rows($result_enfermero) > 0) {
            // El enfermero existe, insertar datos del paciente
            $insertar = "INSERT INTO datos_paciente (fecha_atencion, nombre_enfermero, nombre_paciente, edad, peso, altura, presion_arterial, temperatura, glucosa, frecuencia_cardiaca, sintoma) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt_insertar = mysqli_prepare($enlace, $insertar);
            mysqli_stmt_bind_param($stmt_insertar, 'sssisssssss', $fecha_atencion, $nombre_enfermero, $nombre_paciente, $edad, $peso, $altura, $presion_arterial, $temperatura, $glucosa, $frecuencia_cardiaca, $sintoma);
            mysqli_stmt_execute($stmt_insertar);
            echo "Registro exitoso.";
        } else {
            echo "Error: El enfermero no está registrado en la base de datos.";
        }
    }
    //mysqli_stmt_close($stmt_paciente);
    //mysqli_stmt_close($stmt_enfermero);
    //mysqli_stmt_close($stmt_insertar);
    //mysqli_stmt_close($stmt_insertar_historial);
}
mysqli_close($enlace);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/stilo.css">
    <title>Info de pacientes</title>
    <!-- hacer un CRUD de pacientes y enfermero que atiende, validar, y hacer 
    filtros de info de pacientes y hacer reportes por asi decirlo acciony por el momento seria todo hasta el momento-->
</head>
<body>
<div class="container">
  <div class="logo-container">
    <img class="logo" src="img/logoservicio.png" alt="Logo Servicio">
  </div>
  <h1>Gestor de información de pacientes de comunidad</h1>
  <div class="logo-container">
    <img class="logo2" src="img/logouas.jpg" alt="Logo UAS">
  </div>
</div>


    <style>
            /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 10px; /* Añadido padding para evitar cortes */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start; /* Alineación vertical desde el inicio */
        }

        .header {
            text-align: center;
            width: 100%; /* Asegura que el header ocupa toda la anchura */
        }
      
        form {
            width: 100%;
            max-width: 600px; /* Tamaño máximo del formulario */
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px; /* Espacio después del formulario */
        }

        nav, .center-button {
            margin: 10px 0; /* Espaciado uniforme para la navegación */
            text-align: center;
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

        /* Estilos para los campos de entrada y botones */
        form input[type="submit"],
        form input[type="reset"] 
        {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ddd;
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
    /* Estilo para todos los inputs de texto y otros campos relacionados */
    form input[type="text"],
    form input[type="number"],
    form textarea {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
        background-color: white; /* Fondo blanco */
    }

    /* Estilo adicional para dar indicación visual al enfoque */
    form input[type="text"]:focus,
    form input[type="number"]:focus,
    form textarea:focus {
        border: 1px solid #999;
        outline: none;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1); /* Pequeña sombra para indicar el foco */
    }

                .container {
            display: flex;
            justify-content: space-between; /* Alinear los elementos a los extremos */
            align-items: center;
            width: 100%; /* Ajustar el ancho al 100% del contenedor */
            margin-bottom: 20px; /* Espacio adicional debajo del contenedor */
        }

        .logo-container {
            flex: 1; /* Permitir que el contenedor de la imagen crezca y ocupe espacio */
        }

        .logo {
            max-width: 150px; /* Ajustar el tamaño máximo de la imagen */
        }

        .logo2 {
            max-width: 150px; /* Ajustar el tamaño máximo de la imagen */
        }

        h1 {
            margin: 20px 0; /* Espacio superior e inferior para el título */
            text-align: center; /* Centrar horizontalmente el título */
        }
        .error {
            color: red;
            font-size: 0.9em;
        }

      
    </style>
    <form class="principal" action="pacientes.php" method="post">
        <label for="">Fecha e informes de atencion: </label>
        <input type="date" name="fecha_atencion">
        <label for="">Nombre de enfermero que atendio: </label>
        <select name="nombre_enfermero">
            <?php foreach ($enfermeros as $nombre_enfermero) {
                echo "<option value=\"$nombre_enfermero\">$nombre_enfermero</option>";
            } ?>
        </select>
        <label for="">Nombre de paciente: </label>
        <input type="text" name="nombre_paciente" placeholder="Nombre del paciente">
        <form id="patientForm">
        <div>
            <label for="edad">Edad: </label>
            <input type="number" id="edad" name="edad" placeholder="Edad del paciente">
            <span id="edadError" class="error"></span>
        </div>

        <div>
            <label for="peso">Peso: </label>
            <input type="number" id="peso" name="peso" placeholder="Peso en kg">
            <span id="pesoError" class="error"></span>
        </div>

        <div>
        <label for="altura">Estatura (en metros): </label>
        <input type="number" id="altura" name="altura" placeholder="Ejemplo: 1.75" step="0.01" min="0.1">
        <span id="alturaError" class="error"></span>
    </div>
    <div>
        <!-- Botón para Calcular IMC -->
        <button type="button" id="calcular_imc">Calcular IMC</button>
    </div>

        <div>
            <label for="presion_arterial">Presión arterial: </label>
            <input type="text" id="presion_arterial" name="presion_arterial" placeholder="Presión arterial (120/80)">
            <span id="presionError" class="error"></span>
        </div>

        <label para="temperatura">Temperatura (en °C): </label>
        <input type="number" id="temperatura" name="temperatura" placeholder="Temperatura" step="0.1" min="0.1">
        <span id="temperaturaError" class="error"></span>

        <div>
            <label for="glucosa">Glucosa: </label>
            <input type="number" id="glucosa" name="glucosa" placeholder="Glucosa en mg/dl">
            <span id="glucosaError" class="error"></span>
        </div>
        <label for="frecuencia_cardiaca">Frecuencia cardiaca (en bpm): </label>
        <input type="number" id="frecuencia_cardiaca" name="frecuencia_cardiaca" placeholder="Frecuencia Cardiaca" step="0.1" min="0.1">
        <span id="frecuenciaError" class="error"></span>

        <div>
            <label for="sintoma">Síntomas presentados: </label>
            <textarea id="sintoma" name="sintoma" placeholder="Describe los síntomas"></textarea>
        </div>
        
        <input type= "submit" name="registro">
        <input type="reset">
        </a>
    </form>
        <!-- Validaciones para los campos que se van agregar del paciente -->
            <script>
                const form = document.getElementById("patientForm");

                form.addEventListener("submit", function(event) {
                    let isValid = true;

                    // Borrar mensajes de error
                    const errorSpans = document.querySelectorAll(".error");
                    errorSpans.forEach(span => span.textContent = "");

                    // Validación de Edad
                    const edad = document.getElementById("edad");
                    if (!edad.value || isNaN(edad.value) || parseInt(edad.value) < 0) {
                        document.getElementById("edadError").textContent = "Por favor, ingrese una edad válida.";
                        isValid = false;
                    }

                    // Validación de Peso
                    const peso = document.getElementById("peso");
                    if (!peso.value || isNaN(peso.value) || parseInt(peso.value) <= 0) {
                        document.getElementById("pesoError").textContent = "Por favor, ingrese un peso válido.";
                        isValid = false;
                    }
                    // Validación de Estatura
                    const altura = document.getElementById("altura");
                    const valorAltura = parseFloat(altura.value);

                    if (!altura.value || isNaN(valorAltura) || valorAltura <= 0) {
                        document.getElementById("alturaError").textContent = "Por favor, ingrese una estatura válida (número positivo, decimal o entero).";
                        isValid = false;
                    } else {
                        document.getElementById("alturaError").textContent = ""; // Limpiar cualquier mensaje de error

                    }

                    // Validación de Presión arterial
                    const presion_arterial = document.getElementById("presion_arterial");
                    if (!presion_arterial.value || !/^\d{2,3}\/\d{2,3}$/.test(presion_arterial.value)) {
                        document.getElementById("presionError").textContent = "Por favor, ingrese una presión arterial válida (ejemplo: 120/80).";
                        isValid = false;
                    }

                // Validación de Temperatura (enteros y decimales)
                    const temperatura = document.getElementById("temperatura");
                    const valorTemperatura = parseFloat(temperatura.value); // Convertir a decimal

            if (!temperatura.value || isNaN(valorTemperatura) || valorTemperatura <= 0) {
                document.getElementById("temperaturaError").textContent = "Por favor, ingrese una temperatura válida.";
                isValid = false;
            } else {
                document.getElementById("temperaturaError").textContent = ""; // Limpiar el mensaje de error si es válido
            }

                    // Validación de Glucosa
                    const glucosa = document.getElementById("glucosa");
                    if (!glucosa.value || isNaN(glucosa.value) || parseInt(glucosa.value) < 0) {
                        document.getElementById("glucosaError").textContent = "Por favor, ingrese un nivel de glucosa válido.";
                        isValid = false;
                    }

                // Validación de Frecuencia Cardiaca (enteros y decimales)
                const frecuencia_cardiaca = document.getElementById("frecuencia_cardiaca");
                const valorFrecuencia = parseFloat(frecuencia_cardiaca.value); // Convertir a decimal

                if (!frecuencia_cardiaca.value || isNaN(valorFrecuencia) || valorFrecuencia <= 0) {
                    document.getElementById("frecuenciaError").textContent = "Por favor, ingrese una frecuencia cardiaca válida.";
                    isValid = false;
                } else {
                    document.getElementById("frecuenciaError").textContent = ""; // Limpiar el mensaje de error si es válido
                }

                });
        </script>
        
        <!-- Script para Calcular IMC -->
    <script>
    document.getElementById("calcular_imc").addEventListener("click", function() {
        var peso = parseFloat(document.querySelector("input[name='peso']").value);
        var altura = parseFloat(document.querySelector("input[name='altura']").value);

        if (isNaN(peso) || isNaN(altura) || altura === 0) {
            alert("Por favor, ingrese valores válidos para peso y altura.");
            return;
        }

        var altura_metros = altura;
        var imc = peso / (altura_metros * altura_metros);

        var mensaje = "El IMC del paciente registrado es " + imc.toFixed(2) + ". ";
        if (imc < 18.5) {
            mensaje += "Bajo peso. Por favor, considere consultar a un especialista.";
        } else if (imc < 24.9) {
            mensaje += "Peso normal. ¡Mantenga un estilo de vida saludable!";
        } else if (imc < 29.9) {
            mensaje += "Sobrepeso. Se recomienda ejercicio y dieta equilibrada.";
        } else {
            mensaje += "Obesidad. Consulte a un especialista para un plan de salud.";
        }

        alert(mensaje);
    });
    </script>

       
   <!-- Menú de Navegación -->
   <nav>
        <a href="enfermeros.php" class="btn btn-primary">Registro de Enfermeros</a> |
        <a href="pacientes.php" class="btn btn-primary">Registro de pacientes</a>|
       <a href='historial_pacientes.php' class='btn btn-primary'>Ver Historial</a>



    </nav>
    <div class="center-button">
        <a href="ver_datos_pac.php" class="btn btn-primary">Ver datos</a>
    </div>

</body>
</html>



