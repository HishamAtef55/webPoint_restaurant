"use strict";

$(".nav-item").on("click", function (e) {
    e.stopPropagation();
    let menuTarget = $(this).attr("data-target");

    $(this).toggleClass("active").siblings().removeClass("active");

    $(`.sub-menu.${menuTarget}`)
        .toggleClass("open")
        .siblings(".sub-menu")
        .removeClass("open");
});

$(document).on("click", function () {
    $(".search-result").html("");
    $(`.sub-menu`).removeClass("open");
    $(".nav-item").removeClass("active");
});
