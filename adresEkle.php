<?php
// filepath: c:\xampp\htdocs\patishop\adresEkle.php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: girisYap.php');
    exit;
}

$uyeID = $_SESSION['uyeID'];
$hataMesaji = "";

// Form gönderildiğinde adresi kaydet
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresBilgisi = trim($_POST['adresBilgisi']);

    if (!empty($adresBilgisi)) {
        $adresBilgisi = $baglan->real_escape_string($adresBilgisi);
        $sql = "INSERT INTO t_adresler (adresUyeID, adresBilgisi) VALUES ($uyeID, '$adresBilgisi')";

        if ($baglan->query($sql) === TRUE) {
            header('Location: profil.php#addresses');
            exit;
        } else {
            $hataMesaji = "Adres kaydedilemedi: " . $baglan->error;
        }
    } else {
        $hataMesaji = "Adres bilgisi boş olamaz.";
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adres Ekle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1020px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            resize: none;
            height: 100px;
        }

        .btn {
            display: block;
            width: 30%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            margin: 0 auto;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <?php include 'headerHesap.php'; ?>
    <div class="container">
        <h2>Adres Ekle</h2>
        <form action="adresEkle.php" method="POST">
            <div class="form-group">
                <label for="adresBilgisi">Adres Bilgisi</label>
                <textarea name="adresBilgisi" id="adresBilgisi" placeholder="Adresinizi buraya girin..." required></textarea>
            </div>
            <button type="submit" class="btn">Kaydet</button>
            <?php if (!empty($hataMesaji)): ?>
                <div class="error"><?php echo $hataMesaji; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>