<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

// Get POST data
$data = json_decode(file_get_contents('php://input'), true);

if (!$data) {
    echo json_encode(['success' => false, 'error' => 'No data received']);
    exit;
}

$conn = getDbConnection();

// Prepare SQL statement
$sql = "INSERT INTO bitacora (tipo, persona, horas, recuperar, motivo, baja, timestamp, user) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);

if (!$stmt) {
    echo json_encode(['success' => false, 'error' => 'Prepare failed: ' . $conn->error]);
    exit;
}

$stmt->bind_param(
    "ssssssss",
    $data['tipo'],
    $data['persona'],
    $data['horas'],
    $data['recuperar'],
    $data['motivo'],
    $data['baja'],
    $data['timestamp'],
    $data['user']
);

if ($stmt->execute()) {
    echo json_encode([
        'success' => true,
        'id' => $stmt->insert_id,
        'message' => 'Bitacora entry saved successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'error' => 'Execute failed: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>