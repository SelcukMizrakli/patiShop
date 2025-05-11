<?php
// filepath: c:\xampp\htdocs\patishop\odeme.php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: index.php?showLoginModal=true');
    exit;
}

$uyeID = $_SESSION['uyeID'];

// Kullanıcının adreslerini çek
$sqlAdres = "SELECT adresID, adresBilgisi FROM t_adresler WHERE adresUyeID = $uyeID";
$resultAdres = $baglan->query($sqlAdres);
$adresler = [];
if ($resultAdres->num_rows > 0) {
    while ($row = $resultAdres->fetch_assoc()) {
        $adresler[] = $row;
    }
}

// Sepetteki ürünleri al
$sqlSepet = "SELECT sepetID, sepetUrunID, sepetUrunFiyat, sepetUrunMiktar FROM t_sepet WHERE sepetUyeID = $uyeID AND sepetGorunurluk = 1";
$resultSepet = $baglan->query($sqlSepet);

if ($resultSepet->num_rows > 0) {
    // Toplam tutarı hesapla
    $toplamTutar = 0;
    $sepetIDList = []; // Sepet ID'lerini saklamak için bir dizi
    while ($row = $resultSepet->fetch_assoc()) {
        $toplamTutar += $row['sepetUrunFiyat'] * $row['sepetUrunMiktar'];
        $sepetIDList[] = $row['sepetID']; // Sepet ID'sini diziye ekle
    }

    // Sepet ID'lerini virgülle ayrılmış bir stringe dönüştür
    $sepetIDString = implode(',', $sepetIDList);

    // Kullanıcıdan adres al
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $adresID = isset($_POST['adresID']) ? intval($_POST['adresID']) : null;
        $yeniAdres = isset($_POST['yeniAdres']) ? $baglan->real_escape_string(trim($_POST['yeniAdres'])) : null;

        // Eğer yeni bir adres girilmişse, bunu veri tabanına ekle
        if (empty($adresID) && !empty($yeniAdres)) {
            $sqlYeniAdres = "INSERT INTO t_adresler (adresUyeID, adresBilgisi) VALUES ($uyeID, '$yeniAdres')";
            if ($baglan->query($sqlYeniAdres) === TRUE) {
                $adresID = $baglan->insert_id; // Yeni eklenen adresin ID'sini al
            } else {
                echo "<script>alert('Adres kaydedilemedi: " . $baglan->error . "'); window.history.back();</script>";
                exit;
            }
        }

        // Eğer adresID hala boşsa hata göster
        if (empty($adresID)) {
            echo "<script>alert('Lütfen bir adres seçin veya yeni bir adres girin.'); window.history.back();</script>";
            exit;
        }

        // Yeni sipariş oluştur
        $sqlSiparis = "INSERT INTO t_siparis (siparisUyeID, siparisSepetID, siparisAdresID, siparisDurum, siparisOdemeDurum, siparisOdemeTarih) 
                       VALUES ($uyeID, '$sepetIDString', $adresID, 0, 1, NOW())";
        if ($baglan->query($sqlSiparis) === TRUE) {
            $siparisID = $baglan->insert_id;

            // Sepetteki ürünleri siparişle ilişkilendir
            $sqlSepetGuncelle = "UPDATE t_sepet SET sepetGorunurluk = 0 WHERE sepetUyeID = $uyeID AND sepetGorunurluk = 1";
            if ($baglan->query($sqlSepetGuncelle) === TRUE) {
                echo "<script>alert('Ödeme başarılı! Siparişiniz oluşturuldu.'); window.location.href = 'profil.php#orders';</script>";
            } else {
                echo "<script>alert('Sepet güncellenemedi: " . $baglan->error . "'); window.history.back();</script>";
            }
        } else {
            echo "<script>alert('Sipariş oluşturulamadı: " . $baglan->error . "'); window.history.back();</script>";
        }
    }
} else {
    echo "<script>alert('Sepetinizde ürün bulunmamaktadır.'); window.history.back();</script>";
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
        <form action="odeme.php" method="POST">
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