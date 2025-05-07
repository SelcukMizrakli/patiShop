<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'ayar.php';
session_start();
header('Content-Type: application/json');

// Kullanıcı giriş yapmamışsa giriş yapma formuna yönlendir
if (!isset($_SESSION['uyeID'])) {
    echo json_encode(['success' => false, 'redirect' => 'girisYap.php']);
    exit;
}

// Gelen veriyi kontrol et
$data = json_decode(file_get_contents('php://input'), true);
file_put_contents('debug.log', print_r($data, true), FILE_APPEND);

if (!isset($data['urunID']) || empty($data['urunID']) || !isset($data['miktar']) || empty($data['miktar'])) {
    echo json_encode(['success' => false, 'message' => 'Ürün ID veya miktar eksik veya geçersiz.']);
    exit;
}

$urunID = intval($data['urunID']);
$miktar = intval($data['miktar']);
$uyeID = $_SESSION['uyeID'];

// Sepette aynı ürün varsa miktarı güncelle
$sql = "SELECT sepetID, sepetUrunMiktar FROM t_sepet WHERE sepetUrunID = $urunID AND sepetUyeID = $uyeID AND sepetGorunurluk = 1";
$result = $baglan->query($sql);

if ($result->num_rows > 0) {
    // Ürün zaten sepette, miktarı güncelle
    $row = $result->fetch_assoc();
    $yeniMiktar = $row['sepetUrunMiktar'] + $miktar;
    $updateSql = "UPDATE t_sepet SET sepetUrunMiktar = $yeniMiktar WHERE sepetID = " . $row['sepetID'];
    if ($baglan->query($updateSql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Ürün miktarı güncellendi.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ürün miktarı güncellenemedi: ' . $baglan->error]);
    }
} else {
    // Ürün sepette yok, yeni kayıt ekle
    $insertSql = "INSERT INTO t_sepet (sepetUrunID, sepetUyeID, sepetUrunFiyat, sepetUrunMiktar, sepetOlusturmaTarih, sepetGorunurluk) 
                  SELECT urunID, $uyeID, urunFiyat, $miktar, NOW(), 1 FROM t_urunler WHERE urunID = $urunID";
    if ($baglan->query($insertSql) === TRUE) {
        echo json_encode(['success' => true, 'message' => 'Ürün sepete eklendi.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Ürün sepete eklenemedi: ' . $baglan->error]);
    }
}
