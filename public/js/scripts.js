// Check that the script is loaded
console.log("scripts.js loaded successfully!");

// Toggle menu visibility
function toggleMenu() {
    const nav = document.querySelector("nav");
    nav.classList.toggle("active");
}

document.addEventListener("DOMContentLoaded", () => {
    const menuToggle = document.querySelector("#menuToggle");
    if (menuToggle) {
        menuToggle.addEventListener("click", toggleMenu);
    }
});
