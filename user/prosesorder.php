<?php
    session_start();

    if (!isset($_SESSION['username'])) {
        header('Location: ../index.php');
        exit();
    }

    // Cek apakah form disubmit
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $nama_kegiatan = $_POST['nama_kegiatan'] ?? '';
        $tgl_kegiatan = $_POST['tgl_kegiatan'] ?? '';
        $tempat_kegiatan = $_POST['id_ruangan'] ?? '';
        $kategori_makanan = $_POST['id_kategori_makanan'] ?? '';
        $jam_delivery = $_POST['jam_delivery'] ?? '';
        $jenis_penyajian = $_POST['jenis_penyajian'] ?? '';
        
        if (empty($namakegiatan) || empty($eventDate) || empty($tempatKegiatan) || empty($kategoriMakanan) || empty($jam) || empty($buffetType)) {
            die('Semua field harus diisi.');
        }
        
        // Proses upload file
        $uploadedFilePath = '';
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $fileTmpPath = $_FILES['attachment']['tmp_name'];
            $fileName = $_FILES['attachment']['name'];
            $fileSize = $_FILES['attachment']['size'];
            $fileType = $_FILES['attachment']['type'];
            $fileNameCmps = explode(".", $fileName);
            $fileExtension = strtolower(end($fileNameCmps));
            
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
            if (in_array($fileExtension, $allowedExtensions)) {
                // Generate nama unik untuk file
                $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
                $uploadFileDir = '../uploads/';
                $dest_path = $uploadFileDir . $newFileName;
                
                if (move_uploaded_file($fileTmpPath, $dest_path)) {
                    $uploadedFilePath = $dest_path;
                } else {
                    die('Error saat meng-upload file.');
                }
            } else {
                die('File type tidak diizinkan.');
            }
        }

        require_once('config.php');

        $stmt = $conn->prepare("INSERT INTO orders (user_id, namakegiatan, event_date, tempat_kegiatan, kategori_makanan, jam, buffet_type, attachment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssssss", $_SESSION['user_id'], $namakegiatan, $eventDate, $tempatKegiatan, $kategoriMakanan, $jam, $buffetType, $uploadedFilePath);

        if ($stmt->execute()) {
            echo "Pesanan berhasil diproses.";
        } else {
            echo "Terjadi kesalahan: " . $stmt->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        die('Invalid request method.');
    }
?>
