<?php
require_once 'config/db.php';

$message_success = '';
$message_error = '';

/* RECUPERATION DES SERVICES DE NETTOYAGE */
$sql = "SELECT * FROM services_nettoyage WHERE actif = 1 ORDER BY prix_depart ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$services = $stmt->fetchAll();

/* TRAITEMENT DU FORMULAIRE */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_service = !empty($_POST['id_service']) ? (int) $_POST['id_service'] : null;
    $nom_client = trim($_POST['nom'] ?? '');
    $email_client = trim($_POST['email'] ?? '');
    $telephone_client = trim($_POST['telephone'] ?? '');
    $vehicule = trim($_POST['vehicule'] ?? '');
    $date_souhaitee = trim($_POST['date_souhaitee'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if (
        empty($nom_client) ||
        empty($email_client) ||
        empty($telephone_client) ||
        empty($id_service)
    ) {
        $message_error = "Veuillez remplir les champs obligatoires.";
    } elseif (!filter_var($email_client, FILTER_VALIDATE_EMAIL)) {
        $message_error = "L'adresse email n'est pas valide.";
    } else {
        $insert = $pdo->prepare("
            INSERT INTO demandes_nettoyage (
                id_service,
                nom_client,
                email_client,
                telephone_client,
                vehicule,
                date_souhaitee,
                message
            ) VALUES (
                :id_service,
                :nom_client,
                :email_client,
                :telephone_client,
                :vehicule,
                :date_souhaitee,
                :message
            )
        ");

        $insert->execute([
            ':id_service' => $id_service,
            ':nom_client' => $nom_client,
            ':email_client' => $email_client,
            ':telephone_client' => $telephone_client,
            ':vehicule' => $vehicule,
            ':date_souhaitee' => !empty($date_souhaitee) ? $date_souhaitee : null,
            ':message' => $message
        ]);

        $message_success = "Votre demande de créneau nettoyage a bien été envoyée. Le garage vous recontactera rapidement.";
    }
}

include 'includes/header.php';
?>

<main>

    <!-- HERO PAGE NETTOYAGE -->
    <section class="page-hero cleaning-hero">
        <div class="page-hero-content">
            <p class="subtitle">Nettoyage automobile</p>
            <h1>Redonnez de l’éclat à votre véhicule</h1>
            <p>
                Nettoyage intérieur, extérieur, complet ou detailing premium :
                choisissez la formule adaptée à votre besoin.
            </p>
        </div>
    </section>

    <!-- INTRO NETTOYAGE -->
    <section class="section">
        <div class="about">
            <div>
                <p class="subtitle">Notre savoir-faire</p>
                <h2>Un nettoyage professionnel pour chaque véhicule</h2>
                <p>
                    Nous proposons des prestations adaptées à tous types de véhicules :
                    citadines, berlines, SUV, utilitaires ou véhicules professionnels.
                </p>
                <p>
                    Chaque formule peut être ajustée selon l’état du véhicule, vos attentes
                    et le niveau de finition souhaité.
                </p>
                <a href="#demande-nettoyage" class="btn btn-primary">Demander un créneau</a>
            </div>

            <div class="cleaning-highlight">
                <h3>Pourquoi choisir notre service ?</h3>

                <ul>
                    <li>Produits adaptés aux surfaces automobiles</li>
                    <li>Nettoyage intérieur et extérieur</li>
                    <li>Formules simples et transparentes</li>
                    <li>Possibilité de prestation sur devis</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- FORMULES NETTOYAGE -->
    <section class="section dark-section">
        <div class="section-title">
            <p>Nos formules</p>
            <h2>Prestations de nettoyage</h2>
        </div>

        <div class="cleaning-grid">

            <?php if (count($services) > 0): ?>

            <?php foreach ($services as $service): ?>

                <?php
                    $icone = '🧽';
                    $badge = 'Service';

                    if ($service['slug'] === 'interieur') {
                        $icone = '🧽';
                        $badge = 'Intérieur';
                    } elseif ($service['slug'] === 'exterieur') {
                        $icone = '🚗';
                        $badge = 'Extérieur';
                    } elseif ($service['slug'] === 'complet') {
                        $icone = '✨';
                        $badge = 'Populaire';
                    } elseif ($service['slug'] === 'detailing-premium') {
                        $icone = '💎';
                        $badge = 'Premium';
                    }

                    $is_featured = $service['slug'] === 'complet' ? 'featured-cleaning' : '';
                ?>

                <article class="cleaning-card <?= $is_featured ?>">
                    <div class="cleaning-card-header">
                        <span class="cleaning-icon"><?= $icone ?></span>
                        <span class="badge"><?= htmlspecialchars($badge) ?></span>
                    </div>

                    <h3><?= htmlspecialchars($service['nom']) ?></h3>

                    <p>
                        <?= htmlspecialchars($service['description']) ?>
                    </p>

                    <ul class="cleaning-list">
                        <li>Durée estimée : <?= htmlspecialchars($service['duree_estimee']) ?></li>
                        <li>Prestation adaptée selon l’état du véhicule</li>
                        <li>Confirmation du créneau par le garage</li>
                    </ul>

                    <div class="cleaning-price">
                        <?php if (!empty($service['prix_depart'])): ?>
                            <span>À partir de</span>
                            <strong><?= number_format($service['prix_depart'], 0, ',', ' ') ?> €</strong>
                        <?php else: ?>
                            <span>Tarif</span>
                            <strong>Sur devis</strong>
                        <?php endif; ?>
                    </div>

                    <a 
                        href="#demande-nettoyage" 
                        class="btn-small select-cleaning-btn"
                        data-id="<?= htmlspecialchars($service['id_service']) ?>"
                        data-name="<?= htmlspecialchars($service['nom']) ?>"
                    >
                        Demander
                    </a>
                </article>

            <?php endforeach; ?>

            <?php else: ?>

                <div class="empty-state">
                    <h3>Aucune formule disponible</h3>
                    <p>Les prestations de nettoyage seront bientôt affichées.</p>
                </div>

            <?php endif; ?>

        </div>
    </section>

    <!-- PROCESS -->
    <section class="section">
        <div class="section-title">
            <p>Fonctionnement</p>
            <h2>Comment se déroule la demande ?</h2>
        </div>

        <div class="process-grid">
            <div class="process-step">
                <span>1</span>
                <h3>Vous choisissez une formule</h3>
                <p>
                    Sélectionnez le type de nettoyage souhaité selon votre besoin.
                </p>
            </div>

            <div class="process-step">
                <span>2</span>
                <h3>Vous envoyez votre demande</h3>
                <p>
                    Remplissez le formulaire avec vos informations et une date souhaitée.
                </p>
            </div>

            <div class="process-step">
                <span>3</span>
                <h3>Nous vous recontactons</h3>
                <p>
                    Le garage confirme le créneau ou vous propose une autre disponibilité.
                </p>
            </div>
        </div>
    </section>

    <!-- FORMULAIRE DEMANDE NETTOYAGE -->
    <section id="demande-nettoyage" class="section dark-section">
        <div class="section-title">
            <p>Demande de rendez-vous</p>
            <h2>Réserver un créneau nettoyage</h2>
        </div>

        <form class="form cleaning-form" method="POST" action="nettoyage.php#demande-nettoyage">

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


            <div class="form-row">
                <input type="text" name="nom" placeholder="Nom complet" required>
                <input type="email" name="email" placeholder="Adresse email" required>
            </div>

            <div class="form-row">
                <input type="tel" name="telephone" placeholder="Téléphone" required>

                <select name="id_service" id="idServiceNettoyage" required>
                    <option value="">Type de nettoyage souhaité</option>

                    <?php foreach ($services as $service): ?>
                        <option value="<?= htmlspecialchars($service['id_service']) ?>">
                            <?= htmlspecialchars($service['nom']) ?>
                            <?php if (!empty($service['prix_depart'])): ?>
                                - dès <?= number_format($service['prix_depart'], 0, ',', ' ') ?> €
                            <?php else: ?>
                                - sur devis
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-row">
                <input type="text" name="vehicule" placeholder="Véhicule concerné, ex : Renault Clio">
                <input type="date" name="date_souhaitee">
            </div>

            <textarea name="message" placeholder="Informations complémentaires : état du véhicule, attentes particulières, contraintes horaires..."></textarea>

            <button type="submit" class="btn btn-primary">Envoyer la demande</button>
        </form>
    </section>

</main>

<?php include 'includes/footer.php'; ?>