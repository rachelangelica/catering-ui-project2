<?php
    include '../conf/config.php';


    if (isset($_GET['id'])) {
        $id_pesanan = $_GET['id'];
        echo "ID Pesanan: " . htmlspecialchars($id_pesanan) . "<br>";
        echo "Fetching details for Order ID: " . $id_pesanan . "<br>";


        $sql = "SELECT p.id_pesanan, p.username, p.tgl_pesan, rp.id_menu, m.nama_menu, sp.nama_status_proses, v.nama_vendor, rp.jam_delivery, rp.tgl_kegiatan
                FROM t_pesanan p
                JOIN t_rincian_pesanan rp ON p.id_pesanan = rp.id_pesanan
                JOIN m_menu m ON rp.id_menu = m.id_menu
                JOIN t_proses_rincian_pesanan prp ON rp.id_rincian_pesanan = prp.id_rincian_pesanan
                JOIN m_status_proses sp ON prp.id_status_proses = sp.id_status_proses
                JOIN m_vendor v ON rp.id_vendor = v.id_vendor
                WHERE p.id_pesanan = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_pesanan);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $order = $result->fetch_assoc();            
        } else {
            echo "Order not found.";
            exit;
        }
    } else {
        echo "No order ID provided.";
        exit;
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .order-details {
            width: 90%;
            max-width: 800px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
            font-size: 24px;
        }
        p {
            font-size: 16px;
            margin: 10px 0;
            color: #555;
        }
        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007BFF;
            text-decoration: none;
            padding: 10px 15px;
            background-color: #f8f8f8;
            border: 1px solid #007BFF;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        a:hover {
            background-color: #007BFF;
            color: white;
        }
    </style>
</head>
<body>
    <div class="order-details">
         <h1>Order Details</h1>
        <p>Order ID: <?php echo $order['id_pesanan']; ?></p>
        <p>Username: <?php echo $order['username']; ?></p>
        <p>Tanggal pesan: <?php echo $order['tgl_pesan']; ?></p>
        <p>Tanggal Kegiatan: <?php echo $order['tgl_kegiatan']; ?></p>
        <p>Jam Delivery: <?php echo $order['jam_delivery']; ?></p>
        <p>Menu: <?php echo $order['nama_menu']; ?></p>
        <p>Status: <?php echo $order['nama_status_proses']; ?></p>
        <p>Vendor: <?php echo $order['nama_vendor']; ?></p>
        <a href="admindashboard.php">Back to Orders</a>
    </div>
</body>
</html>
