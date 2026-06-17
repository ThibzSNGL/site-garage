<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$totalVehicules = $pdo->query("SELECT COUNT(*) FROM vehicules")->fetchColumn();
$totalVehiculesDisponibles = $pdo->query("SELECT COUNT(*) FROM vehicules WHERE statut = 'disponible'")->fetchColumn();
$totalVehiculesVendus = $pdo->query("SELECT COUNT(*) FROM vehicules WHERE statut = 'vendu'")->fetchColumn();

$totalDemandesPieces = $pdo->query("SELECT COUNT(*) FROM demandes_pieces WHERE statut = 'nouvelle'")->fetchColumn();
$totalDemandesNettoyage = $pdo->query("SELECT COUNT(*) FROM demandes_nettoyage WHERE statut = 'nouvelle'")->fetchColumn();
$totalMessages = $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE statut = 'nouveau'")->fetchColumn();

$dernieresDemandesPieces = $pdo->query("
    SELECT * 
    FROM demandes_pieces 
    ORDER BY date_demande DESC 
    LIMIT 5
")->fetchAll();

$dernieresDemandesNettoyage = $pdo->query("
    SELECT dn.*, sn.nom AS service_nom
    FROM demandes_nettoyage dn
    LEFT JOIN services_nettoyage sn ON dn.id_service = sn.id_service
    ORDER BY dn.date_demande DESC
    LIMIT 5
")->fetchAll();
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Dashboard</h1>
            <p>Bienvenue, <?= htmlspecialchars($_SESSION['admin_nom']) ?>.</p>
        </div>

        <a href="logout.php" class="admin-topbar-btn">Déconnexion</a>
    </header>

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <span>Véhicules</span>
            <strong><?= $totalVehicules ?></strong>
            <p>Total enregistrés</p>
        </div>

        <div class="admin-stat-card">
            <span>Disponibles</span>
            <strong><?= $totalVehiculesDisponibles ?></strong>
            <p>Véhicules en vente</p>
        </div>

        <div class="admin-stat-card">
            <span>Vendus</span>
            <strong><?= $totalVehiculesVendus ?></strong>
            <p>Véhicules vendus</p>
        </div>

        <div class="admin-stat-card">
            <span>Pièces</span>
            <strong><?= $totalDemandesPieces ?></strong>
            <p>Demandes nouvelles</p>
        </div>

        <div class="admin-stat-card">
            <span>Nettoyage</span>
            <strong><?= $totalDemandesNettoyage ?></strong>
            <p>Demandes nouvelles</p>
        </div>

        <div class="admin-stat-card">
            <span>Messages</span>
            <strong><?= $totalMessages ?></strong>
            <p>Messages non lus</p>
        </div>
    </section>

    <section class="admin-content-grid">
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h2>Dernières demandes de pièces</h2>
                <a href="demandes_pieces.php">Voir tout</a>
            </div>

            <?php if (count($dernieresDemandesPieces) > 0): ?>
                <div class="admin-list">
                    <?php foreach ($dernieresDemandesPieces as $demande): ?>
                        <div class="admin-list-item">
                            <div>
                                <strong><?= htmlspecialchars($demande['piece_recherchee']) ?></strong>
                                <p><?= htmlspecialchars($demande['nom_client']) ?> · <?= htmlspecialchars($demande['telephone_client']) ?></p>
                            </div>
                            <span><?= htmlspecialchars($demande['statut']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="admin-empty">Aucune demande de pièce.</p>
            <?php endif; ?>
        </div>

        <div class="admin-panel">
            <div class="admin-panel-header">
                <h2>Dernières demandes nettoyage</h2>
                <a href="demandes_nettoyage.php">Voir tout</a>
            </div>

            <?php if (count($dernieresDemandesNettoyage) > 0): ?>
                <div class="admin-list">
                    <?php foreach ($dernieresDemandesNettoyage as $demande): ?>
                        <div class="admin-list-item">
                            <div>
                                <strong><?= htmlspecialchars($demande['service_nom'] ?? 'Service') ?></strong>
                                <p><?= htmlspecialchars($demande['nom_client']) ?> · <?= htmlspecialchars($demande['telephone_client']) ?></p>
                            </div>
                            <span><?= htmlspecialchars($demande['statut']) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="admin-empty">Aucune demande nettoyage.</p>
            <?php endif; ?>
        </div>
    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>