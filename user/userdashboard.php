<?php
    require_once('userdb.php');

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #333;
            padding: 10px 20px;
            color: white;
        }
        .navbar h1 {
            margin: 0;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }
        .navbar a:hover {
            text-decoration: underline;
        }
        .navbar .cart-icon {
            font-size: 24px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .profile-section, .order-section, .search-section {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .profile-section h2, .order-section h2, .search-section h2 {
            margin-bottom: 10px;
            border-bottom: 2px solid #333;
            padding-bottom: 5px;
        }
        .search-bar input[type="text"] {
            width: calc(100% - 120px);
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        .search-bar button {
            padding: 10px;
            background-color: #333;
            color: #fff;
            border: none;
            font-size: 16px;
            border-radius: 4px;
            cursor: pointer;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #333;
            color: white;
        }

        .track-order-container {
            background-color: #fff;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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

    <div class="navbar">
        <h1>User Dashboard</h1>
        <a href="orderpage.php">Go to Order Page</a>
        <div class="cart-icon">
            <a href="cart.php">
                <i class="fas fa-shopping-cart"></i>
            </a>
        </div>
    </div>

    <div class="container">
        <div class="profile-section">
            <h2>User Profile</h2>
            <p><strong>Name:</strong> <span id="nama"><?= $nama ?></span></p>
            <p><strong>Username:</strong> <span id="username"><?= $username ?></span></p>
            <p><strong>Email:</strong> <span id="email"><?= $email ?></span></p>
            <button>Edit Profile</button>
        </div>

        <div class="search-section">
            <h2>Search for Vendors</h2>
            <div class="search-bar">
                <input type="text" placeholder="Search vendors...">
                <button type="button">Search</button>
            </div>
        </div>

        <div class="track-order-container">
            <h1>Track Your Order Here</h1>
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
                    <!-- Order items will be dynamically inserted here -->
                </div>
            </section>
        </div>

        <div class="order-section">
            <h2>Current Orders</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Status Pesanan</th>
                        <th>Tanggal Kegiatan</th>
                        <th>Jam Delivery</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($currentOrders as $order): ?>
                        <tr>
                            <td><?= $order['id_pesanan'] ?></td>
                            <td><?= $order['nama_status_pesanan'] ?></td>
                            <td><?= $order['tgl_kegiatan'] ?></td>
                            <td><?= $order['jam_delivery'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="order-section">
            <h2>Order History</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Status Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderHistory as $order): ?>
                        <tr>
                            <td><?= $order['id_pesanan'] ?></td>
                            <td><?= $order['nama_status_pesanan'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButtons = document.querySelectorAll('.filter-btn');
            const orderContainer = document.querySelector('.orders-list');
            const username = "<?php echo $_SESSION['username']; ?>";

            function fetchOrders(status = 'all') {
                fetch('../fetchorders.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username: username, status: status })
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


    <!-- Include FontAwesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
</body>
</html>
