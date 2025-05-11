<?php
// filepath: c:\xampp\htdocs\patishop\siparisDetay.php
include 'ayar.php';
session_start();

if (!isset($_GET['siparisID'])) {
    echo '<p>Geçersiz istek.</p>';
    exit;
}
$siparisID = intval($_GET['siparisID']);

// Sipariş ve müşteri bilgisi
$sqlSiparis = "SELECT s.*, u.uyeAd, u.uyeSoyad, u.uyeMail, a.adresBilgisi
               FROM t_siparis s
               INNER JOIN t_uyeler u ON s.siparisUyeID = u.uyeID
               INNER JOIN t_adresler a ON s.siparisAdresID = a.adresID
               WHERE s.siparisID = $siparisID";
$resSiparis = $baglan->query($sqlSiparis);
if ($resSiparis->num_rows == 0) {
    echo '<p>Sipariş bulunamadı.</p>';
    exit;
}
$siparis = $resSiparis->fetch_assoc();

// Sepet ürünlerini çek
$sepetIDs = array_filter(explode(',', $siparis['siparisSepetID']));
if (empty($sepetIDs)) {
    echo '<p>Siparişe ait ürün bulunamadı.</p>';
    exit;
}
$sepetIDString = implode(',', array_map('intval', $sepetIDs));
$sqlUrunler = "SELECT sp.sepetUrunID, sp.sepetUrunMiktar, sp.sepetUrunFiyat, u.urunAdi, r.resimYolu
               FROM t_sepet sp
               INNER JOIN t_urunler u ON sp.sepetUrunID = u.urunID
               LEFT JOIN t_resimler r ON u.urunResimID = r.resimID
               WHERE sp.sepetID IN ($sepetIDString)";
$resUrunler = $baglan->query($sqlUrunler);

// Toplam tutar
$toplamTutar = 0;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sipariş Detayı</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f8f9fa; margin: 0; padding: 0; }
        .container { max-width: 700px; margin: 40px auto; background: #fff; border-radius: 8px; box-shadow: 0 2px 8px #0001; padding: 30px; }
        .order-header { margin-bottom: 20px; }
        .order-header h2 { margin: 0 0 10px 0; }
        .order-info { color: #555; margin-bottom: 10px; }
        .product-list { margin-top: 20px; }
        .product-item { display: flex; align-items: center; gap: 20px; margin-bottom: 18px; background: #f5f5f5; border-radius: 6px; padding: 10px 15px; }
        .product-item img { width: 70px; height: 70px; object-fit: cover; border-radius: 6px; border: 1px solid #eee; }
        .product-details { flex: 1; }
        .product-name { font-weight: bold; font-size: 16px; margin-bottom: 4px; }
        .product-meta { color: #666; font-size: 14px; }
        .order-total { font-size: 18px; font-weight: bold; margin-top: 25px; text-align: right; }
        a { color: #4CAF50; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>
<div class="container">
    <div class="order-header">
        <h2>Sipariş #<?php echo $siparis['siparisID']; ?></h2>
        <div class="order-info">
            <div><b>Müşteri:</b> <?php echo htmlspecialchars($siparis['uyeAd'] . ' ' . $siparis['uyeSoyad']); ?> (<?php echo htmlspecialchars($siparis['uyeMail']); ?>)</div>
            <div><b>Adres:</b> <?php echo htmlspecialchars($siparis['adresBilgisi']); ?></div>
            <div><b>Tarih:</b> <?php echo $siparis['siparisOdemeTarih']; ?></div>
            <div><b>Durum:</b>
                <?php
                echo ($siparis['siparisDurum'] == 0) ? "Hazırlanıyor" :
                     (($siparis['siparisDurum'] == 1) ? "Kargoya Verildi" : "Teslim Edildi");
                ?>
            </div>
        </div>
    </div>
    <div class="product-list">
        <?php
        while ($urun = $resUrunler->fetch_assoc()) {
            $img = !empty($urun['resimYolu']) ? $urun['resimYolu'] : 'default-image.jpg';
            $toplamTutar += $urun['sepetUrunFiyat'] * $urun['sepetUrunMiktar'];
            echo '<div class="product-item">';
            echo '<a href="urundetay.php?urunID=' . $urun['sepetUrunID'] . '" target="_blank">';
            echo '<img src="' . $img . '" alt="' . htmlspecialchars($urun['urunAdi']) . '">';
            echo '</a>';
            echo '<div class="product-details">';
            echo '<div class="product-name">' . htmlspecialchars($urun['urunAdi']) . '</div>';
            echo '<div class="product-meta">Adet: ' . $urun['sepetUrunMiktar'] . ' | Fiyat: ' . number_format($urun['sepetUrunFiyat'], 2) . ' TL</div>';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
    <div class="order-total">
        Toplam: <?php echo number_format($toplamTutar, 2); ?> TL
    </div>
</div>
</body>
</html>