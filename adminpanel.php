<?php
include 'ayar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $urunAdi = $baglan->real_escape_string($_POST['urunAdi']);
    $urunKategoriID = (int)$_POST['urunKategoriID'];
    $urunFiyat = (float)$_POST['urunFiyat'];
    $stokMiktar = (int)$_POST['stokMiktar'];

    $sql = "INSERT INTO t_urunler (urunAdi, urunKategoriID, urunFiyat, urunKayitTarih) 
            VALUES ('$urunAdi', $urunKategoriID, $urunFiyat, NOW())";

    if ($baglan->query($sql) === TRUE) {
        $urunID = $baglan->insert_id;
        $stokSql = "INSERT INTO t_stok (stokUrunID, stokMiktar, stokGirisTarih) 
                    VALUES ($urunID, $stokMiktar, NOW())";
        $baglan->query($stokSql);
        echo "Ürün başarıyla eklendi.";
    } else {
        echo "Hata: " . $baglan->error;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PatiShop - Admin Paneli</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        :root {
            --primary: #4CAF50;
            --primary-dark: #388E3C;
            --light-gray: #f5f5f5;
            --border-color: #ddd;
            --text-dark: #333;
        }

        body {
            background-color: #f8f9fa;
        }

        .header {
            background-color: var(--primary);
            color: white;
            padding: 15px 0;
            text-align: center;
        }

        .container {
            display: flex;
            min-height: calc(100vh - 60px);
        }

        .sidebar {
            width: 250px;
            background-color: white;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            padding: 20px 0;
        }

        .sidebar-header {
            display: flex;
            align-items: center;
            padding: 0 20px 20px;
            border-bottom: 1px solid var(--border-color);
        }

        .sidebar-header img {
            width: 40px;
            margin-right: 10px;
        }

        .menu-item {
            padding: 12px 20px;
            display: flex;
            align-items: center;
            color: var(--text-dark);
            text-decoration: none;
            transition: all 0.3s;
        }

        .menu-item i {
            margin-right: 10px;
            font-size: 18px;
        }

        .menu-item:hover,
        .menu-item.active {
            background-color: var(--light-gray);
            border-left: 4px solid var(--primary);
        }

        .content {
            flex: 1;
            padding: 20px;
        }

        .content-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            background-color: var(--primary);
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            display: inline-flex;
            align-items: center;
        }

        .btn i {
            margin-right: 5px;
        }

        .btn:hover {
            background-color: var(--primary-dark);
        }

        .card {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            padding: 15px 20px;
            background-color: #f1f1f1;
            border-bottom: 1px solid var(--border-color);
            font-weight: bold;
        }

        .card-body {
            padding: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }

        th {
            background-color: #f8f9fa;
        }

        .action-btns {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-warning {
            background-color: #FFC107;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .stats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            align-items: center;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background-color: rgba(76, 175, 80, 0.1);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            color: var(--primary);
            font-size: 24px;
        }

        .stat-info h3 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .stat-info span {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            font-size: 14px;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
        }

        textarea.form-control {
            min-height: 100px;
        }

        .tab-container {
            margin-bottom: 20px;
        }

        .tabs {
            display: flex;
            border-bottom: 1px solid var(--border-color);
        }

        .tab {
            padding: 10px 20px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
        }

        .tab.active {
            border-bottom: 2px solid var(--primary);
            color: var(--primary);
            font-weight: 500;
        }

        .tab-content {
            padding: 20px 0;
        }

        .product-image-preview {
            width: 120px;
            height: 120px;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            margin-bottom: 10px;
        }

        .product-image-preview img {
            max-width: 100%;
            max-height: 100%;
        }

        .image-upload-btn {
            display: inline-block;
            padding: 8px 15px;
            background-color: #f1f1f1;
            border: 1px solid var(--border-color);
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
        }

        /* İkonlar için basit tanımlar */
        .fa {
            font-family: Arial;
            font-weight: bold;
            font-style: normal;
        }

        /* Modal arka plan */
        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        /* Modal içeriği */
        .modal-content {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            width: 400px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            position: relative;
        }

        /* Kapatma butonu */
        .close {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 20px;
            cursor: pointer;
        }

        .close:hover {
            color: red;
        }
    </style>
</head>

<body>
    <!-- Header -->
    <div class="header">
        <h2>PatiShop Yönetim Paneli</h2>
    </div>

    <!-- Ana Container -->
    <div class="container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="/api/placeholder/50/50" alt="PatiShop Logo">
                <h3>PatiShop Admin</h3>
            </div>

            <!-- Dinamik Menü -->
            <?php
            $sql = "SELECT kategoriAdi, kategoriSlug, kategoriIkonUrl FROM t_kategori WHERE kategoriDurum = 1";
            $result = $baglan->query($sql);

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="kategori.php?kategori=' . $row['kategoriSlug'] . '" class="menu-item">';
                    echo '<i class="fa"><img src="' . $row['kategoriIkonUrl'] . '" alt="' . $row['kategoriAdi'] . '" style="width: 18px; height: 18px;"></i>';
                    echo $row['kategoriAdi'];
                    echo '</a>';
                }
            } else {
                echo '<p style="padding: 20px; color: #666;">Kategori bulunamadı.</p>';
            }
            ?>

            <!-- Sabit Menü Öğeleri -->
            <a href="siparisler.php" class="menu-item">
                <i class="fa">🛒</i> Siparişler
            </a>
            <a href="kullanicilar.php" class="menu-item">
                <i class="fa">👥</i> Kullanıcılar
            </a>
            <a href="kampanyalar.php" class="menu-item">
                <i class="fa">🔖</i> Kampanyalar
            </a>
            <a href="ayarlar.php" class="menu-item">
                <i class="fa">⚙️</i> Ayarlar
            </a>
            <a href="logout.php" class="menu-item">
                <i class="fa">🚪</i> Çıkış Yap
            </a>
        </div>

        <!-- İçerik Alanı -->
        <div class="content">
            <div class="content-header">
                <h2>Dashboard</h2>
                <div>
                    <button class="btn" onclick="openNewProductModal()">
                        <i class="fa">➕</i> Yeni Ürün Ekle
                    </button>
                </div>
            </div>

            <!-- Son Siparişler -->
            <div class="card">
                <div class="card-header">
                    Son Siparişler
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Sipariş No</th>
                                <th>Müşteri</th>
                                <th>Tarih</th>
                                <th>Tutar</th>
                                <th>Durum</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT s.siparisID, u.uyeAd, u.uyeSoyad, s.siparisOdemeTarih, SUM(sp.sepetUrunFiyat * sp.sepetUrunMiktar) AS toplamTutar, 
                                    CASE s.siparisDurum 
                                        WHEN 0 THEN 'Hazırlanıyor' 
                                        WHEN 1 THEN 'Kargoya Verildi' 
                                        WHEN 2 THEN 'Teslim Edildi' 
                                    END AS siparisDurum
                                    FROM t_siparis s
                                    INNER JOIN t_uyeler u ON s.siparisUyeID = u.uyeID
                                    INNER JOIN t_sepet sp ON s.siparisSepetID = sp.sepetID
                                    GROUP BY s.siparisID
                                    ORDER BY s.siparisOdemeTarih DESC
                                    LIMIT 5";
                            $result = $baglan->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>#ORD-' . $row['siparisID'] . '</td>';
                                    echo '<td>' . $row['uyeAd'] . ' ' . $row['uyeSoyad'] . '</td>';
                                    echo '<td>' . $row['siparisOdemeTarih'] . '</td>';
                                    echo '<td>₺' . number_format($row['toplamTutar'], 2) . '</td>';
                                    echo '<td>' . $row['siparisDurum'] . '</td>';
                                    echo '<td class="action-btns"><button class="btn btn-sm">Görüntüle</button></td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6">Sipariş bulunamadı.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Ürün Yönetimi Tab Container -->
            <div class="card">
                <div class="card-header">
                    Ürün Yönetimi
                </div>
                <div class="card-body">
                    <div class="tab-container">
                        <div class="tabs">
                            <div class="tab active">Ürün Listesi</div>
                            <div class="tab" onclick="openNewProductModal()">Yeni Ürün Ekle</div>
                        </div>

                        <div class="tab-content">
                            <table>
                                <thead>
                                    <tr>
                                        <th>Ürün Kodu</th>
                                        <th>Ürün Adı</th>
                                        <th>Kategori</th>
                                        <th>Stok</th>
                                        <th>Fiyat</th>
                                        <th>İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT u.urunID, u.urunAdi, u.urunFiyat, k.kategoriAdi, s.stokMiktar 
                                            FROM t_urunler u
                                            INNER JOIN t_kategori k ON u.urunKategoriID = k.kategoriID
                                            INNER JOIN t_stok s ON u.urunID = s.stokUrunID
                                            LIMIT 10";
                                    $result = $baglan->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo '<tr>';
                                            echo '<td>PRD-' . $row['urunID'] . '</td>';
                                            echo '<td>' . $row['urunAdi'] . '</td>';
                                            echo '<td>' . $row['kategoriAdi'] . '</td>';
                                            echo '<td>' . $row['stokMiktar'] . '</td>';
                                            echo '<td>₺' . number_format($row['urunFiyat'], 2) . '</td>';
                                            echo '<td class="action-btns">';
                                            echo '<button class="btn btn-sm btn-warning" onclick="openUpdateProductModal({
                                                name: \'' . $row['urunAdi'] . '\',
                                                category: \'' . $row['kategoriAdi'] . '\',
                                                price: ' . $row['urunFiyat'] . ',
                                                stock: ' . $row['stokMiktar'] . '
                                            })">Düzenle</button>';
                                            echo '<button class="btn btn-sm btn-danger">Sil</button>';
                                            echo '</td>';
                                            echo '</tr>';
                                        }
                                    } else {
                                        echo '<tr><td colspan="6">Ürün bulunamadı.</td></tr>';
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Yeni Ürün Ekle Modal -->
            <div id="newProductModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close" onclick="closeNewProductModal()">&times;</span>
                    <h2>Yeni Ürün Ekle</h2>
                    <form>
                        <div class="form-group">
                            <label for="productName">Ürün Adı</label>
                            <input type="text" id="productName" class="form-control" placeholder="Ürün adını giriniz">
                        </div>

                        <div class="form-group">
                            <label for="productCategory">Kategori</label>
                            <input type="text" id="productCategory" class="form-control" placeholder="Kategori arayın" onfocus="showAllCategories()" oninput="filterCategories()">
                            <div id="dropdownCategory" class="dropdown" style="margin-top: 10px; display: none; border: 1px solid var(--border-color); border-radius: 4px; background-color: white; max-height: 150px; overflow-y: auto;">
                                <div class="dropdown-item" onclick="selectCategory('Köpek Ürünleri')">Köpek Ürünleri</div>
                                <div class="dropdown-item" onclick="selectCategory('Kedi Ürünleri')">Kedi Ürünleri</div>
                                <div class="dropdown-item" onclick="selectCategory('Balık Ürünleri')">Balık Ürünleri</div>
                                <div class="dropdown-item" onclick="selectCategory('Kuş Ürünleri')">Kuş Ürünleri</div>
                                <div class="dropdown-item" onclick="selectCategory('Hamster Ürünleri')">Hamster Ürünleri</div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="productPrice">Fiyat (₺)</label>
                            <input type="number" id="productPrice" class="form-control" placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label for="productStock">Stok Miktarı</label>
                            <input type="number" id="productStock" class="form-control" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label>Ürün Görseli</label>
                            <div class="product-image-preview">
                                <img src="/api/placeholder/120/120" alt="Ürün Görseli">
                            </div>
                            <label class="image-upload-btn">
                                Görsel Seç
                                <input type="file" style="display: none;">
                            </label>
                        </div>

                        <div class="form-group">
                            <label for="campaignSeason">Kampanya</label>
                            <input type="text" id="campaignSeason" class="form-control" placeholder="Kampanya arayın" onfocus="showAllCampaigns()" oninput="filterCampaigns()">
                            <div id="dropdownCampaign" class="dropdown" style="margin-top: 10px; display: none; border: 1px solid var(--border-color); border-radius: 4px; background-color: white; max-height: 150px; overflow-y: auto;">
                                <div class="dropdown-item" onclick="selectCampaign('Kampanya Yok')">Kampanya yok</div>
                                <div class="dropdown-item" onclick="selectCampaign('Yaz Kampanyası')">Yaz Kampanyası</div>
                                <div class="dropdown-item" onclick="selectCampaign('Kış Kampanyası')">Kış Kampanyası</div>
                                <div class="dropdown-item" onclick="selectCampaign('Bahar Kampanyası')">Bahar Kampanyası</div>
                                <div class="dropdown-item" onclick="selectCampaign('Sonbahar Kampanyası')">Sonbahar Kampanyası</div>
                                <div class="dropdown-item" onclick="selectCampaign('Yılbaşı Kampanyası')">Yılbaşı Kampanyası</div>
                            </div>
                        </div>
                        <script>
                            function showAllCampaigns() {
                                const dropdownList = document.getElementById('dropdownCampaign');
                                const items = dropdownList.getElementsByClassName('dropdown-item');
                                for (let i = 0; i < items.length; i++) {
                                    items[i].style.display = 'block'; // Tüm öğeleri görünür yap
                                }
                                dropdownList.style.display = 'block'; // Dropdown'u görünür yap
                            }

                            function filterCampaigns() {
                                const input = document.getElementById('campaignSeason').value.toLowerCase();
                                const dropdownList = document.getElementById('dropdownCampaign');
                                const items = dropdownList.getElementsByClassName('dropdown-item');
                                let hasVisibleItem = false;

                                for (let i = 0; i < items.length; i++) {
                                    const itemText = items[i].textContent.toLowerCase();
                                    const isVisible = itemText.includes(input);
                                    items[i].style.display = isVisible ? 'block' : 'none';
                                    if (isVisible) hasVisibleItem = true;
                                }

                                // Dropdown görünürlüğünü ayarla
                                dropdownList.style.display = hasVisibleItem || input === '' ? 'block' : 'none';
                            }

                            function selectCampaign(campaign) {
                                const input = document.getElementById('campaignSeason');
                                input.value = campaign;
                                document.getElementById('dropdownCampaign').style.display = 'none';
                            }

                            // Dropdown dışında bir yere tıklanınca dropdown'u kapat
                            document.addEventListener('click', function(event) {
                                const dropdown = document.getElementById('dropdownCampaign');
                                const input = document.getElementById('campaignSeason');
                                if (!dropdown.contains(event.target) && event.target !== input) {
                                    dropdown.style.display = 'none';
                                }
                            });
                        </script>

                        <div class="form-group">
                            <label for="productDescription">Ürün Açıklaması</label>
                            <textarea id="productDescription" class="form-control" placeholder="Ürün detaylarını giriniz"></textarea>
                        </div>

                        <button type="submit" class="btn">Ürünü Kaydet</button>
                    </form>
                </div>
            </div>

            <!-- Ürün Güncelleme Modal -->
            <div id="updateProductModal" class="modal" style="display: none;">
                <div class="modal-content">
                    <span class="close" onclick="closeUpdateProductModal()">&times;</span>
                    <h2>Ürün Bilgilerini Güncelle</h2>
                    <form>
                        <div class="form-group">
                            <label for="updateProductName">Ürün Adı</label>
                            <input type="text" id="updateProductName" class="form-control" placeholder="Ürün adını giriniz">
                        </div>

                        <div class="form-group">
                            <label for="updateProductCategory">Kategori</label>
                            <input type="text" id="updateProductCategory" class="form-control" placeholder="Kategori arayın" onfocus="showAllCategoriesForUpdate()" oninput="filterCategoriesForUpdate()">
                            <div id="dropdownCategoryUpdate" class="dropdown" style="margin-top: 10px; display: none; border: 1px solid var(--border-color); border-radius: 4px; background-color: white; max-height: 150px; overflow-y: auto;">
                                <div class="dropdown-item" onclick="selectCategoryForUpdate('Köpek Ürünleri')">Köpek Ürünleri</div>
                                <div class="dropdown-item" onclick="selectCategoryForUpdate('Kedi Ürünleri')">Kedi Ürünleri</div>
                                <div class="dropdown-item" onclick="selectCategoryForUpdate('Balık Ürünleri')">Balık Ürünleri</div>
                                <div class="dropdown-item" onclick="selectCategoryForUpdate('Kuş Ürünleri')">Kuş Ürünleri</div>
                                <div class="dropdown-item" onclick="selectCategoryForUpdate('Hamster Ürünleri')">Hamster Ürünleri</div>
                            </div>
                        </div>
                        <script>
                            function showAllCategoriesForUpdate() {
                                const dropdownList = document.getElementById('dropdownCategoryUpdate');
                                const items = dropdownList.getElementsByClassName('dropdown-item');
                                for (let i = 0; i < items.length; i++) {
                                    items[i].style.display = 'block'; // Tüm öğeleri görünür yap
                                }
                                dropdownList.style.display = 'block'; // Dropdown'u görünür yap
                            }

                            function filterCategoriesForUpdate() {
                                const input = document.getElementById('updateProductCategory').value.toLowerCase();
                                const dropdownList = document.getElementById('dropdownCategoryUpdate');
                                const items = dropdownList.getElementsByClassName('dropdown-item');
                                let hasVisibleItem = false;

                                for (let i = 0; i < items.length; i++) {
                                    const itemText = items[i].textContent.toLowerCase();
                                    const isVisible = itemText.includes(input);
                                    items[i].style.display = isVisible ? 'block' : 'none';
                                    if (isVisible) hasVisibleItem = true;
                                }

                                // Dropdown görünürlüğünü ayarla
                                dropdownList.style.display = hasVisibleItem || input === '' ? 'block' : 'none';
                            }

                            function selectCategoryForUpdate(category) {
                                const input = document.getElementById('updateProductCategory');
                                input.value = category;
                                document.getElementById('dropdownCategoryUpdate').style.display = 'none';
                            }

                            // Dropdown dışında bir yere tıklanınca dropdown'u kapat
                            document.addEventListener('click', function(event) {
                                const dropdown = document.getElementById('dropdownCategoryUpdate');
                                const input = document.getElementById('updateProductCategory');
                                if (!dropdown.contains(event.target) && event.target !== input) {
                                    dropdown.style.display = 'none';
                                }
                            });
                        </script>

                        <div class="form-group">
                            <label for="updateProductPrice">Fiyat (₺)</label>
                            <input type="number" id="updateProductPrice" class="form-control" placeholder="0.00">
                        </div>

                        <div class="form-group">
                            <label for="updateProductStock">Stok Miktarı</label>
                            <input type="number" id="updateProductStock" class="form-control" placeholder="0">
                        </div>

                        <div class="form-group">
                            <label for="updateProductDescription">Ürün Açıklaması</label>
                            <textarea id="updateProductDescription" class="form-control" placeholder="Ürün açıklamasını giriniz"></textarea>
                        </div>

                        <button type="submit" class="btn">Güncelle</button>
                    </form>
                </div>
            </div>

            <!-- Kullanıcı Yetkileri Yönetimi Card'ı -->
            <div class="card">
                <div class="card-header">
                    Kullanıcı Yetkileri
                </div>
                <div class="card-body">
                    <table>
                        <thead>
                            <tr>
                                <th>Kullanıcı ID</th>
                                <th>Ad Soyad</th>
                                <th>E-posta</th>
                                <th>Rol</th>
                                <th>İşlemler</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT uyeID, uyeAd, uyeSoyad, uyeMail, 
                        CASE uyeYetki 
                            WHEN 0 THEN 'Müşteri' 
                            WHEN 1 THEN 'Çalışan' 
                            WHEN 2 THEN 'Admin' 
                        END AS rol
                        FROM t_uyeler";
                            $result = $baglan->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    echo '<tr>';
                                    echo '<td>USR-' . $row['uyeID'] . '</td>';
                                    echo '<td>' . $row['uyeAd'] . ' ' . $row['uyeSoyad'] . '</td>';
                                    echo '<td>' . $row['uyeMail'] . '</td>';
                                    echo '<td>' . $row['rol'] . '</td>';
                                    echo '<td class="action-btns"><button class="btn btn-sm btn-warning">Yetkiyi Düzenle</button></td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="5">Kullanıcı bulunamadı.</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    function showAllCategories() {
        const dropdownList = document.getElementById('dropdownCategory');
        const items = dropdownList.getElementsByClassName('dropdown-item');
        for (let i = 0; i < items.length; i++) {
            items[i].style.display = 'block'; // Tüm öğeleri görünür yap
        }
        dropdownList.style.display = 'block'; // Dropdown'u görünür yap
    }

    function filterCategories() {
        const input = document.getElementById('productCategory').value.toLowerCase();
        const dropdownList = document.getElementById('dropdownCategory');
        const items = dropdownList.getElementsByClassName('dropdown-item');
        let hasVisibleItem = false;

        for (let i = 0; i < items.length; i++) {
            const itemText = items[i].textContent.toLowerCase();
            const isVisible = itemText.includes(input);
            items[i].style.display = isVisible ? 'block' : 'none';
            if (isVisible) hasVisibleItem = true;
        }

        // Dropdown görünürlüğünü ayarla
        dropdownList.style.display = hasVisibleItem || input === '' ? 'block' : 'none';
    }

    function selectCategory(category) {
        const input = document.getElementById('productCategory');
        input.value = category;
        document.getElementById('dropdownCategory').style.display = 'none';
    }

    // Dropdown dışında bir yere tıklanınca dropdown'u kapat
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('dropdownCategory');
        const input = document.getElementById('productCategory');
        if (!dropdown.contains(event.target) && event.target !== input) {
            dropdown.style.display = 'none';
        }
    });

    function closeNewProductModal() {
        const modal = document.getElementById('newProductModal');
        modal.style.display = 'none';
    }

    function openUpdateProductModal(product) {
        // Modalı aç
        const modal = document.getElementById('updateProductModal');
        modal.style.display = 'flex';

        // Ürün bilgilerini modal formuna doldur
        document.getElementById('updateProductName').value = product.name;
        document.getElementById('updateProductCategory').value = product.category;
        document.getElementById('updateProductPrice').value = product.price;
        document.getElementById('updateProductStock').value = product.stock;
        document.getElementById('updateProductDescription').value = product.description;
    }

    function closeUpdateProductModal() {
        // Modalı kapat
        const modal = document.getElementById('updateProductModal');
        modal.style.display = 'none';
    }

    function openNewProductModal() {
        // Modalı aç
        const modal = document.getElementById('newProductModal');
        modal.style.display = 'flex';
    }

    function closeNewProductModal() {
        // Modalı kapat
        const modal = document.getElementById('newProductModal');
        modal.style.display = 'none';
    }
</script>

</html>