<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$message_success = '';
$message_error = '';

/* SUPPRESSION PIECE */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_piece'])) {
    $id_piece = (int) ($_POST['id_piece'] ?? 0);

    if ($id_piece <= 0) {
        $message_error = "Pièce invalide.";
    } else {
        $stmt = $pdo->prepare("SELECT image FROM pieces WHERE id_piece = :id_piece LIMIT 1");
        $stmt->execute([
            ':id_piece' => $id_piece
        ]);
        $piece = $stmt->fetch();

        if ($piece && !empty($piece['image'])) {
            $image_path = '../' . $piece['image'];

            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        $delete = $pdo->prepare("DELETE FROM pieces WHERE id_piece = :id_piece");
        $delete->execute([
            ':id_piece' => $id_piece
        ]);

        $message_success = "La pièce a bien été supprimée.";
    }
}

/* MISE A JOUR STATUT */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id_piece = (int) ($_POST['id_piece'] ?? 0);
    $statut = $_POST['statut'] ?? '';

    $statuts_valides = ['disponible', 'indisponible'];

    if ($id_piece <= 0) {
        $message_error = "Pièce invalide.";
    } elseif (!in_array($statut, $statuts_valides)) {
        $message_error = "Statut invalide.";
    } else {
        $update = $pdo->prepare("
            UPDATE pieces
            SET statut = :statut
            WHERE id_piece = :id_piece
        ");

        $update->execute([
            ':statut' => $statut,
            ':id_piece' => $id_piece
        ]);

        $message_success = "Le statut de la pièce a bien été mis à jour.";
    }
}

/* FILTRES */
$search = trim($_GET['search'] ?? '');
$filtre_categorie = $_GET['categorie'] ?? '';
$filtre_statut = $_GET['statut'] ?? '';

$categories_valides = ['moteur', 'carrosserie', 'freinage', 'optique', 'interieur', 'suspension', 'autre'];
$statuts_valides = ['disponible', 'indisponible'];

$sql = "SELECT * FROM pieces WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (
        nom LIKE :search
        OR marque LIKE :search
        OR modele_compatible LIKE :search
        OR reference_piece LIKE :search
    )";
    $params[':search'] = '%' . $search . '%';
}

if (!empty($filtre_categorie) && in_array($filtre_categorie, $categories_valides)) {
    $sql .= " AND categorie = :categorie";
    $params[':categorie'] = $filtre_categorie;
}

if (!empty($filtre_statut) && in_array($filtre_statut, $statuts_valides)) {
    $sql .= " AND statut = :statut";
    $params[':statut'] = $filtre_statut;
}

$sql .= " ORDER BY date_ajout DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$pieces = $stmt->fetchAll();

