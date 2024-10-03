<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="checkout-style.css">
    <script src="https://kit.fontawesome.com/7617b7392d.js" crossorigin="anonymous"></script>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-color: #f9f9f9;
            color: #333;
            font-size: 16px;
        }
        .checkout {
            max-width: 900px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .input-box {
            margin-bottom: 20px;
        }
        .input-box label,
        .input-box span {
            display: block;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .input-box input,
        .input-box select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            background-color: #f9f9f9;
        }
        .input-box input[type="time"],
        .input-box input[type="date"] {
            background-color: #fff;
        }
        .input-box input:focus,
        .input-box select:focus {
            border-color: #007bff;
            outline: none;
        }
        fieldset {
            border: 1px solid #ccc;
            padding: 20px;
            margin-top: 10px;
            border-radius: 8px;
        }
        legend {
            font-size: 18px;
            font-weight: 600;
        }
        .checkout-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }
        .checkout-table th,
        .checkout-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        .checkout-table th {
            background-color: #f2f2f2;
            font-weight: 600;
        }
        .checkout-table img {
            max-width: 100px;
        }
        .order-summary {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }
        .order-summary h3 {
            margin-bottom: 20px;
            font-size: 20px;
        }
        .order-summary p {
            font-size: 16px;
            margin-bottom: 10px;
        }
        .order-summary hr {
            border: 0;
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }
        .total {
            font-weight: 700;
            font-size: 18px;
        }
        .checkout-btn {
            width: 100%;
            padding: 15px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .checkout-btn:hover {
            background-color: #218838;
        }
        .buffet-radio {
            margin-bottom: 20px;
        }
        .buffet-radio input {
            margin-right: 10px;
        }
        .buffet-radio label {
            margin-right: 20px;
            font-weight: 500;
        }
        .upload-section {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
        }
        .upload-section h2 {
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
            font-size: 1.2em;
        }
        .upload-section label {
            display: block;
            font-weight: 500;
            margin-bottom: 10px;
        }
        .upload-section input[type="file"] {
            padding: 10px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-bottom: 15px;
        }
        .upload-section button {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        .upload-section button:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="checkout">
        <h1>Lampiran</h1>

        <div class="upload-section">
            <h2>Upload File</h2>
            <form action="prosesorder.php" method="post" enctype="multipart/form-data">
                <label for="fileUpload">Pilih File:</label>
                <input type="file" name="attachment" id="fileUpload" accept="image/*,application/pdf">
                <button type="submit">Upload</button>
            </form>
        </div>

        <div class="attachment-preview">
            <?php
            if (isset($uploadedFilePath) && !empty($uploadedFilePath)) {
                echo "<p>File Uploaded: <a href='" . htmlspecialchars($uploadedFilePath) . "'>" . htmlspecialchars(basename($uploadedFilePath)) . "</a></p>";
                $fileExtension = pathinfo($uploadedFilePath, PATHINFO_EXTENSION);
                if (in_array($fileExtension, ['jpg', 'jpeg', 'png'])) {
                    echo "<img src='" . htmlspecialchars($uploadedFilePath) . "' alt='Attachment' style='max-width: 100px; height: auto;'>";
                }
            }
            ?>
        </div>

        <h1>Rincian Pesanan</h1>

        <form action="prosesorder.php" method="POST">
            <div class="form-group">
                <div class="input-box">
                    <span class="nama_kegiatan">Nama Kegiatan:</span>
                    <input type="text" id="nama_kegiatan" name="nama_kegiatan" placeholder="Masukkan nama kegiatan" required>
                </div>

                <div class="input-box">
                    <span class="tgl_kegiatan">Event Date:</span>
                    <input type="date" id="tgl_kegiatan" name="tgl_kegiatan" required>
                </div>

                <div class="input-box">
                    <span class="tempat_kegiatan">Tempat Kegiatan:</span>
                    <select name="tempat_kegiatan" id="tempat_kegiatan" required>
                        <option value="ruangan1">Ruang 1 Gedung 1</option>
                        <option value="ruangan2">Ruang 2 Gedung 1</option>
                        <option value="ruangan3">Ruang 3 Gedung 1</option>
                        <option value="ruangan4">Ruangan 1 Gedung 2</option>
                        <option value="ruangan5">Ruangan 2 Gedung 2</option>
                        <option value="ruangan6">Ruangan 3 Gedung 2</option>
                    </select>
                </div>

                <div class="input-box">
                    <span class="kategori_makanan">Kategori Makanan:</span>
                    <select name="kategori_makanan" id="kategori_makanan" required>
                        <option value="makanpagi">Makan Pagi (05.00-10.00 WIB)</option>
                        <option value="snackpagi">Snack Pagi (09.00-11.00 WIB)</option>
                        <option value="makansiang">Makan Siang (12.00-13.00 WIB)</option>
                        <option value="snacksiang">Snack Siang (13.30-15.30 WIB)</option>
                        <option value="makanmalam">Makan Malam (18.30-19.30 WIB)</option>
                        <option value="snackmalam">Snack Malam (19.30-21.00 WIB)</option>
                    </select>
                </div>

                <div class="input-box">
                    <label for="jam_delivery">Jam Penyajian:</label>
                    <input type="time" id="jam_delivery" name="jam_delivery" required>
                </div>

                <div class="buffet-radio">
                    <span class="jenis_penyajian">Buffet Type:</span>
                    <label for="jenis_penyajian">
                        <input type="radio" id="jenis_penyajian" name="jenis_penyajian" value="prasmanan" required> Prasmanan
                    </label>
                    <label for="jenis_penyajian">
                        <input type="radio" id="jenis_penyajian" name="jenis_penyajian" value="box" required> Box
                    </label>
                </div>
            </div>

            <div class="order-summary">
                <h3>Order Summary</h3>
                <table>
                <thead>
                    <tr>
                    <th>Item</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($currentOrders as $order): ?>
                    <tr>
                        <td><?= $order['id_item'] ?></td>
                        <td><?= $order['nama_status_pesanan'] ?></td>
                        <td><?= $order['tgl_kegiatan'] ?></td>
                        <td><?= $order['jam_delivery'] ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                </table>
                <hr>
                <p class="total">Total: Rp. 1,000,000</p>
            </div>

            <button class="checkout-btn" type="submit">Order</button>
        </form>
    </div>
</body>
</html>
