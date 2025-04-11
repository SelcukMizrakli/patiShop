<?php
session_start(); // Oturumu başlat

// Kullanıcının giriş yapıp yapmadığını kontrol et
if (!isset($_SESSION['uyeMail'])) {
    header("Location: girisYap.php");
    exit(); // Yönlendirmeden sonra scriptin çalışmasını durdur
}

// Veritabanı bağlantısı
include("ayar.php"); // Veritabanı bağlantı dosyanız

// Kullanıcı bilgilerini al
$uyeMail = $_SESSION['uyeMail'];
$query = $baglan->prepare("SELECT uyeID, uyeAd, uyeSoyad, uyeTelNo, uyeMail, uyeSifre FROM t_uyeler WHERE uyeMail = ?");
$query->bind_param("s", $uyeMail);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$kullaniciID = $user['uyeID']; // Kullanıcı ID'si
$kullaniciAdi = $user['uyeAd'];
$kullaniciSoyad = $user['uyeSoyad'];
$kullaniciTelNo = $user['uyeTelNo'];


// Profil güncelleme işlemi
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    // Güncellenen bilgileri al
    $newAd = !empty($_POST['new_ad']) ? $_POST['new_ad'] : $kullaniciAdi; // Değişiklik yoksa mevcut değeri kullan
    $newSoyad = !empty($_POST['new_soyad']) ? $_POST['new_soyad'] : $kullaniciSoyad; // Değişiklik yoksa mevcut değeri kullan
    $newTelNo = !empty($_POST['new_telno']) ? $_POST['new_telno'] : $kullaniciTelNo; // Değişiklik yoksa mevcut değeri kullan
    $newSifre = !empty($_POST['new_sifre']) ? $_POST['new_sifre'] : $user['uyeSifre']; // Değişiklik yoksa mevcut değeri kullan
}
?>

<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="anasayfa.php">Anasayfa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" 
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="profil.php">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cikisYap.php">Çıkış</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Ana İçerik -->
    <div class="container">
        <h1 class="mb-4">Hoşgeldiniz, <?php echo htmlspecialchars($kullaniciAdi); ?>!</h1>

        <div class="card-body">
    <p><strong>Ad:</strong> <?php echo htmlspecialchars($kullaniciAdi); ?></p>
    <p><strong>Soyad:</strong> <?php echo htmlspecialchars($kullaniciSoyad); ?></p>
    <p><strong>Email:</strong> <?php echo htmlspecialchars($uyeMail); ?></p>
    <p><strong>Telefon Numarası:</strong> <?php echo htmlspecialchars($kullaniciTelNo); ?></p>
    
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateProfileModal">Profilimi Düzenle</button>
</div>

        <!-- Adreslerim Bölümü -->
        <div class="card mb-4">
            <div class="card-header">
                <h2>Adreslerim</h2>
            </div>
            <div class="card-body">
                <p>Henüz adres eklenmedi.</p>
                <!-- Burada adres ekleme ve listeleme işlemleri yapılabilir -->
            </div>
        </div>
    </div>

    <!-- Profil Güncelleme Modal -->
    <div class="modal fade" id="updateProfileModal" tabindex="-1" aria-labelledby="updateProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProfileModalLabel">Profil Güncelle</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="new_ad" class="form-label">Ad:</label>
                            <input type="text" class="form-control" id="new_ad" name="new_ad" value="<?php echo htmlspecialchars($kullaniciAdi); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_soyad" class="form-label">Soyad:</label>
                            <input type="text" class="form-control" id="new_soyad" name="new_soyad" value="<?php echo htmlspecialchars($kullaniciSoyad); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_telno" class="form-label">Telefon Numarası:</label>
                            <input type="text" class="form-control" id="new_telno" name="new_telno" value="<?php echo htmlspecialchars($kullaniciTelNo); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_sifre" class="form-label">Yeni Şifre:</label>
                            <input type="password" class="form-control" id="new_sifre" name="new_sifre">
                        </div>
                        <button type="submit" name="update_profile" class="btn btn-primary">Güncelle</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
