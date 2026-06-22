<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$message_success = '';
$message_error = '';

/* SUPPRESSION MESSAGE */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message'])) {
    $id_message = (int) ($_POST['id_message'] ?? 0);

    if ($id_message <= 0) {
        $message_error = "Message invalide.";
    } else {
        $delete = $pdo->prepare("DELETE FROM messages_contact WHERE id_message = :id_message");
        $delete->execute([
            ':id_message' => $id_message
        ]);

        $message_success = "Le message a bien été supprimé.";
    }
}

/* MISE A JOUR STATUT */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id_message = (int) ($_POST['id_message'] ?? 0);
    $statut = $_POST['statut'] ?? '';

    $statuts_valides = ['nouveau', 'lu', 'traite'];

    if ($id_message <= 0) {
        $message_error = "Message invalide.";
    } elseif (!in_array($statut, $statuts_valides)) {
        $message_error = "Statut invalide.";
    } else {
        $update = $pdo->prepare("
            UPDATE messages_contact
            SET statut = :statut
            WHERE id_message = :id_message
        ");

        $update->execute([
            ':statut' => $statut,
            ':id_message' => $id_message
        ]);

        $message_success = "Le statut du message a bien été mis à jour.";
    }
}

/* FILTRES */
$filtre_statut = $_GET['statut'] ?? '';
$filtre_sujet = $_GET['sujet'] ?? '';

$sql = "SELECT * FROM messages_contact WHERE 1=1";
$params = [];

if (!empty($filtre_statut)) {
    $sql .= " AND statut = :statut";
    $params[':statut'] = $filtre_statut;
}

if (!empty($filtre_sujet)) {
    $sql .= " AND sujet = :sujet";
    $params[':sujet'] = $filtre_sujet;
}

$sql .= " ORDER BY date_message DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$messages = $stmt->fetchAll();

$totalMessages = $pdo->query("SELECT COUNT(*) FROM messages_contact")->fetchColumn();
$totalNouveaux = $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE statut = 'nouveau'")->fetchColumn();
$totalLus = $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE statut = 'lu'")->fetchColumn();
$totalTraites = $pdo->query("SELECT COUNT(*) FROM messages_contact WHERE statut = 'traite'")->fetchColumn();
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Messages contact</h1>
            <p>Consultez les messages envoyés depuis le formulaire de contact.</p>
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

    <section class="admin-stats-grid">
        <div class="admin-stat-card">
            <span>Total</span>
            <strong><?= $totalMessages ?></strong>
            <p>Messages reçus</p>
        </div>

        <div class="admin-stat-card">
            <span>Nouveaux</span>
            <strong><?= $totalNouveaux ?></strong>
            <p>À traiter</p>
        </div>

        <div class="admin-stat-card">
            <span>Lus</span>
            <strong><?= $totalLus ?></strong>
            <p>Pris en compte</p>
        </div>

        <div class="admin-stat-card">
            <span>Traités</span>
            <strong><?= $totalTraites ?></strong>
            <p>Demandes clôturées</p>
        </div>
    </section>

    <section class="admin-panel">
        <div class="admin-panel-header">
            <h2>Liste des messages</h2>

            <form method="GET" action="messages_contact.php" class="admin-filter-form">
                <select name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="nouveau" <?= $filtre_statut === 'nouveau' ? 'selected' : '' ?>>Nouveau</option>
                    <option value="lu" <?= $filtre_statut === 'lu' ? 'selected' : '' ?>>Lu</option>
                    <option value="traite" <?= $filtre_statut === 'traite' ? 'selected' : '' ?>>Traité</option>
                </select>

                <select name="sujet">
                    <option value="">Tous les sujets</option>
                    <option value="vehicule" <?= $filtre_sujet === 'vehicule' ? 'selected' : '' ?>>Véhicule</option>
                    <option value="nettoyage" <?= $filtre_sujet === 'nettoyage' ? 'selected' : '' ?>>Nettoyage</option>
                    <option value="piece" <?= $filtre_sujet === 'piece' ? 'selected' : '' ?>>Pièce</option>
                    <option value="autre" <?= $filtre_sujet === 'autre' ? 'selected' : '' ?>>Autre</option>
                </select>

                <button type="submit">Filtrer</button>
                <a href="messages_contact.php">Réinitialiser</a>
            </form>
        </div>

        <?php if (count($messages) > 0): ?>
            <div class="admin-requests-grid">
                <?php foreach ($messages as $msg): ?>
                    <article class="admin-request-card">
                        <div class="request-card-header">
                            <div>
                                <span class="request-status status-<?= htmlspecialchars($msg['statut']) ?>">
                                    <?= htmlspecialchars($msg['statut']) ?>
                                </span>

                                <span class="request-status subject-<?= htmlspecialchars($msg['sujet']) ?>">
                                    <?= htmlspecialchars($msg['sujet']) ?>
                                </span>

                                <h3><?= htmlspecialchars($msg['nom_client']) ?></h3>
                            </div>

                            <small>
                                <?= date('d/m/Y H:i', strtotime($msg['date_message'])) ?>
                            </small>
                        </div>

                        <div class="request-card-body">
                            <div class="request-section">
                                <h4>Client</h4>
                                <p><strong>Nom :</strong> <?= htmlspecialchars($msg['nom_client']) ?></p>
                                <p><strong>Email :</strong> <?= htmlspecialchars($msg['email_client']) ?></p>
                                <p><strong>Téléphone :</strong> <?= htmlspecialchars($msg['telephone_client'] ?: 'Non renseigné') ?></p>
                            </div>

                            <div class="request-section">
                                <h4>Sujet</h4>
                                <p><strong>Type :</strong> <?= htmlspecialchars($msg['sujet']) ?></p>
                                <p><strong>Statut :</strong> <?= htmlspecialchars($msg['statut']) ?></p>
                            </div>

                            <div class="request-section full">
                                <h4>Message</h4>
                                <p><?= nl2br(htmlspecialchars($msg['message'])) ?></p>
                            </div>
                        </div>

                        <form method="POST" action="messages_contact.php" class="request-update-form">
                            <input 
                                type="hidden" 
                                name="id_message" 
                                value="<?= htmlspecialchars($msg['id_message']) ?>"
                            >

                            <div class="form-row">
                                <select name="statut">
                                    <option value="nouveau" <?= $msg['statut'] === 'nouveau' ? 'selected' : '' ?>>Nouveau</option>
                                    <option value="lu" <?= $msg['statut'] === 'lu' ? 'selected' : '' ?>>Lu</option>
                                    <option value="traite" <?= $msg['statut'] === 'traite' ? 'selected' : '' ?>>Traité</option>
                                </select>
                            </div>

                            <div class="admin-form-actions">
                                <button 
                                    type="submit" 
                                    name="update_status" 
                                    class="admin-save-btn"
                                >
                                    Mettre à jour
                                </button>

                                <button 
                                    type="submit" 
                                    name="delete_message" 
                                    class="admin-action-btn delete"
                                    onclick="return confirm('Voulez-vous vraiment supprimer ce message ?');"
                                >
                                    Supprimer
                                </button>
                            </div>
                        </form>
                    </article>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="admin-empty">Aucun message trouvé.</p>
        <?php endif; ?>
    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>