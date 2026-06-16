<?php
require_once 'config/db.php';

$search = $_GET['search'] ?? '';
$carburant = $_GET['carburant'] ?? '';
$boite = $_GET['boite'] ?? '';
$prix_max = $_GET['prix_max'] ?? '';
$km_max = $_GET['km_max'] ?? '';
$annee_min = $_GET['annee_min'] ?? '';

$sql = "SELECT * FROM vehicules WHERE 1=1";
$params = [];

if (!empty($search)) {
    $sql .= " AND (marque LIKE :search OR modele LIKE :search OR version LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if (!empty($carburant)) {
    $sql .= " AND carburant = :carburant";
    $params[':carburant'] = $carburant;
}

if (!empty($boite)) {
    $sql .= " AND boite = :boite";
    $params[':boite'] = $boite;
}

if (!empty($prix_max)) {
    $sql .= " AND prix <= :prix_max";
    $params[':prix_max'] = $prix_max;
}

if (!empty($km_max)) {
    $sql .= " AND kilometrage <= :km_max";
    $params[':km_max'] = $km_max;
}

if (!empty($annee_min)) {
    $sql .= " AND annee >= :annee_min";
    $params[':annee_min'] = $annee_min;
}

$sql .= " ORDER BY date_ajout DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$vehicules = $stmt->fetchAll();

include 'includes/header.php';
?>

