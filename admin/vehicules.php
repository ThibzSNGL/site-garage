<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$message_success = '';
$message_error = '';

/* SUPPRESSION VEHICULE */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_vehicle'])) {
    $id_vehicule = (int) ($_POST['id_vehicule'] ?? 0);

    if ($id_vehicule <= 0) {
        $message_error = "Véhicule invalide.";
    } else {
        $delete = $pdo->prepare("DELETE FROM vehicules WHERE id_vehicule = :id_vehicule");
        $delete->execute([
            ':id_vehicule' => $id_vehicule
        ]);

        $message_success = "Le véhicule a bien été supprimé.";
    }
}

/* MISE A JOUR STATUT */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id_vehicule = (int) ($_POST['id_vehicule'] ?? 0);
    $statut = $_POST['statut'] ?? '';

    $statuts_valides = ['disponible', 'reserve', 'vendu'];

    if ($id_vehicule <= 0) {
        $message_error = "Véhicule invalide.";
    } elseif (!in_array($statut, $statuts_valides)) {
        $message_error = "Statut invalide.";
    } else {
        $update = $pdo->prepare("
            UPDATE vehicules
            SET statut = :statut
            WHERE id_vehicule = :id_vehicule
        ");

        $update->execute([
            ':statut' => $statut,
            ':id_vehicule' => $id_vehicule
        ]);

        $message_success = "Le statut du véhicule a bien été mis à jour.";
    }
}

/* FILTRES */
$filtre_statut = $_GET['statut'] ?? '';
$search = trim($_GET['search'] ?? '');

$sql = "SELECT * FROM vehicules WHERE 1=1";
$params = [];

if (!empty($filtre_statut)) {
    $sql .= " AND statut = :statut";
    $params[':statut'] = $filtre_statut;
}

if (!empty($search)) {
    $sql .= " AND (
        marque LIKE :search 
        OR modele LIKE :search 
        OR version LIKE :search
    )";
    $params[':search'] = '%' . $search . '%';
}

$sql .= " ORDER BY date_ajout DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vehicules = $stmt->fetchAll();

