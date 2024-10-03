<?php
require_once('config.php');

if (isset($_POST['id_group']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['nama']) && isset($_POST['password'])) {
    $id_group = $_POST['id_group'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $nama = $_POST['nama'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Map user input to group ID
    $group_map = [
        'vendor' => 'GRUP1',
        'user'   => 'GRUP2',
        'operator'  => 'GRUP3',
        'supervisor' => 'GRUP4',
        'admin' => 'GRUP5',
        'superadmin' => 'GRUP6'
    ];

    // Assign the corresponding group ID
    if (array_key_exists($id_group, $group_map)) {
        $id_group = $group_map[$id_group];
    }

    // Prepare SQL query with placeholders
    $stmt = $conn->prepare("INSERT INTO m_account (id_group, email, username, nama, password) VALUES (?, ?, ?, ?, ?)");
    if ($stmt === false) {
        echo "Error preparing the statement: " . $conn->error;
        exit();
    }

    $stmt->bind_param("sssss", $id_group, $email, $username, $nama, $password);

    if ($stmt->execute()) {
        if ($id_group == 'GRUP1') {
            $result = $conn->query("SELECT MAX(CAST(SUBSTRING(id_vendor, 2) AS UNSIGNED)) AS max_id FROM m_vendor");
            $row = $result->fetch_assoc();
            $next_id = $row['max_id'] + 1;
            $id_vendor = 'V'. $next_id;

            $stmt_vendor = $conn->prepare("INSERT INTO m_vendor (id_vendor, username, nama_vendor) VALUES (?, ?, ?)");
            $stmt_vendor->bind_param("sss", $id_vendor, $username, $nama);

            if ($stmt_vendor->execute()) {
                echo "Vendor registration successful!";
            } else {
                echo "Error adding vendor details: " . $stmt_vendor->error;
            }

            $stmt_vendor->close();
        }

        echo "Registration successful!";
        header('Location: ../index.php');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
} else {
    echo "Please fill all fields.";
}

// Close the connection
$conn->close();
?>
