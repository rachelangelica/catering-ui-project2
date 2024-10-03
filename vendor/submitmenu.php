<?php
    require_once('config.php'); 

    $id_menu = $_POST['id_menu'];
    $id_vendor = $_POST['id_vendor'];
    $status = 'active';
    $batas_budget = $_POST['batas_budget'];

    // Insert into m_menu
    $sql_menu = "INSERT INTO m_menu (id_menu, id_vendor, id_kategori_makanan, status, batas_budget)
                VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql_menu);
    $stmt->bind_param("sssss", $id_menu, $id_vendor, $kategori_makanan, $status, $batas_budget);
    $stmt->execute();

    // Insert into m_sub_menu
    $id_sub_menu = $_POST['id_sub_menu'];
    $id_item = $_POST['id_item'];
    $id_jenis_makanan = $_POST['id_jenis_makanan'];

    foreach ($id_item as $index => $item) {
        $sql_sub_menu = "INSERT INTO m_sub_menu (id_menu, id_item, id_vendor, id_jenis_makanan)
                        VALUES (?, ?, ?, ?)";
        $stmt_sub_menu = $conn->prepare($sql_sub_menu);
        $stmt_sub_menu->bind_param("ssss", $id_menu, $item, $id_vendor, $id_jenis_makanan[$index]);
        $stmt_sub_menu->execute();
    }

    // Insert into m_item
    $nama_item = $_POST['nama_item'];
    $stok = $_POST['stok'];
    $harga = $_POST['harga'];
    $id_kategori_makanan_i = $_POST['id_kategori_makanan_i'];
    $id_vendor_i = $_POST['id_vendor_i'];

    foreach ($nama_item as $index => $item_name) {
        $sql_item = "INSERT INTO m_item (id_vendor, id_item, id_kategori_makanan, nama_item, stok, harga)
                    VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);
        $stmt_item->bind_param("ssssss", $id_vendor_i[$index], $id_item[$index], $id_kategori_makanan_i[$index], $item_name, $stok[$index], $harga[$index]);
        $stmt_item->execute();
    }

    // Insert into m_kategori_makanan
    $id_kategori_makanan_k = $_POST['id_kategori_makanan_k'];
    $nama_kategori_makanan = $_POST['nama_kategori_makanan'];

    foreach ($id_kategori_makanan_k as $index => $kategori_id) {
        $sql_kategori = "INSERT IGNORE INTO m_kategori_makanan (id_kategori_makanan, nama_kategori_makanan)
                        VALUES (?, ?)";
        $stmt_kategori = $conn->prepare($sql_kategori);
        $stmt_kategori->bind_param("ss", $kategori_id, $nama_kategori_makanan[$index]);
        $stmt_kategori->execute();
    }

    // Insert into m_jenis_makanan
    $id_jenis_makanan = $_POST['id_jenis_makanan'];
    $nama_jenis_makanan = $_POST['nama_jenis_makanan'];

    foreach ($id_jenis_makanan as $index => $jenis_id) {
        $sql_jenis = "INSERT IGNORE INTO m_jenis_makanan (id_jenis_makanan, nama_jenis_makanan)
                    VALUES (?, ?)";
        $stmt_jenis = $conn->prepare($sql_jenis);
        $stmt_jenis->bind_param("ss", $jenis_id, $nama_jenis_makanan[$index]);
        $stmt_jenis->execute();
    }

    echo "Data successfully inserted!";

    $stmt->close();
    $conn->close();
?>
