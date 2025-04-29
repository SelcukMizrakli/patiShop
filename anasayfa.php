<?php
// filepath: c:\xampp\htdocs\patishop\anasayfa.php
include 'ayar.php';
session_start();
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patishop - Evcil Hayvan Ürünleri</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 15px;
        }

        .header {
            background-color: #fff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .top-bar {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }

        .logo-container {
            display: flex;
            align-items: center;
            padding: 10px 0;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .user-profile {
            display: flex;
            align-items: center;
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }

        .user-profile i {
            margin-right: 5px;
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

        .logo i {
            margin-right: 10px;
            font-size: 28px;
        }

        .search-login {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            margin-left: auto;
        }

        .search-container {
            position: relative;
            margin-right: 20px;
        }

        .search-container input {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 20px;
            width: 250px;
            font-size: 14px;
        }

        .search-container button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
        }

        .login-btn {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .login-btn:hover {
            background-color: #3e8e41;
        }

        .navbar {
            background-color: #f1f1f1;
            overflow: hidden;
        }

        .navbar ul {
            list-style-type: none;
            display: flex;
            padding: 0;
        }

        .navbar li {
            padding: 0;
        }

        .navbar a {
            display: block;
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: #4CAF50;
        }

        .hero {
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('/api/placeholder/1200/400');
            background-size: cover;
            background-position: center;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            margin-bottom: 30px;
        }

        .hero-content h1 {
            font-size: 48px;
            margin-bottom: 20px;
        }

        .hero-content p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .cta-button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 16px;
            border-radius: 25px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .cta-button:hover {
            background-color: #3e8e41;
        }

        .category-section {
            margin: 40px 0;
        }

        .section-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            position: relative;
            padding-bottom: 10px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: #4CAF50;
        }

        .category-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .category-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
        }

        .category-card .content {
            padding: 15px;
        }

        .category-card h3 {
            margin-bottom: 8px;
            font-size: 18px;
            color: #333;
        }

        .category-card p {
            color: #666;
            font-size: 14px;
            margin-bottom: 15px;
        }

        .product-section {
            margin: 40px 0;
        }

        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card .content {
            padding: 15px;
        }

        .product-card h3 {
            margin-bottom: 8px;
            font-size: 16px;
            color: #333;
        }

        .product-card .price {
            color: #4CAF50;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 10px;
        }

        .product-card .rating {
            color: #ffc107;
            margin-bottom: 10px;
        }

        .add-to-cart {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #3e8e41;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
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
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover {
            color: black;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-submit {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s;
        }

        .form-submit:hover {
            background-color: #3e8e41;
        }

        .footer {
            background-color: #333;
            color: white;
            padding: 40px 0;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }

        .footer-section h3 {
            font-size: 18px;
            margin-bottom: 15px;
            position: relative;
            padding-bottom: 10px;
        }

        .footer-section h3::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 40px;
            height: 2px;
            background-color: #4CAF50;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 8px;
        }

        .footer-section ul li a {
            color: #ccc;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section ul li a:hover {
            color: #4CAF50;
        }

        .footer-bottom {
            margin-top: 30px;
            text-align: center;
            padding-top: 20px;
            border-top: 1px solid #444;
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<body>
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="container">
            Türkiye'nin her yerine ücretsiz kargo! 200 TL ve üzeri siparişlerde geçerlidir.
        </div>
    </div>

    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo-container">
                <a href="#" class="logo">
                    <i class="fas fa-paw"></i> PatiShop
                </a>
                <div class="search-login">
                    <div class="search-container">
                        <input type="text" placeholder="Ürün, kategori veya marka ara...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="user-actions">
                        <?php if (isset($_SESSION['uyeID'])): ?>
                            <div class="dropdown">
                                <button class="user-profile">
                                    <i class="fas fa-user"></i> <?php echo $_SESSION['uyeAd'] . ' ' . $_SESSION['uyeSoyad']; ?>
                                </button>
                                <div class="dropdown-content">
                                    <a href="profil.php"><i class="fas fa-user-circle"></i> Profilim</a>
                                    <a href="cikisYap.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <button class="login-btn" id="loginBtn"><i class="fas fa-user"></i> Giriş Yap</button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="navbar">
            <div class="container">
                <ul>
                    <?php
                    $sql = "SELECT hayvanTurAdi, hayvanTurSlug FROM t_hayvanturleri";
                    $result = $baglan->query($sql);

                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<li><a href="kategori.php?tur=' . $row['hayvanTurSlug'] . '"><i class="fas fa-paw"></i> ' . $row['hayvanTurAdi'] . '</a></li>';
                        }
                    } else {
                        echo '<li>Kategori bulunamadı.</li>';
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <h1>Patili Dostlarınız İçin En İyi Ürünler</h1>
            <p>Kaliteli mama, sağlık ürünleri, oyuncaklar ve daha fazlası...</p>
            <a href="#" class="cta-button">Alışverişe Başla</a>
        </div>
    </section>

    <!-- Main Content -->
    <main class="container">
        <!-- Categories Section -->
        <section class="category-section">
            <h2 class="section-title">Hayvan Kategorileri</h2>
            <div class="category-grid">
                <?php
                $sql = "SELECT kategoriAdi, kategoriSlug, kategoriIkonUrl FROM t_kategori WHERE kategoriDurum = 1";
                $result = $baglan->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<div class="category-card">';
                        echo '<img src="' . $row['kategoriIkonUrl'] . '" alt="' . $row['kategoriAdi'] . '">';
                        echo '<div class="content">';
                        echo '<h3>' . $row['kategoriAdi'] . '</h3>';
                        echo '<p>' . $row['kategoriAdi'] . ' için özel ürünler</p>';
                        echo '<a href="kategori.php?kategori=' . $row['kategoriSlug'] . '" class="cta-button">Keşfet</a>';
                        echo '</div>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Kategori bulunamadı.</p>';
                }
                ?>
            </div>
        </section>

        <!-- Products Section -->
        <section class="product-section">
            <h2 class="section-title">Popüler Ürünler</h2>
            <div class="product-grid">
                <?php
                $sql = "SELECT urunID, urunAdi, urunFiyat, urunResimID FROM t_urunler LIMIT 4";
                $result = $baglan->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Resim yolunu almak için t_resimler tablosunu kullanıyoruz
                        $resimSql = "SELECT resimYolu FROM t_resimler WHERE resimID = " . $row['urunResimID'];
                        $resimResult = $baglan->query($resimSql);
                        $resim = $resimResult->fetch_assoc();

                        echo '<div class="product-card">';
                        echo '<a href="urundetay.php?urunID=' . $row['urunID'] . '">'; // Ürün detay sayfasına yönlendirme
                        echo '<img src="' . $resim['resimYolu'] . '" alt="' . $row['urunAdi'] . '">';
                        echo '<div class="content">';
                        echo '<h3>' . $row['urunAdi'] . '</h3>';
                        echo '<div class="price">' . number_format($row['urunFiyat'], 2) . ' TL</div>';
                        echo '</div>';
                        echo '</a>';
                        echo '<button class="add-to-cart" onclick="addToCart(' . $row['urunID'] . ')">Sepete Ekle</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>Ürün bulunamadı.</p>';
                }
                ?>
            </div>
        </section>

        <section class="product-section">
            <h2 class="section-title">İndirimli Ürünler</h2>
            <div class="product-grid">
                <?php
                $sql = "SELECT u.urunID, u.urunAdi, u.urunFiyat, u.urunResimID, k.kampanyaIndirimYuzdesi 
                        FROM t_urunler u
                        INNER JOIN t_kampanya k ON u.urunID = k.kampanyaID
                        LIMIT 4";
                $result = $baglan->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        // Resim yolunu almak için t_resimler tablosunu kullanıyoruz
                        $resimSql = "SELECT resimYolu FROM t_resimler WHERE resimID = " . $row['urunResimID'];
                        $resimResult = $baglan->query($resimSql);
                        $resim = $resimResult->fetch_assoc();

                        $indirimliFiyat = $row['urunFiyat'] * (1 - $row['kampanyaIndirimYuzdesi'] / 100);

                        echo '<div class="product-card">';
                        echo '<a href="urundetay.php?urunID=' . $row['urunID'] . '">'; // Ürün detay sayfasına yönlendirme
                        echo '<img src="' . $resim['resimYolu'] . '" alt="' . $row['urunAdi'] . '">';
                        echo '<div class="content">';
                        echo '<h3>' . $row['urunAdi'] . '</h3>';
                        echo '<div class="price">' . number_format($indirimliFiyat, 2) . ' TL <span style="text-decoration: line-through; color: #999; font-size: 14px;">' . number_format($row['urunFiyat'], 2) . ' TL</span></div>';
                        echo '</div>';
                        echo '</a>';
                        echo '<button class="add-to-cart" onclick="addToCart(' . $row['urunID'] . ')">Sepete Ekle</button>';
                        echo '</div>';
                    }
                } else {
                    echo '<p>İndirimli ürün bulunamadı.</p>';
                }
                ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>Kurumsal</h3>
                    <ul>
                        <li><a href="#">Hakkımızda</a></li>
                        <li><a href="#">Kariyer</a></li>
                        <li><a href="#">İletişim</a></li>
                        <li><a href="#">Mağazalarımız</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Müşteri Hizmetleri</h3>
                    <ul>
                        <li><a href="#">Sıkça Sorulan Sorular</a></li>
                        <li><a href="#">Kargo ve Teslimat</a></li>
                        <li><a href="#">İade ve Değişim</a></li>
                        <li><a href="#">Gizlilik Politikası</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Kategoriler</h3>
                    <ul>
                        <li><a href="#">Köpek</a></li>
                        <li><a href="#">Kedi</a></li>
                        <li><a href="#">Balık</a></li>
                        <li><a href="#">Kuş</a></li>
                        <li><a href="#">Kemirgen</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>İletişim</h3>
                    <ul>
                        <li><i class="fas fa-map-marker-alt"></i> Atatürk Cad. No:123 Kadıköy/İstanbul</li>
                        <li><i class="fas fa-phone"></i> 0850 123 45 67</li>
                        <li><i class="fas fa-envelope"></i> info@patishop.com</li>
                    </ul>
                    <div style="margin-top: 15px;">
                        <a href="#" style="color: white; margin-right: 15px;"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" style="color: white; margin-right: 15px;"><i class="fab fa-instagram"></i></a>
                        <a href="#" style="color: white; margin-right: 15px;"><i class="fab fa-twitter"></i></a>
                        <a href="#" style="color: white;"><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2025 PatiShop. Tüm hakları saklıdır.</p>
            </div>
        </div>
    </footer>

    <!-- Login Modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 style="text-align: center; margin-bottom: 20px;">Giriş Yap</h2>
            <form action="girisYap.php" method="POST">
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="form-submit">Giriş Yap</button>
            </form>
            <p style="text-align: center; margin-top: 15px;">
                Hesabınız yok mu? <button id="registerbtn" style="color: #4CAF50; text-decoration: none;">Kayıt ol</button>
            </p>
        </div>
    </div>
    <!-- Register Modal -->
    <div id="registerModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 style="text-align: center; margin-bottom: 20px;">Kayıt Ol</h2>
            <form>
                <div class="form-group">
                    <label for="name">Ad</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="lastname">Soyad</label>
                    <input type="text" id="lastname" name="lastname" required>
                </div>
                <div class="form-group">
                    <label for="email">E-posta</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Şifre</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="form-submit">Kayıt Ol</button>
                <p style="text-align: center; margin-top: 15px;">
                    Hesabınız var mı? <button id="loginbtn" style="color: #4CAF50; text-decoration: none;">Giriş Yap</button>
                </p>
            </form>
        </div>
    </div>

    <script>
        // Login Modal
        var modalLogin = document.getElementById("loginModal");
        var btnLogin = document.getElementById("loginBtn");
        var spanLogin = document.getElementsByClassName("close")[0];

        btnLogin.onclick = function() {
            modalLogin.style.display = "block";
        }

        spanLogin.onclick = function() {
            modalLogin.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modalLogin) {
                modalLogin.style.display = "none";
            }
        }

        // Register Modal
        var modalRegister = document.getElementById("registerModal");
        var btnRegister = document.getElementById("registerbtn");
        var spanRegister = document.getElementsByClassName("close")[1];

        btnRegister.onclick = function() {
            modalLogin.style.display = "none";
            modalRegister.style.display = "block";
        }

        spanRegister.onclick = function() {
            modalRegister.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modalRegister) {
                modalRegister.style.display = "none";
            }
        }

        // Switch to Login Modal from Register Modal            giriş yap kayıt ol formu düzenlenecek
        var switchToLogin = document.getElementById("loginbtn");
        switchToLogin.onclick = function(event) {
            event.preventDefault();
            modalRegister.style.display = "none";
            modalLogin.style.display = "block";
        }
    </script>
</body>

</html>