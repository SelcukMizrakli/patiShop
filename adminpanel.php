<?php
// Bu kısmı dosyanın en üstünde, çıktı başlamadan önce tutun
session_start();
include 'ayar.php';

// Oturum kontrolü
if (!isset($_SESSION['uyeYetki']) || $_SESSION['uyeYetki'] < 1) {
    echo "<script>window.location.href='index.php';</script>";
    exit;
}

if (isset($_GET['id'])) {
    $kategoriID = (int)$_GET['id'];

    $sql = "SELECT k.*, ht.hayvanTurAdi 
            FROM t_kategori k
            LEFT JOIN t_hayvanturleri ht ON k.kategoriHayvanTurID = ht.hayvanTurID
            WHERE k.kategoriID = ?";

    $stmt = $baglan->prepare($sql);
    $stmt->bind_param("i", $kategoriID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        header('Content-Type: application/json');
        echo json_encode($row);
    } else {
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Kategori bulunamadı']);
    }
    exit; // Önemli: Diğer HTML çıktısını engellemek için
}

// Ürün Silme
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $urunID = intval($_GET['delete']);

    try {
        // Transaction başlat
        $baglan->begin_transaction();

        // 1. Önce resim ilişkilerini sil
        $baglan->query("DELETE FROM t_resimiliskiler WHERE resimIliskilerEklenenID = $urunID");

        // 2. Ürün detaylarını silmeden önce stok ID'sini al
        $stokIDResult = $baglan->query("SELECT urunDStokID FROM t_urundetay WHERE urunDurunID = $urunID");
        $stokID = null;
        if ($stokIDResult && $row = $stokIDResult->fetch_assoc()) {
            $stokID = $row['urunDStokID'];
        }

        // 3. Önce ürün detayını sil (çünkü stok tablosuna referans veriyor)
        $baglan->query("DELETE FROM t_urundetay WHERE urunDurunID = $urunID");

        // 4. Şimdi stok kaydını silebiliriz
        if ($stokID) {
            $baglan->query("DELETE FROM t_stok WHERE stokID = $stokID");
        }

        // 5. Son olarak ana ürün kaydını sil
        $baglan->query("DELETE FROM t_urunler WHERE urunID = $urunID");

        // İşlemi onayla
        $baglan->commit();

        echo "<script>
            alert('Ürün başarıyla silindi!');
            window.location.href='adminpanel.php';
        </script>";
        exit;
    } catch (Exception $e) {
        // Hata durumunda değişiklikleri geri al
        $baglan->rollback();

        echo "<script>
            alert('Hata oluştu: " . $e->getMessage() . "');
            window.location.href='adminpanel.php';
        </script>";
        exit;
    }
}

// Ürün Ekleme
if (isset($_POST['urunEkle'])) {
    // Form verilerini güvenli şekilde al
    $urunAdi = $baglan->real_escape_string($_POST['urunAdi']);
    $urunKategoriID = (int)$_POST['urunKategoriID'];
    $urunFiyat = (float)$_POST['urunFiyat'];
    $hayvanTurID = (int)$_POST['hayvanTurID'];
    $stokMiktar = (int)$_POST['stokMiktar'];
    $urunAciklama = $baglan->real_escape_string($_POST['urunAciklama']);

    try {
        // Transaction başlat
        $baglan->begin_transaction();

        // 1. Ürünü ekle
        $sql = "INSERT INTO t_urunler (urunAdi, urunKategoriID, urunFiyat, urunKayitTarih) 
                VALUES (?, ?, ?, NOW())";
        $stmt = $baglan->prepare($sql);
        $stmt->bind_param("sid", $urunAdi, $urunKategoriID, $urunFiyat);
        $stmt->execute();
        $urunID = $baglan->insert_id;

        // 2. Stok ekle
        $sql = "INSERT INTO t_stok (stokUrunID, stokMiktar, stokGirisTarih) 
                VALUES (?, ?, NOW())";
        $stmt = $baglan->prepare($sql);
        $stmt->bind_param("ii", $urunID, $stokMiktar);
        $stmt->execute();
        $stokID = $baglan->insert_id;

        // 3. Ürün detay ekle
        $sql = "INSERT INTO t_urundetay (urunDurunID, urunDHayvanTurID, urunDAciklama, urunDStokID) 
                VALUES (?, ?, ?, ?)";
        $stmt = $baglan->prepare($sql);
        $stmt->bind_param("iisi", $urunID, $hayvanTurID, $urunAciklama, $stokID);
        $stmt->execute();

        // 4. Resimleri ekle
        if (isset($_FILES['urunResim']) && count($_FILES['urunResim']['name']) > 0) {
            foreach ($_FILES['urunResim']['tmp_name'] as $i => $tmp_name) {
                if ($_FILES['urunResim']['error'][$i] === UPLOAD_ERR_OK) {
                    $dosyaAdi = uniqid('urun_') . '_' . basename($_FILES['urunResim']['name'][$i]);
                    $hedefYol = 'resim/' . $dosyaAdi;

                    if (move_uploaded_file($tmp_name, $hedefYol)) {
                        // Resmi t_resimler tablosuna ekle
                        $sql = "INSERT INTO t_resimler (resimYolu, resimEklenmeTarih) VALUES (?, NOW())";
                        $stmt = $baglan->prepare($sql);
                        $stmt->bind_param("s", $hedefYol);
                        $stmt->execute();
                        $resimID = $baglan->insert_id;

                        // Resim ilişkisini t_resimiliskiler tablosuna ekle
                        $sql = "INSERT INTO t_resimiliskiler (resimIliskilerResimID, resimIliskilerEklenenID) 
                                VALUES (?, ?)";
                        $stmt = $baglan->prepare($sql);
                        $stmt->bind_param("ii", $resimID, $urunID);
                        $stmt->execute();
                    }
                }
            }
        }

        // Tüm işlemler başarılı, değişiklikleri kaydet
        $baglan->commit();

        echo "<script>
            alert('Ürün başarıyla eklendi!');
            window.location.href='adminpanel.php';
        </script>";
        exit;
    } catch (Exception $e) {
        // Hata durumunda değişiklikleri geri al
        $baglan->rollback();

        echo "<script>
            alert('Hata oluştu: " . $e->getMessage() . "');
            window.location.href='adminpanel.php';
        </script>";
        exit;
    }
}

