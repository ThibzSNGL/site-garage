<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$message_success = '';
$message_error = '';

/* MISE A JOUR D'UNE DEMANDE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_demande_piece = (int) ($_POST['id_demande_piece'] ?? 0);
    $statut = $_POST['statut'] ?? '';
    $note_admin = trim($_POST['note_admin'] ?? '');

    $statuts_valides = ['nouvelle', 'en_cours', 'trouvee', 'impossible', 'terminee'];

    if ($id_demande_piece <= 0) {
        $message_error = "Demande invalide.";
    } elseif (!in_array($statut, $statuts_valides)) {
        $message_error = "Statut invalide.";
    } else {
        $update = $pdo->prepare("
            UPDATE demandes_pieces
            SET statut = :statut,
                note_admin = :note_admin
            WHERE id_demande_piece = :id_demande_piece
        ");

        $update->execute([
            ':statut' => $statut,
            ':note_admin' => $note_admin,
            ':id_demande_piece' => $id_demande_piece
        ]);

        $message_success = "La demande a bien été mise à jour.";
    }
}

/* FILTRE PAR STATUT */
$filtre_statut = $_GET['statut'] ?? '';

$sql = "
    SELECT dp.*, p.nom AS nom_piece_stock, p.prix AS prix_piece_stock
    FROM demandes_pieces dp
    LEFT JOIN pieces p ON dp.id_piece = p.id_piece
    WHERE 1=1
";

$params = [];

if (!empty($filtre_statut)) {
    $sql .= " AND dp.statut = :statut";
    $params[':statut'] = $filtre_statut;
}

$sql .= " ORDER BY dp.date_demande DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$demandes = $stmt->fetchAll();
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Demandes de pièces</h1>
            <p>Consultez et traitez les demandes envoyées par les clients.</p>
        </div>

        <a href="dashboard.php" class="admin-topbar-btn">Retour dashboard</a>
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

    <section class="admin-panel">
        <div class="admin-panel-header">
            <h2>Liste des demandes</h2>

            <form method="GET" action="demandes_pieces.php" class="admin-filter-form">
                <select name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="nouvelle" <?= $filtre_statut === 'nouvelle' ? 'selected' : '' ?>>Nouvelle</option>
                    <option value="en_cours" <?= $filtre_statut === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                    <option value="trouvee" <?= $filtre_statut === 'trouvee' ? 'selected' : '' ?>>Trouvée</option>
                    <option value="impossible" <?= $filtre_statut === 'impossible' ? 'selected' : '' ?>>Impossible</option>
                    <option value="terminee" <?= $filtre_statut === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                </select>

                <button type="submit">Filtrer</button>
                <a href="demandes_pieces.php">Réinitialiser</a>
            </form>
        </div>

        <?php if (count($demandes) > 0): ?>
            <div class="admin-requests-grid">
                <?php foreach ($demandes as $demande): ?>
                    <article class="admin-request-card">
                        <div class="request-card-header">
                            <div>
                                <span class="request-status status-<?= htmlspecialchars($demande['statut']) ?>">
                                    <?= htmlspecialchars(str_replace('_', ' ', $demande['statut'])) ?>
                                </span>

                                <h3><?= htmlspecialchars($demande['piece_recherchee']) ?></h3>
                            </div>

                            <small>
                                <?= date('d/m/Y H:i', strtotime($demande['date_demande'])) ?>
                            </small>
                        </div>

                        <div class="request-card-body">
                            <div class="request-section">
                                <h4>Client</h4>
                                <p><strong>Nom :</strong> <?= htmlspecialchars($demande['nom_client']) ?></p>
                                <p><strong>Email :</strong> <?= htmlspecialchars($demande['email_client']) ?></p>
                                <p><strong>Téléphone :</strong> <?= htmlspecialchars($demande['telephone_client']) ?></p>
                            </div>

                            <div class="request-section">
                                <h4>Véhicule</h4>
                                <p><strong>Marque :</strong> <?= htmlspecialchars($demande['marque_vehicule'] ?: 'Non renseignée') ?></p>
                                <p><strong>Modèle :</strong> <?= htmlspecialchars($demande['modele_vehicule'] ?: 'Non renseigné') ?></p>
                                <p><strong>Année :</strong> <?= htmlspecialchars($demande['annee_vehicule'] ?: 'Non renseignée') ?></p>
                            </div>

                            <div class="request-section">
                                <h4>Pièce</h4>
                                <p><strong>Référence :</strong> <?= htmlspecialchars($demande['reference_piece'] ?: 'Non renseignée') ?></p>

                                <?php if (!empty($demande['nom_piece_stock'])): ?>
                                    <p><strong>Stock :</strong> <?= htmlspecialchars($demande['nom_piece_stock']) ?></p>
                                    <p><strong>Prix stock :</strong> <?= number_format($demande['prix_piece_stock'], 0, ',', ' ') ?> €</p>
                                <?php else: ?>
                                    <p><strong>Stock :</strong> Demande libre</p>
                                <?php endif; ?>
                            </div>

                            <div class="request-section full">
                                <h4>Message client</h4>
                                <p><?= nl2br(htmlspecialchars($demande['message'] ?: 'Aucun message.')) ?></p>
                            </div>
                        </div>

                        <form method="POST" action="demandes_pieces.php" class="request-update-form">
                            <input 
                                type="hidden" 
                                name="id_demande_piece" 
                                value="<?= htmlspecialchars($demande['id_demande_piece']) ?>"
                            >

                            <div class="form-row">
                                <select name="statut">
                                    <option value="nouvelle" <?= $demande['statut'] === 'nouvelle' ? 'selected' : '' ?>>Nouvelle</option>
                                    <option value="en_cours" <?= $demande['statut'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                                    <option value="trouvee" <?= $demande['statut'] === 'trouvee' ? 'selected' : '' ?>>Trouvée</option>
                                    <option value="impossible" <?= $demande['statut'] === 'impossible' ? 'selected' : '' ?>>Impossible</option>
                                    <option value="terminee" <?= $demande['statut'] === 'terminee' ? 'selected' : '' ?>>Terminée</option>
                                </select>
                            </div>

                            <textarea 
                                name="note_admin" 
                                placeholder="Note interne pour cette demande..."
                            ><?= htmlspecialchars($demande['note_admin'] ?? '') ?></textarea>

                            <button type="submit" class="admin-save-btn">Mettre à jour</button>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="admin-empty">Aucune demande de pièce trouvée.</p>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>