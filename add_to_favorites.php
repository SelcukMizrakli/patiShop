<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'ayar.php';
session_start();
header('Content-Type: application/json');

// Kullanıcı giriş yapmamışsa giriş yapma formuna yönlendir
if (!isset($_SESSION['uyeID'])) {
    echo json_encode(['success' => false, 'redirect' => 'index.php?showLoginModal=true']);
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

// Ürün zaten favorilerde mi kontrol et
$sqlCheck = "SELECT * FROM t_favoriler WHERE favoriUyeID = $uyeID AND favoriUrunID = $urunID";
$resultCheck = $baglan->query($sqlCheck);

if ($resultCheck->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Bu ürün zaten favorilerinizde.']);
    exit;
}

// Ürünü favorilere ekle
$sqlInsert = "INSERT INTO t_favoriler (favoriUyeID, favoriUrunID, favoriOlusturmaTarih) VALUES ($uyeID, $urunID, NOW())";
if ($baglan->query($sqlInsert) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Ürün favorilere eklendi.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Ürün favorilere eklenemedi: ' . $baglan->error]);
}
?>