// Ürün Güncelleme
if (isset($_POST['urunGuncelle'])) {
    $urunID = (int)$_POST['urunID'];
    $urunAdi = $baglan->real_escape_string($_POST['urunAdi']);
    $urunKategoriID = (int)$_POST['urunKategoriID'];
    $urunFiyat = (float)$_POST['urunFiyat'];
    $hayvanTurID = (int)$_POST['hayvanTurID'];
    $stokMiktar = (int)$_POST['stokMiktar'];
    $urunAciklama = $baglan->real_escape_string($_POST['urunAciklama']);

    // Ürünü güncelle
    $baglan->query("UPDATE t_urunler SET urunAdi='$urunAdi', urunKategoriID=$urunKategoriID, urunFiyat=$urunFiyat, urunGuncellemeTarih=NOW() WHERE urunID=$urunID");

    // Ürün detay ve stok ID'sini bul
    $detay = $baglan->query("SELECT urunDStokID FROM t_urundetay WHERE urunDurunID=$urunID")->fetch_assoc();
    $stokID = $detay['urunDStokID'];

    // Stok güncelle
    $baglan->query("UPDATE t_stok SET stokMiktar=$stokMiktar WHERE stokID=$stokID");

    // Ürün detay güncelle
    $baglan->query("UPDATE t_urundetay SET urunDHayvanTurID=$hayvanTurID, urunDAciklama='$urunAciklama' WHERE urunDurunID=$urunID");

    echo "<script>window.location.href='adminpanel.php';</script>";
    exit;
}

// Yetki güncelleme işlemi
if (isset($_POST['yetkiGuncelle'])) {
    $uyeID = (int)$_POST['uyeID'];
    $yeniYetki = (int)$_POST['yeniYetki'];
    $baglan->query("UPDATE t_uyeler SET uyeYetki = $yeniYetki WHERE uyeID = $uyeID");
    echo "<script>window.location.href='adminpanel.php';</script>";
    exit;
}

