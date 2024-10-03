<?php
require_once('vendordb.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vendor Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            margin-top: 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .orders-table, .menus-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        .btn {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn:hover {
            background-color: #555;
        }
        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }
        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }
        .dropdown-content a:hover {background-color: #f1f1f1}
        .dropdown:hover .dropdown-content {
            display: block;
        }
        /* Styles for the add menu form */
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

        .track-order-container {
            background-color: #fff;
            margin-bottom: 20px;
            border-radius: 10px;
        }

        .track-order-container h1 {
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }

        .order-search {
            display: flex;
            margin-bottom: 20px;
        }

        .order-search input[type="text"] {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
            margin-right: 10px;
        }

        .order-search button {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .order-search button:hover {
            background-color: #555;
        }

        .filter-buttons {
            display: flex;
            margin-bottom: 20px;
        }

        .filter-btn {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 10px;
        }

        .filter-btn:hover {
            background-color: #555;
        }

        .orders-list {
            margin-top: 20px;
        }

        .order-item {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
        }

        .order-header, .order-footer {
            margin-bottom: 10px;
        }

        .order-header span {
            display: block;
        }

        .order-body {
            display: flex;
            align-items: center;
        }

        .order-body img {
            max-width: 100px;
            margin-right: 10px;
        }

        .order-details h3 {
            margin: 0;
        }

        .details-btn {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .details-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Vendor Dashboard</h1>

        <!-- <div class="track-order-container">
            <h2>Track Order</h2>
            <div class="order-search">
                <input type="text" id="search-order-id" placeholder="Enter your order ID">
                <button type="button" id="search-btn">Track</button>
            </div>

            <section class="track-order">
                <div class="filter-buttons">
                    <button class="filter-btn" data-status="all">All</button>
                    <?php foreach ($statuses as $status): ?>
                        <button class="filter-btn" data-status="<?= htmlspecialchars($status['id_status_proses']) ?>">
                            <?= htmlspecialchars($status['nama_status_proses']) ?>
                        </button>
                    <?php endforeach; ?> 
                </div>
                <div class="orders-list" id="orders-list">
                   
                </div>
            </section>
        </div> -->

        <!-- Current Orders Section -->
        <div class="section">
            <h2>Current Orders</h2>
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Status</th>
                        <th>Order Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($orders) && is_array($orders)): ?>
                        <?php foreach ($orders as $order): ?>
                            <?php 
                                $currentStatusIndex = array_search($order['nama_status_proses'], $statusProcesses);
                                $nextStatus = $currentStatusIndex !== false && $currentStatusIndex < count($statusProcesses) - 1 ? $statusProcesses[$currentStatusIndex + 1] : null;
                            ?>
                            <tr>
                                <td><?php echo htmlspecialchars($order['id_pesanan']); ?></td>
                                <td><?php echo htmlspecialchars($order['nama_status_proses']); ?></td>
                                <td><?php echo htmlspecialchars($order['nama_pembayaran']); ?></td>
                                <td>
                                    <?php if ($nextStatus): ?>
                                        <form method="post" style="display: inline;">
                                            <input type="hidden" name="id_pesanan" value="<?php echo htmlspecialchars($order['id_pesanan']); ?>">
                                            <input type="hidden" name="nama_status_proses" value="<?php echo htmlspecialchars($nextStatus); ?>">
                                            <button type="submit" name="update_status" class="btn">Set to <?php echo htmlspecialchars($nextStatus); ?></button>
                                        </form>
                                    <?php else: ?>
                                        <span>Final Status</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4">No orders found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Menus List Section -->
        <div class="section">
            <h2>Menus List</h2>
            <?php if (isset($menus) && is_array($menus)): ?>
                <?php foreach ($menus as $menu): ?>
                    <div style="margin-bottom: 20px;">
                        <h3><?php echo htmlspecialchars($menu['nama_menu']); ?></h3>
                        <button class="btn" onclick="toggleItems(<?php echo $menu['id_menu']; ?>)">Toggle Items</button>
                        <div id="menu-items-<?php echo $menu['id_menu']; ?>" style="display: none;">
                            <?php $items = getMenuItems($menu['id_menu']); ?>
                            <?php if ($items): ?>
                                <table class="menus-table">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($items as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['item_name']); ?></td>
                                                <td>$<?php echo number_format($item['price'], 2); ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php else: ?>
                                <p>No items found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No menus found.</p>
            <?php endif; ?>
        </div>
        
        <!-- Add Menu, Submenu, and Item Section -->
        <div class="section">
            <h2>Add Menu, Submenu, and Item</h2>
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
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const orderContainer = document.querySelector('.orders-list');
            const id_vendor = "<?php echo $_SESSION['id_vendor']; ?>";

            function fetchOrders(status = 'all') {
                fetch('../fetchorders.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id_vendor: id_vendor, status: status })
                })
                .then(response => response.json())
                .then(orders => {
                    orderContainer.innerHTML = ''; 
                    if (orders.length === 0) {
                        orderContainer.innerHTML = '<p>No orders found.</p>';
                    } else {
                        orders.forEach(order => {
                            orderContainer.innerHTML += `
                                <div class="order-item" data-status="${order.nama_status_proses}">
                                    <div class="order-header">
                                        <span>Order ID: ${order.id_pesanan}</span>
                                        <span>Menu: ${order.nama_menu}</span>
                                        <span>Status: ${order.nama_status_proses}</span>
                                    </div>
                                </div>`;
                        });
                    }
                })
                .catch(error => console.error('Error:', error));
            }

            // Default fetch for all orders when the page loads
            fetchOrders();

            // Filter orders by status when a filter button is clicked
            filterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    const status = button.getAttribute('data-status');
                    fetchOrders(status);
                });
            });
        });
    </script>



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

        function toggleItems(menuId) {
            var itemsDiv = document.getElementById('menu-items-' + menuId);
            if (itemsDiv.style.display === 'none') {
                itemsDiv.style.display = 'block';
            } else {
                itemsDiv.style.display = 'none';
            }
        }
    </script>
</body>
</html>
