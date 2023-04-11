/* jshint esversion: 6 */
const toggleNav = document.getElementById('toggle-nav');
const nav = document.querySelector('nav');
const navLinks = document.querySelectorAll('nav ul li a');

/* Ha ugyan azon az oldalon maradunk bezárja a hamburger boxot */
navLinks.forEach(link => {
    link.addEventListener('click', function () {
        toggleNav.checked = false;
    });
});

/* Ha kikattintunk bezárja */
document.addEventListener('click', function (event) {
    if (!nav.contains(event.target) && toggleNav.checked) {
        toggleNav.checked = false;
    }
});

window.addEventListener('load', function () {
    var rowContent = document.querySelector('.anim');
    rowContent.classList.add('anim--animate');
});