if (isset($_POST['yeniKategoriEkle'])) {
    $kategoriAdi = $baglan->real_escape_string($_POST['yeniKategoriAdi']);
    $hayvanTurID = (int)$_POST['yeniKategoriHayvanTurID'];

    // kategoriSlug oluştur (Türkçe karakterleri değiştir ve boşlukları tire ile değiştir)
    $kategoriSlug = str_replace(
        ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç', ' '],
        ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c', '-'],
        mb_strtolower($kategoriAdi, 'UTF-8')
    );

    // Ana kategori olarak ekle
    $baglan->query("INSERT INTO t_kategori (kategoriAdi, kategoriSlug, kategoriAciklama, kategoriHayvanTurID, kategoriParentID) 
                    VALUES ('$kategoriAdi', '$kategoriSlug', '$kategoriAdi', $hayvanTurID, NULL)");

    echo "<script>window.location.href='adminpanel.php';</script>";
    exit;
}
if (isset($_POST['yeniTurEkle'])) {
    $yeniTur = $baglan->real_escape_string($_POST['yeniTurAdi']);
    $baglan->query("INSERT INTO t_hayvanturleri (hayvanTurAdi, hayvanTurSlug, hayvanTurOlusturmaTarih) VALUES ('$yeniTur', '$yeniTur', NOW())");
    echo "<script>window.location.href='adminpanel.php';</script>";
    exit;
}

// Kategori güncelleme işlemi için PHP kodu (en üstteki işlem bloklarına ekleyin)
if (isset($_POST['kategoriGuncelle'])) {
    $kategoriID = (int)$_POST['kategoriID'];
    $kategoriAdi = $baglan->real_escape_string($_POST['kategoriAdi']);
    $kategoriAciklama = $baglan->real_escape_string($_POST['kategoriAciklama']);
    $hayvanTurID = (int)$_POST['kategoriHayvanTurID'];

    // Slug oluştur
    $kategoriSlug = strtolower(
        str_replace(
            ['ı', 'ğ', 'ü', 'ş', 'ö', 'ç', 'İ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç', ' ', 'I'],
            ['i', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c', '-', 'i'],
            $kategoriAdi
        )
    );

    // Resim yükleme işlemi
    if (isset($_FILES['kategoriIkon']) && $_FILES['kategoriIkon']['error'] === UPLOAD_ERR_OK) {
        $dosyaAdi = uniqid('kategori_') . '_' . basename($_FILES['kategoriIkon']['name']);
        $hedefYol = 'resim/kategori/' . $dosyaAdi;

        // Klasör yoksa oluştur
        if (!file_exists('resim/kategori')) {
            mkdir('resim/kategori', 0777, true);
        }

        if (move_uploaded_file($_FILES['kategoriIkon']['tmp_name'], $hedefYol)) {
            // Resim başarıyla yüklendiyse, veritabanını güncelle
            $sql = "UPDATE t_kategori SET 
                    kategoriAdi = ?, 
                    kategoriSlug = ?,
                    kategoriAciklama = ?,
                    kategoriHayvanTurID = ?,
                    kategoriIkonUrl = ?
                    WHERE kategoriID = ?";

            $stmt = $baglan->prepare($sql);
            $stmt->bind_param("sssisi", $kategoriAdi, $kategoriSlug, $kategoriAciklama, $hayvanTurID, $hedefYol, $kategoriID);
        }
    } else {
        // Resim yüklenmediyse, diğer bilgileri güncelle
        $sql = "UPDATE t_kategori SET 
                kategoriAdi = ?, 
                kategoriSlug = ?,
                kategoriAciklama = ?,
                kategoriHayvanTurID = ?
                WHERE kategoriID = ?";

        $stmt = $baglan->prepare($sql);
        $stmt->bind_param("sssii", $kategoriAdi, $kategoriSlug, $kategoriAciklama, $hayvanTurID, $kategoriID);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Kategori başarıyla güncellendi!'); window.location.href='adminpanel.php';</script>";
    } else {
        echo "<script>alert('Hata oluştu!'); window.location.href='adminpanel.php';</script>";
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiShop - Admin Paneli</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/material_green.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/tr.js"></script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #4CAF50;
            --primary-dark: #388E3C;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
            --text-dark: #333;
        }

        body {
            background-color: #f8f9fa;
        }

        .header {
            background-color: var(--primary);
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        .container {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .sidebar {
            width: 250px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-header img {
            width: 40px;
            margin-right: 10px;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s;
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
        }

        .menu-item:hover,
        .menu-item.active {
            background-color: var(--light-gray);
            border-left: 4px solid var(--primary);
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn:hover {
            background-color: var(--primary-dark);
        }

        .flatpickr-input {
            background-color: white !important;
        }

        .flatpickr-calendar {
            box-shadow: 0 3px 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        .flatpickr-day.selected {
            background: var(--primary) !important;
            border-color: var(--primary) !important;
        }

        .flatpickr-day:hover {
            background: var(--light-gray);
        }

        .flatpickr-current-month {
            padding: 8px 0 0 0;
            font-size: 115%;
        }

        .flatpickr-monthDropdown-months {
            font-weight: 500;
        }

        .card {
            width: 100%;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            background-color: #f1f1f1;
            border-bottom: 1px solid var(--border-color);
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: #f8f9fa;
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-warning {
            background-color: #FFC107;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        /* Arama kutusu stilleri */
        #kullaniciArama {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        #kullaniciArama:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Tablo satır hover efekti */
        #kullanicilarTable tbody tr:hover {
            background-color: var(--light-gray);
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(76, 175, 80, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            font-size: 24px;
        }

        .stat-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-info span {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea.form-control {
            min-height: 100px;
        }

        .tab-container {
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border-color);
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            border-bottom: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 500;
        }

        .tab-content {
            padding: 20px 0;
        }

        .product-image-preview {
            width: 120px;
            height: 120px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .product-image-preview img {
            max-width: 100%;
            max-height: 100%;
        }

        .image-upload-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #f1f1f1;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        /* İkonlar için basit tanımlar */
        .fa {
            font-family: Arial;
            font-weight: bold;
            font-style: normal;
        }

        /* Modal arka plan */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Modal içeriği */
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Kapatma butonu */
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }

        .close:hover {
            color: red;
        }

        .menu-item.active {
            background-color: var(--light-gray);
            border-left: 4px solid var(--primary);
        }

        .section {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        /* Ürün filtreleme stilleri */
        .form-control {
            padding: 8px 12px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        select.form-control {
            cursor: pointer;
            background-color: white;
        }

        select.form-control:hover {
            border-color: var(--primary);
        }

        /* Tablo satır hover efekti */
        #urunlerTable tbody tr:hover {
            background-color: var(--light-gray);
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h2>PatiShop Yönetim Paneli</h2>
    </div>

    <!-- Ana Container -->
    <div class="container">
        <!-- Sidebar -->
        <?php
        // En üstte oturum kontrolü yap
        if (!isset($_SESSION['uyeYetki']) || $_SESSION['uyeYetki'] < 1) {
            header("Location: index.php");
            exit;
        }
        ?>

        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>PatiShop Admin</h3>
            </div>

            <!-- Sabit Menü Öğeleri -->
            <a href="anasayfa.php" class="menu-item">
                <i class="fa">🏘️</i> Anasayfa
            </a>

            <?php if ($_SESSION['uyeYetki'] >= 2) { // Sadece admin için göster 
            ?>
                <a href="#dashboard" class="menu-item" onclick="showSection('dashboard')">
                    <i class="fa">🏠</i> Dashboard
                </a>
                <a href="#siparisler" class="menu-item" onclick="showSection('siparisler')">
                    <i class="fa">🛒</i> Siparişler
                </a>
                <a href="#kullanicilar" class="menu-item" onclick="showSection('kullanicilar')">
                    <i class="fa">👥</i> Kullanıcılar
                </a>
                <a href="#kategoriler" class="menu-item" onclick="showSection('kategoriler')">
                    <i class="fa">🔖</i> Kategoriler
                </a>
            <?php } ?>

            <!-- Çalışan ve admin için göster -->
            <a href="#urunler" class="menu-item" onclick="showSection('urunler')">
                <i class="fa">📦</i> Ürünler
            </a>
            <a href="cikisYap.php" class="menu-item">
                <i class="fa">🚪</i> Çıkış Yap
            </a>
        </div>

        <!-- İçerik Alanı -->
        <div class="content">
            <!-- Dashboard Section -->
            <div id="dashboard" class="section">
                <div class="content-header">
                    <h2>Dashboard</h2>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h3>Admin Paneli Kullanım Kılavuzu</h3>
                    </div>
                    <div class="card-body">
                        <div style="line-height: 1.6;">
                            <h4 style="color: #4CAF50; margin-bottom: 10px;">Hoş Geldiniz!</h4>
                            <p style="margin-bottom: 20px;">Bu yönetim panelinde aşağıdaki işlemleri gerçekleştirebilirsiniz:</p>

                            <div style="margin-bottom: 15px;">
                                <strong style="color: #4CAF50;">🛒 Siparişler</strong>
                                <ul style="margin-left: 20px; margin-top: 5px;">
                                    <li>Tüm siparişleri görüntüleme</li>
                                    <li>Sipariş numarasına göre arama yapma</li>
                                    <li>Tarih aralığına göre filtreleme</li>
                                    <li>Sipariş detaylarını inceleme</li>
                                </ul>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <strong style="color: #4CAF50;">👥 Kullanıcı Yönetimi</strong>
                                <ul style="margin-left: 20px; margin-top: 5px;">
                                    <li>Kullanıcı listesini görüntüleme</li>
                                    <li>Kullanıcı yetkilerini güncelleme</li>
                                    <li>Müşteri, çalışan ve admin rollerini atama</li>
                                </ul>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <strong style="color: #4CAF50;">📦 Ürün Yönetimi</strong>
                                <ul style="margin-left: 20px; margin-top: 5px;">
                                    <li>Yeni ürün ekleme</li>
                                    <li>Mevcut ürünleri düzenleme</li>
                                    <li>Ürün silme</li>
                                    <li>Stok takibi yapma</li>
                                    <li>Ürün fiyatlarını güncelleme</li>
                                </ul>
                            </div>

                            <div style="margin-bottom: 15px;">
                                <strong style="color: #4CAF50;">🔖 Kategori Yönetimi</strong>
                                <ul style="margin-left: 20px; margin-top: 5px;">
                                    <li>Yeni kategori ekleme</li>
                                    <li>Kategori düzenleme</li>
                                    <li>Kategori ikonlarını güncelleme</li>
                                    <li>Hayvan türlerine göre kategorileri yönetme</li>
                                </ul>
                            </div>

                            <p style="margin-top: 20px; padding: 10px; background-color: #f8f9fa; border-radius: 4px;">
                                <strong>Not:</strong> Tüm değişiklikler anında sisteme yansıyacaktır. Lütfen işlemlerinizi dikkatli bir şekilde gerçekleştiriniz.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Siparişler Section -->
            <div id="siparisler" class="section" style="display: none;">
                <div class="content-header">
                    <h2>Siparişler</h2>
                </div>
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3>Tüm Siparişler</h3>
                            <div style="display: flex; gap: 10px;">
                                <input type="text" id="siparisArama" class="form-control"
                                    placeholder="Sipariş No Ara..." style="width: 200px;"
                                    onkeyup="siparisleriFiltrele()">

                                <div style="display: flex; align-items: center; gap: 5px;">
                                    <input type="text" id="baslangicTarih" class="form-control datepicker"
                                        placeholder="Başlangıç Tarihi" style="width: 150px;">
                                    <span style="color: #666;">-</span>
                                    <input type="text" id="bitisTarih" class="form-control datepicker"
                                        placeholder="Bitiş Tarihi" style="width: 150px;">
                                </div>

                                <button class="btn btn-sm" onclick="filtreleriSifirla()"
                                    style="display: flex; align-items: center; gap: 5px;">
                                    <i class="fa">🔄</i> Sıfırla
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="siparislerTable">
                            <thead>
                                <tr>
                                    <th>Sipariş No</th>
                                    <th>Müşteri</th>
                                    <th>Tarih</th>
                                    <th>Tutar</th>
                                    <th>Durum</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT s.siparisID, u.uyeAd, u.uyeSoyad, s.siparisOdemeTarih, 
                                        s.siparisSepetID, s.siparisDurum
                                        FROM t_siparis s
                                        INNER JOIN t_uyeler u ON s.siparisUyeID = u.uyeID
                                        ORDER BY s.siparisOdemeTarih DESC";
                                $result = $baglan->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    // SepetID'lerden toplam tutarı hesapla
                                    $sepetIDs = array_filter(explode(',', $row['siparisSepetID']));
                                    $toplamTutar = 0;
                                    if (!empty($sepetIDs)) {
                                        $sepetIDString = implode(',', array_map('intval', $sepetIDs));
                                        $sqlTutar = "SELECT SUM(sepetUrunFiyat * sepetUrunMiktar) as toplam 
                                                   FROM t_sepet WHERE sepetID IN ($sepetIDString)";
                                        $resTutar = $baglan->query($sqlTutar);
                                        if ($resTutar && $resTutar->num_rows > 0) {
                                            $toplamTutar = $resTutar->fetch_assoc()['toplam'];
                                        }
                                    }
                                    $durum = ($row['siparisDurum'] == 0) ? "Hazırlanıyor" : (($row['siparisDurum'] == 1) ? "Kargoya Verildi" : "Teslim Edildi");

                                    echo '<tr data-siparis-no="ORD-' . $row['siparisID'] . '" 
                                              data-tarih="' . $row['siparisOdemeTarih'] . '">';
                                    echo '<td>#ORD-' . $row['siparisID'] . '</td>';
                                    echo '<td>' . $row['uyeAd'] . ' ' . $row['uyeSoyad'] . '</td>';
                                    echo '<td>' . $row['siparisOdemeTarih'] . '</td>';
                                    echo '<td>₺' . number_format($toplamTutar, 2) . '</td>';
                                    echo '<td>' . $durum . '</td>';
                                    echo '<td>
                                            <a href="siparisDetay.php?siparisID=' . $row['siparisID'] . '" 
                                               class="btn btn-sm">Görüntüle</a>
                                         </td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Kullanıcılar Section -->
            <div id="kullanicilar" class="section" style="display: none;">
                <div class="content-header">
                    <h2>Kullanıcı Yönetimi</h2>
                </div>
                <!-- Kullanıcı Yetkileri Yönetimi Card'ı -->
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3>Kullanıcı Yetkileri</h3>
                            <div style="display: flex; gap: 10px;">
                                <input type="text" id="kullaniciArama"
                                    class="form-control"
                                    placeholder="ID veya isim ile ara..."
                                    style="width: 250px;"
                                    onkeyup="kullanicilariFiltrele()">
                                <button class="btn btn-sm" onclick="filtreleriSifirlaKullanici()">
                                    <i class="fa">🔄</i> Sıfırla
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="kullanicilarTable">
                            <thead>
                                <tr>
                                    <th>Kullanıcı ID</th>
                                    <th>Ad Soyad</th>
                                    <th>E-posta</th>
                                    <th>Rol</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT uyeID, uyeAd, uyeSoyad, uyeMail, uyeYetki FROM t_uyeler";
                                $result = $baglan->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    $rol = ($row['uyeYetki'] == 2) ? "Admin" : (($row['uyeYetki'] == 1) ? "Çalışan" : "Müşteri");
                                    echo '<tr data-user-id="USR-' . $row['uyeID'] . '" 
                                             data-user-name="' . htmlspecialchars($row['uyeAd'] . ' ' . $row['uyeSoyad']) . '">';
                                    echo '<td>USR-' . $row['uyeID'] . '</td>';
                                    echo '<td>' . htmlspecialchars($row['uyeAd'] . ' ' . $row['uyeSoyad']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['uyeMail']) . '</td>';
                                    echo '<td>' . $rol . '</td>';
                                    echo '<td>
                                        <form method="post" style="display:inline;">
                                            <input type="hidden" name="uyeID" value="' . $row['uyeID'] . '">
                                            <select name="yeniYetki" class="form-control" style="width: auto; display: inline-block; margin-right: 10px;">
                                                <option value="0"' . ($row['uyeYetki'] == 0 ? ' selected' : '') . '>Müşteri</option>
                                                <option value="1"' . ($row['uyeYetki'] == 1 ? ' selected' : '') . '>Çalışan</option>
                                                <option value="2"' . ($row['uyeYetki'] == 2 ? ' selected' : '') . '>Admin</option>
                                            </select>
                                            <button type="submit" name="yetkiGuncelle" class="btn btn-sm btn-warning">Kaydet</button>
                                        </form>
                                    </td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Ürünler Section -->
            <div id="urunler" class="section" style="display: none;">
                <div class="content-header">
                    <h2>Ürün Yönetimi</h2>
                    <div>
                        <button class="btn" onclick="openNewProductModal()">
                            <i class="fa">➕</i> Yeni Ürün Ekle
                        </button>
                    </div>
                </div>
                <!-- Ürün Yönetimi Tab Container -->
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3>Ürün Yönetimi</h3>
                            <div style="display: flex; gap: 10px;">
                                <input type="text" id="urunArama"
                                    class="form-control"
                                    placeholder="Ürün adı ara..."
                                    style="width: 200px;"
                                    onkeyup="urunleriFiltrele()">

                                <select id="kategoriFiltre"
                                    class="form-control"
                                    style="width: 150px;"
                                    onchange="urunleriFiltrele()">
                                    <option value="">Tüm Kategoriler</option>
                                    <?php
                                    $kategoriler = $baglan->query("SELECT * FROM t_kategori WHERE kategoriDurum = 1");
                                    while ($kat = $kategoriler->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($kat['kategoriAdi']) . '">'
                                            . htmlspecialchars($kat['kategoriAdi']) . '</option>';
                                    }
                                    ?>
                                </select>

                                <select id="turFiltre"
                                    class="form-control"
                                    style="width: 150px;"
                                    onchange="urunleriFiltrele()">
                                    <option value="">Tüm Türler</option>
                                    <?php
                                    $turler = $baglan->query("SELECT * FROM t_hayvanturleri");
                                    while ($tur = $turler->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($tur['hayvanTurAdi']) . '">'
                                            . htmlspecialchars($tur['hayvanTurAdi']) . '</option>';
                                    }
                                    ?>
                                </select>

                                <button class="btn btn-sm" onclick="filtreleriSifirlaUrun()">
                                    <i class="fa">🔄</i> Sıfırla
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="urunlerTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Resim</th>
                                    <th>Ürün Adı</th>
                                    <th>Kategori</th>
                                    <th>Hayvan Türü</th>
                                    <th>Fiyat</th>
                                    <th>Stok</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT u.*, k.kategoriAdi, ht.hayvanTurAdi, ud.urunDAciklama, s.stokMiktar, 
                                               r.resimYolu
                                        FROM t_urunler u
                                        LEFT JOIN t_kategori k ON u.urunKategoriID = k.kategoriID
                                        LEFT JOIN t_urundetay ud ON u.urunID = ud.urunDurunID
                                        LEFT JOIN t_hayvanturleri ht ON ud.urunDHayvanTurID = ht.hayvanTurID
                                        LEFT JOIN t_stok s ON ud.urunDStokID = s.stokID
                                        LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
                                        LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
                                        GROUP BY u.urunID";

                                $result = $baglan->query($sql);
                                if ($result && $result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr data-urun-adi="' . htmlspecialchars($row['urunAdi']) . '" 
                                                 data-kategori="' . htmlspecialchars($row['kategoriAdi'] ?? '') . '"
                                                 data-tur="' . htmlspecialchars($row['hayvanTurAdi'] ?? '') . '">';
                                        echo '<td>PRD-' . $row['urunID'] . '</td>';
                                        echo '<td><img src="' . ($row['resimYolu'] ?? 'resim/default.jpg') . '" style="width: 50px; height: 50px; object-fit: cover;"></td>';
                                        echo '<td>' . htmlspecialchars($row['urunAdi']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['kategoriAdi'] ?? 'Belirtilmemiş') . '</td>';
                                        echo '<td>' . htmlspecialchars($row['hayvanTurAdi'] ?? 'Belirtilmemiş') . '</td>';
                                        echo '<td>₺' . number_format($row['urunFiyat'], 2) . '</td>';
                                        echo '<td>' . ($row['stokMiktar'] ?? 0) . '</td>';
                                        echo '<td class="action-btns">
                                                <button onclick="openUpdateProductModal({
                                                    id: ' . $row['urunID'] . ',
                                                    name: \'' . addslashes($row['urunAdi']) . '\',
                                                    category: \'' . addslashes($row['kategoriAdi'] ?? '') . '\',
                                                    price: ' . $row['urunFiyat'] . ',
                                                    stock: ' . ($row['stokMiktar'] ?? 0) . ',
                                                    description: \'' . addslashes($row['urunDAciklama'] ?? '') . '\'
                                                })" class="btn btn-sm btn-warning">Düzenle</button>
                                                <a href="?delete=' . $row['urunID'] . '" class="btn btn-sm btn-danger" 
                                                   onclick="return confirm(\'Bu ürünü silmek istediğinizden emin misiniz?\')">Sil</a>
                                            </td>';
                                        echo '</tr>';
                                    }
                                } else {
                                    echo '<tr><td colspan="8" style="text-align: center;">Henüz ürün eklenmemiş.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Ürün Ekleme Modalı -->
                <div id="newProductModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <span class="close" onclick="closeNewProductModal()">&times;</span>
                        <h2>Yeni Ürün Ekle</h2>
                        <form method="post" enctype="multipart/form-data">
                            <!-- Ürün alanları -->
                            <div class="form-group">
                                <label for="productName">Ürün Adı</label>
                                <input type="text" id="productName" name="urunAdi" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="productCategory">Kategori</label>
                                <select id="productCategory" name="urunKategoriID" class="form-control" required onchange="kategoriSecildi(this)">
                                    <?php
                                    $kategoriler = $baglan->query("SELECT * FROM t_kategori");
                                    while ($kat = $kategoriler->fetch_assoc()) {
                                        echo '<option value="' . $kat['kategoriID'] . '">' . $kat['kategoriAdi'] . '</option>';
                                    }
                                    ?>
                                    <option value="yeniKategori">+ Yeni Kategori Ekle</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productAnimal">Hayvan Türü</label>
                                <select id="productAnimal" name="hayvanTurID" class="form-control" required onchange="turSecildi(this)">
                                    <?php
                                    $turler = $baglan->query("SELECT * FROM t_hayvanturleri");
                                    while ($tur = $turler->fetch_assoc()) {
                                        echo '<option value="' . $tur['hayvanTurID'] . '">' . $tur['hayvanTurAdi'] . '</option>';
                                    }
                                    ?>
                                    <option value="yeniTur">+ Yeni Tür Ekle</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="productPrice">Fiyat (₺)</label>
                                <input type="number" id="productPrice" name="urunFiyat" class="form-control" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label for="productStock">Stok Miktarı</label>
                                <input type="number" id="productStock" name="stokMiktar" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="productDescription">Ürün Açıklaması</label>
                                <textarea id="productDescription" name="urunAciklama" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="productImage">Ürün Görseli</label>
                                <input type="file" id="productImage" name="urunResim[]" class="form-control" accept="image/*" multiple required>
                            </div>
                            <button type="submit" name="urunEkle" class="btn">Ürünü Kaydet</button>
                        </form>

                        <!-- Yeni Kategori Ekle Formu (form dışında!) -->
                        <div id="yeniKategoriDiv" style="display:none; margin-top:8px;">
                            <form method="post" style="display:flex;gap:8px;">
                                <input type="text" name="yeniKategoriAdi" class="form-control" placeholder="Yeni kategori adı" required>
                                <select name="yeniKategoriHayvanTurID" class="form-control" required>
                                    <option value="">Tür Seçiniz</option>
                                    <?php
                                    $turler = $baglan->query("SELECT * FROM t_hayvanturleri");
                                    while ($tur = $turler->fetch_assoc()) {
                                        echo '<option value="' . $tur['hayvanTurID'] . '">' . $tur['hayvanTurAdi'] . '</option>';
                                    }
                                    ?>
                                </select>
                                <button type="submit" name="yeniKategoriEkle" class="btn btn-sm">Ekle</button>
                            </form>
                        </div>

                        <!-- Yeni Tür Ekle Formu (form dışında!) -->
                        <div id="yeniTurDiv" style="display:none; margin-top:8px;">
                            <form method="post" style="display:flex;gap:8px;">
                                <input type="text" name="yeniTurAdi" class="form-control" placeholder="Yeni tür adı" required>
                                <button type="submit" name="yeniTurEkle" class="btn btn-sm">Ekle</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Ürün Güncelleme Modal -->
                <div id="updateProductModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <span class="close" onclick="closeUpdateProductModal()">&times;</span>
                        <h2>Ürün Bilgilerini Güncelle</h2>
                        <form>
                            <div class="form-group">
                                <label for="updateProductName">Ürün Adı</label>
                                <input type="text" id="updateProductName" class="form-control" placeholder="Ürün adını giriniz">
                            </div>

                            <div class="form-group">
                                <label for="updateProductCategory">Kategori</label>
                                <input type="text" id="updateProductCategory" class="form-control" placeholder="Kategori arayın" onfocus="showAllCategoriesForUpdate()" oninput="filterCategoriesForUpdate()">
                                <div id="dropdownCategoryUpdate" class="dropdown" style="margin-top: 10px; display: none; border: 1px solid var(--border-color); border-radius: 4px; background-color: white; max-height: 150px; overflow-y: auto;">
                                    <div class="dropdown-item" onclick="selectCategoryForUpdate('Köpek Ürünleri')">Köpek Ürünleri</div>
                                    <div class="dropdown-item" onclick="selectCategoryForUpdate('Kedi Ürünleri')">Kedi Ürünleri</div>
                                    <div class="dropdown-item" onclick="selectCategoryForUpdate('Balık Ürünleri')">Balık Ürünleri</div>
                                    <div class="dropdown-item" onclick="selectCategoryForUpdate('Kuş Ürünleri')">Kuş Ürünleri</div>
                                    <div class="dropdown-item" onclick="selectCategoryForUpdate('Hamster Ürünleri')">Hamster Ürünleri</div>
                                </div>
                            </div>
                            <script>
                                function showAllCategoriesForUpdate() {
                                    const dropdownList = document.getElementById('dropdownCategoryUpdate');
                                    const items = dropdownList.getElementsByClassName('dropdown-item');
                                    for (let i = 0; i < items.length; i++) {
                                        items[i].style.display = 'block'; // Tüm öğeleri görünür yap
                                    }
                                    dropdownList.style.display = 'block'; // Dropdown'u görünür yap
                                }

                                function filterCategoriesForUpdate() {
                                    const input = document.getElementById('updateProductCategory').value.toLowerCase();
                                    const dropdownList = document.getElementById('dropdownCategoryUpdate');
                                    const items = dropdownList.getElementsByClassName('dropdown-item');
                                    let hasVisibleItem = false;

                                    for (let i = 0; i < items.length; i++) {
                                        const itemText = items[i].textContent.toLowerCase();
                                        const isVisible = itemText.includes(input);
                                        items[i].style.display = isVisible ? 'block' : 'none';
                                        if (isVisible) hasVisibleItem = true;
                                    }

                                    // Dropdown görünürlüğünü ayarla
                                    dropdownList.style.display = hasVisibleItem || input === '' ? 'block' : 'none';
                                }

                                function selectCategoryForUpdate(category) {
                                    const input = document.getElementById('updateProductCategory');
                                    input.value = category;
                                    document.getElementById('dropdownCategoryUpdate').style.display = 'none';
                                }

                                // Dropdown dışında bir yere tıklanınca dropdown'u kapat
                                document.addEventListener('click', function(event) {
                                    const dropdown = document.getElementById('dropdownCategoryUpdate');
                                    const input = document.getElementById('updateProductCategory');
                                    if (!dropdown.contains(event.target) && event.target !== input) {
                                        dropdown.style.display = 'none';
                                    }
                                });
                            </script>

                            <div class="form-group">
                                <label for="updateProductPrice">Fiyat (₺)</label>
                                <input type="number" id="updateProductPrice" class="form-control" placeholder="0.00">
                            </div>

                            <div class="form-group">
                                <label for="updateProductStock">Stok Miktarı</label>
                                <input type="number" id="updateProductStock" class="form-control" placeholder="0">
                            </div>

                            <div class="form-group">
                                <label for="updateProductDescription">Ürün Açıklaması</label>
                                <textarea id="updateProductDescription" class="form-control" placeholder="Ürün açıklamasını giriniz"></textarea>
                            </div>

                            <button type="submit" class="btn">Güncelle</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Kategoriler Section -->
            <div id="kategoriler" class="section" style="display: none;">
                <div class="content-header">
                    <h2>Kategori Yönetimi</h2>
                </div>
                <!-- Kategori Yönetimi Card'ını güncelle -->
                <div class="card">
                    <div class="card-header">
                        <div style="display: flex; justify-content: space-between; align-items: center;">
                            <h3>Kategori Yönetimi</h3>
                            <div style="display: flex; gap: 10px;">
                                <input type="text"
                                    id="kategoriAdiArama"
                                    class="form-control"
                                    placeholder="Kategori adı ara..."
                                    style="width: 200px;"
                                    onkeyup="kategorileriFiltrele()">

                                <input type="text"
                                    id="slugArama"
                                    class="form-control"
                                    placeholder="Slug ara..."
                                    style="width: 200px;"
                                    onkeyup="kategorileriFiltrele()">

                                <select id="hayvanTuruFiltre"
                                    class="form-control"
                                    style="width: 150px;"
                                    onchange="kategorileriFiltrele()">
                                    <option value="">Tüm Türler</option>
                                    <?php
                                    $turler = $baglan->query("SELECT * FROM t_hayvanturleri");
                                    while ($tur = $turler->fetch_assoc()) {
                                        echo '<option value="' . htmlspecialchars($tur['hayvanTurAdi']) . '">'
                                            . htmlspecialchars($tur['hayvanTurAdi']) . '</option>';
                                    }
                                    ?>
                                </select>

                                <button class="btn btn-sm" onclick="filtreleriSifirlaKategori()">
                                    <i class="fa">🔄</i> Sıfırla
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="kategorilerTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>İkon</th>
                                    <th>Kategori Adı</th>
                                    <th>Slug</th>
                                    <th>Hayvan Türü</th>
                                    <th>İşlemler</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT k.*, ht.hayvanTurAdi 
                                    FROM t_kategori k
                                    LEFT JOIN t_hayvanturleri ht ON k.kategoriHayvanTurID = ht.hayvanTurID";
                                $result = $baglan->query($sql);
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr data-kategori-adi="' . htmlspecialchars($row['kategoriAdi']) . '" 
                                             data-kategori-slug="' . htmlspecialchars($row['kategoriSlug']) . '"
                                             data-hayvan-turu="' . htmlspecialchars($row['hayvanTurAdi'] ?? '') . '">';
                                    echo '<td>CAT-' . $row['kategoriID'] . '</td>';
                                    echo '<td><img src="' . ($row['kategoriIkonUrl'] ?? 'resim/default-category.png') . '" style="width: 40px; height: 40px; object-fit: cover;"></td>';
                                    echo '<td>' . htmlspecialchars($row['kategoriAdi']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['kategoriSlug']) . '</td>';
                                    echo '<td>' . htmlspecialchars($row['hayvanTurAdi']) . '</td>';
                                    echo '<td>
                                        <button onclick="openKategoriDuzenleModal(' . $row['kategoriID'] . ')" class="btn btn-warning btn-sm">Düzenle</button>
                                      </td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Kategori Düzenleme Modal -->
                <div id="kategoriDuzenleModal" class="modal" style="display: none;">
                    <div class="modal-content">
                        <span class="close" onclick="closeKategoriDuzenleModal()">&times;</span>
                        <h2>Kategori Düzenle</h2>
                        <form method="post" enctype="multipart/form-data">
                            <input type="hidden" id="kategoriID" name="kategoriID">

                            <div class="form-group">
                                <label for="kategoriAdi">Kategori Adı</label>
                                <input type="text" id="kategoriAdi" name="kategoriAdi" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="kategoriAciklama">Açıklama</label>
                                <textarea id="kategoriAciklama" name="kategoriAciklama" class="form-control"></textarea>
                            </div>

                            <div class="form-group">
                                <label for="kategoriHayvanTurID">Hayvan Türü</label>
                                <select id="kategoriHayvanTurID" name="kategoriHayvanTurID" class="form-control" required>
                                    <?php
                                    $turler = $baglan->query("SELECT * FROM t_hayvanturleri");
                                    while ($tur = $turler->fetch_assoc()) {
                                        echo '<option value="' . $tur['hayvanTurID'] . '">' . $tur['hayvanTurAdi'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="kategoriIkon">Kategori İkonu</label>
                                <input type="file" id="kategoriIkon" name="kategoriIkon" class="form-control" accept="image/*">
                                <small class="form-text text-muted">Mevcut ikonu değiştirmek istemiyorsanız boş bırakın.</small>
                            </div>

                            <button type="submit" name="kategoriGuncelle" class="btn">Güncelle</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function showAllCategories() {
        const dropdownList = document.getElementById('dropdownCategory');
        const items = dropdownList.getElementsByClassName('dropdown-item');
        for (let i = 0; i < items.length; i++) {
            items[i].style.display = 'block'; // Tüm öğeleri görünür yap
        }
        dropdownList.style.display = 'block'; // Dropdown'u görünür yap
    }

    function filterCategories() {
        const input = document.getElementById('productCategory').value.toLowerCase();
        const dropdownList = document.getElementById('dropdownCategory');
        const items = dropdownList.getElementsByClassName('dropdown-item');
        let hasVisibleItem = false;

        for (let i = 0; i < items.length; i++) {
            const itemText = items[i].textContent.toLowerCase();
            const isVisible = itemText.includes(input);
            items[i].style.display = isVisible ? 'block' : 'none';
            if (isVisible) hasVisibleItem = true;
        }

        // Dropdown görünürlüğünü ayarla
        dropdownList.style.display = hasVisibleItem || input === '' ? 'block' : 'none';
    }

    function selectCategory(category) {
        const input = document.getElementById('productCategory');
        input.value = category;
        document.getElementById('dropdownCategory').style.display = 'none';
    }

    // Dropdown dışında bir yere tıklanınca dropdown'u kapat
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdownCategory');
        const input = document.getElementById('productCategory');
        if (!dropdown.contains(event.target) && event.target !== input) {
            dropdown.style.display = 'none';
        }
    });

    function closeNewProductModal() {
        const modal = document.getElementById('newProductModal');
        modal.style.display = 'none';
    }

    function openUpdateProductModal(product) {
        // Modalı aç
        const modal = document.getElementById('updateProductModal');
        modal.style.display = 'flex';

        // Ürün bilgilerini modal formuna doldur
        document.getElementById('updateProductName').value = product.name;
        document.getElementById('updateProductCategory').value = product.category;
        document.getElementById('updateProductPrice').value = product.price;
        document.getElementById('updateProductStock').value = product.stock;
        document.getElementById('updateProductDescription').value = product.description;
    }

    function closeUpdateProductModal() {
        // Modalı kapat
        const modal = document.getElementById('updateProductModal');
        modal.style.display = 'none';
    }

    function openNewProductModal() {
        // Modalı aç
        const modal = document.getElementById('newProductModal');
        modal.style.display = 'flex';
    }

    function closeNewProductModal() {
        // Modalı kapat
        const modal = document.getElementById('newProductModal');
        modal.style.display = 'none';
    }

    function kategoriSecildi(select) {
        if (select.value === "yeniKategori") {
            document.getElementById('yeniKategoriDiv').style.display = 'block';
        } else {
            document.getElementById('yeniKategoriDiv').style.display = 'none';
        }
    }

    function turSecildi(select) {
        if (select.value === "yeniTur") {
            document.getElementById('yeniTurDiv').style.display = 'block';
        } else {
            document.getElementById('yeniTurDiv').style.display = 'none';
        }
    }

    // Kategori düzenleme fonksiyonlarını güncelleyelim
    function openKategoriDuzenleModal(kategoriID) {
        fetch('adminpanel.php?id=' + kategoriID)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                // Form alanlarını doldur
                document.getElementById('kategoriID').value = data.kategoriID;
                document.getElementById('kategoriAdi').value = data.kategoriAdi;
                document.getElementById('kategoriAciklama').value = data.kategoriAciklama || '';
                document.getElementById('kategoriHayvanTurID').value = data.kategoriHayvanTurID;

                // Modalı göster
                document.getElementById('kategoriDuzenleModal').style.display = 'flex';
            })
            .catch(error => {
                console.error('Hata:', error);
                alert('Kategori bilgileri alınırken bir hata oluştu.');
            });
    }

    function closeKategoriDuzenleModal() {
        const modal = document.getElementById('kategoriDuzenleModal');
        modal.style.display = 'none';
    }

    // Sayfa dışı tıklamalarda modalı kapatma
    window.onclick = function(event) {
        const modal = document.getElementById('kategoriDuzenleModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Sayfa yüklendiğinde hash'e göre ilgili bölümü göster
    window.onload = function() {
        <?php if ($_SESSION['uyeYetki'] == 1): ?>
            showSection('urunler');
        <?php elseif ($_SESSION['uyeYetki'] == 2): ?>
            showSection('dashboard');
        <?php endif; ?>
    }

    // Bölüm gösterme fonksiyonunu güncelle
    function showSection(sectionId) {
        // Yetki kontrolü
        <?php if ($_SESSION['uyeYetki'] == 1): ?>
            // Çalışan için kısıtlı erişim
            const allowedSections = ['urunler'];
            if (!allowedSections.includes(sectionId)) {
                alert('Bu bölüme erişim yetkiniz bulunmamaktadır.');
                return;
            }
        <?php endif; ?>

        // Tüm bölümleri gizle
        const sections = document.querySelectorAll('.content > div');
        sections.forEach(section => {
            section.style.display = 'none';
        });

        // Tüm menü öğelerinden active sınıfını kaldır
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            item.classList.remove('active');
        });

        // İlgili bölümü göster
        const section = document.getElementById(sectionId);
        if (section) {
            section.style.display = 'block';
        }

        // İlgili menü öğesini aktif yap
        const menuItem = document.querySelector(`.menu-item[href="#${sectionId}"]`);
        if (menuItem) {
            menuItem.classList.add('active');
        }

        // URL'yi güncelle
        window.location.hash = sectionId;
    }

    // Siparişleri filtreleme fonksiyonunu güncelle
    function siparisleriFiltrele() {
        const siparisNo = document.getElementById('siparisArama').value.toLowerCase();
        const baslangicTarih = document.getElementById('baslangicTarih').value;
        const bitisTarih = document.getElementById('bitisTarih').value;

        const rows = document.querySelectorAll('#siparislerTable tbody tr');

        rows.forEach(row => {
            const rowSiparisNo = row.getAttribute('data-siparis-no').toLowerCase();
            const rowTarih = row.getAttribute('data-tarih');

            let siparisNoMatch = rowSiparisNo.includes(siparisNo);
            let tarihMatch = true;

            if (baslangicTarih && bitisTarih) {
                // Tarihleri Date objelerine çevir
                const rowDate = new Date(rowTarih);
                const baslangicDate = flatpickr.parseDate(baslangicTarih, "d.m.Y");
                const bitisDate = flatpickr.parseDate(bitisTarih, "d.m.Y");

                // Saat bilgisini sıfırla
                rowDate.setHours(0, 0, 0, 0);
                baslangicDate.setHours(0, 0, 0, 0);
                bitisDate.setHours(0, 0, 0, 0);

                // Tarih aralığını kontrol et
                tarihMatch = rowDate >= baslangicDate && rowDate <= bitisDate;
            }

            if (siparisNoMatch && tarihMatch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    // Flatpickr tarih seçici ayarlarını güncelle
    document.addEventListener('DOMContentLoaded', function() {
        const dateConfig = {
            locale: 'tr',
            dateFormat: 'd.m.Y',
            enableTime: false,
            altInput: true,
            altFormat: 'j F Y',
            monthSelectorType: 'static',
            disableMobile: true,
            onChange: function() {
                siparisleriFiltrele();
            }
        };

        // Başlangıç tarihi seçici
        const baslangicPicker = flatpickr("#baslangicTarih", {
            ...dateConfig,
            onClose: function(selectedDates) {
                if (selectedDates[0]) {
                    // Bitiş tarihinin minimum değerini ayarla
                    bitisPicker.set('minDate', selectedDates[0]);
                    siparisleriFiltrele();
                }
            }
        });

        // Bitiş tarihi seçici
        const bitisPicker = flatpickr("#bitisTarih", {
            ...dateConfig,
            onClose: function(selectedDates) {
                if (selectedDates[0]) {
                    // Başlangıç tarihinin maksimum değerini ayarla
                    baslangicPicker.set('maxDate', selectedDates[0]);
                    siparisleriFiltrele();
                }
            }
        });
    });

    // Filtreleri sıfırlama fonksiyonunu güncelle
    function filtreleriSifirla() {
        document.getElementById('siparisArama').value = '';

        // Flatpickr instance'larını al
        const baslangicPicker = document.querySelector("#baslangicTarih")._flatpickr;
        const bitisPicker = document.querySelector("#bitisTarih")._flatpickr;

        // Tarihleri temizle
        baslangicPicker.clear();
        bitisPicker.clear();

        // Min/max kısıtlamalarını kaldır
        baslangicPicker.set('maxDate', null);
        bitisPicker.set('minDate', null);

        // Tüm satırları göster
        const rows = document.querySelectorAll('#siparislerTable tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    }

    // Kullanıcıları filtreleme fonksiyonu
    function kullanicilariFiltrele() {
        const aramaMetni = document.getElementById('kullaniciArama').value.toLowerCase();
        const satirlar = document.querySelectorAll('#kullanicilarTable tbody tr');

        satirlar.forEach(satir => {
            const kullaniciID = satir.getAttribute('data-user-id').toLowerCase();
            const kullaniciAdi = satir.getAttribute('data-user-name').toLowerCase();

            if (kullaniciID.includes(aramaMetni) || kullaniciAdi.includes(aramaMetni)) {
                satir.style.display = '';
            } else {
                satir.style.display = 'none';
            }
        });
    }

    // Kullanıcı filtresini sıfırlama fonksiyonu
    function filtreleriSifirlaKullanici() {
        document.getElementById('kullaniciArama').value = '';
        const satirlar = document.querySelectorAll('#kullanicilarTable tbody tr');
        satirlar.forEach(satir => {
            satir.style.display = '';
        });
    }

    // Ürünleri filtreleme fonksiyonu
    function urunleriFiltrele() {
        const aramaMetni = document.getElementById('urunArama').value.toLowerCase();
        const secilenKategori = document.getElementById('kategoriFiltre').value.toLowerCase();
        const secilenTur = document.getElementById('turFiltre').value.toLowerCase();

        const satirlar = document.querySelectorAll('#urunlerTable tbody tr');

        satirlar.forEach(satir => {
            const urunAdi = satir.getAttribute('data-urun-adi').toLowerCase();
            const kategori = satir.getAttribute('data-kategori').toLowerCase();
            const tur = satir.getAttribute('data-tur').toLowerCase();

            const isimMatch = urunAdi.includes(aramaMetni);
            const kategoriMatch = secilenKategori === '' || kategori === secilenKategori;
            const turMatch = secilenTur === '' || tur === secilenTur;

            if (isimMatch && kategoriMatch && turMatch) {
                satir.style.display = '';
            } else {
                satir.style.display = 'none';
            }
        });
    }

    // Ürün filtrelerini sıfırlama fonksiyonu
    function filtreleriSifirlaUrun() {
        document.getElementById('urunArama').value = '';
        document.getElementById('kategoriFiltre').value = '';
        document.getElementById('turFiltre').value = '';

        const satirlar = document.querySelectorAll('#urunlerTable tbody tr');
        satirlar.forEach(satir => {
            satir.style.display = '';
        });
    }

    // Select elementleri için stil
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('.form-control');
        selects.forEach(select => {
            select.style.padding = '8px';
            select.style.borderRadius = '4px';
            select.style.border = '1px solid var(--border-color)';
        });
    });

    // Kategorileri filtreleme fonksiyonu
    function kategorileriFiltrele() {
        const kategoriAdi = document.getElementById('kategoriAdiArama').value.toLowerCase();
        const slug = document.getElementById('slugArama').value.toLowerCase();
        const hayvanTuru = document.getElementById('hayvanTuruFiltre').value.toLowerCase();

        const satirlar = document.querySelectorAll('#kategorilerTable tbody tr');

        satirlar.forEach(satir => {
            const rowKategoriAdi = satir.getAttribute('data-kategori-adi').toLowerCase();
            const rowSlug = satir.getAttribute('data-kategori-slug').toLowerCase();
            const rowHayvanTuru = (satir.getAttribute('data-hayvan-turu') || '').toLowerCase();

            const kategoriMatch = rowKategoriAdi.includes(kategoriAdi);
            const slugMatch = rowSlug.includes(slug);
            const turMatch = hayvanTuru === '' || rowHayvanTuru === hayvanTuru;

            if (kategoriMatch && slugMatch && turMatch) {
                satir.style.display = '';
            } else {
                satir.style.display = 'none';
            }
        });
    }

    // Kategori filtrelerini sıfırlama fonksiyonu
    function filtreleriSifirlaKategori() {
        document.getElementById('kategoriAdiArama').value = '';
        document.getElementById('slugArama').value = '';
        document.getElementById('hayvanTuruFiltre').value = '';

        const satirlar = document.querySelectorAll('#kategorilerTable tbody tr');
        satirlar.forEach(satir => {
            satir.style.display = '';
        });
    }
</script>
</script>

</html>