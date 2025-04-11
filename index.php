<?php
session_start();

// Eğer kullanıcı oturumu açıksa (örneğin 'uyeAd' veya 'giris' gibi bir oturum anahtarı varsa)
if (isset($_SESSION['giris']) || (isset($_SESSION['uyeAd']) && isset($_SESSION['uyeMail']))) {
    header("Location: anasayfa.php");
    exit();
}

include("ayar.php"); // ayar.php içerisindeki $baglan ile veritabanına bağlanılıyor

// Kategorileri çekiyoruz
$kategoriSql = "SELECT kategoriID, kategoriAdi 
                FROM t_kategori 
                WHERE kategoriDurum = 1 
                ORDER BY kategoriAdi ASC";
$kategoriSorgu = $baglan->query($kategoriSql);
$categories = [];
if ($kategoriSorgu) {
    while ($row = $kategoriSorgu->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Hayvan türlerini çekiyoruz
$hayvanSql = "SELECT hayvanTurID, hayvanTurAdi 
              FROM t_hayvanturleri 
              ORDER BY hayvanTurAdi ASC";
$hayvanSorgu = $baglan->query($hayvanSql);
$animalTypes = [];
if ($hayvanSorgu) {
    while ($row = $hayvanSorgu->fetch_assoc()) {
        $animalTypes[] = $row;
    }
}

// GET üzerinden gelen filtreler: kategori veya hayvan
$filterCategory = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$filterAnimal   = isset($_GET['hayvan']) ? (int)$_GET['hayvan'] : 0;

// Ürünleri çekiyoruz (JOIN ile t_resimler tablosundan resimYolu alacağız)
$products = [];
if ($filterCategory > 0) {
    // Kategoriye göre filtreleme
    $stmt = $baglan->prepare("
        SELECT u.*, r.resimYolu 
        FROM t_urunler u
        LEFT JOIN t_resimler r ON u.urunResimID = r.resimID
        WHERE u.urunKategoriID = ?
        ORDER BY u.urunKayitTarih DESC
    ");
    $stmt->bind_param("i", $filterCategory);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
} elseif ($filterAnimal > 0) {
    // Hayvan türüne göre filtreleme (JOIN ile)
    $stmt = $baglan->prepare("
        SELECT u.*, r.resimYolu 
        FROM t_urunler u
        JOIN t_kategori k ON u.urunKategoriID = k.kategoriID
        LEFT JOIN t_resimler r ON u.urunResimID = r.resimID
        WHERE k.kategoriHayvanTurID = ?
        ORDER BY u.urunKayitTarih DESC
    ");
    $stmt->bind_param("i", $filterAnimal);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    $stmt->close();
} else {
    // Filtre yoksa tüm ürünler
    $urunSql = "
        SELECT u.*, r.resimYolu 
        FROM t_urunler u
        LEFT JOIN t_resimler r ON u.urunResimID = r.resimID
        ORDER BY u.urunKayitTarih DESC
    ";
    $urunSorgu = $baglan->query($urunSql);
    if ($urunSorgu) {
        while ($row = $urunSorgu->fetch_assoc()) {
            $products[] = $row;
        }
    }
}
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <title>PatiShop</title>
    <link rel="icon" href="/patishop/resim/patiShopLogo.png">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS -->
    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" 
        crossorigin="anonymous"
    >
    <!-- Özel CSS (isteğe bağlı) -->
    <style>
        body {
            background-color:rgb(143, 143, 143);
        }
        /* Hero alanı */
        .hero {
            position: relative;
            background: url('/patishop/resim/hero-bg.jpg') center center no-repeat;
            background-size: cover;
            color: #fff;
            text-align: center;
            padding: 100px 20px;
            margin-bottom: 40px;
        }
        .hero::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: rgba(0, 0, 0, 0.5); /* Daha okunaklı olması için koyu bir örtü */
        }
        .hero h1, .hero p {
            position: relative;
            z-index: 1;
        }
        .hero h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }
        .hero p {
            font-size: 1.3rem;
            margin-bottom: 20px;
        }
        .hero .btn-hero {
            position: relative;
            z-index: 1;
        }
        /* Kart görünümleri */
        .card img {
            height: 200px;
            object-fit: cover;
        }
        /* Footer */
        footer {
            background-color: #343a40;
            color: #ddd;
            padding: 20px 0;
            margin-top: 40px;
        }
    </style>
</head>
<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-sm navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <img src="/patishop/resim/patiShopLogo.png" alt="PatiShop Logo" style="height: 40px;">
                PatiShop
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- Anasayfa Linki -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Anasayfa</a>
                    </li>
                    <!-- Tüm Ürünler Linki -->
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Tüm Ürünler</a>
                    </li>
                    <!-- Hayvan Türleri için Navbar Linkleri -->
                    <?php foreach ($animalTypes as $animal): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="index.php?hayvan=<?= $animal['hayvanTurID']; ?>">
                                <?= htmlspecialchars($animal['hayvanTurAdi']); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <!-- Arama Formu (isteğe bağlı) -->
                <form class="d-flex" action="index.php" method="get">
                    <input class="form-control me-2" type="search" name="search" placeholder="Ürün ara...">
                    <button class="btn btn-outline-primary" type="submit">Ara</button>
                </form>
                <!-- Kullanıcı Giriş/Kayıt -->
                <div class="dropdown ms-3">
                    <button 
                        class="btn btn-outline-secondary dropdown-toggle" 
                        type="button" 
                        data-bs-toggle="dropdown" 
                        aria-expanded="false"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" 
                             class="bi bi-person" viewBox="0 0 16 16">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 
                                     1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 
                                     8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="girisYap.php">Giriş Yap</a></li>
                        <li><a class="dropdown-item" href="kayitOl.php">Kayıt Ol</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- HERO BÖLÜMÜ -->
    <div class="hero">
        <div class="container">
            <h1>Welcome to PatiShop!</h1>
            <p>Your one-stop shop for all your pet needs.</p>
            <a href="#products" class="btn btn-primary btn-hero">Alışverişe Başla</a>
        </div>
    </div>

    <!-- ANA İÇERİK: Sidebar + Ürünler -->
    <div class="container" id="products">
        <div class="row">
            <!-- SIDEBAR: Kategoriler -->
            <aside class="col-md-3 mb-4">
                <div class="list-group">
                    <a href="index.php" 
                       class="list-group-item <?= ($filterCategory == 0 && $filterAnimal == 0) ? 'active' : ''; ?>">
                        Tüm Ürünler
                    </a>
                    <?php foreach ($categories as $category): ?>
                        <a href="index.php?category=<?= $category['kategoriID']; ?>" 
                           class="list-group-item <?= ($filterCategory == $category['kategoriID']) ? 'active' : ''; ?>">
                            <?= htmlspecialchars($category['kategoriAdi']); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </aside>

            <!-- ÜRÜNLERİN LİSTELENDİĞİ BÖLÜM -->
            <main class="col-md-9">
                <div class="row">
                    <?php if (count($products) > 0): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-4 mb-4">
                                <div class="card h-100">
                                    <?php 
                                        // Eğer resimYolu boş değilse onu gösterelim, yoksa varsayılan bir resim ekleyebiliriz.
                                        $imagePath = !empty($product['resimYolu']) 
                                                     ? $product['resimYolu'] 
                                                     : "/patishop/resim/varsayilan.png"; 
                                    ?>
                                    <img 
                                        src="<?= htmlspecialchars($imagePath); ?>" 
                                        class="card-img-top" 
                                        alt="<?= htmlspecialchars($product['urunAdi']); ?>"
                                    >
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?= htmlspecialchars($product['urunAdi']); ?></h5>
                                        <p class="card-text mb-2">
                                            Fiyat: <strong><?= number_format($product['urunFiyat'], 2); ?> TL</strong>
                                        </p>
                                        <!-- Dilerseniz kısa bir açıklama vb. ekleyebilirsiniz -->
                                        <div class="mt-auto">
                                            <button class="btn btn-primary">Sepete Ekle</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <p class="text-muted">Ürün bulunamadı.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="text-center">
        <div class="container">
            <p>Contact us: <a href="mailto:info@patishop.com" class="text-light">info@patishop.com</a></p>
            <p>Follow us on social media!</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script 
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" 
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" 
        crossorigin="anonymous">
    </script>
</body>
</html>
