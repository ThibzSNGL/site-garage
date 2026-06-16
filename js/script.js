const burgerBtn = document.getElementById("burgerBtn");
const navLinks = document.getElementById("navLinks");
const menuLinks = document.querySelectorAll(".nav-links a");

if (burgerBtn && navLinks) {
    burgerBtn.addEventListener("click", () => {
        burgerBtn.classList.toggle("active");
        navLinks.classList.toggle("active");
    });

    menuLinks.forEach((link) => {
        link.addEventListener("click", () => {
            burgerBtn.classList.remove("active");
            navLinks.classList.remove("active");
        });
    });
}

/* FILTRE DES PIECES */

const pieceSearch = document.getElementById("pieceSearch");
const pieceCategory = document.getElementById("pieceCategory");
const pieceBrand = document.getElementById("pieceBrand");
const partCards = document.querySelectorAll(".part-card");

function filterParts() {
    if (!pieceSearch || !pieceCategory || !pieceBrand || partCards.length === 0) {
        return;
    }

    const searchValue = pieceSearch.value.toLowerCase().trim();
    const categoryValue = pieceCategory.value;
    const brandValue = pieceBrand.value;

    partCards.forEach((card) => {
        const name = card.dataset.name.toLowerCase();
        const category = card.dataset.category;
        const brand = card.dataset.brand;
        const reference = card.dataset.reference.toLowerCase();

        const matchesSearch =
            name.includes(searchValue) ||
            reference.includes(searchValue);

        const matchesCategory =
            categoryValue === "all" || category === categoryValue;

        const matchesBrand =
            brandValue === "all" || brand === brandValue;

        if (matchesSearch && matchesCategory && matchesBrand) {
            card.classList.remove("hidden");
        } else {
            card.classList.add("hidden");
        }
    });
}

if (pieceSearch && pieceCategory && pieceBrand) {
    pieceSearch.addEventListener("input", filterParts);
    pieceCategory.addEventListener("change", filterParts);
    pieceBrand.addEventListener("change", filterParts);
}

/* SELECTION D'UNE PIECE */

const selectPartButtons = document.querySelectorAll(".select-part-btn");
const selectedPartBox = document.getElementById("selectedPartBox");
const idPiece = document.getElementById("idPiece");
const pieceRecherchee = document.getElementById("pieceRecherchee");
const referencePiece = document.getElementById("referencePiece");
const messagePiece = document.getElementById("messagePiece");

selectPartButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const card = button.closest(".part-card");

        const name = card.dataset.name;
        const reference = card.dataset.reference;
        const price = card.dataset.price;

        const id = card.dataset.id;

        if (idPiece) {
            idPiece.value = id;
        }

        if (pieceRecherchee) {
            pieceRecherchee.value = name;
        }

        if (referencePiece) {
            referencePiece.value = reference;
        }

        if (messagePiece) {
            messagePiece.value = `Bonjour, je suis intéressé par la pièce suivante : ${name}, référence ${reference}, affichée au prix de ${price}. Merci de me recontacter pour confirmer la disponibilité.`;
        }

        if (selectedPartBox) {
            selectedPartBox.classList.add("active");
            selectedPartBox.innerHTML = `
                <p>
                    Pièce sélectionnée :
                    <strong>${name}</strong><br>
                    Référence : ${reference}<br>
                    Prix affiché : ${price}
                </p>
            `;
        }

        const formSection = document.getElementById("formulaire-piece");

        if (formSection) {
            formSection.scrollIntoView({
                behavior: "smooth"
            });
        }
    });
});

console.log("Site Garage chargé avec succès.");

/* SELECTION D'UNE FORMULE DE NETTOYAGE */

const selectCleaningButtons = document.querySelectorAll(".select-cleaning-btn");
const idServiceNettoyage = document.getElementById("idServiceNettoyage");

selectCleaningButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const serviceId = button.dataset.id;

        if (idServiceNettoyage) {
            idServiceNettoyage.value = serviceId;
        }
    });
});