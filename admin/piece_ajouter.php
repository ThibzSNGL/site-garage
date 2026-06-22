<?php
require_once '../config/db.php';
include 'includes/admin_header.php';
include 'includes/admin_sidebar.php';

$message_success = '';
$message_error = '';

$nom = '';
$categorie = '';
$marque = '';
$modele_compatible = '';
$reference_piece = '';
$etat = 'occasion';
$prix = '';
$quantite = 1;
$description = '';
$statut = 'disponible';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $categorie = $_POST['categorie'] ?? '';
    $marque = trim($_POST['marque'] ?? '');
    $modele_compatible = trim($_POST['modele_compatible'] ?? '');
    $reference_piece = trim($_POST['reference_piece'] ?? '');
    $etat = $_POST['etat'] ?? 'occasion';
    $prix = trim($_POST['prix'] ?? '');
    $quantite = trim($_POST['quantite'] ?? '1');
    $description = trim($_POST['description'] ?? '');
    $statut = $_POST['statut'] ?? 'disponible';

    $categories_valides = ['moteur', 'carrosserie', 'freinage', 'optique', 'interieur', 'suspension', 'autre'];
    $etats_valides = ['neuf', 'occasion', 'reconditionne'];
    $statuts_valides = ['disponible', 'indisponible'];

    $image = null;

    if (empty($nom) || empty($categorie) || empty($etat) || empty($quantite) || empty($statut)) {
        $message_error = "Veuillez remplir tous les champs obligatoires.";
    } elseif (!in_array($categorie, $categories_valides)) {
        $message_error = "La catégorie sélectionnée n'est pas valide.";
    } elseif (!in_array($etat, $etats_valides)) {
        $message_error = "L'état sélectionné n'est pas valide.";
    } elseif (!in_array($statut, $statuts_valides)) {
        $message_error = "Le statut sélectionné n'est pas valide.";
    } elseif (!ctype_digit((string)$quantite) || (int)$quantite < 0) {
        $message_error = "La quantité n'est pas valide.";
    } elseif (!empty($prix) && (!is_numeric($prix) || (float)$prix < 0)) {
        $message_error = "Le prix n'est pas valide.";
    } else {
        if (!empty($_FILES['image']['name'])) {
            $upload_dir = '../uploads/pieces/';
            $db_path_dir = 'uploads/pieces/';

            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0775, true);
            }

            $file_name = $_FILES['image']['name'];
            $file_tmp = $_FILES['image']['tmp_name'];
            $file_size = $_FILES['image']['size'];
            $file_error = $_FILES['image']['error'];

            $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
            $allowed_extensions = ['jpg', 'jpeg', 'png', 'webp'];

            if ($file_error !== UPLOAD_ERR_OK) {
                $message_error = "Erreur lors de l'envoi de l'image.";
            } elseif (!in_array($extension, $allowed_extensions)) {
                $message_error = "Format d'image non autorisé. Utilisez jpg, jpeg, png ou webp.";
            } elseif ($file_size > 3 * 1024 * 1024) {
                $message_error = "L'image est trop lourde. Taille maximum : 3 Mo.";
            } else {
                $new_file_name = 'piece_' . time() . '_' . bin2hex(random_bytes(5)) . '.' . $extension;
                $destination = $upload_dir . $new_file_name;

                if (move_uploaded_file($file_tmp, $destination)) {
                    $image = $db_path_dir . $new_file_name;
                } else {
                    $message_error = "Impossible d'enregistrer l'image sur le serveur.";
                }
            }
        }

        if (empty($message_error)) {
            $insert = $pdo->prepare("
                INSERT INTO pieces (
                    nom,
                    categorie,
                    marque,
                    modele_compatible,
                    reference_piece,
                    etat,
                    prix,
                    quantite,
                    description,
                    image,
                    statut
                ) VALUES (
                    :nom,
                    :categorie,
                    :marque,
                    :modele_compatible,
                    :reference_piece,
                    :etat,
                    :prix,
                    :quantite,
                    :description,
                    :image,
                    :statut
                )
            ");

            $insert->execute([
                ':nom' => $nom,
                ':categorie' => $categorie,
                ':marque' => !empty($marque) ? $marque : null,
                ':modele_compatible' => !empty($modele_compatible) ? $modele_compatible : null,
                ':reference_piece' => !empty($reference_piece) ? $reference_piece : null,
                ':etat' => $etat,
                ':prix' => !empty($prix) ? (float)$prix : null,
                ':quantite' => (int)$quantite,
                ':description' => !empty($description) ? $description : null,
                ':image' => $image,
                ':statut' => $statut
            ]);

            $message_success = "La pièce a bien été ajoutée.";

            $nom = '';
            $categorie = '';
            $marque = '';
            $modele_compatible = '';
            $reference_piece = '';
            $etat = 'occasion';
            $prix = '';
            $quantite = 1;
            $description = '';
            $statut = 'disponible';
        }
    }
}
?>

