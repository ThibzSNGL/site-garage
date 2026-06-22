<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$message_success = '';
$message_error = '';

$id_admin = $_SESSION['admin_id'];

/* RECUPERATION ADMIN CONNECTE */
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id_admin = :id_admin LIMIT 1");
$stmt->execute([
    ':id_admin' => $id_admin
]);

$admin = $stmt->fetch();

if (!$admin) {
    header('Location: logout.php');
    exit;
}

$nom = $admin['nom'];
$email = $admin['email'];

/* MISE A JOUR PROFIL */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');

    if (empty($nom) || empty($email)) {
        $message_error = "Veuillez remplir le nom et l'adresse email.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_error = "L'adresse email n'est pas valide.";
    } else {
        $check = $pdo->prepare("
            SELECT id_admin 
            FROM admins 
            WHERE email = :email 
            AND id_admin != :id_admin
            LIMIT 1
        ");

        $check->execute([
            ':email' => $email,
            ':id_admin' => $id_admin
        ]);

        if ($check->fetch()) {
            $message_error = "Cette adresse email est déjà utilisée par un autre compte.";
        } else {
            $update = $pdo->prepare("
                UPDATE admins
                SET nom = :nom,
                    email = :email
                WHERE id_admin = :id_admin
            ");

            $update->execute([
                ':nom' => $nom,
                ':email' => $email,
                ':id_admin' => $id_admin
            ]);

            $_SESSION['admin_nom'] = $nom;
            $_SESSION['admin_email'] = $email;

            $message_success = "Vos informations ont bien été mises à jour.";
        }
    }
}

/* MISE A JOUR MOT DE PASSE */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $ancien_mot_de_passe = $_POST['ancien_mot_de_passe'] ?? '';
    $nouveau_mot_de_passe = $_POST['nouveau_mot_de_passe'] ?? '';
    $confirmation_mot_de_passe = $_POST['confirmation_mot_de_passe'] ?? '';

    if (
        empty($ancien_mot_de_passe) ||
        empty($nouveau_mot_de_passe) ||
        empty($confirmation_mot_de_passe)
    ) {
        $message_error = "Veuillez remplir tous les champs du mot de passe.";
    } elseif (!password_verify($ancien_mot_de_passe, $admin['mot_de_passe'])) {
        $message_error = "L'ancien mot de passe est incorrect.";
    } elseif ($nouveau_mot_de_passe !== $confirmation_mot_de_passe) {
        $message_error = "La confirmation du mot de passe ne correspond pas.";
    } elseif (strlen($nouveau_mot_de_passe) < 8) {
        $message_error = "Le nouveau mot de passe doit contenir au moins 8 caractères.";
    } else {
        $hash = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);

        $update = $pdo->prepare("
            UPDATE admins
            SET mot_de_passe = :mot_de_passe
            WHERE id_admin = :id_admin
        ");

        $update->execute([
            ':mot_de_passe' => $hash,
            ':id_admin' => $id_admin
        ]);

        $message_success = "Votre mot de passe a bien été modifié.";
    }
}
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Mon compte</h1>
            <p>Gérez vos informations de connexion administrateur.</p>
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

    <section class="admin-account-grid">

        <!-- INFORMATIONS PROFIL -->
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h2>Informations du compte</h2>
            </div>

            <form method="POST" action="mon_compte.php" class="admin-form">
                <div class="admin-form-group">
                    <label>Nom</label>
                    <input 
                        type="text" 
                        name="nom" 
                        value="<?= htmlspecialchars($nom) ?>" 
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Adresse email</label>
                    <input 
                        type="email" 
                        name="email" 
                        value="<?= htmlspecialchars($email) ?>" 
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Rôle</label>
                    <input 
                        type="text" 
                        value="<?= htmlspecialchars($admin['role']) ?>" 
                        disabled
                    >
                    <small>Le rôle ne peut pas être modifié depuis cette page.</small>
                </div>

                <div class="admin-form-actions">
                    <button 
                        type="submit" 
                        name="update_profile" 
                        class="admin-save-btn"
                    >
                        Enregistrer les informations
                    </button>
                </div>
            </form>
        </div>

        <!-- MOT DE PASSE -->
        <div class="admin-panel">
            <div class="admin-panel-header">
                <h2>Modifier le mot de passe</h2>
            </div>

            <form method="POST" action="mon_compte.php" class="admin-form">
                <div class="admin-form-group">
                    <label>Ancien mot de passe</label>
                    <input 
                        type="password" 
                        name="ancien_mot_de_passe" 
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Nouveau mot de passe</label>
                    <input 
                        type="password" 
                        name="nouveau_mot_de_passe" 
                        required
                    >
                    <small>Minimum 8 caractères.</small>
                </div>

                <div class="admin-form-group">
                    <label>Confirmer le nouveau mot de passe</label>
                    <input 
                        type="password" 
                        name="confirmation_mot_de_passe" 
                        required
                    >
                </div>

                <div class="admin-form-actions">
                    <button 
                        type="submit" 
                        name="update_password" 
                        class="admin-save-btn"
                    >
                        Modifier le mot de passe
                    </button>
                </div>
            </form>
        </div>

    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>