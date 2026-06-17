<?php
require_once 'config/db.php';

$message_success = '';
$message_error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom_client = trim($_POST['nom'] ?? '');
    $email_client = trim($_POST['email'] ?? '');
    $telephone_client = trim($_POST['telephone'] ?? '');
    $sujet = trim($_POST['sujet'] ?? 'autre');
    $message = trim($_POST['message'] ?? '');

    $sujets_valides = ['vehicule', 'nettoyage', 'piece', 'autre'];

    if (
        empty($nom_client) ||
        empty($email_client) ||
        empty($message)
    ) {
        $message_error = "Veuillez remplir les champs obligatoires.";
    } elseif (!filter_var($email_client, FILTER_VALIDATE_EMAIL)) {
        $message_error = "L'adresse email n'est pas valide.";
    } elseif (!in_array($sujet, $sujets_valides)) {
        $message_error = "Le sujet sélectionné n'est pas valide.";
    } else {
        $insert = $pdo->prepare("
            INSERT INTO messages_contact (
                nom_client,
                email_client,
                telephone_client,
                sujet,
                message
            ) VALUES (
                :nom_client,
                :email_client,
                :telephone_client,
                :sujet,
                :message
            )
        ");

        $insert->execute([
            ':nom_client' => $nom_client,
            ':email_client' => $email_client,
            ':telephone_client' => $telephone_client,
            ':sujet' => $sujet,
            ':message' => $message
        ]);

        $message_success = "Votre message a bien été envoyé. Le garage vous recontactera rapidement.";
    }
}

include 'includes/header.php';
?>

<main>

    <!-- HERO PAGE CONTACT -->
    <section class="page-hero contact-hero">
        <div class="page-hero-content">
            <p class="subtitle">Contact</p>
            <h1>Contactez le garage</h1>
            <p>
                Une question sur un véhicule, une demande de nettoyage ou une recherche de pièce ?
                Envoyez-nous un message ou contactez-nous directement.
            </p>
        </div>
    </section>

    <!-- CONTACT RAPIDE -->
    <section class="section">
        <div class="section-title">
            <p>Nous joindre</p>
            <h2>Un contact simple et rapide</h2>
        </div>

        <div class="contact-cards-grid">

            <article class="contact-card">
                <div class="contact-icon">📍</div>
                <h3>Adresse</h3>
                <p>12 Rue Exemple</p>
                <p>18100 Vierzon</p>
            </article>

            <article class="contact-card">
                <div class="contact-icon">☎</div>
                <h3>Téléphone</h3>
                <p>06 00 00 00 00</p>
                <a href="tel:0600000000">Appeler maintenant</a>
            </article>

            <article class="contact-card">
                <div class="contact-icon">✉</div>
                <h3>Email</h3>
                <p>contact@sitegarage.fr</p>
                <a href="mailto:contact@sitegarage.fr">Envoyer un email</a>
            </article>

            <article class="contact-card">
                <div class="contact-icon">✆</div>
                <h3>WhatsApp</h3>
                <p>Réponse rapide selon disponibilité</p>
                <a href="https://wa.me/33600000000" target="_blank">Écrire sur WhatsApp</a>
            </article>

        </div>
    </section>

    <!-- INFOS + FORMULAIRE -->
    <section class="section dark-section">
        <div class="contact-page-grid">

            <!-- INFOS GARAGE -->
            <div class="contact-panel">
                <p class="subtitle">Informations</p>
                <h2>Coordonnées du garage</h2>

                <div class="contact-info-list">
                    <div>
                        <strong>Adresse</strong>
                        <p>12 Rue Exemple, 18100 Vierzon</p>
                    </div>

                    <div>
                        <strong>Téléphone</strong>
                        <p>06 00 00 00 00</p>
                    </div>

                    <div>
                        <strong>Email</strong>
                        <p>contact@sitegarage.fr</p>
                    </div>

                    <div>
                        <strong>Horaires</strong>
                        <p>Lundi - Samedi : 9h00 - 18h00</p>
                        <p>Dimanche : fermé</p>
                    </div>
                </div>

                <div class="contact-actions">
                    <a href="tel:0600000000" class="btn btn-primary">Appeler</a>
                    <a href="https://wa.me/33600000000" target="_blank" class="btn btn-secondary">WhatsApp</a>
                </div>
            </div>

            <!-- FORMULAIRE -->
            <form class="form contact-page-form" method="POST" action="contact.php">
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
                    <input type="tel" name="telephone" placeholder="Téléphone">
                    <select name="sujet" required>
                        <option value="">Sujet de la demande</option>
                        <option value="vehicule">Question sur un véhicule</option>
                        <option value="nettoyage">Demande nettoyage</option>
                        <option value="piece">Recherche de pièce</option>
                        <option value="autre">Autre demande</option>
                    </select>
                </div>

                <textarea name="message" placeholder="Votre message..." required></textarea>

                <button type="submit" class="btn btn-primary">Envoyer le message</button>
            </form>

        </div>
    </section>

    <!-- CARTE -->
    <section class="section">
        <div class="section-title">
            <p>Localisation</p>
            <h2>Nous trouver</h2>
        </div>

        <div class="map-box">
            <iframe
                src="https://www.google.com/maps/search/Parc%20des%20Princes
                width="100%"
                height="400"
                style="border:0; border-radius:14px;"
                allowfullscreen=""
                loading="lazy">
            </iframe>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>