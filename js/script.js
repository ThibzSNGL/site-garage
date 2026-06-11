const burgerBtn = document.getElementById("burgerBtn");
const navLinks = document.getElementById("navLinks");
const menuLinks = document.querySelectorAll(".nav-links a");

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

console.log("Site Garage chargé avec succès.");