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
                    <li><a href="#" style="color: #f44336;">Çıkış Yap</a></li>
                </ul>
            </div>

            <!-- Main Content Area -->
            <div class="profile-content">
                <!-- Profil Bilgileri -->
                <div id="profile" class="tab-content active">
                    <h2 class="content-title">Profil Bilgileri</h2>

                    <form>
                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="firstName">Ad</label>
                                    <input type="text" id="firstName" class="form-control" value="Selçuk">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="lastName">Soyad</label>
                                    <input type="text" id="lastName" class="form-control" value="Mızraklı">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email">E-posta</label>
                            <input type="email" id="email" class="form-control" value="selcukmizrakli20@gmail.com">
                        </div>

                        <div class="form-group">
                            <label for="phone">Telefon</label>
                            <input type="tel" id="phone" class="form-control" value="0531 317 3971">
                        </div>

                        <div class="form-row">
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="currentPassword">Mevcut Şifre</label>
                                    <input type="password" id="currentPassword" class="form-control">
                                </div>
                            </div>
                            <div class="form-col">
                                <div class="form-group">
                                    <label for="newPassword">Yeni Şifre</label>
                                    <input type="password" id="newPassword" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">Bilgileri Güncelle</button>
                        </div>
                    </form>
                </div>

                <!-- Siparişlerim -->
                <div id="orders" class="tab-content">
                    <h2 class="content-title">Geçmiş Siparişlerim</h2>

                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Sipariş #12345</span>
                            <span class="order-date">15 Mart 2025</span>
                            <span class="order-status status-delivered">Teslim Edildi</span>
                        </div>

                        <div class="product-list">
                            <div class="product-item">
                                <div class="product-image"></div>
                                <div class="product-details">
                                    <div class="product-name">Royal Canin Kedi Maması</div>
                                    <div class="product-meta">1 kg, Yetişkin</div>
                                </div>
                                <div class="product-price">249 ₺</div>
                            </div>
                            <div class="product-item">
                                <div class="product-image"></div>
                                <div class="product-details">
                                    <div class="product-name">Kedi Oyuncağı Set</div>
                                    <div class="product-meta">5 Parça</div>
                                </div>
                                <div class="product-price">120 ₺</div>
                            </div>
                        </div>

                        <div class="order-total">
                            Toplam: 369 ₺
                        </div>
                    </div>

                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Sipariş #12289</span>
                            <span class="order-date">2 Şubat 2025</span>
                            <span class="order-status status-delivered">Teslim Edildi</span>
                        </div>

                        <div class="product-list">
                            <div class="product-item">
                                <div class="product-image"></div>
                                <div class="product-details">
                                    <div class="product-name">Pro Plan Köpek Maması</div>
                                    <div class="product-meta">3 kg, Yavru</div>
                                </div>
                                <div class="product-price">450 ₺</div>
                            </div>
                            <div class="product-item">
                                <div class="product-image"></div>
                                <div class="product-details">
                                    <div class="product-name">Köpek Tasması</div>
                                    <div class="product-meta">Medium, Kırmızı</div>
                                </div>
                                <div class="product-price">85 ₺</div>
                            </div>
                        </div>

                        <div class="order-total">
                            Toplam: 535 ₺
                        </div>
                    </div>
                </div>

                <!-- Güncel Siparişim -->
                <div id="current-order" class="tab-content">
                    <h2 class="content-title">Güncel Siparişim</h2>

                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-id">Sipariş #12789</span>
                            <span class="order-date">10 Nisan 2025</span>
                            <span class="order-status status-processing">Hazırlanıyor</span>
                        </div>

                        <div class="product-list">
                            <div class="product-item">
                                <div class="product-image"></div>
                                <div class="product-details">
                                    <div class="product-name">N&D Köpek Maması</div>
                                    <div class="product-meta">2.5 kg, Tahılsız</div>
                                </div>
                                <div class="product-price">350 ₺</div>
                            </div>
                            <div class="product-item">
                                <div class="product-image"></div>
                                <div class="product-details">
                                    <div class="product-name">Köpek Diş Bakım Seti</div>
                                    <div class="product-meta">3 Parça</div>
                                </div>
                                <div class="product-price">95 ₺</div>
                            </div>
                        </div>

                        <div class="order-total">
                            Toplam: 445 ₺
                        </div>

                        <div style="margin-top: 20px;">
                            <h4>Kargo Takibi</h4>
                            <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin-top: 10px;">
                                <div style="display: flex; justify-content: space-between; margin-bottom: 20px;">
                                    <div style="text-align: center; flex: 1;">
                                        <div style="width: 25px; height: 25px; background-color: var(--primary-color); border-radius: 50%; margin: 0 auto; color: white; line-height: 25px;">✓</div>
                                        <div style="font-size: 12px; margin-top: 5px;">Sipariş Alındı</div>
                                    </div>
                                    <div style="text-align: center; flex: 1;">
                                        <div style="width: 25px; height: 25px; background-color: var(--primary-color); border-radius: 50%; margin: 0 auto; color: white; line-height: 25px;">✓</div>
                                        <div style="font-size: 12px; margin-top: 5px;">Hazırlanıyor</div>
                                    </div>
                                    <div style="text-align: center; flex: 1;">
                                        <div style="width: 25px; height: 25px; background-color: #ddd; border-radius: 50%; margin: 0 auto;"></div>
                                        <div style="font-size: 12px; margin-top: 5px;">Kargoya Verildi</div>
                                    </div>
                                    <div style="text-align: center; flex: 1;">
                                        <div style="width: 25px; height: 25px; background-color: #ddd; border-radius: 50%; margin: 0 auto;"></div>
                                        <div style="font-size: 12px; margin-top: 5px;">Teslim Edildi</div>
                                    </div>
                                </div>
                                <div style="font-size: 14px; color: #555;">
                                    <p>Siparişiniz hazırlanıyor. En kısa sürede kargoya verilecektir.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Favorilerim -->
                <div id="favorites" class="tab-content">
                    <h2 class="content-title">Favori Ürünlerim</h2>

                    <div class="favorite-card">
                        <div class="product-item">
                            <div class="product-image"></div>
                            <div class="product-details">
                                <div class="product-name">Royal Canin Sterilised Kedi Maması</div>
                                <div class="product-meta">10 kg</div>
                                <div class="fav-date">12 Mart 2025 tarihinde eklendi</div>
                            </div>
                            <div class="product-price">899 ₺</div>
                        </div>
                        <div class="favorite-actions">
                            <a href="#" class="btn btn-primary">Sepete Ekle</a>
                            <a href="#" class="btn btn-outline">Favorilerden Çıkar</a>
                        </div>
                    </div>

                    <div class="favorite-card">
                        <div class="product-item">
                            <div class="product-image"></div>
                            <div class="product-details">
                                <div class="product-name">Otomatik Su Kabı</div>
                                <div class="product-meta">2.5 Litre</div>
                                <div class="fav-date">29 Mart 2025 tarihinde eklendi</div>
                            </div>
                            <div class="product-price">175 ₺</div>
                        </div>
                        <div class="favorite-actions">
                            <a href="#" class="btn btn-primary">Sepete Ekle</a>
                            <a href="#" class="btn btn-outline">Favorilerden Çıkar</a>
                        </div>
                    </div>

                    <div class="favorite-card">
                        <div class="product-item">
                            <div class="product-image"></div>
                            <div class="product-details">
                                <div class="product-name">Kedi Tırmalama Tahtası</div>
                                <div class="product-meta">Büyük Boy</div>
                                <div class="fav-date">5 Nisan 2025 tarihinde eklendi</div>
                            </div>
                            <div class="product-price">280 ₺</div>
                        </div>
                        <div class="favorite-actions">
                            <a href="#" class="btn btn-primary">Sepete Ekle</a>
                            <a href="#" class="btn btn-outline">Favorilerden Çıkar</a>
                        </div>
                    </div>
                </div>

                <!-- Adreslerim -->
                <div id="addresses" class="tab-content">
                    <h2 class="content-title">Adreslerim</h2>

                    <div style="margin-bottom: 20px;">
                        <div style="background: #f5f5f5; padding: 15px; border-radius: 5px; margin-bottom: 15px;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                                <h4>Ev Adresi</h4>
                                <span style="background: #e8f5e9; color: #2e7d32; padding: 3px 10px; border-radius: 20px; font-size: 12px;">Varsayılan</span>
                            </div>
                            <p style="margin-bottom: 10px;">Selçuk Mızraklı</p>
                            <p style="margin-bottom: 5px;">Atatürk Mahallesi, Gül Sokak No:42 D:5</p>
                            <p style="margin-bottom: 5px;">Kadıköy / İstanbul</p>
                            <p style="margin-bottom: 10px;">0531 317 3971</p>

                            <div style="display: flex; gap: 10px;">
                                <a href="#" class="btn btn-outline">Düzenle</a>
                                <a href="#" class="btn" style="color: #f44336; border: 1px solid #f44336; background: white;">Sil</a>
                            </div>
                        </div>

                        <div style="background: #f5f5f5; padding: 15px; border-radius: 5px;">
                            <div style="margin-bottom: 10px;">
                                <h4>İş Adresi</h4>
                            </div>
                            <p style="margin-bottom: 10px;">Selçuk Mızraklı</p>
                            <p style="margin-bottom: 5px;">Barbaros Bulvarı, Yıldız Plaza No:127 Kat:8</p>
                            <p style="margin-bottom: 5px;">Beşiktaş / İstanbul</p>
                            <p style="margin-bottom: 10px;">0531 317 3971</p>

                            <div style="display: flex; gap: 10px;">
                                <a href="#" class="btn btn-outline">Düzenle</a>
                                <a href="#" class="btn" style="color: #f44336; border: 1px solid #f44336; background: white;">Sil</a>
                                <a href="#" class="btn btn-outline">Varsayılan Yap</a>
                            </div>
                        </div>
                    </div>

                    <a href="#" class="btn btn-primary">+ Yeni Adres Ekle</a>
                </div>
            </div>
        </div>
    </main>

    <script>
        function showTab(tabId) {
            // Önce tüm tabları gizle
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Seçilen tabı göster
            document.getElementById(tabId).classList.add('active');

            // Menü öğelerinin active class'ını güncelle
            const menuItems = document.querySelectorAll('.profile-menu a');
            menuItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === '#' + tabId) {
                    item.classList.add('active');
                }
            });
        }
    </script>
</body>

</html>