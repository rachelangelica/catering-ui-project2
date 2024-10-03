<?php
    include '../conf/config.php';

    if (isset($_GET['id'])) {
        $id_pesanan = $_GET['id'];

        $sql = "SELECT p.id_pesanan, sp.id_status_proses, sp.nama_status_proses, p.nama_kegiatan, spes.nama_status_pesanan,
                u.nama_unit, g.nama_gedung, r.nama_ruangan, rp.tgl_kegiatan, rp.jam_delivery, rp.jenis_penyajian, rp.id_menu
                FROM t_rincian_pesanan rp
                JOIN t_pesanan p ON rp.id_pesanan = rp.id_pesanan
                JOIN t_proses_rincian_pesanan prp ON rp.id_rincian_pesanan = prp.id_rincian_pesanan
                JOIN m_status_proses sp ON prp.id_status_proses = sp.id_status_proses
                JOIN m_status_pesanan spes ON p.id_status_pesanan = spes.id_status_pesanan
                JOIN m_gedung g ON p.id_gedung = g.id_gedung
                JOIN m_ruangan r ON g.id_gedung = r.id_ruangan
                JOIN m_unit u ON p.id_unit = u.id_unit
                WHERE p.id_pesanan = ?";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $id_pesanan);
        $stmt->execute();
        $result = $stmt->get_result();
        $order = $result->fetch_assoc();
    }

    if (isset($_POST['update_status'])) {
        $new_status = $_POST['status'];

        $update_sql = "UPDATE t_proses_rincian_pesanan SET id_status_proses = ? WHERE id_pesanan = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("ii", $new_status, $id_pesanan);
        
        if ($update_stmt->execute()) {
            echo "Order status updated successfully!";
        } else {
            echo "Failed to update status.";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: auto;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        select, input[type="date"], input[type="time"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type="submit"] {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
        }

        input[type="submit"]:hover {
            background-color: #218838;
        }

        a {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
            color: black;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>

</head>
<body>
    <h1>Edit Order</h1>
    <form action="" method="POST">
        <label for="nama_gedung">Nama Gedung dan Ruangan:</label>
        <select id="nama_gedung" name="nama_gedung">
            <option value="">Select Gedung</option>
            <?php
                $gedung_sql = "SELECT id_gedung, nama_gedung FROM m_gedung";
                $gedung_result = $conn->query($gedung_sql);
                while ($gedung = $gedung_result->fetch_assoc()) {
                    echo "<option value='{$gedung['id_gedung']}' " . ($gedung['id_gedung'] == $order['id_gedung'] ? 'selected' : '') . ">{$gedung['nama_gedung']}</option>";
                }
            ?>
        </select><br><br>

        <label for="nama_ruangan">Nama Ruangan:</label>
        <select id="nama_ruangan" name="nama_ruangan">
            <option value="">Select Ruangan</option>
            <?php

                $ruangan_sql = "SELECT id_ruangan, nama_ruangan FROM m_ruangan";
                $ruangan_result = $conn->query($ruangan_sql);
                while ($ruangan = $ruangan_result->fetch_assoc()) {
                    echo "<option value='{$ruangan['id_ruangan']}' " . ($ruangan['id_ruangan'] == $order['id_ruangan'] ? 'selected' : '') . ">{$ruangan['nama_ruangan']}</option>";
                }
            ?>
        </select><br><br>

        <label for="nama_unit">Nama Unit:</label>
        <select id="nama_unit" name="nama_unit">
            <option value="">Select Unit</option>
            <?php
                $unit_sql = "SELECT id_unit, nama_unit FROM m_unit";
                $unit_result = $conn->query($unit_sql);
                while ($unit = $unit_result->fetch_assoc()) {
                    echo "<option value='{$unit['id_unit']}' " . ($unit['id_unit'] == $order['id_unit'] ? 'selected' : '') . ">{$unit['nama_unit']}</option>";
                }
            ?>
        </select><br><br>

        <label for="tgl_kegiatan">Tanggal Kegiatan:</label>
        <input type="date" id="tgl_kegiatan" name="tgl_kegiatan" value="<?php echo $order['tgl_kegiatan']; ?>"><br><br>

        <label for="jam_delivery">Jam Delivery:</label>
        <input type="time" id="jam_delivery" name="jam_delivery" value="<?php echo $order['jam_delivery']; ?>"><br><br>

        <label for="status_pesanan">Status Pesanan:</label>
        <select id="status_pesanan" name="status_pesanan">
            <option value="">Select status</option>
            <?php
                $status_sql = "SELECT id_status_pesanan, nama_status_pesanan FROM m_status_pesanan";
                $status_result = $conn->query($status_sql);
                while ($status = $status_result->fetch_assoc()) {
                    echo "<option value='{$status['id_status_pesanan']}' " . ($status['id_status_pesanan'] == $order['id_status_pesanan'] ? 'selected' : '') . ">{$status['nama_status_pesanan']}</option>";
                }
            ?>
        </select><br><br>

        <input type="submit" name="update_order" value="Update Order">
    </form>

    <a href="admindashboard.php">Back to Orders</a>
</body>
</html>
