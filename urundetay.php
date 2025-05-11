<?php
include 'ayar.php';
session_start();

// Kullanıcı giriş yapmamışsa ürün bilgilerini gösterebilir ancak sepete veya favorilere ekleme işlemi yapamaz
$girisYapmisMi = isset($_SESSION['uyeID']);

// Ürün ID kontrolü
if (!isset($_GET['urunID'])) {
    header('Location: index.php');
    exit;
}

$urunID = intval($_GET['urunID']);

// Ürün bilgilerini çek
$sql = "
    SELECT 
        u.urunAdi, 
        u.urunFiyat, 
        u.urunKategoriID, 
        u.urunKayitTarih, 
        u.urunGuncellemeTarih, 
        d.urunDAciklama, 
        d.urunDHayvanTurID, 
        d.urunDKampanyaID, 
        s.stokMiktar, 
        s.stokGirisTarih, 
        s.stokCikisTarih, 
        k.kategoriAdi, 
        k.kategoriAciklama, 
        k.kategoriSlug, 
        ht.hayvanTurAdi, 
        kp.kampanyaBaslik, 
        kp.kampanyaIndirimYuzdesi,
        r.resimYolu
    FROM t_urunler u
    LEFT JOIN t_urundetay d ON u.urunID = d.urunDurunID
    LEFT JOIN t_stok s ON d.urunDStokID = s.stokID
    LEFT JOIN t_kategori k ON u.urunKategoriID = k.kategoriID
    LEFT JOIN t_hayvanturleri ht ON d.urunDHayvanTurID = ht.hayvanTurID
    LEFT JOIN t_kampanya kp ON d.urunDKampanyaID = kp.kampanyaID
    LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
    LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
    WHERE u.urunID = $urunID
    LIMIT 1
";
$result = $baglan->query($sql);

if ($result->num_rows > 0) {
    $urun = $result->fetch_assoc();
} else {
    die("Ürün bulunamadı.");
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $urun['urunAdi']; ?> - Ürün Detayı</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
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

        .related-products {
            margin-top: 40px;
        }

        .related-products h3 {
            font-size: 20px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }

        .product-grid {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .product-card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            flex: 0 0 23%;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
            margin-bottom: 10px;
        }

        .product-card h4 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #333;
        }

        .product-card p {
            font-size: 14px;
            color: #4CAF50;
            margin-bottom: 10px;
        }

        .product-card .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
        }
    </style>
</head>

