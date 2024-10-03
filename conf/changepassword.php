<?php
require_once('config.php'); // Database configuration

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if the email exists in the database
    $stmt = $conn->prepare("SELECT * FROM m_account WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // Email exists, generate a reset token
        $reset_token = bin2hex(random_bytes(32));
        $reset_token_expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        // Save token and expiry in the database
        $stmt = $conn->prepare("UPDATE m_account SET reset_token = ?, reset_token_expiry = ? WHERE email = ?");
        $stmt->bind_param("sss", $reset_token, $reset_token_expiry, $email);
        $stmt->execute();

        // Send email
        $reset_link = "http://yourdomain.com/reset-password.php?token=" . $reset_token;

        $subject = "Password Reset Request";
        $message = "Click the following link to reset your password: " . $reset_link . "\n\nNote: This link is valid for 1 hour.";
        $headers = "From: no-reply@yourdomain.com";

        if (mail($email, $subject, $message, $headers)) {
            echo "A password reset link has been sent to your email.";
        } else {
            echo "Failed to send the email.";
        }
    } else {
        echo "No account found with that email.";
    }

    $stmt->close();
}

$conn->close();
?>
