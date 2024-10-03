<?php
    include '../conf/config.php';

    // Fetch the order details from the database (same as your previous query)
    $sql = "SELECT p.id_pesanan, p.username, p.tgl_pesan, rp.id_menu, p.nama_kegiatan, sp.nama_status_proses, v.nama_vendor, spes.nama_status_pesanan
            FROM t_pesanan p
            JOIN t_rincian_pesanan rp ON p.id_pesanan = rp.id_pesanan
            JOIN m_menu m ON rp.id_menu = m.id_menu
            JOIN t_proses_rincian_pesanan prp ON rp.id_rincian_pesanan = prp.id_rincian_pesanan
            JOIN m_status_proses sp ON prp.id_status_proses = sp.id_status_proses
            JOIN m_vendor v ON rp.id_vendor = v.id_vendor
            JOIN m_status_pesanan spes ON p.id_status_pesanan = spes.id_status_pesanan";

    $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Order Tracking</title>
    <link rel="stylesheet" href="styles.css">
    <style>    
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .admin-dashboard {
            width: 90%;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 12px;
            text-align: left;
            font-size: 16px;
        }

        th {
            background-color: #f8f8f8;
            color: #333;
        }

        td {
            background-color: #fff;
        }

        a {
            text-decoration: none;
            color: #007BFF;
        }

        a:hover {
            text-decoration: underline;
        }

        .no-orders {
            text-align: center;
            font-size: 18px;
            color: #888;
        }

        .action-buttons a {
            padding: 6px 10px;
            background-color: #4CAF50;
            color: white;
            border-radius: 4px;
            margin-right: 5px;
            text-decoration: none;
        }

        .action-buttons a:hover {
            background-color: #45a049;
        }

        .action-buttons .edit-btn {
            background-color: #007BFF;
        }

        .action-buttons .edit-btn:hover {
            background-color: #0056b3;
        }

        .action-buttons .btn-cancel {
            padding: 6px 10px; /* Match padding of view and edit buttons */
            background-color: #FF5733; /* Existing background color */
            color: white; /* Existing text color */
            border-radius: 4px; /* Match border radius */
            margin-right: 5px; /* Match margin */
            text-decoration: none; /* No underline */
            border: none; /* Remove default border */
            cursor: pointer; /* Pointer cursor on hover */
        }

        .action-buttons .btn-cancel:hover {
            background-color: #C70039; /* Existing hover color */
        }

        #message {
            margin-bottom: 20px;
            text-align: center;
            font-size: 18px;
            color: #28a745;
        }
    </style>

</head>
<body>
    <?php
        if (isset($_GET['message'])) {
            echo "<div id='message'>" . htmlspecialchars($_GET['message']) . "</div>";
        }
    ?>
    <div class="admin-dashboard">
        <h1>Admin Dashboard - Order Tracking</h1>
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>User ID</th>
                    <th>Tanggal/Waktu Pesan</th>
                    <th>Nama Kegiatan</th>
                    <th>Status Tracking</th>
                    <th>Vendor</th>
                    <th>Status Pesanan</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id_pesanan']}</td>
                                <td>{$row['username']}</td>
                                <td>{$row['tgl_pesan']}</td>
                                <td>{$row['nama_kegiatan']}</td>
                                <td>{$row['nama_status_proses']}</td>
                                <td>{$row['nama_vendor']}</td>
                                <td>{$row['nama_status_pesanan']}</td>
                                <td class='action-buttons'>
                                    <a href='order-details.php?id={$row["id_pesanan"]}' class='btn btn-view'>View</a>
                                    <a href='edit-order.php?id={$row["id_pesanan"]}' class='btn btn-edit'>Edit</a>
                                    <form action='cancel-order.php' method='post' style='display:inline;'>
                                        <input type='hidden' name='id' value='{$row["id_pesanan"]}'>
                                        <button type='submit' class='btn btn-cancel' onclick=\"return confirm('Are you sure you want to cancel this order?');\">Cancel</button>
                                    </form>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='7'>No orders found</td></tr>";
                }
            ?>
            </tbody>
        </table>
    </div>
</body>
</html>