<body>
    <div class="top-banner">
        Türkiye'nin her yerine ücretsiz kargo! 200 TL ve üzeri siparişlerde geçerlidir.
    </div>

    <?php include 'headerHesap.php'; ?>

    <div class="breadcrumb">
        <a href="index.php">Ana Sayfa</a> <span>></span>
        <a href="kategori.php?kategoriID=<?php echo $urun['urunKategoriID']; ?>"><?php echo $urun['kategoriAdi']; ?></a> <span>></span>
        <a href="urundetay.php?urunID=<?php echo $urunID; ?>"><?php echo $urun['urunAdi']; ?></a>
    </div>

    <div class="product-container">
        <div class="product-gallery">
            <div class="main-image">
                <img src="<?php echo !empty($urun['resimYolu']) ? $urun['resimYolu'] : 'resim/default.jpg'; ?>" alt="<?php echo $urun['urunAdi']; ?>">
            </div>
        </div>

        <div class="product-info">
            <h1 class="product-name"><?php echo $urun['urunAdi']; ?></h1>
            <div class="product-brand">Marka: PatiPlus</div>

            <div class="product-price">
                <?php echo number_format($urun['urunFiyat'], 2); ?> TL
            </div>

            <div class="quantity-selector">
                <button onclick="decreaseQuantity()">-</button>
                <input type="number" id="quantity" value="1" min="1">
                <button onclick="increaseQuantity()">+</button>
            </div>

            <script>
                function increaseQuantity() {
                    const quantityInput = document.getElementById('quantity');
                    let currentValue = parseInt(quantityInput.value);
                    quantityInput.value = currentValue + 1;
                }

                function decreaseQuantity() {
                    const quantityInput = document.getElementById('quantity');
                    let currentValue = parseInt(quantityInput.value);
                    if (currentValue > 1) {
                        quantityInput.value = currentValue - 1;
                    }
                }

                function addToCart(productId) {
                    const quantity = document.getElementById('quantity').value;
                    console.log('Ürün ID:', productId);
                    console.log('Miktar:', quantity);

                    fetch('add_to_cart.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                urunID: productId,
                                miktar: quantity
                            }),
                        })
                        .then(response => response.json())
                        .then(data => {
                            console.log(data); // Sunucudan gelen yanıtı kontrol edin
                            if (data.success) {
                                alert(data.message);
                            } else if (data.redirect) {
                                window.location.href = data.redirect;
                            } else {
                                alert('Ürün sepete eklenemedi: ' + data.message);
                            }
                        })
                        .catch(error => console.error('Hata:', error));
                }

                function addToFavorites(productId) {
                    fetch('add_to_favorites.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            urunID: productId
                        }),
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP error! status: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log(data); // Sunucu yanıtını kontrol edin
                        if (data.success) {
                            alert('Ürün favorilere eklendi!');
                        } else if (data.redirect) {
                            window.location.href = data.redirect; // Giriş yapma sayfasına yönlendir
                        } else {
                            alert('Ürün favorilere eklenemedi: ' + data.message);
                        }
                    })
                    .catch(error => console.error('Hata:', error));
                }
            </script>

            <button
                onclick="<?php echo $girisYapmisMi ? "addToCart($urunID)" : "redirectToLoginModal()" ?>"
                class="add-to-cart">
                Sepete Ekle
            </button>

            <button
                onclick="<?php echo $girisYapmisMi ? "addToFavorites($urunID)" : "redirectToLoginModal()" ?>"
                class="add-to-cart"
                style="background-color: #ff6b6b;">
                Favorilere Ekle
            </button>

            <?php if (isset($favoriMesaj)): ?>
                <p><?php echo $favoriMesaj; ?></p>
            <?php endif; ?>

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
                    <p><?php echo $urun['urunDAciklama']; ?></p>
                </div>
            </div>

            <div class="product-specs">
                <h3 class="description-title">Ürün Özellikleri</h3>
                <table class="specs-table">
                    <tr>
                        <td>İçerik</td>
                        <td><?php echo $urun['urunDAciklama']; ?></td>
                    </tr>
                    <tr>
                        <td>Kategori</td>
                        <td><?php echo $urun['kategoriAdi']; ?></td>
                    </tr>
                    <tr>
                        <td>Fiyat</td>
                        <td><?php echo number_format($urun['urunFiyat'], 2); ?> TL</td>
                    </tr>
                    <tr>
                        <td>Hayvan Türü</td>
                        <td><?php echo $urun['hayvanTurAdi']; ?></td>
                    </tr>
                    <tr>
                        <td>Kampanya</td>
                        <td><?php echo $urun['kampanyaBaslik'] ? $urun['kampanyaBaslik'] . ' (%' . $urun['kampanyaIndirimYuzdesi'] . ')' : 'Yok'; ?></td>
                    </tr>
                    <tr>
                        <td>Stok Miktarı</td>
                        <td><?php echo $urun['stokMiktar']; ?></td>
                    </tr>
                    <tr>
                        <td>Stok Giriş Tarihi</td>
                        <td><?php echo $urun['stokGirisTarih']; ?></td>
                    </tr>
                    <tr>
                        <td>Stok Çıkış Tarihi</td>
                        <td><?php echo $urun['stokCikisTarih']; ?></td>
                    </tr>
                </table>
            </div>

            <div class="related-products">
                <h3>Benzer Ürünler</h3>
                <div class="product-grid">
                    <?php
                    $kategoriID = $urun['urunKategoriID'];
                    $relatedSql = "SELECT u.urunID, u.urunAdi, u.urunFiyat, r.resimYolu 
                                   FROM t_urunler u 
                                   LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
                                   LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
                                   WHERE u.urunKategoriID = $kategoriID AND u.urunID != $urunID
                                   GROUP BY u.urunID
                                   LIMIT 4";
                    $relatedResult = $baglan->query($relatedSql);

                    if ($relatedResult->num_rows > 0) {
                        while ($related = $relatedResult->fetch_assoc()) {
                            $resimYolu = !empty($related['resimYolu']) ? $related['resimYolu'] : 'resim/default.jpg';
                            echo '<div class="product-card">';
                            echo '<img src="' . $resimYolu . '" alt="' . $related['urunAdi'] . '">';
                            echo '<h4>' . $related['urunAdi'] . '</h4>';
                            echo '<p>' . number_format($related['urunFiyat'], 2) . ' TL</p>';
                            echo '<a href="urundetay.php?urunID=' . $related['urunID'] . '" class="btn">Detay</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p>Benzer ürün bulunamadı.</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2025 PatiShop - Tüm Hakları Saklıdır.</p>
    </footer>

    <script>
        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value; // Miktar bilgisini al
            console.log('Ürün ID:', productId);
            console.log('Miktar:', quantity);

            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        urunID: productId, // Ürün ID'si gönderiliyor
                        miktar: quantity // Miktar bilgisi gönderiliyor
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data); // Sunucudan gelen yanıtı kontrol edin
                    if (data.success) {
                        alert(data.message);
                    } else if (data.redirect) {
                        window.location.href = data.redirect; // Giriş yapma sayfasına yönlendir
                    } else {
                        alert('Ürün sepete eklenemedi: ' + data.message);
                    }
                })
                .catch(error => console.error('Hata:', error));
        }

        function addToFavorites(productId) {
            fetch('add_to_favorites.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    urunID: productId
                }),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! status: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                console.log(data); // Sunucu yanıtını kontrol edin
                if (data.success) {
                    alert('Ürün favorilere eklendi!');
                } else if (data.redirect) {
                    window.location.href = data.redirect; // Giriş yapma sayfasına yönlendir
                } else {
                    alert('Ürün favorilere eklenemedi: ' + data.message);
                }
            })
            .catch(error => console.error('Hata:', error));
        }

        function redirectToLoginModal() {
            // Kullanıcı giriş yapmamışsa index.php sayfasına yönlendir ve giriş yap modalını aç
            window.location.href = 'index.php?showLoginModal=true';
        }
    </script>
</body>

</html>