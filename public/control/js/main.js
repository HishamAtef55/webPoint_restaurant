/*
  ====================
  == Start Asidebar ==
  ====================
*/

$("#sidebar").hcOffcanvasNav({
    navTitle: "Settings",
    removeOriginalNav: true,
    customToggle: ".sidebar-btn"
});

$(".sidebar-btn").on("click", function(e) {
    e.preventDefault();
    console.log("hi");
});

/*
  ===================
  == End  Asidebar ==
  ===================
*/

/*
    ===================
    == Start Global  ==
    ===================
*/

/* ============ Start Inputs Effect ============= */
$(document).on("focus", ".form-element .mycustom-input", function() {
    if (
        $(this)
            .val()
            .trim() === ""
    ) {
        $(this)
            .prev("label")
            .addClass("focused");
        $(this)
            .next("span")
            .addClass("fill");
    }
});

$(document).on("blur", ".form-element .mycustom-input", function() {
    if (
        $(this)
            .val()
            .trim() === ""
    ) {
        $(this).val("");
        $(this)
            .prev("label")
            .removeClass("focused");
        $(this)
            .next("span")
            .removeClass("fill");
    }
});
/* ============ End Inputs Effect ============= */

/* ========= Start SelectBax ========= */
$("select.form-element-field").on("change", function() {
    $(this)
        .siblings(".form-element-label")
        .css("color", "#337ab7");
    $(this)
        .siblings(".under_line")
        .css({
            width: "100%",
            bottom: "-10px"
        });
});

$("select.chosen").on("change", function() {
    $(this)
        .siblings(".form-element-label")
        .css("color", "#337ab7")
        .animate(
            {
                top: "-10px",
                "font-size": "14px"
            },
            200
        );
    $(this)
        .siblings(".under_line")
        .css({
            width: "100%",
            bottom: "0px"
        });
});
/* ========= End SelectBax ========= */

/* Upload Image */
function resetImage(input) {
    var receiver =
        input.nextElementSibling.nextElementSibling.firstElementChild;
    input.value = "";
    input.onchange();
    input.setAttribute("title", "");
    receiver.style.backgroundImage = "none";
}

function readImage(input) {
    var receiver =
        input.nextElementSibling.nextElementSibling.firstElementChild;
    if (input.files && input.files[0]) {
        input.setAttribute("title", input.value.replace(/^.*[\\/]/, ""));
        var reader = new FileReader();
        reader.onload = function(e) {
            receiver.style.backgroundImage = "url(" + e.target.result + ")";
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ======= Start Add Items Page ============= */

$("#table-price").on("change", function() {
    /* When Write in Table Price Write in Other */
    $("#take-away-price")
        .focus()
        .val($(this).val())
        .blur();
    $("#dellvery-price")
        .focus()
        .val($(this).val())
        .blur();
    $("#extra-price")
        .focus()
        .val($(this).val())
        .blur();
});

$("#item-name").on("change", function() {
    /* When Write in Item Name Write in Other */
    $("#item-chick-name")
        .focus()
        .val($(this).val())
        .blur();
    $("#item-slep-name")
        .focus()
        .val($(this).val())
        .blur();
});

/* ======= End Add Items Page ============= */

/* ======= Start Add Items Details Page ============= */
$(".mycustom-input-section").on("blur", function() {
    let sections = Array.from(
        $("#table-section tbody").find("td:nth-child(4)")
    );
    let secName = [];
    let maxArra = [];
    let myValue = $(this).val();
    let maxInput = $(".mycustom-input-max");

    sections.forEach(ele => {
        secName.push(ele.textContent);
        maxArra.push(ele.getAttribute("max"));
    });

    if (secName.indexOf(myValue) > -1) {
        maxInput.attr("disabled", "disabled");
        maxInput.val(maxArra[secName.indexOf(myValue)]);
        maxInput.prev("label").addClass("focused");
    } else {
        maxInput.removeAttr("disabled");
    }

    console.log(maxArra);
});
/* ======= End Add Items Details Page ============= */

/*
    =================
    == End Global  ==
    =================
*/

$(".search").on("click", "li", function() {
    let text = $(this).text();
    $(this)
        .parent()
        .siblings("input")
        .val(text);
    $(this)
        .parent()
        .hide();
});

/*
    ============================
    == Start Add Tables Page  ==
    ============================
*/

$(".btn-toggle-form").on("click", function() {
    $(this).toggleClass("active");

    $(this)
        .children("i")
        .toggleClass("fa-spin");

    $(".tables .form-tables").toggleClass("open");
});

// Add Hole Class To Tabs For Create Tables On It
$(".nav-tabs a").click(function() {
    var href = $(this)
        .tab()
        .attr("href"); // For Select Id Tabs Content
    $(href)
        .addClass("hole")
        .siblings()
        .removeClass("hole");
});

$("body").on("click", ".new", function(e) {
    e.stopPropagation();
    $("#table_delete")
        .children(".table-menu")
        .fadeOut("fast");
    $("#table_delete").attr("id", "");
    $(this).attr("id", "table_delete");
    $(this)
        .children(".table-menu")
        .fadeIn();
});

$("body").on("click", function() {
    $("#table_delete").attr("id", "");
    $(".tables .table-menu").fadeOut("fast");
});

$(document).on("change", "#select", function() {
    // $('section.tables').find('.nav-link').first().click();

    setTimeout(function() {
        $("section.tables")
            .find(".nav-link")
            .first()
            .click();
    }, 400);
});

/*
    ==========================
    == End Add Tables Page  ==
    ==========================
*/
