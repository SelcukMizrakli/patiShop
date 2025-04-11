<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PatiShop - Kayıt Ol</title>
    <link rel="icon" href="/patishop/resim/patiShopLogo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="/patishop/styles.css">
</head>
<body>
<header>
  <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php"><img src="/patishop/resim/patiShopLogo.png" style="height: 35px; width:40px;"></img></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="mynavbar">
      <ul class="navbar-nav me-auto">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Anasayfa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="girisYap.php">Giriş Yap</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="kayitOl.php">Kayıt Ol</a>
        </li>
      <form class="d-flex">
        <input class="form-control me-2" type="text" placeholder="Ara">
        <button class="btn btn-primary" type="button">Ara</button>
      </form>
      </ul>
      <div class="dropdown" style="margin-right: 5%;">
        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-person" viewBox="0 0 16 16">
            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z"/>
          </svg>
        </button>
        <ul class="dropdown-menu">
          <li><a class="dropdown-item" href="girisYap.php">Giriş Yap</a></li>
          <li><a class="dropdown-item" href="kayitOl.php">Kayıt Ol</a></li>
        </ul>
      </div>
    </div>
  </div>
  </nav>
  <div class="hero">
    <h1>Kayıt Ol</h1>
    <p>Lütfen bilgilerinizi girin.</p>
  </div>
  <div class="row" style="margin-right: 35%; margin-left: 35%;">
    <form action="kayitOl.php" method="post" class="form needs-validation">
        <div class="mb-3 d-flex gap-5 align-items-center">
            <div>
                <label for="ad" class="form-label">Ad <span class="text-danger">*</span></label>
                <input type="text" name="ad" id="ad" class="form-control" required>
                <div class="invalid-feedback">Lütfen adınızı girin.</div>
            </div>
            <div>
                <label for="soyad" class="form-label">Soyad <span class="text-danger">*</span></label>
                <input type="text" name="soyad" id="soyad" class="form-control" required>
                <div class="invalid-feedback">Lütfen soyadınızı girin.</div>
            </div>
        </div>

        <div class="mb-3">
            <label for="telefon" class="form-label">Tel No <span class="text-danger">*</span></label>
            <input type="text" name="telNo" id="telNo" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email adresi <span class="text-danger">*</span></label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Şifre <span class="text-danger">*</span></label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password_repeat" class="form-label">Şifre Tekrar <span class="text-danger">*</span></label>
            <input type="password" name="password_repeat" id="password_repeat" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Kayıt Ol</button>
    </form>
  </div>

  <?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include("ayar.php"); // Veritabanı bağlantınızı sağlayın

    $ad = trim($_POST['ad']);
    $soyad = trim($_POST['soyad']);
    $telNo = trim($_POST['telNo']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];

    // E-posta daha önce kullanılmış mı kontrol et
    $emailCheck = $baglan->prepare("SELECT uyeMail FROM t_uyeler WHERE uyeMail = ?");
    $emailCheck->bind_param("s", $email);
    $emailCheck->execute();
    $result = $emailCheck->get_result();

    if ($result->num_rows > 0) {
        echo "<script>alert('Bu e-posta adresi zaten kullanılıyor!'); window.location.href='kayitOl.php';</script>";
        exit();
    }

    // Şifrelerin eşleştiğini kontrol et
    if ($password === $password_repeat) {
        // Şifreyi hashle
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Kullanıcıyı veritabanına ekle
        $stmt = $baglan->prepare("INSERT INTO t_uyeler (uyeAd, uyeSoyad, uyeTelNo, uyeMail, uyeSifre) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $ad, $soyad, $telNo, $email, $hashed_password);
        
        if ($stmt->execute()) { 
            // Kayıt başarılı, oturumu başlat
            $_SESSION['user_id'] = $stmt->insert_id; // Kullanıcının ID'sini kaydet
            $_SESSION['user_name'] = $ad; // Kullanıcının adını kaydet
            $_SESSION['user_email'] = $email; // Kullanıcının e-posta adresini kaydet

            echo "<script>alert('Kayıt başarılı!'); window.location.href='anasayfa.php';</script>";
        } else {
            echo "<script>alert('Kayıt sırasında bir hata oluştu.');</script>";
        }
    } else {
        echo "<script>alert('Şifreler eşleşmiyor!');</script>";
    }
}
?>

<footer>
  <p>Contact us: info@patishop.com</p>
  <p>Follow us on social media!</p>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<script>
    // Form doğrulama scripti
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })()
</script>
</body>
</html>