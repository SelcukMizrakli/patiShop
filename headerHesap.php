<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include('ayar.php');
?>
<style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .header {
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .logo-container {
        display: flex;
        align-items: center;
        padding: 10px 0;
    }

    .logo {
        font-size: 24px;
        font-weight: bold;
        color: #4CAF50;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .logo i {
        margin-right: 10px;
        font-size: 28px;
    }

    .search-login {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-left: auto;
    }

    .search-container {
        position: relative;
        margin-right: 20px;
    }

    .search-container input {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 20px;
        width: 250px;
        font-size: 14px;
    }

    .search-container button {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #666;
    }

    .user-profile {
        display: flex;
        align-items: center;
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 20px;
        border-radius: 20px;
        cursor: pointer;
        font-weight: bold;
    }

    .user-profile i {
        margin-right: 5px;
    }

    .dropdown {
        position: relative;
        display: inline-block;
    }

    .dropdown-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 5px;
    }

    .dropdown-content a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }

    .dropdown-content a:hover {
        background-color: #f1f1f1;
    }

    .dropdown:hover .dropdown-content {
        display: block;
    }

    .navbarH {
        background-color: rgb(255, 255, 255);
        overflow: hidden;
    }

    .navbarH .ulH {
        list-style-type: none;
        display: flex;
        padding: 0;
    }

    .navbarH .liH {
        padding: 0;
    }

    .navbarH a {
        display: block;
        color: #333;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .navbarH a:hover {
        background-color: #ddd;
        color: #4CAF50;
    }

    .headerHesap .top-bar {
        background-color: #4CAF50;
        color: white;
        padding: 10px 0;
        text-align: center;
    }

    .headerHesap .header {
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .headerHesap .logo-container {
        display: flex;
        align-items: center;
        padding: 10px 0;
    }

    .headerHesap .logo {
        font-size: 24px;
        font-weight: bold;
        color: #4CAF50;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .headerHesap .navbarH ulH {
        list-style-type: none;
        display: flex;
        padding: 0;
    }

    .headerHesap .navbarH a {
        display: block;
        color: #333;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .headerHesap .navbarH a:hover {
        background-color: #ddd;
        color: #4CAF50;
    }
</style>
<style>
    .patiHeader {
        background-color: #fff;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .patiLogoContainer {
        display: flex;
        align-items: center;
        padding: 10px 0;
    }

    .patiLogo {
        font-size: 24px;
        font-weight: bold;
        color: #4CAF50;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .patiLogo i {
        margin-right: 10px;
        font-size: 28px;
    }

    .patiSearchLogin {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin-left: auto;
    }

    .patiSearchContainer {
        position: relative;
        margin-right: 20px;
    }

    .patiSearchContainer input {
        padding: 8px 15px;
        border: 1px solid #ddd;
        border-radius: 20px;
        width: 250px;
        font-size: 14px;
    }

    .patiSearchContainer button {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        color: #666;
    }

    .patiUserProfile {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 8px 15px;
        border-radius: 20px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
        text-decoration: none;
        display: flex;
        align-items: center;
    }

    .patiUserProfile:hover {
        background-color: #3e8e41;
    }

    .patiUserProfile i {
        margin-right: 5px;
    }

    .patiDropdown {
        position: relative;
        display: inline-block;
    }

    .patiDropdownContent {
        display: none;
        position: absolute;
        right: 0;
        background-color: #f9f9f9;
        min-width: 160px;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 5px;
    }

    .patiDropdownContent a {
        color: black;
        padding: 12px 16px;
        text-decoration: none;
        display: block;
        text-align: left;
    }

    .patiDropdownContent a:hover {
        background-color: #f1f1f1;
    }

    .patiDropdown:hover .patiDropdownContent {
        display: block;
    }

    .patiNav {
        background-color: #f1f1f1;
        overflow: hidden;
    }

    .patiNavUl {
        list-style-type: none;
        display: flex;
        padding: 0;
        margin: 0;
    }

    .patiNavLi {
        padding: 0;
    }

    .patiNavLink {
        display: block;
        color: #333;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
        font-weight: 500;
        transition: background-color 0.3s;
    }

    .patiNavLink:hover {
        background-color: #ddd;
        color: #4CAF50;
    }

    @media (max-width: 992px) {
        .patiSearchContainer input {
            width: 200px;
        }
    }

    @media (max-width: 768px) {
        .patiSearchContainer input {
            width: 180px;
        }
    }

    @media (max-width: 576px) {
        .patiLogoContainer {
            flex-direction: column;
            align-items: flex-start;
        }

        .patiSearchLogin {
            margin-top: 15px;
            width: 100%;
            justify-content: space-between;
        }

        .patiSearchContainer {
            width: 70%;
        }

        .patiSearchContainer input {
            width: 100%;
        }

        .patiNavUl {
            flex-direction: column;
        }
    }

    .patiContainer {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }
</style>

<body>
    <!-- Header -->
    <header class="patiHeader">
        <div class="patiContainer">
            <div class="patiLogoContainer">
                <a href="<?php echo isset($_SESSION['uyeID']) ? 'anasayfa.php' : 'index.php'; ?>" class="patiLogo">
                    <i class="fas fa-paw"></i> PatiShop
                </a>
                <div class="patiSearchLogin">
                    <div class="patiSearchContainer">
                        <form action="kategori.php" method="GET">
                            <input type="text" name="arama" placeholder="Ürün, kategori veya marka ara..." required>
                            <button type="submit"><i class="fas fa-search"></i></button>
                        </form>
                    </div>
                    <div class="patiUserActions">
                        <?php if (isset($_SESSION['uyeID'])): ?>
                            <div class="patiDropdown">
                                <button class="patiUserProfile">
                                    <i class="fas fa-user"></i> Hesabım
                                </button>
                                <div class="patiDropdownContent">
                                    <a href="profil.php#profile"><i class="fas fa-user-circle"></i> Profil</a>
                                    <a href="profil.php#favorites"><i class="fas fa-heart"></i> Favorilerim</a>
                                    <a href="profil.php#orders"><i class="fas fa-box"></i> Siparişlerim</a>
                                    <a href="cikisYap.php"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a>
                                </div>
                            </div>
                        <?php else: ?>
                            <a href="index.php?showLoginModal=true" class="patiUserProfile">
                                <i class="fas fa-sign-in-alt"></i> Giriş Yap
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="patiNav">
            <div class="patiContainer">
                <ul class="patiNavUl">
                    <li class="patiNavLi">
                        <a href="<?php echo isset($_SESSION['uyeID']) ? 'anasayfa.php' : 'index.php'; ?>" class="patiNavLink">
                            <i class="fas fa-home"></i> Ana Sayfa
                        </a>
                    </li>
                    <?php
                    $query = "SELECT * FROM t_kategori WHERE kategoriDurum = 1";
                    $result = mysqli_query($baglan, $query);
                    if ($result && mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<li class="patiNavLi">';
                            echo '<a href="kategori.php?kategori=' . $row['kategoriSlug'] . '" class="patiNavLink">';
                            echo '<i class="fas fa-paw"></i> ' . htmlspecialchars($row['kategoriAdi']);
                            echo '</a></li>';
                        }
                    }
                    ?>
                </ul>
            </div>
        </nav>
    </header>
    <script>
        // Sayfa yüklendiğinde doğru sekmeyi göster
        document.addEventListener('DOMContentLoaded', () => {
            const hash = window.location.hash.substring(1); // URL'deki hash değerini al
            if (hash) {
                showTab(hash); // Hash varsa ilgili sekmeyi göster
            } else {
                showTab('profile'); // Hash yoksa varsayılan olarak 'profile' sekmesini göster
            }
        });

        function showTab(tabId) {
            // Önce tüm tabları gizle
            const tabs = document.querySelectorAll('.tab-content');
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });

            // Seçilen tabı göster
            const activeTab = document.getElementById(tabId);
            if (activeTab) {
                activeTab.classList.add('active');
            }

            // Menü öğelerinin active class'ını güncelle
            const menuItems = document.querySelectorAll('.profile-menu a');
            menuItems.forEach(item => {
                item.classList.remove('active');
                if (item.getAttribute('href') === '#' + tabId) {
                    item.classList.add('active');
                }
            });
        }

        function delayedLoginModal(event) {
            // Varsayılan tıklama davranışını engelle
            event.preventDefault();

            // Kullanıcıyı index.php sayfasına yönlendir
            window.location.href = 'index.php';

            // Belirli bir süre sonra openLoginModal fonksiyonunu çalıştır
            setTimeout(() => {
                openLoginModal();

                // "//*[@id='loginBtn']" path'indeki öğeye click olayı gerçekleştir
                const loginButton = document.querySelector('#loginBtn');
                if (loginButton) {
                    loginButton.click();
                }
            }, 5000); // 2.5 saniye (2500 ms) bekleme süresi
        }

        function openLoginModal() {
            // Giriş yap modalını açma işlemleri burada yapılır
            console.log('Giriş yap modalı açıldı.');
            // Modal açma kodlarınızı buraya ekleyin
        }
    </script>
</body>
</html>