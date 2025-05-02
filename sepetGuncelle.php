<?php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sepetID = intval($_POST['sepetID']);
    $action = $_POST['action'];

    // Mevcut ürün miktarını al
    $sql = "SELECT sepetUrunMiktar FROM t_sepet WHERE sepetID = $sepetID AND sepetUyeID = " . $_SESSION['uyeID'];
    $result = $baglan->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $miktar = intval($row['sepetUrunMiktar']);

        if ($action === 'increase') {
            $miktar++;
        } elseif ($action === 'decrease' && $miktar > 1) {
            $miktar--;
        }

        // Ürün miktarını güncelle
        $updateSql = "UPDATE t_sepet SET sepetUrunMiktar = $miktar WHERE sepetID = $sepetID AND sepetUyeID = " . $_SESSION['uyeID'];
        $baglan->query($updateSql);
    }
}

header('Location: profil.php#cart');
exit;
?>