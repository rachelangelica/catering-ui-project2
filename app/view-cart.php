<?php
// Simulasi data produk di keranjang
$cartItems = [
    ["name" => "Laptop", "price" => 1200, "time" => "2 mins ago"],
    ["name" => "Smartphone", "price" => 800, "time" => "1 hour ago"],
    ["name" => "Headphones", "price" => 200, "time" => "3 hours ago"],
    ["name" => "Tablet", "price" => 500, "time" => "4 hours ago"],
    ["name" => "Camera", "price" => 600, "time" => "1 day ago"],
];

$cartItemCount = count($cartItems); // Jumlah item di keranjang
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cart Page</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h1>Cart Items</h1>
        <?php if ($cartItemCount > 0): ?>
            <ul class="list-group">
                <?php foreach ($cartItems as $item): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1"><?= htmlspecialchars($item['name']) ?></h5>
                            <p class="mb-1">Price: $<?= htmlspecialchars($item['price']) ?></p>
                            <small class="text-muted"><?= htmlspecialchars($item['time']) ?></small>
                        </div>
                        <span class="badge badge-primary badge-pill">$<?= htmlspecialchars($item['price']) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>No items in the cart.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-3 mt-4">
        <div class="container">
            <p class="mb-0">&copy; 2024 Your Company. All rights reserved.</p>
            <p class="mb-0">
                <a href="#" class="text-white">Privacy Policy</a> | 
                <a href="#" class="text-white">Terms of Service</a>
            </p>
        </div>
    </footer>

    <!-- Bootstrap JS (untuk fungsionalitas jika diperlukan) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
