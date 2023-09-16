"use strict";
// Show & Hide SideBar
const menuToggleBtn = document.querySelector(".toggle-menu");
const sideBar = document.querySelector("aside");

menuToggleBtn.onclick = function () {
    sideBar.classList.toggle("show");
};
document.getElementById("close_menu").onclick = function () {
    sideBar.classList.remove("show");
};
// Show & Hide SideBar

$(document).on("click", function () {
    $(".search-result").html("");
});
