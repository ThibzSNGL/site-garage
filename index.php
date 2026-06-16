<?php include 'includes/header.php'; ?>

<main>

    <!-- HERO -->
    <section class="hero">
        <div class="hero-content">
            <p class="subtitle">Garage automobile professionnel</p>
            <h1>L’automobile sereine, un seul interlocuteur.</h1>
            <p class="hero-text">
                Vente de véhicules d’occasion, nettoyage automobile et recherche de pièces détachées.
                Un service simple, rapide et professionnel.
            </p>

            <div class="hero-buttons">
                <a href="vehicules.php" class="btn btn-primary">Voir les véhicules</a>
                <a href="nettoyage.php" class="btn btn-secondary">Devis nettoyage</a>
            </div>
        </div>
    </section>

    <!-- SERVICES -->
    <section class="section">
        <div class="section-title">
            <p>Nos activités</p>
            <h2>Trois services pour votre véhicule</h2>
        </div>

        <div class="cards-grid">
            <div class="card">
                <h3>Véhicules d’occasion</h3>
                <p>
                    Retrouvez une sélection de véhicules contrôlés et prêts à prendre la route.
                </p>
                <a href="vehicules.php" class="card-link">Découvrir les véhicules</a>
            </div>

            <div class="card">
                <h3>Nettoyage automobile</h3>
                <p>
                    Nettoyage intérieur, extérieur, complet ou detailing selon vos besoins.
                </p>
                <a href="nettoyage.php" class="card-link">Voir les formules</a>
            </div>

            <div class="card">
                <h3>Recherche de pièces</h3>
                <p>
                    Faites une demande de pièce détachée adaptée à votre véhicule.
                </p>
                <a href="demande_piece.php" class="card-link">Faire une demande</a>
            </div>
        </div>
    </section>

    <!-- VEHICULES MIS EN AVANT -->
    <section class="section dark-section">
        <div class="section-title">
            <p>Sélection</p>
            <h2>Véhicules mis en avant</h2>
        </div>

        <div class="vehicles-grid">
            <div class="vehicle-card">
                <div class="vehicle-image placeholder-img">Image véhicule</div>
                <div class="vehicle-info">
                    <span class="badge">Disponible</span>
                    <h3>Mercedes Classe V</h3>
                    <p>Diesel · 2019 · 85 000 km</p>
                    <strong>39 990 €</strong>
                    <a href="vehicules.php" class="btn-small">Voir le véhicule</a>
                </div>
            </div>

            <div class="vehicle-card">
                <div class="vehicle-image placeholder-img">Image véhicule</div>
                <div class="vehicle-info">
                    <span class="badge">Disponible</span>
                    <h3>Citroën C5 Shine</h3>
                    <p>Essence · 2020 · 62 000 km</p>
                    <strong>18 490 €</strong>
                    <a href="vehicules.php" class="btn-small">Voir le véhicule</a>
                </div>
            </div>

            <div class="vehicle-card">
                <div class="vehicle-image placeholder-img">Image véhicule</div>
                <div class="vehicle-info">
                    <span class="badge">Disponible</span>
                    <h3>Renault Clio V</h3>
                    <p>Essence · 2021 · 45 000 km</p>
                    <strong>13 990 €</strong>
                    <a href="vehicules.php" class="btn-small">Voir le véhicule</a>
                </div>
            </div>
        </div>

        <div class="section-action">
            <a href="vehicules.php" class="btn btn-primary">Voir tous les véhicules</a>
        </div>
    </section>

    <!-- PRESENTATION ENTREPRISE -->
    <section class="section">
        <div class="about">
            <div>
                <p class="subtitle">À propos</p>
                <h2>Un garage proche de ses clients</h2>
                <p>
                    Notre objectif est simple : accompagner nos clients dans l’achat,
                    l’entretien esthétique et la recherche de pièces pour leur véhicule.
                </p>
                <p>
                    Nous proposons une approche directe, transparente et professionnelle,
                    avec un seul interlocuteur pour faciliter toutes vos démarches.
                </p>
            </div>

            <div class="stats">
                <div>
                    <strong>500+</strong>
                    <span>véhicules accompagnés</span>
                </div>

                <div>
                    <strong>98%</strong>
                    <span>clients satisfaits</span>
                </div>

                <div>
                    <strong>3</strong>
                    <span>activités principales</span>
                </div>
            </div>
        </div>
    </section>

    <!-- EQUIPE -->
    <section class="section dark-section">
        <div class="section-title">
            <p>Notre équipe</p>
            <h2>Des professionnels à votre écoute</h2>
        </div>

        <div class="cards-grid">
            <div class="card dark-card">
                <h3>Conseil automobile</h3>
                <p>
                    Accompagnement dans le choix du véhicule selon votre budget et vos besoins.
                </p>
            </div>

            <div class="card dark-card">
                <h3>Préparation esthétique</h3>
                <p>
                    Nettoyage, préparation et soin du véhicule avant livraison ou rendez-vous.
                </p>
            </div>

            <div class="card dark-card">
                <h3>Recherche de pièces</h3>
                <p>
                    Identification et recherche de pièces mécaniques adaptées à votre véhicule.
                </p>
            </div>
        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>