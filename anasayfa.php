<?php
session_start(); // Oturumu başlat

// Eğer oturum verileri tanımlı değilse, kullanıcıyı giriş sayfasına yönlendir
if (!isset($_SESSION['uyeAd']) || !isset($_SESSION['uyeMail'])) {
    header("Location: girisYap.php");
    exit();
}

// Oturumdaki verilerden kullanıcı bilgilerini al
$kullaniciAdi = $_SESSION['uyeAd'];
$kullaniciEmail = $_SESSION['uyeMail'];

// Giriş yapıldığını belirtiyoruz, eğer ihtiyaç varsa
$_SESSION["giris"] = sha1(md5("var"));
// Ekstra çerez ayarı, sadece gerekiyorsa
setcookie("kullanici", "msb", time() + 3600, "/");
?>

<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PatiShop</title>
    <link rel="icon" href="/patishop/resim/patiShopLogo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/patishop/styles.css">
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="anasayfa.php">
                    <img src="/patishop/resim/patiShopLogo.png" style="height: 35px; width:40px;">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="mynavbar">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="anasayfa.php">Anasayfa</a>
                        </li>
                        <form class="d-flex">
                            <input class="form-control me-2" type="text" placeholder="Ara">
                            <button class="btn btn-primary" type="button">Ara</button>
                        </form>
                    </ul>
                        <div class="dropdown" style="margin-right: 5%;">
                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
                                </svg>
                                <?php echo htmlspecialchars($kullaniciAdi); ?> (<?php echo htmlspecialchars($kullaniciEmail); ?>)
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="profil.php">Profil</a></li>
                                <li><a class="dropdown-item" href="profil.php">Favoriler</a></li>
                                <li><a class="dropdown-item" href="profil.php">Sepet</a></li>
                                <li><a class="dropdown-item text-danger" href="cikisYap.php">Çıkış Yap</a></li>
                            </ul>
                        </div>
                </div>
            </div>
        </nav>
    </header>

    <div class="container mt-5 text-center">
        <h1>Hoşgeldiniz, <?php echo htmlspecialchars($kullaniciAdi); ?>!</h1>
        <p><?php echo "PatiShop'a giriş yaptınız. Özel fırsatları kaçırmayın!";?></p>
    </div>

    <section class="featured-products">
        <h2>Öne Çıkan Ürünler</h2>
        <div class="product-grid">
            <div class="product">
                <img src="path/to/product1.jpg" alt="Ürün 1">
                <h3>Ürün 1</h3>
                <p>Fiyat: $19.99</p>
            </div>
            <div class="product">
                <img src="path/to/product2.jpg" alt="Ürün 2">
                <h3>Ürün 2</h3>
                <p>Fiyat: $29.99</p>
            </div>
            <div class="product">
                <img src="path/to/product3.jpg" alt="Ürün 3">
                <h3>Ürün 3</h3>
                <p>Fiyat: $39.99</p>
            </div>
        </div>
    </section>

    <footer>
        <p>İletişim: info@patishop.com</p>
        <p>Sosyal medyada bizi takip edin!</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>