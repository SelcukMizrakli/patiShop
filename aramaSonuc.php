<?php
// filepath: c:\xampp\htdocs\patishop\aramaSonuc.php
session_start();
include 'ayar.php';
include 'headerHesap.php';

// Arama terimini al
$arama = isset($_GET['arama']) ? $baglan->real_escape_string($_GET['arama']) : '';

if (empty($arama)) {
    die("Arama terimi boş olamaz.");
}

// Arama sorgusu
$urunSql = "
    SELECT u.urunID, u.urunAdi, u.urunFiyat, u.urunResimID 
    FROM t_urunler u
    WHERE u.urunAdi LIKE '%$arama%'
";
$urunResult = $baglan->query($urunSql);
?>

<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Sonuçları - <?php echo htmlspecialchars($arama); ?></title>
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
    </style>
</head>

<body>
    <div class="container">
        <h1 class="mt-4">Arama Sonuçları: "<?php echo htmlspecialchars($arama); ?>"</h1>


        <!-- Ürünler -->
        <div class="product-grid">
            <?php
            if ($urunResult->num_rows > 0) {
                while ($urun = $urunResult->fetch_assoc()) {
                    // Resim yolunu almak için t_resimler tablosunu kullanıyoruz
                    $resimSql = "SELECT resimYolu FROM t_resimler WHERE resimID = " . $urun['urunResimID'];
                    $resimResult = $baglan->query($resimSql);
                    $resim = $resimResult->fetch_assoc();

                    echo '<div class="product-card">';
                    echo '<a href="urundetay.php?urunID=' . $urun['urunID'] . '">';
                    echo '<img src="' . $resim['resimYolu'] . '" alt="' . $urun['urunAdi'] . '">';
                    echo '<div class="content">';
                    echo '<h3>' . $urun['urunAdi'] . '</h3>';
                    echo '<div class="price">' . number_format($urun['urunFiyat'], 2) . ' TL</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '<button class="add-to-cart" onclick="addToCart(' . $urun['urunID'] . ')">Sepete Ekle</button>';
                    echo '</div>';
                }
            } else {
                echo '<p>Aramanıza uygun ürün bulunamadı.</p>';
            }
            ?>
        </div>
    </div>

</body>

</html>