<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$message_success = '';
$message_error = '';

/* MISE A JOUR D'UNE DEMANDE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_demande_nettoyage = (int) ($_POST['id_demande_nettoyage'] ?? 0);
    $statut = $_POST['statut'] ?? '';
    $note_admin = trim($_POST['note_admin'] ?? '');

    $statuts_valides = ['nouvelle', 'confirmee', 'refusee', 'realisee', 'annulee'];

    if ($id_demande_nettoyage <= 0) {
        $message_error = "Demande invalide.";
    } elseif (!in_array($statut, $statuts_valides)) {
        $message_error = "Statut invalide.";
    } else {
        $update = $pdo->prepare("
            UPDATE demandes_nettoyage
            SET statut = :statut,
                note_admin = :note_admin
            WHERE id_demande_nettoyage = :id_demande_nettoyage
        ");

        $update->execute([
            ':statut' => $statut,
            ':note_admin' => $note_admin,
            ':id_demande_nettoyage' => $id_demande_nettoyage
        ]);

        $message_success = "La demande de nettoyage a bien été mise à jour.";
    }
}

/* FILTRE PAR STATUT */
$filtre_statut = $_GET['statut'] ?? '';

$sql = "
    SELECT 
        dn.*,
        sn.nom AS service_nom,
        sn.prix_depart AS service_prix,
        sn.duree_estimee AS service_duree
    FROM demandes_nettoyage dn
    LEFT JOIN services_nettoyage sn ON dn.id_service = sn.id_service
    WHERE 1=1
";

$params = [];

if (!empty($filtre_statut)) {
    $sql .= " AND dn.statut = :statut";
    $params[':statut'] = $filtre_statut;
}

$sql .= " ORDER BY dn.date_demande DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$demandes = $stmt->fetchAll();
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Demandes de nettoyage</h1>
            <p>Consultez et traitez les demandes de créneaux envoyées par les clients.</p>
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

            <form method="GET" action="demandes_nettoyage.php" class="admin-filter-form">
                <select name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="nouvelle" <?= $filtre_statut === 'nouvelle' ? 'selected' : '' ?>>Nouvelle</option>
                    <option value="confirmee" <?= $filtre_statut === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                    <option value="refusee" <?= $filtre_statut === 'refusee' ? 'selected' : '' ?>>Refusée</option>
                    <option value="realisee" <?= $filtre_statut === 'realisee' ? 'selected' : '' ?>>Réalisée</option>
                    <option value="annulee" <?= $filtre_statut === 'annulee' ? 'selected' : '' ?>>Annulée</option>
                </select>

                <button type="submit">Filtrer</button>
                <a href="demandes_nettoyage.php">Réinitialiser</a>
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

                                <h3>
                                    <?= htmlspecialchars($demande['service_nom'] ?? 'Service non renseigné') ?>
                                </h3>
                            </div>

                            <small>
                                Demande reçue le <?= date('d/m/Y H:i', strtotime($demande['date_demande'])) ?>
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
                                <h4>Nettoyage</h4>

                                <p>
                                    <strong>Formule :</strong>
                                    <?= htmlspecialchars($demande['service_nom'] ?? 'Non renseignée') ?>
                                </p>

                                <p>
                                    <strong>Prix :</strong>
                                    <?php if (!empty($demande['service_prix'])): ?>
                                        <?= number_format($demande['service_prix'], 0, ',', ' ') ?> €
                                    <?php else: ?>
                                        Sur devis
                                    <?php endif; ?>
                                </p>

                                <p>
                                    <strong>Durée :</strong>
                                    <?= htmlspecialchars($demande['service_duree'] ?? 'Non renseignée') ?>
                                </p>
                            </div>

                            <div class="request-section">
                                <h4>Rendez-vous</h4>

                                <p>
                                    <strong>Véhicule :</strong>
                                    <?= htmlspecialchars($demande['vehicule'] ?: 'Non renseigné') ?>
                                </p>

                                <p>
                                    <strong>Date souhaitée :</strong>
                                    <?php if (!empty($demande['date_souhaitee'])): ?>
                                        <?= date('d/m/Y', strtotime($demande['date_souhaitee'])) ?>
                                    <?php else: ?>
                                        Non renseignée
                                    <?php endif; ?>
                                </p>
                            </div>

                            <div class="request-section full">
                                <h4>Message client</h4>
                                <p><?= nl2br(htmlspecialchars($demande['message'] ?: 'Aucun message.')) ?></p>
                            </div>
                        </div>

                        <form method="POST" action="demandes_nettoyage.php" class="request-update-form">
                            <input 
                                type="hidden" 
                                name="id_demande_nettoyage" 
                                value="<?= htmlspecialchars($demande['id_demande_nettoyage']) ?>"
                            >

                            <div class="form-row">
                                <select name="statut">
                                    <option value="nouvelle" <?= $demande['statut'] === 'nouvelle' ? 'selected' : '' ?>>Nouvelle</option>
                                    <option value="confirmee" <?= $demande['statut'] === 'confirmee' ? 'selected' : '' ?>>Confirmée</option>
                                    <option value="refusee" <?= $demande['statut'] === 'refusee' ? 'selected' : '' ?>>Refusée</option>
                                    <option value="realisee" <?= $demande['statut'] === 'realisee' ? 'selected' : '' ?>>Réalisée</option>
                                    <option value="annulee" <?= $demande['statut'] === 'annulee' ? 'selected' : '' ?>>Annulée</option>
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
            <p class="admin-empty">Aucune demande de nettoyage trouvée.</p>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>