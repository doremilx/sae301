<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<link rel="stylesheet" href="style/style_header.css">

<header>
    <div class="logo-container">
        <img src="img/logo_crousty.png" alt="Logo">
    </div>

    <nav>
        <div class="right">
            <ul>
                <li><a href="index.php">Explorer</a></li>
                <li><a href="profile.php" class="<?= basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : '' ?>">Mon profil</a></li>
                <li><a href="#" class="izlyButton">
                        <i class="fa-solid fa-rss"></i> Mon izly
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</header>