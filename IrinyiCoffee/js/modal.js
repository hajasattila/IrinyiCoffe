/* jshint esversion: 6 */
// Get the modal
var modal = document.getElementById("myModal");
var regimodal = document.getElementById("registration")

// Get the buttons that opens the modal
var btn1 = document.getElementById("loginButton");
var btn2 = document.getElementById("loginButton1");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal with animation
btn1.onclick = btn2.onclick = function () {
    modal.style.display = "block";
    modal.classList.add("fade-in");
}

// When the user clicks on <span> (x), close the modal with animation
span.onclick = function () {
    modal.classList.remove("fade-in");
    modal.classList.add("fade-out");
    setTimeout(function () {
        modal.style.display = "none";
        modal.classList.remove("fade-out");
    }, 500); // adjust duration of animation (in ms) as needed
}

// When the user clicks anywhere outside of the modal, close it with animation
window.onclick = function (event) {
    if (event.target == modal || event.target == regimodal) {
        modal.classList.remove("fade-in");
        modal.classList.add("fade-out");
        setTimeout(function () {
            modal.style.display = "none";
            modal.classList.remove("fade-out");
        }, 500); // adjust duration of animation (in ms) as needed
    }
}
