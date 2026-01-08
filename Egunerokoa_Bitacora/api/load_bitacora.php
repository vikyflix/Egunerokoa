<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once 'db_config.php';

$conn = getDbConnection();

// Optional: Filter by date range
$date_filter = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

$sql = "SELECT * FROM bitacora 
        WHERE DATE(created_at) = ? 
        ORDER BY created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $date_filter);
$stmt->execute();

$result = $stmt->get_result();
$bitacora = [];

while ($row = $result->fetch_assoc()) {
    $bitacora[] = [
        'id' => $row['id'],
        'tipo' => $row['tipo'],
        'persona' => $row['persona'],
        'horas' => $row['horas'],
        'recuperar' => $row['recuperar'],
        'motivo' => $row['motivo'],
        'baja' => $row['baja'],
        'timestamp' => $row['timestamp'],
        'user' => $row['user']
    ];
}

echo json_encode([
    'success' => true,
    'bitacora' => $bitacora,
    'count' => count($bitacora)
]);

$stmt->close();
$conn->close();
?>