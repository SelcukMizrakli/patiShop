<?php
include 'ayar.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $baglan->real_escape_string($_POST['email']);
    $password = $baglan->real_escape_string($_POST['password']);

    // Kullanıcı bilgilerini kontrol et
    $sql = "SELECT * FROM t_uyeler WHERE uyeMail = '$email' AND uyeSifre = '$password' AND uyeAktiflikDurumu = 1";
    $result = $baglan->query($sql);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Kullanıcı bilgilerini oturuma kaydet
        $_SESSION['uyeID'] = $user['uyeID'];
        $_SESSION['uyeAd'] = $user['uyeAd'];
        $_SESSION['uyeSoyad'] = $user['uyeSoyad'];
        $_SESSION['uyeYetki'] = $user['uyeYetki'];

        // Anasayfaya yönlendir
        header('Location: anasayfa.php');
        exit;
    } else {
        // Hatalı giriş durumunda geri yönlendir
        header('Location: index.php?error=1');
        exit;
    }
}
?>

<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PatiShop - Giriş Yap</title>
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
                <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6m2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0m4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4m-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10s-3.516.68-4.168 1.332c-.678.678-.83 1.418-.832 1.664z" />
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
      <h1>Giriş Yap</h1>
      <p>Lütfen bilgilerinizi girin.</p>
    </div>
    <div class="row" style="margin-right: 35%; margin-left: 35%;">
      <form method="post" class="form needs-validation">
        <div class="mb-3">
          <label for="email" class="form-label">Email adresi <span class="text-danger">*</span></label>
          <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
          <label for="sifre" class="form-label">Şifre <span class="text-danger">*</span></label>
          <input type="password" name="password" id="sifre" class="form-control" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Giriş Yap</button>
      </form>
    </div>

    <footer>
      <p>Contact us: info@patishop.com</p>
      <p>Follow us on social media!</p>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
      // Form doğrulama scripti
      (function() {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')
        Array.prototype.slice.call(forms).forEach(function(form) {
          form.addEventListener('submit', function(event) {
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