<main>

    <!-- HERO PAGE VEHICULES -->
    <section class="page-hero">
        <div class="page-hero-content">
            <p class="subtitle">Nos véhicules</p>
            <h1>Véhicules disponibles</h1>
            <p>
                Découvrez notre sélection de véhicules d’occasion contrôlés,
                prêts à prendre la route.
            </p>
        </div>
    </section>

    <!-- FILTRES VEHICULES -->
    <section class="section">
        <div class="filters-box">
            <div class="section-title compact-title">
                <p>Recherche</p>
                <h2>Trouvez votre prochain véhicule</h2>
            </div>

            <form class="filters-form" method="GET" action="vehicules.php">
                <div class="form-row">
                <input 
                        type="text" 
                        name="search"
                        placeholder="Rechercher une marque, un modèle..."
                        value="<?= htmlspecialchars($search) ?>"
                    >

                    <select name="carburant">
                        <option value="">Carburant</option>
                        <option value="essence" <?= $carburant === 'essence' ? 'selected' : '' ?>>Essence</option>
                        <option value="diesel" <?= $carburant === 'diesel' ? 'selected' : '' ?>>Diesel</option>
                        <option value="hybride" <?= $carburant === 'hybride' ? 'selected' : '' ?>>Hybride</option>
                        <option value="electrique" <?= $carburant === 'electrique' ? 'selected' : '' ?>>Électrique</option>
                        <option value="autre" <?= $carburant === 'autre' ? 'selected' : '' ?>>Autre</option>
                    </select>

                    <select name="boite">
                        <option value="">Boîte</option>
                        <option value="manuelle" <?= $boite === 'manuelle' ? 'selected' : '' ?>>Manuelle</option>
                        <option value="automatique" <?= $boite === 'automatique' ? 'selected' : '' ?>>Automatique</option>
                    </select>
                </div>

                <div class="form-row">
                    <select name="prix_max">
                        <option value="">Prix maximum</option>
                        <option value="10000" <?= $prix_max === '10000' ? 'selected' : '' ?>>10 000 €</option>
                        <option value="15000" <?= $prix_max === '15000' ? 'selected' : '' ?>>15 000 €</option>
                        <option value="20000" <?= $prix_max === '20000' ? 'selected' : '' ?>>20 000 €</option>
                        <option value="30000" <?= $prix_max === '30000' ? 'selected' : '' ?>>30 000 €</option>
                        <option value="50000" <?= $prix_max === '50000' ? 'selected' : '' ?>>50 000 €</option>
                    </select>

                    <select name="km_max">
                        <option value="">Kilométrage maximum</option>
                        <option value="50000" <?= $km_max === '50000' ? 'selected' : '' ?>>50 000 km</option>
                        <option value="100000" <?= $km_max === '100000' ? 'selected' : '' ?>>100 000 km</option>
                        <option value="150000" <?= $km_max === '150000' ? 'selected' : '' ?>>150 000 km</option>
                        <option value="200000" <?= $km_max === '200000' ? 'selected' : '' ?>>200 000 km</option>
                    </select>

                    <select name="annee_min">
                        <option value="">Année minimum</option>
                        <option value="2024" <?= $annee_min === '2024' ? 'selected' : '' ?>>2024</option>
                        <option value="2022" <?= $annee_min === '2022' ? 'selected' : '' ?>>2022</option>
                        <option value="2020" <?= $annee_min === '2020' ? 'selected' : '' ?>>2020</option>
                        <option value="2018" <?= $annee_min === '2018' ? 'selected' : '' ?>>2018</option>
                        <option value="2015" <?= $annee_min === '2015' ? 'selected' : '' ?>>2015</option>
                    </select>
                </div>

                <div class="filters-actions">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                    <a href="vehicules.php" class="btn btn-light">Réinitialiser</a>
                </div>
            </form>
        </div>
    </section>

    <!-- LISTE VEHICULES -->
    <section class="section dark-section vehicles-list-section">
        <div class="section-title">
            <p>En stock</p>
            <h2>Nos véhicules actuellement disponibles</h2>
        </div>

        <div class="vehicles-grid vehicles-page-grid">

                <?php if (count($vehicules) > 0): ?>

                    <?php foreach ($vehicules as $vehicule): ?>

                        <article class="vehicle-card vehicle-full-card">
                            <div class="vehicle-image placeholder-img">
                                <?php if (!empty($vehicule['image_principale'])): ?>
                                    <img 
                                        src="<?= htmlspecialchars($vehicule['image_principale']) ?>" 
                                        alt="<?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?>"
                                    >
                                <?php else: ?>
                                    Image véhicule
                                <?php endif; ?>

                                <span class="vehicle-status <?= $vehicule['statut'] === 'vendu' ? 'sold' : '' ?>">
                                    <?= ucfirst(htmlspecialchars($vehicule['statut'])) ?>
                                </span>
                            </div>

                            <div class="vehicle-info">
                                <div class="vehicle-top">
                                    <span class="badge">
                                        <?= ucfirst(htmlspecialchars($vehicule['carburant'])) ?>
                                    </span>

                                    <span class="vehicle-year">
                                        <?= htmlspecialchars($vehicule['annee']) ?>
                                    </span>
                                </div>

                                <h3>
                                    <?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?>
                                </h3>

                                <?php if (!empty($vehicule['version'])): ?>
                                    <p><?= htmlspecialchars($vehicule['version']) ?></p>
                                <?php endif; ?>

                                <ul class="vehicle-details">
                                    <li><?= number_format($vehicule['kilometrage'], 0, ',', ' ') ?> km</li>
                                    <li>Boîte <?= htmlspecialchars($vehicule['boite']) ?></li>
                                    <li><?= ucfirst(htmlspecialchars($vehicule['carburant'])) ?></li>
                                </ul>

                                <strong>
                                    <?= number_format($vehicule['prix'], 0, ',', ' ') ?> €
                                </strong>

                                <div class="vehicle-actions">
                                    <a href="contact.php" class="btn-small">Contacter</a>
                                <a href="#" class="vehicle-link">Voir détails</a>
                                </div>
                            </div>
                        </article>

                    <?php endforeach; ?>

                <?php else: ?>

                    <div class="empty-state">
                        <h3>Aucun véhicule trouvé</h3>
                        <p>Aucun véhicule ne correspond à votre recherche pour le moment.</p>
                        <a href="vehicules.php" class="btn btn-primary">Voir tous les véhicules</a>
                    </div>

                <?php endif; ?>

            </div>

        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>