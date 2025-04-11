<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Premium Köpek Maması - PatiShop</title>
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
            padding: 10px 0;
            font-size: 14px;
        }

        header {
            background-color: white;
            padding: 15px 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .logo {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .logo h1 {
            color: #4CAF50;
            font-size: 24px;
            margin-left: 10px;
        }

        .logo-icon {
            color: #4CAF50;
            font-size: 28px;
        }

        .search-bar {
            display: flex;
            align-items: center;
            width: 40%;
        }

        .search-bar input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 20px;
            outline: none;
        }

        .search-bar button {
            background: white;
            border: none;
            margin-left: -40px;
            cursor: pointer;
        }

        .user-actions button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: bold;
        }

        nav {
            background-color: #f9f9f9;
            padding: 10px 5%;
            border-bottom: 1px solid #eee;
        }

        nav ul {
            display: flex;
            list-style: none;
        }

        nav ul li {
            margin-right: 20px;
        }

        nav ul li a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
        }

        nav ul li a i {
            margin-right: 5px;
        }

        .breadcrumb {
            padding: 15px 5%;
            background-color: white;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .breadcrumb a {
            color: #4CAF50;
            text-decoration: none;
        }

        .breadcrumb span {
            color: #999;
            margin: 0 5px;
        }

        .product-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            flex-wrap: wrap;
        }

        .product-gallery {
            flex: 0 0 40%;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .main-image {
            width: 100%;
            height: 400px;
            background-color: #f9f9f9;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            border-radius: 4px;
            overflow: hidden;
        }

        .main-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .thumbnails {
            display: flex;
            gap: 10px;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            background-color: #f9f9f9;
            border: 2px solid #eee;
            border-radius: 4px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumbnail img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .thumbnail.active {
            border-color: #4CAF50;
        }

        .product-info {
            flex: 0 0 55%;
            margin-left: 5%;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .product-name {
            font-size: 24px;
            margin-bottom: 10px;
            color: #333;
        }

        .product-brand {
            color: #666;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .product-rating {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .stars {
            color: #FFD700;
            font-size: 20px;
            margin-right: 10px;
        }

        .review-count {
            color: #4CAF50;
            text-decoration: none;
        }

        .product-price {
            font-size: 28px;
            font-weight: bold;
            color: #4CAF50;
            margin-bottom: 20px;
        }

        .old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 18px;
            margin-left: 10px;
            font-weight: normal;
        }

        .discount {
            background-color: #ff6b6b;
            color: white;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 14px;
            margin-left: 10px;
        }

        .product-variants {
            margin-bottom: 20px;
        }

        .variant-label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        .variant-options {
            display: flex;
            gap: 10px;
        }

        .variant-option {
            border: 2px solid #ddd;
            padding: 8px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        .variant-option.selected {
            border-color: #4CAF50;
            background-color: #e8f5e9;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .quantity-selector button {
            width: 40px;
            height: 40px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
            font-size: 18px;
            cursor: pointer;
        }

        .quantity-selector input {
            width: 60px;
            height: 40px;
            border: 1px solid #ddd;
            text-align: center;
            font-size: 16px;
            margin: 0 5px;
        }

        .add-to-cart {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 15px 0;
            width: 100%;
            border-radius: 4px;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 20px;
            transition: background-color 0.3s;
        }

        .add-to-cart:hover {
            background-color: #45a049;
        }

        .delivery-info {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 15px 0;
            margin-bottom: 20px;
        }

        .delivery-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .delivery-icon {
            margin-right: 10px;
            color: #4CAF50;
            font-size: 18px;
        }

        .product-description {
            margin-top: 40px;
        }

        .description-title {
            font-size: 20px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .description-content {
            line-height: 1.6;
            color: #666;
        }

        .description-content p {
            margin-bottom: 15px;
        }

        .product-specs {
            margin-top: 30px;
        }

        .specs-table {
            width: 100%;
            border-collapse: collapse;
        }

        .specs-table tr:nth-child(odd) {
            background-color: #f9f9f9;
        }

        .specs-table td {
            padding: 10px;
            border: 1px solid #eee;
        }

        .specs-table td:first-child {
            font-weight: bold;
            width: 30%;
        }

        footer {
            background-color: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 50px;
        }
    </style>
</head>

<body>
    <div class="top-banner">
        Türkiye'nin her yerine ücretsiz kargo! 200 TL ve üzeri siparişlerde geçerlidir.
    </div>

    <header>
        <a href="#" class="logo">
            <span class="logo-icon">🐾</span>
            <h1>PatiShop</h1>
        </a>

        <div class="search-bar">
            <input type="text" placeholder="Ürün, kategori veya marka ara...">
            <button>🔍</button>
        </div>

        <div class="user-actions">
            <button>Giriş Yap</button>
        </div>
    </header>

    <nav>
        <ul>
            <li><a href="#"><i>🏠</i> Ana Sayfa</a></li>
            <li><a href="#"><i>🐕</i> Köpek</a></li>
            <li><a href="#"><i>🐈</i> Kedi</a></li>
            <li><a href="#"><i>🐠</i> Balık</a></li>
            <li><a href="#"><i>🐦</i> Kuş</a></li>
            <li><a href="#"><i>🦔</i> Kemirgen</a></li>
            <li><a href="#"><i>🏷️</i> Kampanyalar</a></li>
            <li><a href="#"><i>🆕</i> Yeni Ürünler</a></li>
        </ul>
    </nav>

    <div class="breadcrumb">
        <a href="#">Ana Sayfa</a> <span>></span>
        <a href="#">Köpek</a> <span>></span>
        <a href="#">Köpek Mamaları</a> <span>></span>
        <a href="#">Premium Köpek Maması</a>
    </div>

    <div class="product-container">
        <div class="product-gallery">
            <div class="main-image">
                <img src="/api/placeholder/400/320" alt="Premium Köpek Maması">
            </div>
            <div class="thumbnails">
                <div class="thumbnail active">
                    <img src="/api/placeholder/80/80" alt="Premium Köpek Maması Görsel 1">
                </div>
                <div class="thumbnail">
                    <img src="/api/placeholder/80/80" alt="Premium Köpek Maması Görsel 2">
                </div>
                <div class="thumbnail">
                    <img src="/api/placeholder/80/80" alt="Premium Köpek Maması Görsel 3">
                </div>
                <div class="thumbnail">
                    <img src="/api/placeholder/80/80" alt="Premium Köpek Maması Görsel 4">
                </div>
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-name">PatiPlus Premium Tahılsız Kuzu Etli Köpek Maması</h1>
            <div class="product-brand">Marka: PatiPlus</div>

            <div class="product-rating">
                <div class="stars">★★★★★</div>
                <a href="#reviews" class="review-count">(128 Değerlendirme)</a>
            </div>

            <div class="product-price">
                349,90 TL <span class="old-price">399,90 TL</span> <span class="discount">%13</span>
            </div>

            <div class="product-variants">
                <label class="variant-label">Paket Boyutu:</label>
                <div class="variant-options">
                    <div class="variant-option">3 kg</div>
                    <div class="variant-option selected">5 kg</div>
                    <div class="variant-option">10 kg</div>
                    <div class="variant-option">15 kg</div>
                </div>
            </div>

            <div class="quantity-selector">
                <button>-</button>
                <input type="number" value="1" min="1">
                <button>+</button>
            </div>

            <button class="add-to-cart">Sepete Ekle</button>

            <div class="delivery-info">
                <div class="delivery-item">
                    <span class="delivery-icon">🚚</span>
                    <div>200 TL üzeri siparişlerde kargo bedava</div>
                </div>
                <div class="delivery-item">
                    <span class="delivery-icon">📦</span>
                    <div>Bugün sipariş verirseniz yarın kargoda</div>
                </div>
                <div class="delivery-item">
                    <span class="delivery-icon">💰</span>
                    <div>Kapıda ödeme seçeneği mevcuttur</div>
                </div>
            </div>

            <div class="product-description">
                <h3 class="description-title">Ürün Açıklaması</h3>
                <div class="description-content">
                    <p>PatiPlus Premium Tahılsız Kuzu Etli Köpek Maması, köpeğinizin sağlıklı beslenmesi için özenle formüle edilmiştir. Tahıl içermeyen yapısı ile hassas sindirim sistemine sahip köpekler için idealdir.</p>

                    <p>Yüksek kaliteli kuzu eti proteini ile zenginleştirilmiş bu mama, köpeğinizin kas gelişimini destekler ve günlük enerji ihtiyacını karşılar. İçeriğindeki Omega-3 ve Omega-6 yağ asitleri sayesinde sağlıklı bir deri ve parlak bir tüy yapısı sağlar.</p>

                    <p>Prebiyotikler ve lifler ile zenginleştirilmiş formülü sindirim sistemini destekler ve bağışıklık sistemini güçlendirir. Ayrıca eklem sağlığını desteklemek için glukozamin ve kondroitin içerir.</p>
                </div>
            </div>

            <div class="product-specs">
                <h3 class="description-title">Ürün Özellikleri</h3>
                <table class="specs-table">
                    <tr>
                        <td>İçerik</td>
                        <td>Kuzu eti (%30), tatlı patates, bezelye, elma, havuç, yaban mersini, keten tohumu, balık yağı, vitaminler ve mineraller</td>
                    </tr>
                    <tr>
                        <td>Protein Oranı</td>
                        <td>%28</td>
                    </tr>
                    <tr>
                        <td>Yağ Oranı</td>
                        <td>%16</td>
                    </tr>
                    <tr>
                        <td>Lif Oranı</td>
                        <td>%3.5</td>
                    </tr>
                    <tr>
                        <td>Kül Oranı</td>
                        <td>%7.5</td>
                    </tr>
                    <tr>
                        <td>Nem Oranı</td>
                        <td>%10</td>
                    </tr>
                    <tr>
                        <td>Uygun Köpek Yaşı</td>
                        <td>Yetişkin (1 yaş ve üzeri)</td>
                    </tr>
                    <tr>
                        <td>Uygun Köpek Boyutu</td>
                        <td>Tüm ırklar için uygundur</td>
                    </tr>
                    <tr>
                        <td>Üretim Yeri</td>
                        <td>Türkiye</td>
                    </tr>
                    <tr>
                        <td>Raf Ömrü</td>
                        <td>Üretim tarihinden itibaren 18 ay</td>
                    </tr>
                    <tr>
                        <td>Saklama Koşulları</td>
                        <td>Serin ve kuru ortamda saklayınız. Açıldıktan sonra 30 gün içinde tüketilmesi önerilir.</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 PatiShop - Tüm Hakları Saklıdır.</p>
    </footer>
</body>

</html>