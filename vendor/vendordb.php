<?php
    require_once('../conf/config.php');

    session_start();
    if (!isset($_SESSION['id_group']) || $_SESSION['id_group'] !== 'GRUP1') {
        die('Vendor not logged in');
    }

    // Fetch id_vendor based on username
    $username = $_SESSION['username'];
    $stmt_vendor = $conn->prepare("SELECT id_vendor FROM m_vendor WHERE username = ?");
    $stmt_vendor->bind_param("s", $username);
    $stmt_vendor->execute();
    $stmt_vendor->store_result();
    $stmt_vendor->bind_result($id_vendor);

    if ($stmt_vendor->num_rows > 0) {
        $stmt_vendor->fetch();
        $_SESSION['id_vendor'] = $id_vendor; // Set id_vendor in session
    } else {
        die('Vendor not found'); // Handle case where id_vendor is not found
    }
    $stmt_vendor->close();

    $statuses = getOrderStatuses($conn);

    function getOrderStatuses($conn) {
        $query = "SELECT id_status_proses, nama_status_proses FROM m_status_proses";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // Fetch current orders
    function getCurrentOrders($id_vendor) {
        global $conn;
        $stmt = $conn->prepare("SELECT rp.id_pesanan, sp.nama_status_proses, jp.nama_pembayaran
            FROM t_rincian_pesanan rp
            JOIN t_proses_rincian_pesanan prp ON rp.id_rincian_pesanan = prp.id_rincian_pesanan
            JOIN m_status_proses sp ON prp.id_status_proses = sp.id_status_proses
            JOIN m_jenis_pembayaran jp ON rp.id_pembayaran = jp.id_pembayaran
            WHERE rp.id_vendor = ?");
        $stmt->bind_param("i", $id_vendor);
        $stmt->execute();
        $result = $stmt->get_result();
        $orders = [];
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
        $stmt->close();
        return $orders;
    }

    // Fetch menus and items
    function getMenus($id_vendor) {
        global $conn;
        $stmt = $conn->prepare("SELECT m.id_menu, m.nama_menu, sm.nama_submenu, i.nama_item, i.harga
            FROM m_menu m
            JOIN m_sub_menu sm ON m.id_menu = sm.id_menu
            JOIN m_item i ON sm.id_item = i.id_item
            WHERE m.id_vendor = ?");
        $stmt->bind_param("i", $id_vendor);
        $stmt->execute();
        $result = $stmt->get_result();
        $menus = [];
        while ($row = $result->fetch_assoc()) {
            $menus[] = $row;
        }
        $stmt->close();
        return $menus;
    }

    function getMenuItems($id_menu) {
        global $conn;
        $stmt = $conn->prepare("SELECT sm.nama_submenu, i.nama_item, i.harga
            FROM m_sub_menu sm
            JOIN m_item i ON sm.id_item = i.id_item
            WHERE sm.id_menu = ?");
        $stmt->bind_param("i", $id_menu);
        $stmt->execute();
        $result = $stmt->get_result();
        $items = [];
        while ($row = $result->fetch_assoc()) {
            $items[] = $row;
        }
        $stmt->close();
        return $items;
    }

    // Add menu functionality
    if (isset($_POST['add_menu'])) {
        $menuName = $_POST['nama_menu'];
        
        $stmt = $conn->prepare("INSERT INTO m_menu (id_vendor, nama_menu) VALUES (?, ?)");
        $stmt->bind_param("is", $id_vendor, $menuName);
        $stmt->execute();
        $stmt->close();
        
        header('Location: vendor_dashboard.php');
        exit();
    }

    // Add submenu and item functionality
    if (isset($_POST['add_item'])) {
        $menuName = $_POST['nama_menu'];
        $submenuName = $_POST['nama_submenu'];
        $itemName = $_POST['nama_item'];
        $itemType = $_POST['jenis_makanan'];
        $price = $_POST['harga'];
        $stock = $_POST['stok'];

        // Insert new menu if not exists
        $stmt = $conn->prepare("INSERT INTO m_menu (id_vendor, nama_menu) VALUES (?, ?) ON DUPLICATE KEY UPDATE id_menu=LAST_INSERT_ID(id_menu)");
        $stmt->bind_param("is", $id_vendor, $menuName);
        $stmt->execute();
        $id_menu = $conn->insert_id;
        $stmt->close();

        // Insert new submenu
        $stmt = $conn->prepare("INSERT INTO m_sub_menu (id_menu, nama_submenu) VALUES (?, ?)");
        $stmt->bind_param("is", $id_menu, $submenuName);
        $stmt->execute();
        $id_sub_menu = $conn->insert_id;
        $stmt->close();

        // Insert new item
        $stmt = $conn->prepare("INSERT INTO m_item (nama_item, id_jenis_makanan, harga, stok) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("siii", $itemName, $itemType, $price, $stock);
        $stmt->execute();
        $id_item = $conn->insert_id;
        $stmt->close();

        // Link item with submenu
        $stmt = $conn->prepare("INSERT INTO m_sub_menu_item (id_sub_menu, id_item) VALUES (?, ?)");
        $stmt->bind_param("ii", $id_sub_menu, $id_item);
        $stmt->execute();
        $stmt->close();

        header('Location: vendor_dashboard.php');
        exit();
    }

    // Fetch status processes
    function getStatusProcesses() {
        global $conn;
        $stmt = $conn->prepare("SELECT id_status_proses, nama_status_proses FROM m_status_proses ORDER BY id_status_proses ASC");
        $stmt->execute();
        $result = $stmt->get_result();
        $statuses = [];
        while ($row = $result->fetch_assoc()) {
            $statuses[] = $row;
        }
        $stmt->close();
        return $statuses;
    }

    $statusProcesses = getStatusProcesses();

    // Update order status
    if (isset($_POST['update_status'])) {
        $id_pesanan = $_POST['id_pesanan'];
        $newStatus = $_POST['status'];
        
        $stmt = $conn->prepare("UPDATE t_rincian_pesanan SET id_status_proses = ? WHERE id_pesanan = ?");
        $stmt->bind_param("ii", $newStatus, $id_pesanan);
        $stmt->execute();
        $stmt->close();
    }
?>
