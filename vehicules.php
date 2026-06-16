<?php include 'includes/header.php'; ?>

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

            <form class="filters-form">
                <div class="form-row">
                    <input type="text" placeholder="Rechercher une marque, un modèle...">

                    <select>
                        <option value="">Carburant</option>
                        <option value="essence">Essence</option>
                        <option value="diesel">Diesel</option>
                        <option value="hybride">Hybride</option>
                        <option value="electrique">Électrique</option>
                    </select>

                    <select>
                        <option value="">Boîte</option>
                        <option value="manuelle">Manuelle</option>
                        <option value="automatique">Automatique</option>
                    </select>
                </div>

                <div class="form-row">
                    <select>
                        <option value="">Prix maximum</option>
                        <option value="10000">10 000 €</option>
                        <option value="15000">15 000 €</option>
                        <option value="20000">20 000 €</option>
                        <option value="30000">30 000 €</option>
                        <option value="50000">50 000 €</option>
                    </select>

                    <select>
                        <option value="">Kilométrage maximum</option>
                        <option value="50000">50 000 km</option>
                        <option value="100000">100 000 km</option>
                        <option value="150000">150 000 km</option>
                        <option value="200000">200 000 km</option>
                    </select>

                    <select>
                        <option value="">Année minimum</option>
                        <option value="2024">2024</option>
                        <option value="2022">2022</option>
                        <option value="2020">2020</option>
                        <option value="2018">2018</option>
                        <option value="2015">2015</option>
                    </select>
                </div>

                <div class="filters-actions">
                    <button type="button" class="btn btn-primary">Rechercher</button>
                    <button type="reset" class="btn btn-light">Réinitialiser</button>
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

            <!-- VEHICULE 1 -->
            <article class="vehicle-card vehicle-full-card">
                <div class="vehicle-image placeholder-img">
                    Image véhicule
                    <span class="vehicle-status">Disponible</span>
                </div>

                <div class="vehicle-info">
                    <div class="vehicle-top">
                        <span class="badge">Diesel</span>
                        <span class="vehicle-year">2019</span>
                    </div>

                    <h3>Mercedes Classe V</h3>

                    <ul class="vehicle-details">
                        <li>85 000 km</li>
                        <li>Boîte automatique</li>
                        <li>7 places</li>
                    </ul>

                    <strong>39 990 €</strong>

                    <div class="vehicle-actions">
                        <a href="contact.php" class="btn-small">Contacter</a>
                        <a href="#" class="vehicle-link">Voir détails</a>
                    </div>
                </div>
            </article>

            <!-- VEHICULE 2 -->
            <article class="vehicle-card vehicle-full-card">
                <div class="vehicle-image placeholder-img">
                    Image véhicule
                    <span class="vehicle-status">Disponible</span>
                </div>

                <div class="vehicle-info">
                    <div class="vehicle-top">
                        <span class="badge">Essence</span>
                        <span class="vehicle-year">2020</span>
                    </div>

                    <h3>Citroën C5 Shine</h3>

                    <ul class="vehicle-details">
                        <li>62 000 km</li>
                        <li>Boîte manuelle</li>
                        <li>5 places</li>
                    </ul>

                    <strong>18 490 €</strong>

                    <div class="vehicle-actions">
                        <a href="contact.php" class="btn-small">Contacter</a>
                        <a href="#" class="vehicle-link">Voir détails</a>
                    </div>
                </div>
            </article>

            <!-- VEHICULE 3 -->
            <article class="vehicle-card vehicle-full-card">
                <div class="vehicle-image placeholder-img">
                    Image véhicule
                    <span class="vehicle-status">Disponible</span>
                </div>

                <div class="vehicle-info">
                    <div class="vehicle-top">
                        <span class="badge">Essence</span>
                        <span class="vehicle-year">2021</span>
                    </div>

                    <h3>Renault Clio V</h3>

                    <ul class="vehicle-details">
                        <li>45 000 km</li>
                        <li>Boîte manuelle</li>
                        <li>5 places</li>
                    </ul>

                    <strong>13 990 €</strong>

                    <div class="vehicle-actions">
                        <a href="contact.php" class="btn-small">Contacter</a>
                        <a href="#" class="vehicle-link">Voir détails</a>
                    </div>
                </div>
            </article>

            <!-- VEHICULE 4 -->
            <article class="vehicle-card vehicle-full-card">
                <div class="vehicle-image placeholder-img">
                    Image véhicule
                    <span class="vehicle-status">Disponible</span>
                </div>

                <div class="vehicle-info">
                    <div class="vehicle-top">
                        <span class="badge">Hybride</span>
                        <span class="vehicle-year">2022</span>
                    </div>

                    <h3>Toyota Corolla</h3>

                    <ul class="vehicle-details">
                        <li>38 000 km</li>
                        <li>Boîte automatique</li>
                        <li>5 places</li>
                    </ul>

                    <strong>22 990 €</strong>

                    <div class="vehicle-actions">
                        <a href="contact.php" class="btn-small">Contacter</a>
                        <a href="#" class="vehicle-link">Voir détails</a>
                    </div>
                </div>
            </article>

            <!-- VEHICULE 5 -->
            <article class="vehicle-card vehicle-full-card">
                <div class="vehicle-image placeholder-img">
                    Image véhicule
                    <span class="vehicle-status sold">Vendu</span>
                </div>

                <div class="vehicle-info">
                    <div class="vehicle-top">
                        <span class="badge">Diesel</span>
                        <span class="vehicle-year">2018</span>
                    </div>

                    <h3>Peugeot 3008</h3>

                    <ul class="vehicle-details">
                        <li>96 000 km</li>
                        <li>Boîte manuelle</li>
                        <li>5 places</li>
                    </ul>

                    <strong>16 990 €</strong>

                    <div class="vehicle-actions">
                        <a href="contact.php" class="btn-small">Contacter</a>
                        <a href="#" class="vehicle-link">Voir détails</a>
                    </div>
                </div>
            </article>

            <!-- VEHICULE 6 -->
            <article class="vehicle-card vehicle-full-card">
                <div class="vehicle-image placeholder-img">
                    Image véhicule
                    <span class="vehicle-status">Disponible</span>
                </div>

                <div class="vehicle-info">
                    <div class="vehicle-top">
                        <span class="badge">Électrique</span>
                        <span class="vehicle-year">2023</span>
                    </div>

                    <h3>Renault Mégane E-Tech</h3>

                    <ul class="vehicle-details">
                        <li>18 000 km</li>
                        <li>Boîte automatique</li>
                        <li>5 places</li>
                    </ul>

                    <strong>28 990 €</strong>

                    <div class="vehicle-actions">
                        <a href="contact.php" class="btn-small">Contacter</a>
                        <a href="#" class="vehicle-link">Voir détails</a>
                    </div>
                </div>
            </article>

        </div>
    </section>

</main>

<?php include 'includes/footer.php'; ?>