<?php
// Conexión a la base de datos (reemplaza con tus datos)
$conn = new mysqli("tu_host", "tu_usuario", "tu_contraseña", "tu_base_de_datos");

if ($conn->connect_error) {
    die(json_encode(['success' => false, 'error' => 'Error de conexión: ' . $conn->connect_error]));
}

// Obtener el ID del cofrade desde la petición AJAX
$cofrade_id = $_POST['id'];

// Iniciar una transacción para garantizar la consistencia de los datos
$conn->begin_transaction();

try {
    // Actualizar numero_activo a 0 para este registro
    $update_query = "UPDATE cofrades SET numero_activo = 0 WHERE id = $cofrade_id";
    $conn->query($update_query);

    // Reasignar numero_activo para los registros activos
    $active_cofrades = $conn->query("SELECT id FROM cofrades WHERE status = 1 AND id != $cofrade_id ORDER BY id ASC");
    $new_numero_activo = 1;
    while ($row = $active_cofrades->fetch_assoc()) {
        $conn->query("UPDATE cofrades SET numero_activo = $new_numero_activo WHERE id = {$row['id']}");
        $new_numero_activo++;
    }

    // Confirmar la transacción si todo ha ido bien
    $conn->commit();
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conn->rollback();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>
