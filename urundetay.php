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
$sql = "SELECT 
    u.*, d.*, s.*, k.*, ht.*, kp.*, r.resimYolu,
    GROUP_CONCAT(r.resimYolu) as resimler
    FROM t_urunler u
    LEFT JOIN t_urundetay d ON u.urunID = d.urunDurunID
    LEFT JOIN t_stok s ON d.urunDStokID = s.stokID
    LEFT JOIN t_kategori k ON u.urunKategoriID = k.kategoriID
    LEFT JOIN t_hayvanturleri ht ON d.urunDHayvanTurID = ht.hayvanTurID
    LEFT JOIN t_kampanya kp ON d.urunDKampanyaID = kp.kampanyaID
    LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
    LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
    WHERE u.urunID = $urunID
    GROUP BY u.urunID";
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
        /* Sadece ürün detay sayfasına özel stiller kalacak */
        body {
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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

        .gallery-container {
            position: relative;
            width: 100%;
            margin-bottom: 20px;
        }

        .gallery-nav {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(0, 0, 0, 0.5);
            color: white;
            border: none;
            padding: 15px;
            cursor: pointer;
            font-size: 20px;
            transition: background 0.3s;
            z-index: 2;
        }

        .gallery-nav:hover {
            background: rgba(0, 0, 0, 0.7);
        }

        .gallery-nav.prev {
            left: 0;
            border-radius: 0 3px 3px 0;
        }

        .gallery-nav.next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        .main-image {
            width: 100%;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9f9f9;
            border-radius: 8px;
            overflow: hidden;
        }

        .main-image img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
            transition: transform 0.3s;
        }

        .thumbnails {
            display: flex;
            gap: 10px;
            margin-top: 20px;
            overflow-x: auto;
            padding: 10px 0;
        }

        .thumbnail {
            width: 80px;
            height: 80px;
            border: 2px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
            overflow: hidden;
            flex-shrink: 0;
            transition: border-color 0.3s;
        }

        .thumbnail.active {
            border-color: #4CAF50;
        }

        .thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .top-bar {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="top-bar">
        Türkiye'nin her yerine ücretsiz kargo! 200 TL ve üzeri siparişlerde geçerlidir.
    </div>

    <?php include 'headerHesap.php'; ?>

    <div class="breadcrumb">
        <div class="patiContainer">
            <a href="index.php">Ana Sayfa</a> <span>></span>
            <a href="kategori.php?kategori=<?php echo $urun['kategoriSlug']; ?>"><?php echo $urun['kategoriAdi']; ?></a> <span>></span>
            <span><?php echo $urun['urunAdi']; ?></span>
        </div>
    </div>

    <div class="product-container patiContainer">
        <div class="product-gallery">
            <div class="gallery-container">
                <button class="gallery-nav prev" onclick="prevImage()">❮</button>
                <div class="main-image">
                    <img id="mainImage" src="<?php echo !empty($urun['resimYolu']) ? $urun['resimYolu'] : 'resim/default.jpg'; ?>" alt="<?php echo $urun['urunAdi']; ?>">
                </div>
                <button class="gallery-nav next" onclick="nextImage()">❯</button>
            </div>

            <div class="thumbnails">
                <?php
                if (!empty($urun['resimler'])) {
                    $resimler = explode(',', $urun['resimler']);
                    foreach ($resimler as $index => $resim) {
                        echo '<div class="thumbnail' . ($index === 0 ? ' active' : '') . '" onclick="changeImage(' . $index . ')">';
                        echo '<img src="' . $resim . '" alt="Thumbnail ' . ($index + 1) . '">';
                        echo '</div>';
                    }
                }
                ?>
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
    <script>
        let currentImageIndex = 0;
        const images = <?php echo json_encode(explode(',', $urun['resimler'])); ?>;

        function changeImage(index) {
            if (index >= 0 && index < images.length) {
                currentImageIndex = index;
                document.getElementById('mainImage').src = images[currentImageIndex];

                // Thumbnail'ları güncelle
                document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
                    thumb.classList.toggle('active', i === index);
                });
            }
        }

        function nextImage() {
            changeImage((currentImageIndex + 1) % images.length);
        }

        function prevImage() {
            changeImage((currentImageIndex - 1 + images.length) % images.length);
        }

        // Klavye ok tuşları için event listener
        document.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                prevImage();
            } else if (e.key === 'ArrowRight') {
                nextImage();
            }
        });
    </script>
</body>

</html>