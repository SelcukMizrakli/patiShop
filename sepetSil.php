<?php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['sepetID'])) {
    $sepetID = intval($_GET['sepetID']);
    $sql = "DELETE FROM t_sepet WHERE sepetID = $sepetID AND sepetUyeID = " . $_SESSION['uyeID'];
    if ($baglan->query($sql) === TRUE) {
        header('Location: profil.php#cart');
        exit;
    } else {
        echo "Hata: " . $baglan->error;
    }
} else {
    header('Location: profil.php#cart');
    exit;
}
?>