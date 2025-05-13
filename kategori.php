<?php
session_start();
include 'ayar.php';
include 'headerHesap.php';

// Kategori slug'ını al
$kategoriSlug = isset($_GET['kategori']) ? $baglan->real_escape_string($_GET['kategori']) : '';

// Arama parametresini al
$arama = isset($_GET['arama']) ? $baglan->real_escape_string($_GET['arama']) : '';

// SQL sorgusunu hazırla
if ($arama) {
    // Arama yapılıyorsa
    $urunSql = "
        SELECT u.urunID, u.urunAdi, u.urunFiyat, r.resimYolu, k.kategoriAdi
        FROM t_urunler u
        LEFT JOIN t_kategori k ON u.urunKategoriID = k.kategoriID
        LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
        LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
        WHERE u.urunAdi LIKE '%$arama%'
        GROUP BY u.urunID
    ";
} else {
    // Kategoriye göre listeleme yapılıyorsa
    $urunSql = "
        SELECT u.urunID, u.urunAdi, u.urunFiyat, r.resimYolu, k.kategoriAdi
        FROM t_urunler u
        INNER JOIN t_kategori k ON u.urunKategoriID = k.kategoriID
        LEFT JOIN t_resimiliskiler ri ON u.urunID = ri.resimIliskilerEklenenID
        LEFT JOIN t_resimler r ON ri.resimIliskilerResimID = r.resimID
        WHERE k.kategoriSlug = '$kategoriSlug'
        GROUP BY u.urunID
    ";
}

// Sorguyu çalıştır
$urunResult = $baglan->query($urunSql);

// Sayfa başlığı için kategori adını al
$kategoriAdi = '';
if ($kategoriSlug) {
    $kategoriSorgu = $baglan->query("SELECT kategoriAdi FROM t_kategori WHERE kategoriSlug = '$kategoriSlug'");
    if ($kategoriSorgu->num_rows > 0) {
        $kategoriAdi = $kategoriSorgu->fetch_assoc()['kategoriAdi'];
    }
}
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Sonuçları - PatiShop</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-top: 20px;
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

        .no-results {
            grid-column: 1 / -1;
            text-align: center;
            padding: 40px;
            background: #f8f9fa;
            border-radius: 8px;
            color: #666;
            font-size: 1.1em;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h1 class="mb-4">
            <?php 
            if ($arama) {
                echo 'Arama Sonuçları: "' . htmlspecialchars($arama) . '"';
            } else {
                echo htmlspecialchars($kategoriAdi);
            }
            ?>
        </h1>

        <div class="product-grid">
            <?php
            if ($urunResult && $urunResult->num_rows > 0) {
                while ($urun = $urunResult->fetch_assoc()) {
                    $resimYolu = !empty($urun['resimYolu']) ? $urun['resimYolu'] : 'resim/patiShopLogo.png';
                    ?>
                    <div class="product-card">
                        <a href="urundetay.php?urunID=<?php echo $urun['urunID']; ?>">
                            <img src="<?php echo htmlspecialchars($resimYolu); ?>" alt="<?php echo htmlspecialchars($urun['urunAdi']); ?>">
                            <div class="content">
                                <h3><?php echo htmlspecialchars($urun['urunAdi']); ?></h3>
                                <div class="price"><?php echo number_format($urun['urunFiyat'], 2); ?> TL</div>
                            </div>
                        </a>
                        <button class="add-to-cart" onclick="addToCart(<?php echo $urun['urunID']; ?>)">Sepete Ekle</button>
                    </div>
                    <?php
                }
            } else {
                if ($arama) {
                    echo '<p class="no-results">Aramanızla eşleşen ürün bulunamadı.</p>';
                } else {
                    echo '<p class="no-results">Bu kategoride henüz ürün bulunmamakta.</p>';
                }
            }
            ?>
        </div>
    </div>

    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        urunID: productId
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Ürün sepete eklendi!');
                    } else if (data.redirect) {
                        window.location.href = data.redirect; // Giriş yapma sayfasına yönlendir
                    } else {
                        alert('Ürün sepete eklenemedi: ' + data.message);
                    }
                })
                .catch(error => console.error('Hata:', error));
        }
    </script>

</body>

</html>