<?php
include 'ayar.php';
session_start();
header('Content-Type: application/json');

// Kullanıcı giriş yapmamışsa giriş yapma formuna yönlendir
if (!isset($_SESSION['uyeID'])) {
    echo json_encode(['success' => false, 'redirect' => 'girisYap.php']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$urunID = $data['urunID'];
$uyeID = $_SESSION['uyeID'];

$sql = "INSERT INTO t_favoriler (favoriUyeID, favoriUrunID, favoriOlusturmaTarih) 
        VALUES ($uyeID, $urunID, NOW())";

if ($baglan->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $baglan->error]);
}
?>