<?php include 'includes/header.php'; ?>

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

            <!-- PIECE 1 -->
            <article class="part-card"
                data-name="Alternateur Renault Clio IV"
                data-category="moteur"
                data-brand="renault"
                data-reference="ALT-REN-CLIO4-120"
                data-price="129 €">

                <div class="part-img placeholder-img">Image pièce</div>

                <div class="part-content">
                    <span class="badge">Disponible</span>
                    <h3>Alternateur Renault Clio IV</h3>
                    <p>Compatible Renault Clio IV · Essence/Diesel selon version</p>

                    <ul class="part-details">
                        <li>Catégorie : Moteur</li>
                        <li>Référence : ALT-REN-CLIO4-120</li>
                        <li>État : Occasion contrôlée</li>
                    </ul>

                    <strong>129 €</strong>

                    <button type="button" class="btn-small select-part-btn">
                        Sélectionner cette pièce
                    </button>
                </div>
            </article>

            <!-- PIECE 2 -->
            <article class="part-card"
                data-name="Pare-chocs avant Peugeot 208"
                data-category="carrosserie"
                data-brand="peugeot"
                data-reference="PC-PEU-208-AV"
                data-price="180 €">

                <div class="part-img placeholder-img">Image pièce</div>

                <div class="part-content">
                    <span class="badge">Disponible</span>
                    <h3>Pare-chocs avant Peugeot 208</h3>
                    <p>Compatible Peugeot 208 · À repeindre selon couleur véhicule</p>

                    <ul class="part-details">
                        <li>Catégorie : Carrosserie</li>
                        <li>Référence : PC-PEU-208-AV</li>
                        <li>État : Bon état</li>
                    </ul>

                    <strong>180 €</strong>

                    <button type="button" class="btn-small select-part-btn">
                        Sélectionner cette pièce
                    </button>
                </div>
            </article>

            <!-- PIECE 3 -->
            <article class="part-card"
                data-name="Phare avant droit Citroën C3"
                data-category="optique"
                data-brand="citroen"
                data-reference="PH-CIT-C3-AVD"
                data-price="95 €">

                <div class="part-img placeholder-img">Image pièce</div>

                <div class="part-content">
                    <span class="badge">Disponible</span>
                    <h3>Phare avant droit Citroën C3</h3>
                    <p>Optique avant droit compatible Citroën C3</p>

                    <ul class="part-details">
                        <li>Catégorie : Optique</li>
                        <li>Référence : PH-CIT-C3-AVD</li>
                        <li>État : Occasion</li>
                    </ul>

                    <strong>95 €</strong>

                    <button type="button" class="btn-small select-part-btn">
                        Sélectionner cette pièce
                    </button>
                </div>
            </article>

            <!-- PIECE 4 -->
            <article class="part-card"
                data-name="Disques de frein Volkswagen Golf"
                data-category="freinage"
                data-brand="volkswagen"
                data-reference="FR-VW-GOLF-DISQ"
                data-price="75 €">

                <div class="part-img placeholder-img">Image pièce</div>

                <div class="part-content">
                    <span class="badge">Disponible</span>
                    <h3>Disques de frein Volkswagen Golf</h3>
                    <p>Jeu de disques de frein avant pour Volkswagen Golf</p>

                    <ul class="part-details">
                        <li>Catégorie : Freinage</li>
                        <li>Référence : FR-VW-GOLF-DISQ</li>
                        <li>État : Neuf</li>
                    </ul>

                    <strong>75 €</strong>

                    <button type="button" class="btn-small select-part-btn">
                        Sélectionner cette pièce
                    </button>
                </div>
            </article>

            <!-- PIECE 5 -->
            <article class="part-card"
                data-name="Rétroviseur gauche Mercedes Classe A"
                data-category="carrosserie"
                data-brand="mercedes"
                data-reference="RET-MER-CLA-G"
                data-price="145 €">

                <div class="part-img placeholder-img">Image pièce</div>

                <div class="part-content">
                    <span class="badge">Disponible</span>
                    <h3>Rétroviseur gauche Mercedes Classe A</h3>
                    <p>Rétroviseur extérieur gauche électrique</p>

                    <ul class="part-details">
                        <li>Catégorie : Carrosserie</li>
                        <li>Référence : RET-MER-CLA-G</li>
                        <li>État : Très bon état</li>
                    </ul>

                    <strong>145 €</strong>

                    <button type="button" class="btn-small select-part-btn">
                        Sélectionner cette pièce
                    </button>
                </div>
            </article>

            <!-- PIECE 6 -->
            <article class="part-card"
                data-name="Siège avant Renault Mégane"
                data-category="interieur"
                data-brand="renault"
                data-reference="SIE-REN-MEG-AV"
                data-price="210 €">

                <div class="part-img placeholder-img">Image pièce</div>

                <div class="part-content">
                    <span class="badge">Disponible</span>
                    <h3>Siège avant Renault Mégane</h3>
                    <p>Siège avant conducteur pour Renault Mégane</p>

                    <ul class="part-details">
                        <li>Catégorie : Intérieur</li>
                        <li>Référence : SIE-REN-MEG-AV</li>
                        <li>État : Occasion contrôlée</li>
                    </ul>

                    <strong>210 €</strong>

                    <button type="button" class="btn-small select-part-btn">
                        Sélectionner cette pièce
                    </button>
                </div>
            </article>

        </div>
    </section>

    <!-- FORMULAIRE DEMANDE PIECE -->
    <section id="formulaire-piece" class="section">
        <div class="section-title">
            <p>Demande de pièce</p>
            <h2>Envoyer une demande au garage</h2>
        </div>

        <form class="form part-request-form">

            <div class="selected-part-box" id="selectedPartBox">
                <p>Aucune pièce sélectionnée pour le moment.</p>
            </div>

            <input type="hidden" name="piece_selectionnee" id="pieceSelectionnee">

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