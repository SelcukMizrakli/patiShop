<?php
// filepath: c:\xampp\htdocs\patishop\favoriSepeteEkle.php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: index.php?showLoginModal=true');
    exit;
}

$uyeID = $_SESSION['uyeID'];
$favoriID = isset($_GET['favoriID']) ? intval($_GET['favoriID']) : null;

if (!$favoriID) {
    echo "<script>alert('Geçersiz işlem.'); window.history.back();</script>";
    exit;
}

// Favori bilgilerini al
$sqlFavori = "SELECT favoriUrunID FROM t_favoriler WHERE favoriID = $favoriID AND favoriUyeID = $uyeID";
$resultFavori = $baglan->query($sqlFavori);

if ($resultFavori->num_rows > 0) {
    $favori = $resultFavori->fetch_assoc();
    $urunID = $favori['favoriUrunID'];

    // Ürünü sepete ekle
    $sqlSepet = "INSERT INTO t_sepet (sepetUrunID, sepetUyeID, sepetUrunFiyat, sepetUrunMiktar, sepetOlusturmaTarih, sepetGorunurluk)
                 SELECT urunID, $uyeID, urunFiyat, 1, NOW(), 1 FROM t_urunler WHERE urunID = $urunID";
    if ($baglan->query($sqlSepet) === TRUE) {
        // Favorilerden kaldır
        $sqlFavoriSil = "DELETE FROM t_favoriler WHERE favoriID = $favoriID";
        if ($baglan->query($sqlFavoriSil) === TRUE) {
            echo "<script>alert('Ürün sepete eklendi ve favorilerden kaldırıldı.'); window.location.href = 'profil.php#favorites';</script>";
        } else {
            echo "<script>alert('Ürün sepete eklendi ancak favorilerden kaldırılamadı: " . $baglan->error . "'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Ürün sepete eklenemedi: " . $baglan->error . "'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Favori bulunamadı.'); window.history.back();</script>";
}
?>