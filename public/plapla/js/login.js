$("document").ready(function () {
    setTimeout(function () {
        $("#email").val("");
        $("#password").val("");
    }, 500);

    // $(".main div").on("click", function() {
    //     $(".container-main").addClass("hide");
    //     $(".container-login").addClass("show");

    //     let department = $(this).data("department");

    //     $("section.login").attr("department", department);
    // });

    // ------- Close Icons When Click ON Section Div
    $(".menu-item").on("click", function () {
        $(this).parents(".welcome-page").removeClass("open");
        let sectionVal = $(this).find("span").text();
        let type = $(this).attr("data-value");
        $(".login-section").text(type);
        $("#login_method").val(type);
        setTimeout(() => {
            $("section.login").removeClass("close");
        }, 400);
    });
    // ------- Back Login Section To Choose Login Method
    $(".back-btn").on("click", function () {
        $(".login-section").text("");
        $(this).parents("section.login").addClass("close");
        setTimeout(() => {
            $(".welcome-page").addClass("open");
        }, 400);
    });

    $(".key-numbers span").on("click", function () {
        let passParent = $(".star-pass");
        let NumberKey = $(this).data("number");

        typePassword(NumberKey, passParent);
        fitSize(passParent);
        getPassword();
    });

    $(document).on("keyup", function (e) {
        $(".email-select").blur();
        $(`.key-numbers span[data-number="${e.key}"]`).click();
        if (e.key === "Backspace" || e.key === "Escape") {
            $(`.key-numbers span[data-number="c"]`).click();
        }
        if (e.key === "Enter") {
            $(".login-btn").click();
        }
    });

    function fitSize(parent) {
        let starLength = Array.from($(".star-pass span"));

        if (starLength.length > 13) {
            parent.css("font-size", parent.css("font-size") + 3);
        } else {
            parent.css("font-size", 96 - starLength.length * 5);
        }
    }

    function typePassword(number, parent) {
        if (number !== "c") {
            if (parent.children().length < 15) {
                let star = $(`<span data-value='${number}'>*</span>`);
                star.appendTo(parent);
            }
        } else {
            $("#password").val("");
            parent.children().remove();
        }
    }

    function getPassword() {
        let passArray = [];
        let starValue = Array.from($(".star-pass span"));

        starValue.forEach((star) => {
            passArray.push(star.dataset.value);
        });
        $("#password").val(passArray.join(""));
    }

    // $('button[type="submit"]').on('click', function() {
    //     console.log($('#password').val())
    // });

    // $("#login_logo").on("click", function() {
    //     console.log("hi");
    //     $("#password").focus();
    // });
});
