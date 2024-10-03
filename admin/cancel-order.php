<?php 
include '../conf/config.php';

if (isset($_POST['id'])) {
    $id_pesanan = $_POST['id'];

    // First, get the id_rincian_pesanan for the given id_pesanan
    $query = "SELECT id_rincian_pesanan FROM t_rincian_pesanan WHERE id_pesanan = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id_pesanan);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $rincian = $result->fetch_assoc();
        $id_rincian_pesanan = $rincian['id_rincian_pesanan'];

        // Now perform the updates using id_rincian_pesanan
        $sql1 = "UPDATE t_proses_rincian_pesanan SET id_status_proses = 'SPR10' WHERE id_rincian_pesanan = ?";
        $sql2 = "UPDATE t_pesanan SET id_status_pesanan = 'SPES3' WHERE id_pesanan = ?";
        
        $stmt1 = $conn->prepare($sql1);
        $stmt1->bind_param("i", $id_rincian_pesanan);
        
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bind_param("i", $id_pesanan);

        if ($stmt1->execute() && $stmt2->execute()) {
            $message = "Order has been successfully cancelled.";
        } else {
            $message = "Failed to cancel the order. Please try again.";
        }
    } else {
        $message = "No detail found for this order.";
    }
} else {
    $message = "No order ID provided.";
}

// Redirect back to the admin dashboard with a message
header("Location: admindashboard.php?message=" . urlencode($message));
exit;
?>
