<?php
// filepath: c:\xampp\htdocs\patishop\siparisDetay.php
include 'ayar.php';
session_start();

if (!isset($_GET['siparisID'])) {
    echo '<p>Geçersiz sipariş ID.</p>';
    exit;
}

$siparisID = intval($_GET['siparisID']);
$sql = "SELECT s.siparisID, s.siparisOdemeTarih, s.siparisDurum, k.kargoFirmaAdi, k.kargoTeslimatTarihi, 
               u.urunID, u.urunAdi, u.urunFiyat, r.resimYolu, sp.sepetUrunMiktar
        FROM t_siparis s
        INNER JOIN t_kargo k ON s.siparisID = k.kargoSiparisID
        INNER JOIN t_sepet sp ON s.siparisSepetID = sp.sepetID
        INNER JOIN t_urunler u ON sp.sepetUrunID = u.urunID
        LEFT JOIN t_resimler r ON u.urunResimID = r.resimID
        WHERE s.siparisID = $siparisID";

$result = $baglan->query($sql);

if ($result->num_rows > 0) {
    // İlk satırı al ve genel bilgileri göster
    $row = $result->fetch_assoc();
    echo '<p><strong>Sipariş ID:</strong> #' . $row['siparisID'] . '</p>';
    echo '<p><strong>Kargo Firması:</strong> ' . $row['kargoFirmaAdi'] . '</p>';
    echo '<p><strong>Teslimat Tarihi:</strong> ' . $row['kargoTeslimatTarihi'] . '</p>';
    echo '<h3>Ürünler:</h3>';
    echo '<ul>';

    // İlk satırdaki ürün bilgilerini göster
    echo '<li>';
    echo '<a href="urunDetay.php?urunID=' . $row['urunID'] . '">';
    echo '<img src="' . $row['resimYolu'] . '" alt="' . $row['urunAdi'] . '" style="width: 50px; height: 50px; object-fit: cover;">';
    echo '</a> ';
    echo $row['urunAdi'] . ' - ' . $row['sepetUrunMiktar'] . ' Adet - ' . number_format($row['urunFiyat'], 2) . ' TL';
    echo '</li>';

    // Kalan ürünleri döngüyle göster
    while ($row = $result->fetch_assoc()) {
        echo '<li>';
        echo '<a href="urunDetay.php?urunID=' . $row['urunID'] . '">';
        echo '<img src="' . $row['resimYolu'] . '" alt="' . $row['urunAdi'] . '" style="width: 50px; height: 50px; object-fit: cover;">';
        echo '</a> ';
        echo $row['urunAdi'] . ' - ' . $row['sepetUrunMiktar'] . ' Adet - ' . number_format($row['urunFiyat'], 2) . ' TL';
        echo '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>Sipariş detayları bulunamadı.</p>';
}
?>