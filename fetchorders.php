<?php
include 'config.php'; // Ensure you include your database connection

// Fetch the POST request body
$data = json_decode(file_get_contents('php://input'), true);

$username = $data['username'];
$status = $data['status'];

// Prepare the query
$sql = "
    SELECT rp.id_pesanan, m_menu.nama_menu, m_status_proses.nama_status_proses
    FROM t_rincian_pesanan rp
    JOIN t_proses_rincian_pesanan prp ON rp.id_rincian_pesanan = prp.id_rincian_pesanan
    JOIN m_menu m ON rp.id_menu = m.id_menu
    JOIN m_status_proses sp ON prp.id_status_proses = sp.id_status_proses
    JOIN t_pesanan p ON rp.id_pesanan = p.id_pesanan
    WHERE p.username = ?
";

if ($status !== 'all') {
    $sql .= " AND m_status_proses.id_status_proses = ?";
}

$stmt = $conn->prepare($sql);

if ($status === 'all') {
    $stmt->bind_param('s', $username);
} else {
    $stmt->bind_param('ss', $username, $status);
}

$stmt->execute();
$result = $stmt->get_result();

$orders = [];
while ($row = $result->fetch_assoc()) {
    $orders[] = $row;
}

header('Content-Type: application/json');
echo json_encode($orders);
?>
