const body = document.querySelector("body");
const big_wrapper = document.querySelector(".big-wrapper");
const overlay = document.querySelector(".overlay");
const navlinks = document.querySelectorAll(".navlinks li");


// Removes the hamburger menu when clicked on empty space
function toggleOverlay() {
    big_wrapper.classList.toggle("active");
}

overlay.addEventListener("click", toggleOverlay);


// Enables or disables the hamburger menu in mobile view
function toggleAnimation() {
    big_wrapper.classList.toggle("active");
}


function updateSelection() {
    navlinks.forEach((navlink) => navlink.classList.remove("active"));
    this.classList.add("active");
}


navlinks.forEach((navlink) => navlink.addEventListener("click", updateSelection));