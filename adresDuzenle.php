<?php
// filepath: c:\xampp\htdocs\patishop\adresDuzenle.php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: girisYap.php');
    exit;
}

$uyeID = $_SESSION['uyeID'];
$adresID = isset($_GET['adresID']) ? intval($_GET['adresID']) : 0;
$hataMesaji = "";

// Adres bilgilerini çek
$sql = "SELECT adresBilgisi FROM t_adresler WHERE adresID = $adresID AND adresUyeID = $uyeID";
$result = $baglan->query($sql);

if ($result->num_rows > 0) {
    $adres = $result->fetch_assoc();
} else {
    header('Location: profil.php#addresses');
    exit;
}

// Form gönderildiğinde adresi güncelle
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresBilgisi = trim($_POST['adresBilgisi']);

    if (!empty($adresBilgisi)) {
        $adresBilgisi = $baglan->real_escape_string($adresBilgisi);
        $sql = "UPDATE t_adresler SET adresBilgisi = '$adresBilgisi' WHERE adresID = $adresID AND adresUyeID = $uyeID";

        if ($baglan->query($sql) === TRUE) {
            header('Location: profil.php#addresses');
            exit;
        } else {
            $hataMesaji = "Adres güncellenemedi: " . $baglan->error;
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
    <title>Adres Düzenle</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
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
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
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
        <h2>Adres Düzenle</h2>
        <form action="adresDuzenle.php?adresID=<?php echo $adresID; ?>" method="POST">
            <div class="form-group">
                <label for="adresBilgisi">Adres Bilgisi</label>
                <textarea name="adresBilgisi" id="adresBilgisi" required><?php echo htmlspecialchars($adres['adresBilgisi']); ?></textarea>
            </div>
            <button type="submit" class="btn">Güncelle</button>
            <?php if (!empty($hataMesaji)): ?>
                <div class="error"><?php echo $hataMesaji; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>

</html>