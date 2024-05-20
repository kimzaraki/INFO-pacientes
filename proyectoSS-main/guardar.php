<?php
// $servidor = "localhost";
// $usuario = "root";
// $clave = "";
// $baseDeDatos = "enfermeros";

// $enlace = mysqli_connect($servidor, $usuario, $clave, $baseDeDatos);

// if (!$enlace) {
//     die("Conexión fallida: " . mysqli_connect_error());
// }

// if(isset($_POST['accion']) && $_POST['accion'] === "guardar") {
    
//     $fecha_atencion = $_POST['fecha_atencion'];  
//     $nombre_enfermero = $_POST['nombre_enfermero'];
//     $nombre_paciente = $_POST['nombre_paciente'];
//     $edad = $_POST['edad'];
//     $peso = $_POST['peso'];
//     $altura = $_POST['altura'];
//     $presion_arterial = $_POST['presion_arterial'];
//     $SpO2 = $_POST['SpO2'];
//     $temperatura = $_POST['temperatura'];
//     $glucosa = $_POST['glucosa'];
//     $frecuencia_cardiaca = $_POST['frecuencia_cardiaca'];
//     $frecuencia_respiratoria = $_POST['frecuencia_respiratoria'];
//     $sintomas = $_POST['sintomas'];
    
//     // Asumiendo que la tabla datos_paciente tiene columnas adecuadas. Ajusta según tu estructura.
//     $insertar = "INSERT INTO datos_paciente(fecha_atencion, nombre_enfermero, nombre_paciente, edad, peso, altura, presion_arterial, SpO2, temperatura, glucosa, frecuencia_cardiaca, frecuencia_respiratoria, sintomas) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
//     $stmt = mysqli_prepare($enlace, $insertar);
//     mysqli_stmt_bind_param($stmt, $fecha_atencion, $nombre_enfermero, $nombre_paciente, $edad, $peso, $altura, $presion_arterial, $SpO2, $temperatura, $glucosa, $frecuencia_cardiaca, $frecuencia_respiratoria, $sintomas);
    
//     $ejecutarinsertar = mysqli_stmt_execute($stmt);

//     if ($ejecutarinsertar) {
//         echo "Registro exitoso";
//     } else {
//         echo "Error: " . mysqli_error($enlace);
//     }
// }


//validar datos
if ($fecha_atencion==null){

}

// Chequear si el formulario ha sido enviado
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accion'])) {
    if ($_POST['accion'] == 'guardar') {    // Nombre del archivo CSV donde se almacenarán los datos
    $archivo_csv = "datos.csv";
    
    // Recoger los datos del formulario
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
    $sintomas = $_POST['sintomas'];

    // Preparar la línea de datos para el CSV
    $linea = [$fecha_atencion, $nombre_enfermero, $nombre_paciente, $edad, $peso, $altura, $presion_arterial, $temperatura, $glucosa,  $frecuencia_cardiaca, $sintoma];
    
    // Abrir el archivo CSV o crearlo si no existe
    $archivo = fopen($archivo_csv, 'a'); // 'a' es para modo append
    
    // Guardar los datos en el archivo CSV
    fputcsv($archivo, $linea);
    
    // Cerrar el archivo
    fclose($archivo);
    
    // // Preparar el archivo para su descarga
    // header('Content-Type: application/csv');
    // header('Content-Disposition: attachment; filename="'.basename($archivo_csv).'";');
    // readfile($archivo_csv); // Leer el contenido del archivo y enviarlo al navegador
    echo "Datos guardados correctamente.";
        exit;
    ;
}
 }
?>
