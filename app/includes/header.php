<?php
$currentUser = $currentUser ?? false;
?>


<header>
    <a href="/app/index.php" class="logo">Le cercle des pêcheurs</a>
    <div class="header-mobile">
        <div class="header-mobile-icon">
            <img src="/app/public/images/menu-burger-horizontal-bold.svg" alt="menu-mobile">
        </div>
        <ul class="header-mobile-list">
            <?php if ($currentUser) : ?>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/app/form-article.php' ? 'active' : '' ?>>
                    <a href="/app/form-article.php">Ecrire un article</a>
                </li>
                <li>
                    <a href="/app/auth-logout.php">Déconnexion</a>
                </li>
                <li class="<?= $_SERVER['REQUEST_URI'] === '/app/profile.php' ? 'active' : '' ?>">
                    <a href="/app/profile.php">Mon espace</a>
                </li>
            <?php else : ?>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/app/auth-register.php' ? 'active' : '' ?>>
                    <a href="/app/auth-register.php">Inscription</a>
                </li>
                <li class=<?= $_SERVER['REQUEST_URI'] === '/app/auth-login.php' ? 'active' : '' ?>>
                    <a href="/app/auth-login.php">Connexion</a>
                </li>
            <?php endif; ?>
        </ul>

    </div>
    <ul class="header-menu">
        <?php if ($currentUser) : ?>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/app/form-article.php' ? 'active' : '' ?>>
                <a href="/app/form-article.php">Ecrire un article</a>
            </li>
            <li>
                <a href="/app/auth-logout.php">Déconnexion</a>
            </li>
            <li class="<?= $_SERVER['REQUEST_URI'] === '/app/profile.php' ? 'active' : '' ?> header-profile">
                <a href="/app/profile.php"><?= $currentUser['firstname'][0] . $currentUser['lastname'][0] ?></a>
            </li>
        <?php else : ?>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/app/auth-register.php' ? 'active' : '' ?>>
                <a href="/app/auth-register.php">Inscription</a>
            </li>
            <li class=<?= $_SERVER['REQUEST_URI'] === '/app/auth-login.php' ? 'active' : '' ?>>
                <a href="/app/auth-login.php">Connexion</a>
            </li>
        <?php endif; ?>
    </ul>
</header>