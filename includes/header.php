<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Garage</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

<header class="header">
    <nav class="navbar">
        <a href="index.php" class="logo">
            <span>Site</span> Garage
        </a>

        <ul class="nav-links" id="navLinks">
            <li>
                <a href="index.php" class="<?= $current_page === 'index.php' ? 'active' : '' ?>">
                    Accueil
                </a>
            </li>

            <li>
                <a href="vehicules.php" class="<?= $current_page === 'vehicules.php' ? 'active' : '' ?>">
                    Véhicules
                </a>
            </li>

            <li>
                <a href="nettoyage.php" class="<?= $current_page === 'nettoyage.php' ? 'active' : '' ?>">
                    Nettoyage
                </a>
            </li>

            <li>
                <a href="demande_piece.php" class="<?= $current_page === 'demande_piece.php' ? 'active' : '' ?>">
                    Demande de pièce
                </a>
            </li>

            <li>
                <a href="contact.php" class="<?= $current_page === 'contact.php' ? 'active' : '' ?>">
                    Contact
                </a>
            </li>
        </ul>

        <a href="contact.php" class="nav-btn">Nous contacter</a>

        <button class="burger" id="burgerBtn" aria-label="Ouvrir le menu">
            <span></span>
            <span></span>
            <span></span>
        </button>
    </nav>
</header>