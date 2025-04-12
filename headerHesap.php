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

        .top-bar {
            background-color: #4CAF50;
            color: white;
            padding: 10px 0;
            text-align: center;
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

        .navbar {
            background-color: #f1f1f1;
            overflow: hidden;
        }

        .navbar ul {
            list-style-type: none;
            display: flex;
            padding: 0;
        }

        .navbar li {
            padding: 0;
        }

        .navbar a {
            display: block;
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .navbar a:hover {
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

        .headerHesap .navbar ul {
            list-style-type: none;
            display: flex;
            padding: 0;
        }

        .headerHesap .navbar a {
            display: block;
            color: #333;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.3s;
        }

        .headerHesap .navbar a:hover {
            background-color: #ddd;
            color: #4CAF50;
        }
    </style>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="logo-container">
                <a href="#" class="logo">
                    <i class="fas fa-paw"></i> PatiShop
                </a>
                <div class="search-login">
                    <div class="search-container">
                        <input type="text" placeholder="Ürün, kategori veya marka ara...">
                        <button type="submit"><i class="fas fa-search"></i></button>
                    </div>
                    <div class="user-actions">
                        <div class="dropdown">
                            <button class="user-profile">
                                <i class="fas fa-user"></i> Hesabım
                            </button>
                            <div class="dropdown-content">
                                <a href="#"><i class="fas fa-user-circle"></i> Profil</a>
                                <a href="#"><i class="fas fa-heart"></i> Favorilerim</a>
                                <a href="#"><i class="fas fa-box"></i> Siparişlerim</a>
                                <a href="#"><i class="fas fa-sign-out-alt"></i> Çıkış Yap</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="navbar">
            <div class="container">
                <ul>
                    <li><a href="#"><i class="fas fa-home"></i> Ana Sayfa</a></li>
                    <li><a href="#"><i class="fas fa-dog"></i> Köpek</a></li>
                    <li><a href="#"><i class="fas fa-cat"></i> Kedi</a></li>
                    <li><a href="#"><i class="fas fa-fish"></i> Balık</a></li>
                    <li><a href="#"><i class="fas fa-dove"></i> Kuş</a></li>
                    <li><a href="#"><i class="fas fa-rabbit"></i> Kemirgen</a></li>
                    <li><a href="#"><i class="fas fa-percentage"></i> Kampanyalar</a></li>
                    <li><a href="#"><i class="fas fa-star"></i> Yeni Ürünler</a></li>
                </ul>
            </div>
        </nav>
    </header>
</body>
