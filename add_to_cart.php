<?php
// filepath: c:\xampp\htdocs\patishop\add_to_cart.php
include 'ayar.php';
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$urunID = $data['urunID'];
$uyeID = 1; // Örnek kullanıcı ID'si (giriş yapmış kullanıcıdan alınmalı)

$sql = "INSERT INTO t_sepet (sepetUrunID, sepetUyeID, sepetUrunFiyat, sepetUrunMiktar, sepetOlusturmaTarih) 
        SELECT urunID, $uyeID, urunFiyat, 1, NOW() FROM t_urunler WHERE urunID = $urunID";

if ($baglan->query($sql) === TRUE) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => $baglan->error]);
}
?>