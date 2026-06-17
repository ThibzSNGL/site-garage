<aside class="admin-sidebar">
    <div class="admin-brand">
        <span>Site</span> Garage
    </div>

    <nav class="admin-menu">
        <a href="dashboard.php" class="<?= $current_admin_page === 'dashboard.php' ? 'active' : '' ?>">
            Dashboard
        </a>

        <a href="vehicules.php" class="<?= $current_admin_page === 'vehicules.php' ? 'active' : '' ?>">
            Véhicules
        </a>

        <a href="demandes_pieces.php" class="<?= $current_admin_page === 'demandes_pieces.php' ? 'active' : '' ?>">
            Demandes pièces
        </a>

        <a href="demandes_nettoyage.php" class="<?= $current_admin_page === 'demandes_nettoyage.php' ? 'active' : '' ?>">
            Demandes nettoyage
        </a>

        <a href="mon_compte.php" class="<?= $current_admin_page === 'mon_compte.php' ? 'active' : '' ?>">
            Mon compte
        </a>
    </nav>

    <div class="admin-sidebar-footer">
        <a href="../index.php">Voir le site</a>
        <a href="logout.php" class="logout-link">Déconnexion</a>
    </div>
</aside>