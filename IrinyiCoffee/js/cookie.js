/* jshint esversion: 6 */
document.addEventListener("DOMContentLoaded", () => {
    const cookieContainer = document.querySelector(".cookie-container");
    const acceptButton = document.querySelector(".btn");
    acceptButton.addEventListener("click", () => {
        cookieContainer.classList.remove("active");
        setCookie("cookieBannerDisplayed", "true", 30);
    });

    setTimeout(() => {
        if (!getCookie("cookieBannerDisplayed")) {
            cookieContainer.classList.add("active");
        }
    }, 1500);
});

function setCookie(name, value, expirationDays) {
    const date = new Date();
    date.setTime(date.getTime() + (expirationDays * 24 * 60 * 60 * 1000));
    const expires = "expires=" + date.toUTCString();
    document.cookie = name + "=" + value + ";" + expires + ";path=/";
}

function getCookie(name) {
    const decodedCookie = decodeURIComponent(document.cookie);
    const cookies = decodedCookie.split(";");
    for (let i = 0; i < cookies.length; i++) {
        let cookie = cookies[i];
        while (cookie.charAt(0) === " ") {
            cookie = cookie.substring(1);
        }
        if (cookie.indexOf(name) === 0) {
            return cookie.substring(name.length + 1, cookie.length);
        }
    }
    return "";
}