<?php
// filepath: c:\xampp\htdocs\patishop\odeme.php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: girisYap.php');
    exit;
}

$uyeID = $_SESSION['uyeID'];

// Kullanıcının sepet bilgilerini çek
$sqlSepet = "SELECT sepetID FROM t_sepet WHERE sepetUyeID = $uyeID LIMIT 1";
$resultSepet = $baglan->query($sqlSepet);
$sepetID = null;

if ($resultSepet->num_rows > 0) {
    $rowSepet = $resultSepet->fetch_assoc();
    $sepetID = $rowSepet['sepetID'];
}

// Kullanıcının adreslerini çek
$sqlAdres = "SELECT adresID, adresBilgisi FROM t_adresler WHERE adresUyeID = $uyeID";
$resultAdres = $baglan->query($sqlAdres);
$adresler = [];
if ($resultAdres->num_rows > 0) {
    while ($row = $resultAdres->fetch_assoc()) {
        $adresler[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Yap</title>
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
            margin: 5px auto;
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

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .form-group textarea {
            resize: none;
            height: 80px;
        }

        .btn {
            display: block;
            width: 50%;
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            text-align: center;
            margin: 20px auto;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .error {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <?php include 'headerHesap.php'; ?>
    <div class="container">
        <h2>Ödeme Yap</h2>
        <form action="odemeIsle.php" method="POST">
            <!-- Sepet ID -->
            <input type="hidden" name="sepetID" value="<?php echo $sepetID; ?>">

            <!-- Adres Seçimi -->
            <div class="form-group">
                <label for="adres">Adres Seçimi</label>
                <?php if (!empty($adresler)): ?>
                    <select name="adresID" id="adres" required>
                        <option value="">Adres Seçin</option>
                        <?php foreach ($adresler as $adres): ?>
                            <option value="<?php echo $adres['adresID']; ?>"><?php echo htmlspecialchars($adres['adresBilgisi']); ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <textarea name="yeniAdres" placeholder="Lütfen yeni bir adres girin" required></textarea>
                <?php endif; ?>
            </div>

            <!-- Kart Bilgileri -->
            <div class="form-group">
                <label for="kartNo">Kart Numarası</label>
                <input type="text" name="kartNo" id="kartNo" maxlength="16" pattern="\d{16}" placeholder="16 haneli kart numarası" required>
            </div>
            <div class="form-group">
                <label for="sonKullanma">Son Kullanma Tarihi</label>
                <input type="text" name="sonKullanma" id="sonKullanma" placeholder="MM/YY" maxlength="5" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" name="cvv" id="cvv" maxlength="3" pattern="\d{3}" placeholder="3 haneli CVV" required>
            </div>

            <!-- Ödeme Yap Butonu -->
            <button type="submit" class="btn">Ödeme Yap</button>
        </form>
    </div>

    <script>
        // Son Kullanma Tarihi Alanına "/" Ekleyen Fonksiyon
        document.getElementById('sonKullanma').addEventListener('input', function(e) {
            let value = e.target.value;

            // Eğer 2 karakter girilmişse ve "/" yoksa otomatik olarak ekle
            if (value.length === 2 && !value.includes('/')) {
                e.target.value = value + '/';
            }
        });
    </script>
</body>

</html>