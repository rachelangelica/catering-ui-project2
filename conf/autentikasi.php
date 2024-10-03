<?php
require_once('config.php');

// Check if form data is set
if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Prepare SQL query with placeholders
    $stmt = $conn->prepare("SELECT password, id_group FROM m_account WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($hashed_password, $id_group);

    if ($stmt->num_rows > 0) {
        $stmt->fetch();

        // Verify the password
        if (password_verify($password, $hashed_password)) {
            // Start session and set session variables here
            session_start();
            $_SESSION['username'] = $username;
            $_SESSION['id_group'] = $id_group;

            // Redirect based on the id_group
            switch ($id_group) {
                case 'GRUP1':
                    header('Location: ../vendor/vendordashboard.php');
                    break;
                case 'GRUP2':
                    header('Location: ../user/userdashboard.php');
                    break;
                case 'GRUP3':
                    header('Location: ../operator/operatordashboard.php');
                    break;
                case 'GRUP4':
                    header('Location: ../supervisor/supervisordash1.php');
                    break;
                case 'GRUP5':
                    header('Location: ../admin/admindashboard.php');
                    break;
                case 'GRUP6':
                    header('Location: ../superadmin/dashboard.php');
                    break;
                default:
                    header('Location: ../app');
            }
            exit();

        } else {
            header('Location: ../index.php?error=1');
            exit();
        }
    } else {
        header('Location: ../index.php?error=1');
        exit();
    }
} else {
    header('Location: ../index.php?error=1');
    exit();
}


