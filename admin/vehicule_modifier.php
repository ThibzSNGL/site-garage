<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$message_success = '';
$message_error = '';

$id_vehicule = (int) ($_GET['id'] ?? $_POST['id_vehicule'] ?? 0);

if ($id_vehicule <= 0) {
    header('Location: vehicules.php');
    exit;
}

/* RECUPERATION DU VEHICULE */
$stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id_vehicule = :id_vehicule LIMIT 1");
$stmt->execute([
    ':id_vehicule' => $id_vehicule
]);

$vehicule = $stmt->fetch();

if (!$vehicule) {
    header('Location: vehicules.php');
    exit;
}

/* VALEURS PAR DEFAUT */
$marque = $vehicule['marque'];
$modele = $vehicule['modele'];
$version = $vehicule['version'];
$annee = $vehicule['annee'];
$kilometrage = $vehicule['kilometrage'];
$carburant = $vehicule['carburant'];
$boite = $vehicule['boite'];
$prix = $vehicule['prix'];
$description = $vehicule['description'];
$statut = $vehicule['statut'];
$mis_en_avant = $vehicule['mis_en_avant'];
$image_actuelle = $vehicule['image_principale'];

/* TRAITEMENT DU FORMULAIRE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $marque = trim($_POST['marque'] ?? '');
    $modele = trim($_POST['modele'] ?? '');
    $version = trim($_POST['version'] ?? '');
    $annee = trim($_POST['annee'] ?? '');
    $kilometrage = trim($_POST['kilometrage'] ?? '');
    $carburant = $_POST['carburant'] ?? '';
    $boite = $_POST['boite'] ?? '';
    $prix = trim($_POST['prix'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $statut = $_POST['statut'] ?? 'disponible';
    $mis_en_avant = isset($_POST['mis_en_avant']) ? 1 : 0;

    $carburants_valides = ['essence', 'diesel', 'hybride', 'electrique', 'autre'];
    $boites_valides = ['manuelle', 'automatique'];
    $statuts_valides = ['disponible', 'reserve', 'vendu'];

    $image_principale = $image_actuelle;

    if (
        empty($marque) ||
        empty($modele) ||
        empty($annee) ||
        empty($kilometrage) ||
        empty($carburant) ||
        empty($boite) ||
        empty($prix)
    ) {
        $message_error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!ctype_digit((string)$annee) || (int)$annee < 1950 || (int)$annee > (int)date('Y') + 1) {
        $message_error = "L'année du véhicule n'est pas valide.";
    } elseif (!ctype_digit((string)$kilometrage) || (int)$kilometrage < 0) {
        $message_error = "Le kilométrage n'est pas valide.";
    } elseif (!is_numeric($prix) || (float)$prix < 0) {
        $message_error = "Le prix n'est pas valide.";
    } elseif (!in_array($carburant, $carburants_valides)) {
        $message_error = "Le carburant sélectionné n'est pas valide.";
    } elseif (!in_array($boite, $boites_valides)) {
        $message_error = "La boîte sélectionnée n'est pas valide.";
    } elseif (!in_array($statut, $statuts_valides)) {
        $message_error = "Le statut sélectionné n'est pas valide.";
    } else {

        /* UPLOAD NOUVELLE IMAGE SI ENVOYEE */
        if (!empty($_FILES['image_principale']['name'])) {
            $upload_dir = '../uploads/vehicules/';
            $db_path_dir = 'uploads/vehicules/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0775, true);
            }

            $file_name = $_FILES['image_principale']['name'];
            $file_tmp = $_FILES['image_principale']['tmp_name'];
            $file_size = $_FILES['image_principale']['size'];
            $file_error = $_FILES['image_principale']['error'];

            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

            if ($file_error !== UPLOAD_ERR_OK) {
                $message_error = "Erreur lors de l'envoi de l'image.";
            } elseif (!in_array($extension, $allowed_extensions)) {
                $message_error = "Format d'image non autorisé. Utilisez jpg, jpeg, png ou webp.";
            } elseif ($file_size > 3 * 1024 * 1024) {
                $message_error = "L'image est trop lourde. Taille maximum : 3 Mo.";
            } else {
                $new_file_name = 'vehicule_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
                $destination = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $image_principale = $db_path_dir . $new_file_name;

                    /* SUPPRESSION ANCIENNE IMAGE SI ELLE EXISTE */
                    if (!empty($image_actuelle)) {
                        $old_image_path = '../' . $image_actuelle;

                        if (file_exists($old_image_path)) {
                            unlink($old_image_path);
                        }
                    }
                } else {
                    $message_error = "Impossible d'enregistrer l'image sur le serveur.";
                }
            }
        }

        if (empty($message_error)) {
            $update = $pdo->prepare("
                UPDATE vehicules
                SET 
                    marque = :marque,
                    modele = :modele,
                    version = :version,
                    annee = :annee,
                    kilometrage = :kilometrage,
                    carburant = :carburant,
                    boite = :boite,
                    prix = :prix,
                    description = :description,
                    image_principale = :image_principale,
                    statut = :statut,
                    mis_en_avant = :mis_en_avant
                WHERE id_vehicule = :id_vehicule
            ");

            $update->execute([
                ':marque' => $marque,
                ':modele' => $modele,
                ':version' => !empty($version) ? $version : null,
                ':annee' => (int)$annee,
                ':kilometrage' => (int)$kilometrage,
                ':carburant' => $carburant,
                ':boite' => $boite,
                ':prix' => (float)$prix,
                ':description' => !empty($description) ? $description : null,
                ':image_principale' => $image_principale,
                ':statut' => $statut,
                ':mis_en_avant' => $mis_en_avant,
                ':id_vehicule' => $id_vehicule
            ]);

            $message_success = "Le véhicule a bien été modifié.";

            /* RAFRAICHIR LES DONNEES */
            $stmt = $pdo->prepare("SELECT * FROM vehicules WHERE id_vehicule = :id_vehicule LIMIT 1");
            $stmt->execute([
                ':id_vehicule' => $id_vehicule
            ]);

            $vehicule = $stmt->fetch();

            $marque = $vehicule['marque'];
            $modele = $vehicule['modele'];
            $version = $vehicule['version'];
            $annee = $vehicule['annee'];
            $kilometrage = $vehicule['kilometrage'];
            $carburant = $vehicule['carburant'];
            $boite = $vehicule['boite'];
            $prix = $vehicule['prix'];
            $description = $vehicule['description'];
            $statut = $vehicule['statut'];
            $mis_en_avant = $vehicule['mis_en_avant'];
            $image_actuelle = $vehicule['image_principale'];
        }
    }
}
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Modifier un véhicule</h1>
            <p>
                Modification de :
                <strong><?= htmlspecialchars($marque . ' ' . $modele) ?></strong>
            </p>
        </div>

        <a href="vehicules.php" class="admin-topbar-btn">Retour aux véhicules</a>
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
            <h2>Informations du véhicule</h2>
        </div>

        <?php if (!empty($image_actuelle)): ?>
            <div class="admin-current-image">
                <p>Image actuelle</p>
                <img 
                    src="../<?= htmlspecialchars($image_actuelle) ?>" 
                    alt="<?= htmlspecialchars($marque . ' ' . $modele) ?>"
                >
            </div>
        <?php endif; ?>

        <form 
            method="POST" 
            action="vehicule_modifier.php?id=<?= htmlspecialchars($id_vehicule) ?>" 
            enctype="multipart/form-data" 
            class="admin-form"
        >
            <input 
                type="hidden" 
                name="id_vehicule" 
                value="<?= htmlspecialchars($id_vehicule) ?>"
            >

            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label>Marque *</label>
                    <input 
                        type="text" 
                        name="marque" 
                        value="<?= htmlspecialchars($marque) ?>" 
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Modèle *</label>
                    <input 
                        type="text" 
                        name="modele" 
                        value="<?= htmlspecialchars($modele) ?>" 
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Version</label>
                    <input 
                        type="text" 
                        name="version" 
                        value="<?= htmlspecialchars($version ?? '') ?>"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Année *</label>
                    <input 
                        type="number" 
                        name="annee" 
                        value="<?= htmlspecialchars($annee) ?>" 
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Kilométrage *</label>
                    <input 
                        type="number" 
                        name="kilometrage" 
                        value="<?= htmlspecialchars($kilometrage) ?>" 
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Prix *</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="prix" 
                        value="<?= htmlspecialchars($prix) ?>" 
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Carburant *</label>
                    <select name="carburant" required>
                        <option value="">Sélectionner</option>
                        <option value="essence" <?= $carburant === 'essence' ? 'selected' : '' ?>>Essence</option>
                        <option value="diesel" <?= $carburant === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                        <option value="hybride" <?= $carburant === 'hybride' ? 'selected' : '' ?>>Hybride</option>
                        <option value="electrique" <?= $carburant === 'electrique' ? 'selected' : '' ?>>Électrique</option>
                        <option value="autre" <?= $carburant === 'autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Boîte *</label>
                    <select name="boite" required>
                        <option value="">Sélectionner</option>
                        <option value="manuelle" <?= $boite === 'manuelle' ? 'selected' : '' ?>>Manuelle</option>
                        <option value="automatique" <?= $boite === 'automatique' ? 'selected' : '' ?>>Automatique</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Statut *</label>
                    <select name="statut" required>
                        <option value="disponible" <?= $statut === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                        <option value="reserve" <?= $statut === 'reserve' ? 'selected' : '' ?>>Réservé</option>
                        <option value="vendu" <?= $statut === 'vendu' ? 'selected' : '' ?>>Vendu</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Nouvelle image principale</label>
                    <input 
                        type="file" 
                        name="image_principale" 
                        accept=".jpg,.jpeg,.png,.webp"
                    >
                    <small>Laisser vide pour conserver l’image actuelle.</small>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Description</label>
                <textarea 
                    name="description" 
                    placeholder="Description du véhicule..."
                ><?= htmlspecialchars($description ?? '') ?></textarea>
            </div>

            <label class="admin-checkbox">
                <input 
                    type="checkbox" 
                    name="mis_en_avant" 
                    <?= $mis_en_avant ? 'checked' : '' ?>
                >
                Mettre ce véhicule en avant sur la page d’accueil
            </label>

            <div class="admin-form-actions">
                <button type="submit" class="admin-save-btn">Enregistrer les modifications</button>
                <a href="vehicules.php" class="admin-cancel-btn">Annuler</a>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>