$totalVehicules = $pdo->query("SELECT COUNT(*) FROM vehicules")->fetchColumn();
$totalDisponibles = $pdo->query("SELECT COUNT(*) FROM vehicules WHERE statut = 'disponible'")->fetchColumn();
$totalReserves = $pdo->query("SELECT COUNT(*) FROM vehicules WHERE statut = 'reserve'")->fetchColumn();
$totalVendus = $pdo->query("SELECT COUNT(*) FROM vehicules WHERE statut = 'vendu'")->fetchColumn();
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Gestion des véhicules</h1>
            <p>Ajoutez, modifiez, supprimez ou changez le statut des véhicules en vente.</p>
        </div>

        <a href="vehicule_ajouter.php" class="admin-topbar-btn">Ajouter un véhicule</a>
    </header>

    <?php if (!empty($message_success)): ?>
        <div class="admin-alert admin-alert-success">
            <?= htmlspecialchars($message_success) ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($message_error)): ?>
        <div class="admin-alert admin-alert-error">
            <?= htmlspecialchars($message_error) ?>
        </div>
    <?php endif; ?>

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <span>Total</span>
            <strong><?= $totalVehicules ?></strong>
            <p>Véhicules enregistrés</p>
        </div>

        <div class="admin-stat-card">
            <span>Disponibles</span>
            <strong><?= $totalDisponibles ?></strong>
            <p>En vente actuellement</p>
        </div>

        <div class="admin-stat-card">
            <span>Réservés</span>
            <strong><?= $totalReserves ?></strong>
            <p>Véhicules réservés</p>
        </div>

        <div class="admin-stat-card">
            <span>Vendus</span>
            <strong><?= $totalVendus ?></strong>
            <p>Véhicules vendus</p>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <h2>Liste des véhicules</h2>

            <form method="GET" action="vehicules.php" class="admin-filter-form">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Rechercher un véhicule..."
                    value="<?= htmlspecialchars($search) ?>"
                >

                <select name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="disponible" <?= $filtre_statut === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="reserve" <?= $filtre_statut === 'reserve' ? 'selected' : '' ?>>Réservé</option>
                    <option value="vendu" <?= $filtre_statut === 'vendu' ? 'selected' : '' ?>>Vendu</option>
                </select>

                <button type="submit">Filtrer</button>
                <a href="vehicules.php">Réinitialiser</a>
            </form>
        </div>

        <?php if (count($vehicules) > 0): ?>

            <div class="admin-vehicles-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Véhicule</th>
                            <th>Année</th>
                            <th>Kilométrage</th>
                            <th>Carburant</th>
                            <th>Boîte</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Mise en avant</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($vehicules as $vehicule): ?>
                            <tr>
                                <td>
                                    <div class="admin-vehicle-cell">
                                        <div class="admin-vehicle-img">
                                            <?php if (!empty($vehicule['image_principale'])): ?>
                                                <img 
                                                    src="../<?= htmlspecialchars($vehicule['image_principale']) ?>" 
                                                    alt="<?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?>"
                                                >
                                            <?php else: ?>
                                                Auto
                                            <?php endif; ?>
                                        </div>

                                        <div>
                                            <strong>
                                                <?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?>
                                            </strong>

                                            <?php if (!empty($vehicule['version'])): ?>
                                                <p><?= htmlspecialchars($vehicule['version']) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td><?= htmlspecialchars($vehicule['annee']) ?></td>

                                <td>
                                    <?= number_format($vehicule['kilometrage'], 0, ',', ' ') ?> km
                                </td>

                                <td><?= ucfirst(htmlspecialchars($vehicule['carburant'])) ?></td>

                                <td><?= ucfirst(htmlspecialchars($vehicule['boite'])) ?></td>

                                <td>
                                    <strong><?= number_format($vehicule['prix'], 0, ',', ' ') ?> €</strong>
                                </td>

                                <td>
                                    <span class="request-status status-<?= htmlspecialchars($vehicule['statut']) ?>">
                                        <?= htmlspecialchars(str_replace('reserve', 'réservé', $vehicule['statut'])) ?>
                                    </span>
                                </td>

                                <td>
                                    <?= $vehicule['mis_en_avant'] ? 'Oui' : 'Non' ?>
                                </td>

                                <td>
                                    <div class="admin-actions">
                                        <a 
                                            href="vehicule_modifier.php?id=<?= htmlspecialchars($vehicule['id_vehicule']) ?>" 
                                            class="admin-action-btn edit"
                                        >
                                            Modifier
                                        </a>

                                        <form method="POST" action="vehicules.php" class="inline-form">
                                            <input 
                                                type="hidden" 
                                                name="id_vehicule" 
                                                value="<?= htmlspecialchars($vehicule['id_vehicule']) ?>"
                                            >

                                            <select name="statut">
                                                <option value="disponible" <?= $vehicule['statut'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                                                <option value="reserve" <?= $vehicule['statut'] === 'reserve' ? 'selected' : '' ?>>Réservé</option>
                                                <option value="vendu" <?= $vehicule['statut'] === 'vendu' ? 'selected' : '' ?>>Vendu</option>
                                            </select>

                                            <button 
                                                type="submit" 
                                                name="update_status" 
                                                class="admin-action-btn status"
                                            >
                                                OK
                                            </button>
                                        </form>

                                        <form 
                                            method="POST" 
                                            action="vehicules.php" 
                                            class="inline-form"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce véhicule ?');"
                                        >
                                            <input 
                                                type="hidden" 
                                                name="id_vehicule" 
                                                value="<?= htmlspecialchars($vehicule['id_vehicule']) ?>"
                                            >

                                            <button 
                                                type="submit" 
                                                name="delete_vehicle" 
                                                class="admin-action-btn delete"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>

            <p class="admin-empty">Aucun véhicule trouvé.</p>

        <?php endif; ?>
    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>