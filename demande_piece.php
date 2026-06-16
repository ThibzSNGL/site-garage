<?php
require_once 'config/db.php';

$message_success = '';
$message_error = '';

/* RECUPERATION DES PIECES EN STOCK */
$sql = "SELECT * FROM pieces WHERE statut = 'disponible' AND quantite > 0 ORDER BY date_ajout DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$pieces = $stmt->fetchAll();

/* TRAITEMENT DU FORMULAIRE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_piece = !empty($_POST['id_piece']) ? (int) $_POST['id_piece'] : null;
    $nom_client = trim($_POST['nom'] ?? '');
    $email_client = trim($_POST['email'] ?? '');
    $telephone_client = trim($_POST['telephone'] ?? '');
    $marque_vehicule = trim($_POST['marque_vehicule'] ?? '');
    $modele_vehicule = trim($_POST['modele_vehicule'] ?? '');
    $annee_vehicule = trim($_POST['annee_vehicule'] ?? '');
    $piece_recherchee = trim($_POST['piece_recherchee'] ?? '');
    $reference_piece = trim($_POST['reference_piece'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (
        empty($nom_client) ||
        empty($email_client) ||
        empty($telephone_client) ||
        empty($piece_recherchee)
    ) {
        $message_error = "Veuillez remplir les champs obligatoires.";
    } elseif (!filter_var($email_client, FILTER_VALIDATE_EMAIL)) {
        $message_error = "L'adresse email n'est pas valide.";
    } else {
        $insert = $pdo->prepare("
            INSERT INTO demandes_pieces (
                id_piece,
                nom_client,
                email_client,
                telephone_client,
                marque_vehicule,
                modele_vehicule,
                annee_vehicule,
                piece_recherchee,
                reference_piece,
                message
            ) VALUES (
                :id_piece,
                :nom_client,
                :email_client,
                :telephone_client,
                :marque_vehicule,
                :modele_vehicule,
                :annee_vehicule,
                :piece_recherchee,
                :reference_piece,
                :message
            )
        ");

        $insert->execute([
            ':id_piece' => $id_piece,
            ':nom_client' => $nom_client,
            ':email_client' => $email_client,
            ':telephone_client' => $telephone_client,
            ':marque_vehicule' => $marque_vehicule,
            ':modele_vehicule' => $modele_vehicule,
            ':annee_vehicule' => $annee_vehicule,
            ':piece_recherchee' => $piece_recherchee,
            ':reference_piece' => $reference_piece,
            ':message' => $message
        ]);

        $message_success = "Votre demande de pièce a bien été envoyée. Le garage vous recontactera rapidement.";
    }
}

include 'includes/header.php';
?>

<main>

    <!-- HERO PAGE PIECES -->
    <section class="page-hero parts-hero">
        <div class="page-hero-content">
            <p class="subtitle">Pièces mécaniques</p>
            <h1>Recherche et disponibilité de pièces auto</h1>
            <p>
                Consultez les pièces déjà disponibles en stock ou envoyez une demande
                personnalisée pour une pièce spécifique.
            </p>
        </div>
    </section>

    <!-- INTRO -->
    <section class="section">
        <div class="about">
            <div>
                <p class="subtitle">Stock disponible</p>
                <h2>Trouvez rapidement une pièce pour votre véhicule</h2>
                <p>
                    Certaines pièces sont déjà disponibles au garage. Vous pouvez les rechercher,
                    les filtrer, puis les sélectionner pour préremplir automatiquement le formulaire.
                </p>
                <p>
                    Si la pièce souhaitée n’apparaît pas dans la liste, envoyez une demande
                    personnalisée via le formulaire.
                </p>
            </div>

            <div class="cleaning-highlight">
                <h3>Types de pièces possibles</h3>
                <ul>
                    <li>Pièces moteur</li>
                    <li>Pièces carrosserie</li>
                    <li>Optiques et phares</li>
                    <li>Freinage et suspension</li>
                    <li>Accessoires et équipements</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- FILTRES PIECES -->
    <section class="section dark-section">
        <div class="section-title">
            <p>Catalogue</p>
            <h2>Pièces disponibles en stock</h2>
        </div>

        <div class="parts-filters">
            <input type="text" id="pieceSearch" placeholder="Rechercher une pièce, une marque, une référence...">

            <select id="pieceCategory">
                <option value="all">Toutes les catégories</option>
                <option value="moteur">Moteur</option>
                <option value="carrosserie">Carrosserie</option>
                <option value="freinage">Freinage</option>
                <option value="optique">Optique</option>
                <option value="interieur">Intérieur</option>
            </select>

            <select id="pieceBrand">
                <option value="all">Toutes les marques</option>
                <option value="renault">Renault</option>
                <option value="peugeot">Peugeot</option>
                <option value="citroen">Citroën</option>
                <option value="mercedes">Mercedes</option>
                <option value="volkswagen">Volkswagen</option>
            </select>
        </div>

        <div class="parts-grid" id="partsGrid">
            <?php if (count($pieces) > 0): ?>

        <?php foreach ($pieces as $piece): ?>

            <article class="part-card"
                data-id="<?= htmlspecialchars($piece['id_piece']) ?>"
                data-name="<?= htmlspecialchars($piece['nom']) ?>"
                data-category="<?= htmlspecialchars($piece['categorie']) ?>"
                data-brand="<?= htmlspecialchars(strtolower($piece['marque'])) ?>"
                data-reference="<?= htmlspecialchars($piece['reference_piece']) ?>"
                data-price="<?= !empty($piece['prix']) ? number_format($piece['prix'], 0, ',', ' ') . ' €' : 'Sur devis' ?>">

                <div class="part-img placeholder-img">
                    <?php if (!empty($piece['image'])): ?>
                        <img 
                            src="<?= htmlspecialchars($piece['image']) ?>" 
                            alt="<?= htmlspecialchars($piece['nom']) ?>"
                        >
                    <?php else: ?>
                        Image pièce
                    <?php endif; ?>
                </div>

                <div class="part-content">
                    <span class="badge">Disponible</span>

                    <h3><?= htmlspecialchars($piece['nom']) ?></h3>

                    <p>
                        Compatible <?= htmlspecialchars($piece['marque']) ?>
                        <?= htmlspecialchars($piece['modele_compatible']) ?>
                    </p>

                    <ul class="part-details">
                        <li>Catégorie : <?= ucfirst(htmlspecialchars($piece['categorie'])) ?></li>
                        <li>Référence : <?= htmlspecialchars($piece['reference_piece']) ?></li>
                        <li>État : <?= ucfirst(htmlspecialchars($piece['etat'])) ?></li>
                        <li>Quantité : <?= htmlspecialchars($piece['quantite']) ?></li>
                    </ul>

                    <strong>
                        <?= !empty($piece['prix']) ? number_format($piece['prix'], 0, ',', ' ') . ' €' : 'Sur devis' ?>
                    </strong>

                    <button type="button" class="btn-small select-part-btn">
                        Sélectionner cette pièce
                    </button>
                </div>
            </article>

        <?php endforeach; ?>

    <?php else: ?>

        <div class="empty-state">
            <h3>Aucune pièce disponible</h3>
            <p>Aucune pièce n’est actuellement en stock.</p>
        </div>

    <?php endif; ?>


        
        </div>
    </section>

    <!-- FORMULAIRE DEMANDE PIECE -->
    <section id="formulaire-piece" class="section">
        <div class="section-title">
            <p>Demande de pièce</p>
            <h2>Envoyer une demande au garage</h2>
        </div>

        <form class="form part-request-form" method="POST" action="demande_piece.php#formulaire-piece">

            <?php if (!empty($message_success)): ?>
                <div class="alert alert-success">
                    <?= htmlspecialchars($message_success) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($message_error)): ?>
                <div class="alert alert-error">
                    <?= htmlspecialchars($message_error) ?>
                </div>
            <?php endif; ?>

            <div class="selected-part-box" id="selectedPartBox">
                <p>Aucune pièce sélectionnée pour le moment.</p>
            </div>

            <input type="hidden" name="id_piece" id="idPiece">

            <div class="form-row">
                <input type="text" name="nom" placeholder="Nom complet" required>
                <input type="email" name="email" placeholder="Adresse email" required>
            </div>

            <div class="form-row">
                <input type="tel" name="telephone" placeholder="Téléphone" required>
                <input type="text" name="marque_vehicule" placeholder="Marque du véhicule">
            </div>

            <div class="form-row">
                <input type="text" name="modele_vehicule" placeholder="Modèle du véhicule">
                <input type="text" name="annee_vehicule" placeholder="Année du véhicule">
            </div>

            <div class="form-row">
                <input type="text" name="piece_recherchee" id="pieceRecherchee" placeholder="Pièce recherchée" required>
                <input type="text" name="reference_piece" id="referencePiece" placeholder="Référence si connue">
            </div>

            <textarea name="message" id="messagePiece" placeholder="Informations complémentaires : motorisation, immatriculation, urgence, détails particuliers..."></textarea>

            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
        </form>
    </section>

</main>

<?php include 'includes/footer.php'; ?>