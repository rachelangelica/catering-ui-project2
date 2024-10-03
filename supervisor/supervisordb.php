<?php
    session_start();
    require_once('../conf/config.php');

    $username = $_SESSION['username'];

    // Profile Query
    $sql_profile = "SELECT nama, email, username FROM m_account WHERE username = ?";
    $stmt_profile = $conn->prepare($sql_profile);
    $stmt_profile->bind_param("s", $username);
    $stmt_profile->execute();
    $result_profile = $stmt_profile->get_result();

    if ($result_profile->num_rows > 0) {
        $row_profile = $result_profile->fetch_assoc();
        $nama = $row_profile['nama'];
        $email = $row_profile['email'];
        $username = $row_profile['username'];
    } else {
        echo "Error: User profile data not found.";
    }
    
    // Fetch status filter buttons
    $statuses = getOrderStatuses($conn);
    function getOrderStatuses($conn) {
        $query = "SELECT id_status_proses, nama_status_proses FROM m_status_proses";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Use get_result and fetch_all for MySQLi
    }

    // Current Orders Query
    $sql_current_orders = 
    "SELECT p.id_pesanan, sp.nama_status_pesanan, rp.tgl_kegiatan, rp.jam_delivery
    FROM t_pesanan as p 
    JOIN m_status_pesanan as sp ON sp.id_status_pesanan = p.id_status_pesanan
    JOIN t_rincian_pesanan as rp ON rp.id_pesanan = p.id_pesanan
    WHERE p.username = ? AND sp.nama_status_pesanan = 'open'";
    $stmt_current_orders = $conn->prepare($sql_current_orders);
    $stmt_current_orders->bind_param("s", $username);
    $stmt_current_orders->execute();
    $result_current_orders = $stmt_current_orders->get_result();

    $currentOrders = [];
    if ($result_current_orders->num_rows > 0) {
        while ($row_current_order = $result_current_orders->fetch_assoc()) {
            $currentOrders[] = $row_current_order;
        }
    }

    // Order History Query
    $sql_order_history = 
    "SELECT p.id_pesanan, sp.nama_status_pesanan
    FROM t_pesanan as p 
    JOIN m_status_pesanan as sp ON sp.id_status_pesanan = p.id_status_pesanan
    JOIN t_rincian_pesanan as rp ON rp.id_pesanan = p.id_pesanan
    WHERE p.username = ? AND sp.nama_status_pesanan IN ('closed', 'cancelled')";
    $stmt_order_history = $conn->prepare($sql_order_history);
    $stmt_order_history->bind_param("s", $username);
    $stmt_order_history->execute();
    $result_order_history = $stmt_order_history->get_result();

    $orderHistory = [];
    if ($result_order_history->num_rows > 0) {
        while ($row_order_history = $result_order_history->fetch_assoc()) {
            $orderHistory[] = $row_order_history;
        }
    }
    
    function getCurrentOrders($conn, $username) {
        $query = "SELECT * FROM t_rincian_pesanan WHERE username = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username); // Bind parameters for MySQLi
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC); // Use get_result and fetch_all for MySQLi
    }

$conn->close();

?>