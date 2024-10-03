<?php
    session_start();

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    function getItemDetails($id_item) {
        global $conn;
        $stmt = $conn->prepare("SELECT nama_item, harga FROM m_item WHERE id_item = ?");
        $stmt->bind_param("i", $id_item);
        $stmt->execute();
        $stmt->bind_result($nama_item, $harga);
        if ($stmt->fetch()) {
            $stmt->close();
            return [
                'nama' => $nama_item,
                'harga' => $harga
            ];
        } else {
            $stmt->close();
            return null;
        }
    }
    
    
    // Remove item
    if (isset($_GET['remove'])) {
        $id_item = $_GET['remove'];
        if (isset($_SESSION['cart'][$id_item])) {
            unset($_SESSION['cart'][$id_item]);
        }
    }

    if (isset($_POST['update'])) {
        foreach ($_POST['quantity'] as $id_item => $quantity) {
            if ($quantity <= 0) {
                unset($_SESSION['cart'][$id_item]);
            } else {
                $_SESSION['cart'][$id_item] = $quantity;
            }
        }
    }

    // Total price
    $totalPrice = 0;
    foreach ($_SESSION['cart'] as $id_item => $quantity) {
        $item = getItemDetails($id_item);
        if ($item) {
            $totalPrice += $item['harga'] * $quantity;
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
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
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #333;
            color: #fff;
        }
        .total {
            font-size: 1.2em;
            font-weight: bold;
            margin-top: 20px;
        }
        .btn {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Shopping Cart</h1>
        <form method="post">
            <table>
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($_SESSION['cart'])): ?>
                        <?php foreach ($_SESSION['cart'] as $id_item => $quantity): ?>
                            <?php $item = getItemDetails($id_item); ?>
                            <?php if ($item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['nama_item']); ?></td>
                                    <td>$<?php echo number_format($item['harga'], 2); ?></td>
                                    <td>
                                        <input type="number" name="quantity[<?php echo $id_item; ?>]" value="<?php echo $quantity; ?>" min="0" style="width: 60px;">
                                    </td>
                                    <td>$<?php echo number_format($item['harga'] * $quantity, 2); ?></td>
                                    <td>
                                        <a href="shopping_cart.php?remove=<?php echo $id_item; ?>" class="btn">Remove</a>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Your cart is empty.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (!empty($_SESSION['cart'])): ?>
                <div class="total">
                    Total: $<?php echo number_format($totalPrice, 2); ?>
                </div>
                <button type="submit" name="update" class="btn">Update Cart</button>
                <a href="checkout.php" class="btn">Checkout</a>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>