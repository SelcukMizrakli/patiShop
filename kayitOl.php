<!DOCTYPE html>
<html lang="tr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kayıt Ol - PatiShop</title>
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

    .main-content {
      max-width: 600px;
      margin: 40px auto;
      background-color: white;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    h2 {
      color: #333;
      margin-bottom: 25px;
      text-align: center;
    }

    .form-group {
      margin-bottom: 20px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: bold;
      color: #555;
    }

    .form-group input {
      width: 100%;
      padding: 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      font-size: 14px;
    }

    .form-group input:focus {
      border-color: #4CAF50;
      outline: none;
    }

    .form-actions {
      margin-top: 30px;
    }

    .btn-register {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 12px 0;
      width: 100%;
      border-radius: 4px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .btn-register:hover {
      background-color: #45a049;
    }

    .login-link {
      text-align: center;
      margin-top: 20px;
    }

    .login-link a {
      color: #4CAF50;
      text-decoration: none;
    }

    .login-link a:hover {
      text-decoration: underline;
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

  <div class="main-content">
    <h2>Hesap Oluştur</h2>

    <form>
      <div class="form-group">
        <label for="fullname">Ad Soyad</label>
        <input type="text" id="fullname" name="fullname" required>
      </div>

      <div class="form-group">
        <label for="email">E-posta Adresi</label>
        <input type="email" id="email" name="email" required>
      </div>

      <div class="form-group">
        <label for="phone">Telefon Numarası</label>
        <input type="tel" id="phone" name="phone" required>
      </div>

      <div class="form-group">
        <label for="password">Şifre</label>
        <input type="password" id="password" name="password" required>
      </div>

      <div class="form-group">
        <label for="confirm-password">Şifre Tekrar</label>
        <input type="password" id="confirm-password" name="confirm-password" required>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-register">Kayıt Ol</button>
      </div>

      <div class="login-link">
        Zaten hesabınız var mı? <a href="#">Giriş Yap</a>
      </div>
    </form>
  </div>

  <footer>
    <p>&copy; 2025 PatiShop - Tüm Hakları Saklıdır.</p>
  </footer>
</body>

</html>