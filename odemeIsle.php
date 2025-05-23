<?php
include 'ayar.php';
session_start();

if (!isset($_SESSION['uyeID'])) {
    header('Location: girisYap.php');
    exit;
}

$uyeID = $_SESSION['uyeID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $adresID = isset($_POST['adresID']) ? intval($_POST['adresID']) : null;
    $yeniAdres = isset($_POST['yeniAdres']) ? trim($_POST['yeniAdres']) : null;
    $kartNo = isset($_POST['kartNo']) ? trim($_POST['kartNo']) : null;
    $sonKullanma = isset($_POST['sonKullanma']) ? trim($_POST['sonKullanma']) : null;
    $cvv = isset($_POST['cvv']) ? trim($_POST['cvv']) : null;

    // Kullanıcının sepetteki tüm ürünlerinin sepetID'lerini al
    $sqlSepet = "SELECT sepetID FROM t_sepet WHERE sepetUyeID = $uyeID AND sepetGorunurluk = 1";
    $resultSepet = $baglan->query($sqlSepet);
    $sepetIDList = [];
    if ($resultSepet && $resultSepet->num_rows > 0) {
        while ($row = $resultSepet->fetch_assoc()) {
            $sepetIDList[] = $row['sepetID'];
        }
    }

    if (empty($sepetIDList)) {
        echo "<script>alert('Sepetinizde ürün bulunmamaktadır.'); window.history.back();</script>";
        exit;
    }

    $sepetIDString = implode(',', $sepetIDList); // <-- DİKKAT: Virgülle ayırıyoruz!

    // Eksik bilgi kontrolü
    if (empty($kartNo) || empty($sonKullanma) || empty($cvv) || (empty($adresID) && empty($yeniAdres))) {
        echo "<script>alert('Lütfen tüm alanları doldurun.'); window.history.back();</script>";
        exit;
    }

    // Yeni adres ekleme
    if (empty($adresID) && !empty($yeniAdres)) {
        $yeniAdres = $baglan->real_escape_string($yeniAdres);
        $sql = "INSERT INTO t_adresler (adresUyeID, adresBilgisi) VALUES ($uyeID, '$yeniAdres')";
        if ($baglan->query($sql) === TRUE) {
            $adresID = $baglan->insert_id;
        } else {
            echo "<script>alert('Adres kaydedilemedi: " . $baglan->error . "'); window.history.back();</script>";
            exit;
        }
    }

    // Sipariş bilgilerini t_siparis tablosuna ekle
    $sqlSiparis = "INSERT INTO t_siparis (siparisUyeID, siparisAdresID, siparisSepetID, siparisOdemeTarih, siparisDurum, siparisOdemeDurum) 
               VALUES ($uyeID, $adresID, '$sepetIDString', NOW(), 0, 1)";
    if ($baglan->query($sqlSiparis) === TRUE) {
        $siparisID = $baglan->insert_id;

        // Kargo bilgilerini t_kargo tablosuna ekle
        $kargoFirmaAdi = "TDM";
        $kargoNo = uniqid('KARGO');
        $kargoDurumu = 0;
        $kargoyaVerilmeTarihi = "NULL";
        $teslimatGun = rand(3, 8);
        $kargoTeslimatTarihi = date('Y-m-d', strtotime("+$teslimatGun days"));

        $sqlKargo = "INSERT INTO t_kargo (kargoSiparisID, kargoNo, kargoDurumu, kargoFirmaAdi, kargoyaVerilmeTarihi, kargoTeslimatTarihi) 
                     VALUES ($siparisID, '$kargoNo', $kargoDurumu, '$kargoFirmaAdi', $kargoyaVerilmeTarihi, '$kargoTeslimatTarihi')";
        if ($baglan->query($sqlKargo) === TRUE) {
            // Sepetteki tüm ürünleri görünmez yap
            $sqlSepetGuncelle = "UPDATE t_sepet SET sepetGorunurluk = 0 WHERE sepetID IN ($sepetIDString)";
            if ($baglan->query($sqlSepetGuncelle) === TRUE) {
                echo "<script>alert('Ödeme Başarılı! Siparişiniz oluşturuldu.'); window.location.href = 'profil.php#current-order';</script>";
                exit;
            } else {
                echo "<script>alert('Sepet güncellenemedi: " . $baglan->error . "'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Kargo bilgileri kaydedilemedi: " . $baglan->error . "'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Sipariş kaydedilemedi: " . $baglan->error . "'); window.history.back();</script>";
        exit;
    }
}
