<?php
// require 'conexion.php'; 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "e9_cofradia";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}



ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Content-Type: application/json'); // Asegúrate de que la salida es JSON

if (isset($_GET['provincia_id'])) {
    $provincia_id = $_GET['provincia_id'];
    
    if (!is_numeric($provincia_id)) {
        echo json_encode(['error' => 'ID de provincia inválido']);
        exit;
    }

    $localidades = $conn->query("SELECT * FROM localidad WHERE delete_flag = 0 AND provincia_id = $provincia_id ORDER BY `name` ASC");
    
    if (!$localidades) {
        echo json_encode(['error' => $conn->error]);
        exit;
    }
    
    $localidadArray = [];

    while ($row = $localidades->fetch_assoc()) {
        $localidadArray[] = $row;
    }

    echo json_encode($localidadArray);
} else {
    echo json_encode(['error' => 'No se proporcionó ID de provincia']);
}
?>


