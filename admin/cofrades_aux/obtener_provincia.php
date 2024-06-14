<?php
header('Content-Type: application/json');
// Conexión a la base de datos (ajusta los valores)
$conn = new mysqli("localhost", "roots", "", "e9_cofradia");

if (isset($_GET['provincia_id'])) {
    $provincia_id = $_GET['provincia_id'];
    $provincia = $conn->query("SELECT des_provincia AS nombre FROM provincia WHERE id = '$provincia_id'")->fetch_assoc();
    $localidades = $conn->query("SELECT id, des_localidad AS nombre FROM localidad WHERE provincia_id = '$provincia_id' AND delete_flag = 0 ORDER BY nombre ASC");

    $localidadesArray = [];
    while ($row = $localidades->fetch_assoc()) {
        $localidadesArray[] = $row;
    }

    $response = [
        'nombre' => $provincia['nombre'],
        'localidades' => $localidadesArray
    ];

    echo json_encode($response);
} else {
    echo json_encode(['error' => 'Provincia no especificada']);
}

?>