<main class="admin-main">
    <header class="admin-topbar">
        <div>
            <h1>Ajouter une pièce</h1>
            <p>Ajoutez une nouvelle pièce au stock visible sur la page de demande de pièce.</p>
        </div>

        <a href="pieces.php" class="admin-topbar-btn">Retour aux pièces</a>
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
            <h2>Informations de la pièce</h2>
        </div>

        <form 
            method="POST" 
            action="piece_ajouter.php" 
            enctype="multipart/form-data" 
            class="admin-form"
        >
            <div class="admin-form-grid">
                <div class="admin-form-group">
                    <label>Nom de la pièce *</label>
                    <input 
                        type="text" 
                        name="nom" 
                        value="<?= htmlspecialchars($nom) ?>" 
                        placeholder="Ex : Alternateur Renault Clio IV"
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Catégorie *</label>
                    <select name="categorie" required>
                        <option value="">Sélectionner</option>
                        <option value="moteur" <?= $categorie === 'moteur' ? 'selected' : '' ?>>Moteur</option>
                        <option value="carrosserie" <?= $categorie === 'carrosserie' ? 'selected' : '' ?>>Carrosserie</option>
                        <option value="freinage" <?= $categorie === 'freinage' ? 'selected' : '' ?>>Freinage</option>
                        <option value="optique" <?= $categorie === 'optique' ? 'selected' : '' ?>>Optique</option>
                        <option value="interieur" <?= $categorie === 'interieur' ? 'selected' : '' ?>>Intérieur</option>
                        <option value="suspension" <?= $categorie === 'suspension' ? 'selected' : '' ?>>Suspension</option>
                        <option value="autre" <?= $categorie === 'autre' ? 'selected' : '' ?>>Autre</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Marque</label>
                    <input 
                        type="text" 
                        name="marque" 
                        value="<?= htmlspecialchars($marque) ?>" 
                        placeholder="Ex : Renault"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Modèle compatible</label>
                    <input 
                        type="text" 
                        name="modele_compatible" 
                        value="<?= htmlspecialchars($modele_compatible) ?>" 
                        placeholder="Ex : Clio IV 2012-2019"
                    >
                </div>

                <div class="admin-form-group">
                    <label>Référence pièce</label>
                    <input 
                        type="text" 
                        name="reference_piece" 
                        value="<?= htmlspecialchars($reference_piece) ?>" 
                        placeholder="Ex : ALT-RN-CL4-001"
                    >
                </div>

                <div class="admin-form-group">
                    <label>État *</label>
                    <select name="etat" required>
                        <option value="neuf" <?= $etat === 'neuf' ? 'selected' : '' ?>>Neuf</option>
                        <option value="occasion" <?= $etat === 'occasion' ? 'selected' : '' ?>>Occasion</option>
                        <option value="reconditionne" <?= $etat === 'reconditionne' ? 'selected' : '' ?>>Reconditionné</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Prix</label>
                    <input 
                        type="number" 
                        step="0.01" 
                        name="prix" 
                        value="<?= htmlspecialchars($prix) ?>" 
                        placeholder="Ex : 89.90"
                    >
                    <small>Laisser vide si le prix est sur devis.</small>
                </div>

                <div class="admin-form-group">
                    <label>Quantité *</label>
                    <input 
                        type="number" 
                        name="quantite" 
                        value="<?= htmlspecialchars($quantite) ?>" 
                        min="0"
                        required
                    >
                </div>

                <div class="admin-form-group">
                    <label>Statut *</label>
                    <select name="statut" required>
                        <option value="disponible" <?= $statut === 'disponible' ? 'selected' : '' ?>>Disponible</option>
                        <option value="indisponible" <?= $statut === 'indisponible' ? 'selected' : '' ?>>Indisponible</option>
                    </select>
                </div>

                <div class="admin-form-group">
                    <label>Image</label>
                    <input 
                        type="file" 
                        name="image" 
                        accept=".jpg,.jpeg,.png,.webp"
                    >
                    <small>Formats acceptés : jpg, jpeg, png, webp. Max : 3 Mo.</small>
                </div>
            </div>

            <div class="admin-form-group">
                <label>Description</label>
                <textarea 
                    name="description" 
                    placeholder="Description de la pièce, état réel, remarques..."
                ><?= htmlspecialchars($description) ?></textarea>
            </div>

            <div class="admin-form-actions">
                <button type="submit" class="admin-save-btn">Ajouter la pièce</button>
                <a href="pieces.php" class="admin-cancel-btn">Annuler</a>
            </div>
        </form>
    </section>
</main>

<?php include 'includes/admin_footer.php'; ?>