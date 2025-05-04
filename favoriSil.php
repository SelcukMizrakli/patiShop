<?php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['favoriID'])) {
    $favoriID = intval($_GET['favoriID']);
    $sql = "DELETE FROM t_favoriler WHERE favoriID = $favoriID AND favoriUyeID = " . $_SESSION['uyeID'];
    if ($baglan->query($sql) === TRUE) {
        header('Location: profil.php#favorites');
        exit;
    } else {
        echo "Hata: " . $baglan->error;
    }
} else {
    header('Location: profil.php#favorites');
    exit;
}
?>