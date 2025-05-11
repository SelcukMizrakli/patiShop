<?php
include 'ayar.php';

// Ürün Silme
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $urunID = intval($_GET['delete']);
    $baglan->query("DELETE FROM t_urunler WHERE urunID = $urunID");
    $baglan->query("DELETE FROM t_stok WHERE stokUrunID = $urunID");
    header("Location: adminpanel.php");
    exit;
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

    header("Location: adminpanel.php");
    exit;
}

// Yetki güncelleme işlemi
if (isset($_POST['yetkiGuncelle'])) {
    $uyeID = (int)$_POST['uyeID'];
    $yeniYetki = (int)$_POST['yeniYetki'];
    $baglan->query("UPDATE t_uyeler SET uyeYetki = $yeniYetki WHERE uyeID = $uyeID");
    header("Location: adminpanel.php");
    exit;
}

if (isset($_POST['yeniKategoriEkle'])) {
    $yeniKategori = $baglan->real_escape_string($_POST['yeniKategoriAdi']);
    $hayvanTurID = (int)$_POST['yeniKategoriHayvanTurID'];
    // Ana kategori olarak ekle (kategoriParentID NULL)
    $baglan->query("INSERT INTO t_kategori (kategoriAdi, kategoriHayvanTurID, kategoriParentID) VALUES ('$yeniKategori', $hayvanTurID, NULL)");
    echo "<script>window.location.href='adminpanel.php';</script>";
    exit;
}
if (isset($_POST['yeniTurEkle'])) {
    $yeniTur = $baglan->real_escape_string($_POST['yeniTurAdi']);
    $baglan->query("INSERT INTO t_hayvanturleri (hayvanTurAdi, hayvanTurSlug, hayvanTurOlusturmaTarih) VALUES ('$yeniTur', '$yeniTur', NOW())");
    echo "<script>window.location.href='adminpanel.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiShop - Admin Paneli</title>
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

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
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
        <div class="sidebar">
            <div class="sidebar-header">
                <h3>PatiShop Admin</h3>
            </div>

            <!-- Sabit Menü Öğeleri -->
            <a href="anasayfa.php" class="menu-item">
                <i class="fa">🏠</i> Ana Sayfa
            </a>
            <a href="siparisler.php" class="menu-item">
                <i class="fa">🛒</i> Siparişler
            </a>
            <a href="kullanicilar.php" class="menu-item">
                <i class="fa">👥</i> Kullanıcılar
            </a>
            <a href="kampanyalar.php" class="menu-item">
                <i class="fa">🔖</i> Kampanyalar
            </a>
            <a href="ayarlar.php" class="menu-item">
                <i class="fa">⚙️</i> Ayarlar
            </a>
            <a href="cikisYap.php" class="menu-item">
                <i class="fa">🚪</i> Çıkış Yap
            </a>
        </div>

        <!-- İçerik Alanı -->
        <div class="content">
            <div class="content-header">
                <h2>Dashboard</h2>
                <div>
                    <button class="btn" onclick="openNewProductModal()">
                        <i class="fa">➕</i> Yeni Ürün Ekle
                    </button>
                </div>
            </div>

            <!-- Son Siparişler -->
            <div class="card">
                <div class="card-header">
                    Son Siparişler
                </div>
                <div class="card-body">
                    <table>
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
                            $sql = "SELECT s.siparisID, u.uyeAd, u.uyeSoyad, s.siparisOdemeTarih, s.siparisSepetID, s.siparisDurum
                                    FROM t_siparis s
                                    INNER JOIN t_uyeler u ON s.siparisUyeID = u.uyeID
                                    ORDER BY s.siparisOdemeTarih DESC
                                    LIMIT 5";
                            $result = $baglan->query($sql);
                            while ($row = $result->fetch_assoc()) {
                                // SepetID'lerden toplam tutarı hesapla
                                $sepetIDs = array_filter(explode(',', $row['siparisSepetID']));
                                $toplamTutar = 0;
                                if (!empty($sepetIDs)) {
                                    $sepetIDString = implode(',', array_map('intval', $sepetIDs));
                                    $sqlTutar = "SELECT SUM(sepetUrunFiyat * sepetUrunMiktar) as toplam FROM t_sepet WHERE sepetID IN ($sepetIDString)";
                                    $resTutar = $baglan->query($sqlTutar);
                                    if ($resTutar && $resTutar->num_rows > 0) {
                                        $toplamTutar = $resTutar->fetch_assoc()['toplam'];
                                    }
                                }
                                $durum = ($row['siparisDurum'] == 0) ? "Hazırlanıyor" : (($row['siparisDurum'] == 1) ? "Kargoya Verildi" : "Teslim Edildi");
                                echo '<tr>';
                                echo '<td>#ORD-' . $row['siparisID'] . '</td>';
                                echo '<td>' . $row['uyeAd'] . ' ' . $row['uyeSoyad'] . '</td>';
                                echo '<td>' . $row['siparisOdemeTarih'] . '</td>';
                                echo '<td>₺' . number_format($toplamTutar, 2) . '</td>';
                                echo '<td>' . $durum . '</td>';
                                echo '<td><a href="siparisDetay.php?siparisID=' . $row['siparisID'] . '" class="btn btn-sm">Görüntüle</a></td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ürün Yönetimi Tab Container -->
            <div class="card">
                <div class="card-header">
                    Ürün Yönetimi
                </div>
                <div class="card-body">
                    <div class="tab-container">
                        <div class="tabs">
                            <div class="tab active">Ürün Listesi</div>
                            <div class="tab" onclick="openNewProductModal()">Yeni Ürün Ekle</div>
                        </div>

                        <div class="tab-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Ürün Kodu</th>
                                        <th>Ürün Adı</th>
                                        <th>Kategori</th>
                                        <th>Hayvan Türü</th>
                                        <th>Stok</th>
                                        <th>Fiyat</th>
                                        <th>Açıklama</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT u.urunID, u.urunAdi, u.urunFiyat, k.kategoriAdi, 
               ht.hayvanTurAdi, s.stokMiktar, ud.urunDAciklama,
               r.resimYolu
        FROM t_urunler u
        LEFT JOIN t_kategori k ON u.urunKategoriID = k.kategoriID
        LEFT JOIN t_urundetay ud ON ud.urunDurunID = u.urunID
        LEFT JOIN t_hayvanturleri ht ON ud.urunDHayvanTurID = ht.hayvanTurID
        LEFT JOIN t_stok s ON ud.urunDStokID = s.stokID
        LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
        LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
        GROUP BY u.urunID";
                                    $result = $baglan->query($sql);
                                    while ($row = $result->fetch_assoc()) {
                                        echo '<tr>';
                                        echo '<td>PRD-' . $row['urunID'] . '</td>';
                                        echo '<td>' . htmlspecialchars($row['urunAdi']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['kategoriAdi']) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['hayvanTurAdi']) . '</td>';
                                        echo '<td>' . $row['stokMiktar'] . '</td>';
                                        echo '<td>₺' . number_format($row['urunFiyat'], 2) . '</td>';
                                        echo '<td>' . htmlspecialchars($row['urunDAciklama']) . '</td>';
                                        echo '<td>
                                            <a href="adminpanel.php?edit=' . $row['urunID'] . '" class="btn btn-warning btn-sm">Düzenle</a>
                                            <a href="adminpanel.php?delete=' . $row['urunID'] . '" class="btn btn-danger btn-sm" onclick="return confirm(\'Silmek istediğinize emin misiniz?\')">Sil</a>
                                        </td>';
                                        echo '</tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>

                    </div>
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

            <!-- Kullanıcı Yetkileri Yönetimi Card'ı -->
            <div class="card">
                <div class="card-header">
                    Kullanıcı Yetkileri
                </div>
                <div class="card-body">
                    <table>
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
                                echo '<tr>';
                                echo '<td>USR-' . $row['uyeID'] . '</td>';
                                echo '<td>' . $row['uyeAd'] . ' ' . $row['uyeSoyad'] . '</td>';
                                echo '<td>' . $row['uyeMail'] . '</td>';
                                echo '<td>' . $rol . '</td>';
                                echo '<td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="uyeID" value="' . $row['uyeID'] . '">
                                        <select name="yeniYetki">
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
</script>

</html>