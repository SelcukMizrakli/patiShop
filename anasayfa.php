<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiShop - Evcil Hayvan Ürünleri</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background-color: #f5f5f5;
        }

        .top-banner {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 14px;
        }

        header {
            background-color: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            color: #4CAF50;
            font-size: 24px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo i {
            font-size: 28px;
            margin-right: 8px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            flex-grow: 0.5;
            max-width: 500px;
        }

        .search-bar input {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 25px;
            font-size: 14px;
        }

        .search-bar button {
            background-color: white;
            border: none;
            margin-left: -40px;
            cursor: pointer;
        }

        .user-actions {
            display: flex;
            align-items: center;
        }

        .btn-primary {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
        }

        .btn-primary i {
            margin-right: 5px;
        }

        nav {
            background-color: #f9f9f9;
            padding: 15px 40px;
            border-bottom: 1px solid #eee;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        nav a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        nav a i {
            margin-right: 5px;
        }

        .hero {
            background-color: #555;
            color: white;
            padding: 80px 40px;
            text-align: center;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5));
            background-size: cover;
            background-position: center;
        }

        .hero h1 {
            font-size: 40px;
            margin-bottom: 20px;
        }

        .hero p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        .btn-hero {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 25px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            display: inline-block;
        }

        .section-title {
            margin: 40px 0 20px 40px;
            color: #333;
            font-size: 24px;
            border-left: 4px solid #4CAF50;
            padding-left: 12px;
        }

        .category-container {
            display: flex;
            justify-content: space-between;
            padding: 0 40px;
            margin-bottom: 40px;
            gap: 20px;
        }

        .category-card {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            flex: 1;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .category-img {
            text-align: center;
            margin-bottom: 15px;
        }

        .category-img img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
        }

        .category-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }

        .category-desc {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
        }

        .btn-secondary {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
            display: inline-block;
            margin-top: auto;
            align-self: flex-start;
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
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="top-banner">
        Türkiye'nin her yerine ücretsiz kargo! 200 TL ve üzeri siparişlerde geçerlidir.
    </div>

    <header>
        <a href="#" class="logo">
            <i class="fas fa-paw"></i> PatiShop
        </a>
        <div class="search-bar">
            <input type="text" placeholder="Ürün, kategori veya marka ara...">
            <button type="submit"><i class="fas fa-search"></i></button>
        </div>
        <div class="user-actions">
            <div class="dropdown">
                <button class="user-profile">
                    <i class="fas fa-user"></i> Hesabım
                </button>
                <div class="dropdown-content">
                    <a href="#"><i class="fas fa-user-circle"></i> Profil</a>
                    <a href="#"><i class="fas fa-heart"></i> Favorilerim</a>
                    <a href="#"><i class="fas fa-box"></i> Siparişlerim</a>
                    <a href="#"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a>
                </div>
            </div>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="#"><i class="fas fa-home"></i> Ana Sayfa</a></li>
            <li><a href="#"><i class="fas fa-dog"></i> Köpek</a></li>
            <li><a href="#"><i class="fas fa-cat"></i> Kedi</a></li>
            <li><a href="#"><i class="fas fa-fish"></i> Balık</a></li>
            <li><a href="#"><i class="fas fa-crow"></i> Kuş</a></li>
            <li><a href="#"><i class="fas fa-bug"></i> Kemirgen</a></li>
            <li><a href="#"><i class="fas fa-percent"></i> Kampanyalar</a></li>
            <li><a href="#"><i class="fas fa-star"></i> Yeni Ürünler</a></li>
        </ul>
    </nav>

    <section class="hero">
        <h1>Patili Dostlarınız İçin En İyi Ürünler</h1>
        <p>Kaliteli mama, sağlık ürünleri, oyuncaklar ve daha fazlası...</p>
        <button class="btn-hero">Alışverişe Başla</button>
    </section>

    <h2 class="section-title">Hayvan Kategorileri</h2>
    <div class="category-container">
        <div class="category-card">
            <div class="category-img">
                <img src="/api/placeholder/150/150" alt="Köpek Ürünleri">
            </div>
            <h3 class="category-title">Köpek Ürünleri</h3>
            <p class="category-desc">Dostunuz için mama, oyuncak ve bakım ürünleri</p>
            <button class="btn-secondary">Keşfet</button>
        </div>
        <div class="category-card">
            <div class="category-img">
                <img src="/api/placeholder/150/150" alt="Kedi Ürünleri">
            </div>
            <h3 class="category-title">Kedi Ürünleri</h3>
            <p class="category-desc">Kediler için özel ürün ve aksesuarlar</p>
            <button class="btn-secondary">Keşfet</button>
        </div>
        <div class="category-card">
            <div class="category-img">
                <img src="/api/placeholder/150/150" alt="Balık Ürünleri">
            </div>
            <h3 class="category-title">Balık Ürünleri</h3>
            <p class="category-desc">Akvaryum ve balık bakım ürünleri</p>
            <button class="btn-secondary">Keşfet</button>
        </div>
        <div class="category-card">
            <div class="category-img">
                <img src="/api/placeholder/150/150" alt="Kuş Ürünleri">
            </div>
            <h3 class="category-title">Kuş Ürünleri</h3>
            <p class="category-desc">Kuşlar için kafes, yem ve aksesuarlar</p>
            <button class="btn-secondary">Keşfet</button>
        </div>
    </div>

    <h2 class="section-title">Popüler Ürünler</h2>
    <!-- Popüler ürünler içeriği buraya eklenebilir -->
</body>

</html>