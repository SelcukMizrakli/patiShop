<?php
// filepath: c:\xampp\htdocs\patishop\profil.php
include 'ayar.php';
session_start();

// Kullanıcı oturum kontrolü
if (!isset($_SESSION['uyeID'])) {
    header('Location: index.php');
    exit;
}

$uyeID = $_SESSION['uyeID'];
$sql = "SELECT uyeAd, uyeSoyad, uyeMail, uyeTelNo FROM t_uyeler WHERE uyeID = $uyeID";
$result = $baglan->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("Kullanıcı bilgileri bulunamadı.");
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiShop - Hesabım</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4caf50;
            --secondary-color: #388e3c;
            --light-gray: #f5f5f5;
            --dark-gray: #444;
            --white: #fff;
        }

        .header {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .logo-container {
            display: flex;
            align-items: center;
            padding: 10px 0;
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
            border-radius: 5px;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .search-login {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-left: auto;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f9f9f9;
        }

        .top-banner {
            background-color: var(--primary-color);
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }

        .logo {
            display: flex;
            align-items: center;
            color: var(--primary-color);
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo i {
            font-size: 28px;
            margin-right: 10px;
        }

        .search-bar {
            display: flex;
            width: 40%;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px 0 0 5px;
            outline: none;
        }

        .search-bar button {
            background: var(--primary-color);
            color: white;
            border: none;
            padding: 0 15px;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        .user-actions a {
            background-color: var(--primary-color);
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            text-decoration: none;
        }

        nav {
            background-color: var(--white);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .nav-links {
            display: flex;
            list-style: none;
            padding: 0 5%;
        }

        .nav-links li {
            padding: 15px 20px;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-gray);
            font-weight: 500;
        }

        main {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .profile-container {
            display: flex;
            gap: 30px;
        }

        .sidebar {
            width: 250px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 20px;
        }

        .profile-menu {
            list-style: none;
        }

        .profile-menu li {
            margin-bottom: 15px;
        }

        .profile-menu a {
            display: block;
            padding: 10px 15px;
            text-decoration: none;
            color: var(--dark-gray);
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .profile-menu a:hover,
        .profile-menu a.active {
            background-color: var(--light-gray);
            color: var(--primary-color);
        }

        .profile-menu a.active {
            border-left: 3px solid var(--primary-color);
            font-weight: bold;
        }

        .profile-content {
            flex: 1;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 25px;
        }

        .content-title {
            font-size: 22px;
            color: var(--dark-gray);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .order-card,
        .favorite-card {
            background: var(--light-gray);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
        }

        .order-header,
        .fav-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .order-id {
            font-weight: bold;
            color: var(--primary-color);
        }

        .order-date,
        .fav-date {
            color: #777;
            font-size: 14px;
        }

        .order-status {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: bold;
        }

        .status-delivered {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-processing {
            background: #fff8e1;
            color: #ff8f00;
        }

        .product-list {
            margin-top: 10px;
        }

        .product-item {
            display: flex;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }

        .product-item:last-child {
            border-bottom: none;
        }

        .product-image {
            width: 60px;
            height: 60px;
            border-radius: 5px;
            margin-right: 15px;
            background-color: #ddd;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-weight: 500;
            margin-bottom: 5px;
        }

        .product-meta {
            font-size: 13px;
            color: #777;
        }

        .product-price {
            font-weight: bold;
            color: var(--dark-gray);
        }

        .order-total {
            text-align: right;
            margin-top: 15px;
            font-weight: bold;
        }

        .btn {
            padding: 8px 15px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            display: inline-block;
            text-decoration: none;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-outline {
            border: 1px solid var(--primary-color);
            color: var(--primary-color);
            background: white;
        }

        .favorite-actions {
            margin-top: 10px;
            display: flex;
            gap: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-gray);
        }

        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-row {
            display: flex;
            gap: 15px;
        }

        .form-col {
            flex: 1;
        }

        .form-actions {
            margin-top: 25px;
        }

        @media (max-width: 992px) {

            .category-grid,
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {

            .category-grid,
            .product-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-container input {
                width: 180px;
            }
        }

        @media (max-width: 576px) {

            .category-grid,
            .product-grid {
                grid-template-columns: 1fr;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }

            .logo-container {
                flex-direction: column;
                align-items: flex-start;
            }

            .search-login {
                margin-top: 15px;
                width: 100%;
                justify-content: space-between;
            }

            .search-container {
                width: 70%;
            }

            .search-container input {
                width: 100%;
            }

            .navbar ul {
                flex-direction: column;
            }
        }

        .cart-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ddd;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .product-image img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 5px;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .product-meta {
            font-size: 14px;
            color: #777;
        }

        .product-price {
            font-weight: bold;
            color: #333;
        }

        .cart-actions {
            margin-left: 10px;
        }

        .cart-total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 15px;
            text-align: right;
        }

        .product-meta form {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .product-meta button {
            padding: 5px 10px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            cursor: pointer;
            border-radius: 5px;
            font-size: 14px;
        }

        .product-meta button:hover {
            background-color: #e0e0e0;
        }

        .product-meta input[type="number"] {
            width: 50px;
            text-align: center;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }

        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            border-radius: 8px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <!-- Üst Banner -->
    <div class="top-banner">
        Türkiye'nin her yerine ücretsiz kargo! 200 TL ve üzeri siparişlerde geçerlidir.
    </div>

    <?php include 'headerHesap.php'; ?>

    <!-- Main Content -->
    <main>
        <div class="profile-container">
            <!-- Sidebar Menu -->
            <div class="sidebar">
                <ul class="profile-menu">
                    <li><a href="#profile" class="active" onclick="showTab('profile')">Profil Bilgileri</a></li>
                    <li><a href="#orders" onclick="showTab('orders')">Siparişlerim</a></li>
                    <li><a href="#current-order" onclick="showTab('current-order')">Güncel Siparişim</a></li>
                    <li><a href="#favorites" onclick="showTab('favorites')">Favorilerim</a></li>
                    <li><a href="#addresses" onclick="showTab('addresses')">Adreslerim</a></li>
                    <li><a href="#cart" onclick="showTab('cart')">Sepetim</a></li>
                    <li><a href="cikisYap.php" style="color: #f44336;">Çıkış Yap</a></li>
                </ul>
            </div>

            <!-- Main Content Area -->
            <div class="profile-content">
                <!-- Profil Bilgileri -->
                <div id="profile" class="tab-content active">
                    <h2 class="content-title">Profil Bilgileri</h2>

                    <form action="profilGuncelle.php" method="POST">
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="firstName">Ad</label>
                                    <input type="text" id="firstName" name="uyeAd" class="form-control" value="<?php echo $user['uyeAd']; ?>">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="lastName">Soyad</label>
                                    <input type="text" id="lastName" name="uyeSoyad" class="form-control" value="<?php echo $user['uyeSoyad']; ?>">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">E-posta</label>
                            <input type="email" id="email" name="uyeMail" class="form-control" value="<?php echo $user['uyeMail']; ?>">
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <input type="tel" id="phone" name="uyeTelNo" class="form-control" value="<?php echo $user['uyeTelNo']; ?>">
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Bilgileri Güncelle</button>
                        </div>
                    </form>
                </div>

                <!-- Siparişlerim -->
                <div id="orders" class="tab-content">
                    <h2 class="content-title">Geçmiş Siparişlerim</h2>
                    <?php
                    // Sadece tamamlanmış siparişler (ör: siparisDurum = 2)
                    $sql = "SELECT s.siparisID, s.siparisOdemeTarih, s.siparisDurum, s.siparisSepetID
                            FROM t_siparis s
                            WHERE s.siparisUyeID = $uyeID AND s.siparisDurum = 2
                            ORDER BY s.siparisOdemeTarih DESC";
                    $result = $baglan->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
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
                            $durum = "Teslim Edildi";
                            echo '<div class="order-card">';
                            echo '<div class="order-header">';
                            echo '<span class="order-id">Sipariş #' . $row['siparisID'] . '</span>';
                            echo '<span class="order-date">' . $row['siparisOdemeTarih'] . '</span>';
                            echo '<span class="order-status">' . $durum . '</span>';
                            echo '</div>';
                            echo '<div class="order-total">Toplam: ' . number_format($toplamTutar, 2) . ' TL</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Henüz tamamlanmış siparişiniz bulunmamaktadır.</p>';
                    }
                    ?>
                </div>

                <!-- Güncel Siparişim -->
                <div id="current-order" class="tab-content">
                    <h2 class="content-title">Güncel Siparişlerim</h2>
                    <?php
                    // Sadece hazırlanan veya kargoya verilen siparişler (siparisDurum = 0 veya 1)
                    $sql = "SELECT s.siparisID, s.siparisOdemeTarih, s.siparisDurum, s.siparisSepetID
                            FROM t_siparis s
                            WHERE s.siparisUyeID = $uyeID AND s.siparisDurum IN (0,1)
                            ORDER BY s.siparisOdemeTarih DESC";
                    $result = $baglan->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $sepetIDs = array_filter(explode(',', $row['siparisSepetID']));
                            $toplamTutar = 0;
                            $urunler = [];
                            if (!empty($sepetIDs)) {
                                $sepetIDString = implode(',', array_map('intval', $sepetIDs));
                                // Sepet ürünlerini ve ürün detaylarını çek
                                $sqlUrunler = "SELECT sp.sepetUrunID, sp.sepetUrunMiktar, sp.sepetUrunFiyat, 
                                                     u.urunAdi, r.resimYolu
                                              FROM t_sepet sp
                                              INNER JOIN t_urunler u ON sp.sepetUrunID = u.urunID
                                              LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID 
                                              LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
                                              WHERE sp.sepetID IN ($sepetIDString)
                                              GROUP BY sp.sepetUrunID"; // Group by to prevent duplicate entries
                                $resUrunler = $baglan->query($sqlUrunler);
                                while ($urun = $resUrunler->fetch_assoc()) {
                                    $urunler[] = $urun;
                                    $toplamTutar += $urun['sepetUrunFiyat'] * $urun['sepetUrunMiktar'];
                                }
                            }
                            $durum = ($row['siparisDurum'] == 0) ? "Hazırlanıyor" : "Kargoya Verildi";
                            echo '<div class="order-card" style="cursor:pointer;" onclick="showOrderDetails(' . $row['siparisID'] . ')">';
                            echo '<div class="order-header">';
                            echo '<span class="order-id">Sipariş #' . $row['siparisID'] . '</span>';
                            echo '<span class="order-date">' . $row['siparisOdemeTarih'] . '</span>';
                            echo '<span class="order-status">' . $durum . '</span>';
                            echo '</div>';
                            // Ürün görselleri küçük olarak
                            if (!empty($urunler)) {
                                echo '<div class="product-list" style="display:flex;gap:10px;">';
                                foreach ($urunler as $urun) {
                                    $img = !empty($urun['resimYolu']) ? $urun['resimYolu'] : 'default-image.jpg';
                                    echo '<a href="urundetay.php?urunID=' . $urun['sepetUrunID'] . '" target="_blank" onclick="event.stopPropagation();">';
                                    echo '<img src="' . $img . '" alt="' . htmlspecialchars($urun['urunAdi']) . '" style="width:50px;height:50px;object-fit:cover;border-radius:5px;">';
                                    echo '</a>';
                                }
                                echo '</div>';
                            }
                            echo '<div class="order-total">Toplam: ' . number_format($toplamTutar, 2) . ' TL</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Güncel bir siparişiniz bulunmamaktadır.</p>';
                    }
                    ?>
                </div>

                <!-- Favorilerim -->
                <div id="favorites" class="tab-content">
                    <h2 class="content-title">Favori Ürünlerim</h2>
                    <?php
                    $sql = "SELECT f.favoriID, u.urunAdi, u.urunFiyat, r.resimYolu, f.favoriOlusturmaTarih
                            FROM t_favoriler f
                            INNER JOIN t_urunler u ON f.favoriUrunID = u.urunID
                            LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
                            LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
                            WHERE f.favoriUyeID = $uyeID
                            GROUP BY f.favoriID";
                    $result = $baglan->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="favorite-card">';
                            echo '<div class="product-item">';
                            echo '<div class="product-image"><img src="' . $row['resimYolu'] . '" alt="' . $row['urunAdi'] . '"></div>';
                            echo '<div class="product-details">';
                            echo '<div class="product-name">' . $row['urunAdi'] . '</div>';
                            echo '<div class="fav-date">' . $row['favoriOlusturmaTarih'] . ' tarihinde eklendi</div>';
                            echo '</div>';
                            echo '<div class="product-price">' . number_format($row['urunFiyat'], 2) . ' TL</div>';
                            echo '</div>';
                            echo '<div class="favorite-actions">';
                            echo '<a href="favoriSepeteEkle.php?favoriID=' . $row['favoriID'] . '" class="btn btn-primary">Sepete Ekle</a>';
                            echo '<a href="favoriSil.php?favoriID=' . $row['favoriID'] . '" class="btn btn-outline">Favorilerden Çıkar</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Henüz favori ürününüz bulunmamaktadır.</p>';
                    }
                    ?>
                </div>

                <!-- Adreslerim -->
                <div id="addresses" class="tab-content">
                    <h2 class="content-title">Adreslerim</h2>
                    <?php
                    $sql = "SELECT adresID, adresBilgisi FROM t_adresler WHERE adresUyeID = $uyeID";
                    $result = $baglan->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<div class="address-card">';
                            echo '<p>' . $row['adresBilgisi'] . '</p>';
                            echo '<div class="address-actions">';
                            echo '<a href="adresDuzenle.php?adresID=' . $row['adresID'] . '" class="btn btn-outline">Düzenle</a>';
                            echo '<a href="adresSil.php?adresID=' . $row['adresID'] . '" class="btn btn-outline" style="color: red;">Sil</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Henüz adresiniz bulunmamaktadır.</p>';
                    }
                    ?>
                    <a href="adresEkle.php" class="btn btn-primary">+ Yeni Adres Ekle</a>
                </div>

                <!-- Sepetim -->
                <div id="cart" class="tab-content">
                    <h2 class="content-title">Sepetim</h2>
                    <?php
                    $sql = "SELECT sp.sepetID, u.urunAdi, u.urunFiyat, r.resimYolu, sp.sepetUrunMiktar
                            FROM t_sepet sp
                            INNER JOIN t_urunler u ON sp.sepetUrunID = u.urunID
                            LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
                            LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
                            WHERE sp.sepetUyeID = $uyeID 
                            AND sp.sepetGorunurluk = 1
                            GROUP BY sp.sepetID";
                    $result = $baglan->query($sql);

                    if ($result->num_rows > 0) {
                        $toplamTutar = 0;
                        while ($row = $result->fetch_assoc()) {
                            $urunToplam = $row['urunFiyat'] * $row['sepetUrunMiktar'];
                            $toplamTutar += $urunToplam;

                            echo '<div class="cart-item">';
                            echo '<div class="product-item">';
                            echo '<div class="product-image">';
                            if (!empty($row['resimYolu'])) {
                                echo '<img src="' . $row['resimYolu'] . '" alt="' . $row['urunAdi'] . '">';
                            } else {
                                echo '<img src="default-image.jpg" alt="Varsayılan Resim">'; // Varsayılan resim
                            }
                            echo '</div>';
                            echo '<div class="product-details">';
                            echo '<div class="product-name">' . htmlspecialchars($row['urunAdi']) . '</div>';
                            echo '<div class="product-meta">';
                            echo '<form action="sepetGuncelle.php" method="POST" style="display: flex; align-items: center;">';
                            echo '<input type="hidden" name="sepetID" value="' . $row['sepetID'] . '">';
                            echo '<button type="submit" name="action" value="decrease" class="btn btn-outline">-</button>';
                            echo '<input type="number" name="miktar" value="' . $row['sepetUrunMiktar'] . '" readonly style="width: 50px; text-align: center; margin: 0 10px;">';
                            echo '<button type="submit" name="action" value="increase" class="btn btn-outline">+</button>';
                            echo '</form>';
                            echo '</div>';
                            echo '</div>';
                            echo '<div class="product-price">' . number_format($urunToplam, 2) . ' TL</div>';
                            echo '</div>';
                            echo '<div class="cart-actions">';
                            echo '<a href="sepetSil.php?sepetID=' . $row['sepetID'] . '" class="btn btn-outline" style="color: red;">Kaldır</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                        echo '<div class="cart-total">Toplam Tutar: ' . number_format($toplamTutar, 2) . ' TL</div>';
                        echo '<a href="odeme.php" class="btn btn-primary">Ödeme Yap</a>';
                    } else {
                        echo '<p>Sepetinizde ürün bulunmamaktadır.</p>';
                    }
                    ?>
                </div>

            </div>
        </div>
    </main>

    <!-- Modal -->
    <div id="orderDetailsModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2>Sipariş Detayları</h2>
            <div id="orderDetailsContent">
                <!-- Sipariş detayları buraya yüklenecek -->
            </div>
        </div>
    </div>

    <script>
        // Sayfa yüklendiğinde doğru sekmeyi göster
        document.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1); // URL'deki hash değerini al
            if (hash) {
                showTab(hash); // Hash varsa ilgili sekmeyi göster
            } else {
                showTab('profile'); // Hash yoksa varsayılan olarak 'profile' sekmesini göster
            }
        });

        function showTab(tabId) {
            // Önce tüm tabları gizle
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Seçilen tabı göster
            const activeTab = document.getElementById(tabId);
            if (activeTab) {
                activeTab.classList.add('active');
            }

            // Menü öğelerinin active class'ını güncelle
            const menuItems = document.querySelectorAll('.profile-menu a');
            menuItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === '#' + tabId) {
                    item.classList.add('active');
                }
            });
        }

        function showOrderDetails(siparisID) {
            // Modal'ı göster
            const modal = document.getElementById('orderDetailsModal');
            modal.style.display = 'block';

            // Sipariş detaylarını yükle
            const content = document.getElementById('orderDetailsContent');
            content.innerHTML = '<p>Yükleniyor...</p>';

            // AJAX ile sipariş detaylarını çek
            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'siparisDetay.php?siparisID=' + siparisID, true);
            xhr.onload = function() {
                if (this.status === 200) {
                    content.innerHTML = this.responseText;
                } else {
                    content.innerHTML = '<p>Detaylar yüklenemedi.</p>';
                }
            };
            xhr.send();
        }

        function closeModal() {
            const modal = document.getElementById('orderDetailsModal');
            modal.style.display = 'none';
        }
    </script>
</body>

</html>