$totalPieces = $pdo->query("SELECT COUNT(*) FROM pieces")->fetchColumn();
$totalDisponibles = $pdo->query("SELECT COUNT(*) FROM pieces WHERE statut = 'disponible'")->fetchColumn();
$totalIndisponibles = $pdo->query("SELECT COUNT(*) FROM pieces WHERE statut = 'indisponible'")->fetchColumn();
$totalQuantite = $pdo->query("SELECT COALESCE(SUM(quantite), 0) FROM pieces")->fetchColumn();
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Pièces en stock</h1>
            <p>Gérez les pièces visibles sur la page de demande de pièce.</p>
        </div>

        <a href="piece_ajouter.php" class="admin-topbar-btn">Ajouter une pièce</a>
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
            <strong><?= $totalPieces ?></strong>
            <p>Références enregistrées</p>
        </div>

        <div class="admin-stat-card">
            <span>Disponibles</span>
            <strong><?= $totalDisponibles ?></strong>
            <p>Pièces affichées côté client</p>
        </div>

        <div class="admin-stat-card">
            <span>Indisponibles</span>
            <strong><?= $totalIndisponibles ?></strong>
            <p>Pièces masquées ou épuisées</p>
        </div>

        <div class="admin-stat-card">
            <span>Quantité totale</span>
            <strong><?= $totalQuantite ?></strong>
            <p>Unités en stock</p>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <h2>Liste des pièces</h2>

            <form method="GET" action="pieces.php" class="admin-filter-form">
                <input 
                    type="text" 
                    name="search" 
                    placeholder="Rechercher une pièce..."
                    value="<?= htmlspecialchars($search) ?>"
                >

                <select name="categorie">
                    <option value="">Toutes les catégories</option>
                    <option value="moteur" <?= $filtre_categorie === 'moteur' ? 'selected' : '' ?>>Moteur</option>
                    <option value="carrosserie" <?= $filtre_categorie === 'carrosserie' ? 'selected' : '' ?>>Carrosserie</option>
                    <option value="freinage" <?= $filtre_categorie === 'freinage' ? 'selected' : '' ?>>Freinage</option>
                    <option value="optique" <?= $filtre_categorie === 'optique' ? 'selected' : '' ?>>Optique</option>
                    <option value="interieur" <?= $filtre_categorie === 'interieur' ? 'selected' : '' ?>>Intérieur</option>
                    <option value="suspension" <?= $filtre_categorie === 'suspension' ? 'selected' : '' ?>>Suspension</option>
                    <option value="autre" <?= $filtre_categorie === 'autre' ? 'selected' : '' ?>>Autre</option>
                </select>

                <select name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="disponible" <?= $filtre_statut === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                    <option value="indisponible" <?= $filtre_statut === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
                </select>

                <button type="submit">Filtrer</button>
                <a href="pieces.php">Réinitialiser</a>
            </form>
        </div>

        <?php if (count($pieces) > 0): ?>

            <div class="admin-vehicles-table-wrapper">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Pièce</th>
                            <th>Catégorie</th>
                            <th>Marque</th>
                            <th>Compatible</th>
                            <th>Référence</th>
                            <th>État</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($pieces as $piece): ?>
                            <tr>
                                <td>
                                    <div class="admin-vehicle-cell">
                                        <div class="admin-vehicle-img">
                                            <?php if (!empty($piece['image'])): ?>
                                                <img 
                                                    src="../<?= htmlspecialchars($piece['image']) ?>" 
                                                    alt="<?= htmlspecialchars($piece['nom']) ?>"
                                                >
                                            <?php else: ?>
                                                Pièce
                                            <?php endif; ?>
                                        </div>

                                        <div>
                                            <strong><?= htmlspecialchars($piece['nom']) ?></strong>

                                            <?php if (!empty($piece['description'])): ?>
                                                <p><?= htmlspecialchars(mb_strimwidth($piece['description'], 0, 70, '...')) ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>

                                <td><?= ucfirst(htmlspecialchars($piece['categorie'])) ?></td>
                                <td><?= htmlspecialchars($piece['marque'] ?: 'Non renseignée') ?></td>
                                <td><?= htmlspecialchars($piece['modele_compatible'] ?: 'Non renseigné') ?></td>
                                <td><?= htmlspecialchars($piece['reference_piece'] ?: 'Non renseignée') ?></td>
                                <td><?= ucfirst(htmlspecialchars($piece['etat'])) ?></td>

                                <td>
                                    <?php if (!empty($piece['prix'])): ?>
                                        <strong><?= number_format($piece['prix'], 0, ',', ' ') ?> €</strong>
                                    <?php else: ?>
                                        Sur devis
                                    <?php endif; ?>
                                </td>

                                <td><?= htmlspecialchars($piece['quantite']) ?></td>

                                <td>
                                    <span class="request-status status-<?= htmlspecialchars($piece['statut']) ?>">
                                        <?= htmlspecialchars($piece['statut']) ?>
                                    </span>
                                </td>

                                <td>
                                    <div class="admin-actions">
                                        <a 
                                            href="piece_modifier.php?id=<?= htmlspecialchars($piece['id_piece']) ?>" 
                                            class="admin-action-btn edit"
                                        >
                                            Modifier
                                        </a>

                                        <form method="POST" action="pieces.php" class="inline-form">
                                            <input 
                                                type="hidden" 
                                                name="id_piece" 
                                                value="<?= htmlspecialchars($piece['id_piece']) ?>"
                                            >

                                            <select name="statut">
                                                <option value="disponible" <?= $piece['statut'] === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                                                <option value="indisponible" <?= $piece['statut'] === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
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
                                            action="pieces.php" 
                                            class="inline-form"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette pièce ?');"
                                        >
                                            <input 
                                                type="hidden" 
                                                name="id_piece" 
                                                value="<?= htmlspecialchars($piece['id_piece']) ?>"
                                            >

                                            <button 
                                                type="submit" 
                                                name="delete_piece" 
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

            <p class="admin-empty">Aucune pièce trouvée.</p>

        <?php endif; ?>
    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>