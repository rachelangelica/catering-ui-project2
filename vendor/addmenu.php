<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Menu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }

        h1 {
            text-align: center;
            color: #333;
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
        }

        input, select, button {
            padding: 8px;
            font-size: 16px;
            border-radius: 4px;
            border: 1px solid #ccc;
            outline: none;
            transition: border-color 0.3s;
        }

        input:focus, select:focus {
            border-color: #007bff;
        }

        .item-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }

        .item-row label, .item-row input, .item-row select {
            grid-column: span 2;
        }

        @media (min-width: 600px) {
            .item-row label, .item-row input, .item-row select {
                grid-column: span 1;
            }
        }

        .add-item-btn, .submit-btn {
            display: block;
            width: 100%;
            padding: 10px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .add-item-btn:hover, .submit-btn:hover {
            background-color: #0056b3;
        }

        .add-item-btn {
            background-color: #28a745;
        }

        .add-item-btn:hover {
            background-color: #218838;
        }
    </style>

    <script>
        function addItem() {
            var container = document.getElementById('item-container');
            var index = container.children.length;

            var html = `<div class="item-row">

                            <div class="form-group">
                                <label for="nama_item_${index}">Nama Item:</label>
                                <input type="text" id="nama_item_${index}" name="nama_item[]" required>
                            </div>

                            <div class="form-group">
                                <label for="kategori_makanan_${index}">Kategori Makanan:</label>
                                <select id="kategori_makanan_${index}" name="kategori_makanan[]" required>
                                    <option value="makanpagi">Makan Pagi</option>
                                    <option value="snackpagi">Snack Pagi</option>
                                    <option value="makansiang">Makan Siang</option>
                                    <option value="snacksiang">Snack Siang</option>
                                    <option value="makanmalam">Makan Malam</option>
                                    <option value="snackmalam">Snack Malam</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="jenis_makanan_${index}">Jenis Makanan:</label>
                                <select id="jenis_makanan_${index}" name="jenis_makanan[]" required>
                                    <option value="nasi">Nasi</option>
                                    <option value="sayur">Sayur</option>
                                    <option value="prohe">Prohe</option>
                                    <option value="kerupuk">Kerupuk</option>
                                    <option value="sambal">Sambal</option>
                                    <option value="buah">Buah/Puding/Jus</option>
                                    <option value="asin">Asin</option>
                                    <option value="manis">Manis</option>
                                    <option value="keletikan">Keletikan</option>
                                    <option value="airmineral">Air Mineral</option>
                                    <option value="kopi">Kopi/Teh/Krimer</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="stok_${index}">Stok:</label>
                                <input type="number" id="stok_${index}" name="stok[]" min="0" required>
                            </div>

                            <div class="form-group">
                                <label for="harga_${index}">Harga:</label>
                                <input type="number" id="harga_${index}" name="harga[]" min="1" required>
                            </div>

                        </div>`;

            container.insertAdjacentHTML('beforeend', html);
        }
    </script>

</head>
<body>
    <h1>Add Items to Menu</h1>
    <form action="submit_menu.php" method="post">
        <div class="form-group">
            <label for="id_menu">ID Menu:</label>
            <input type="text" id="id_menu" name="id_menu" required>
        </div>

        <div class="form-group">
            <label for="batas_budget">Batas Budget:</label>
            <input type="number" id="batas_budget" name="batas_budget" min="0" required>
        </div>

        <div id="item-container">
            <!-- Items will be added here -->
        </div>

        <button type="button" class="add-item-btn" onclick="addItem()">Add Item</button><br>

        <button type="submit" class="submit-btn">Submit Menu</button>
    </form>
</body>
</html>
