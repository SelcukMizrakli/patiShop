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
if (!isset($data['urunID']) || empty($data['urunID'])) {
    echo json_encode(['success' => false, 'message' => 'Ürün ID eksik veya geçersiz.']);
    exit;
}

$urunID = intval($data['urunID']);
$uyeID = $_SESSION['uyeID'];
$now = date('Y-m-d H:i:s');

// Favorilere ekleme sorgusu
$sql = "INSERT INTO t_favoriler (favoriUyeID, favoriUrunID, favoriOlusturmaTarih, favoriGuncellenmeTarih, favoriSilmeTarih) 
        VALUES ($uyeID, $urunID, '$now', '$now', NULL)";

if ($baglan->query($sql) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Ürün favorilere eklendi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ürün favorilere eklenemedi: ' . $baglan->error]);
}
?>