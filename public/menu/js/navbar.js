$("document").ready(function() {
    let subMenuNav = $(".sub-menu"); // For Sub Menu Navbar (Drink, Pizza)

    const months = [
        "Jan",
        "Feb",
        "Mar",
        "Apr",
        "May",
        "Jun",
        "Jul",
        "Aug",
        "Sep",
        "Oct",
        "Nov",
        "Dec"
    ];

    var date = new Date(),
        year = date.getFullYear(),
        day = ("0" + date.getDate()).slice(-2),
        month = months[date.getMonth()];

    $(".date-time .date").text(`${day} / ${month} / ${year}`);

    function startTime() {
        var today = new Date(),
            hour = today.getHours(),
            minutes = today.getMinutes(),
            seconds = today.getSeconds(),
            session = "AM";

        if (hour == 0) {
            hour = 12;
        }

        if (hour > 12) {
            hour = hour - 12;
            session = "PM";
        }
        minutes = checkTime(minutes);
        seconds = checkTime(seconds);
        $(".date-time .time").text(`${hour} : ${minutes}  ${session}`);
        setTimeout(startTime, 500);
    }
    function checkTime(i) {
        if (i < 10) {
            i = "0" + i;
        } // add zero in front of numbers < 10
        return i;
    }

    startTime();

    // When Click On Option Button In Navbar
    $(".navbar-nav li[data-menu]").on("click", function(e) {
        e.stopPropagation();

        let dataBtn = $(this).data("menu");
        $(`#${dataBtn}`)
            .addClass("slideDown")
            .siblings()
            .removeClass("slideDown");
        if ($(`#${dataBtn}`).hasClass("slideDown")) {
            subMenuNav.addClass("down");
        } else {
            subMenuNav.removeClass("down");
        }
    });

    let operation = $("#operation").attr("value");
    let takeAway = $(".navbar-nav li[data-menu='takeaway-menu']");
    let Delivery = $(".navbar-nav li[data-menu='delivery-menu']");

    // ================== Start Delivery NavBar =====================
    if (operation === "Delivery" || window.location.href.indexOf("Delivery") > -1) {
        Delivery.click();
        $(".nav-delivery a").addClass("active");
        if (operation === "Delivery") {
            $("#delivery-menu .newOrder").addClass("active");
        }
        if(window.location.href.indexOf("Delivery_Order") > -1) {
            $("#delivery-menu .deliveryOrder").addClass("active");
        }
        if(window.location.href.indexOf("to_pilot") > -1) {
            $("#delivery-menu .toPilot").addClass("active");
        }
        if(window.location.href.indexOf("pilot_account") > -1) {
            $("#delivery-menu .pilot-acc").addClass("active");
        }
        if(window.location.href.indexOf("Delivery_holding_list") > -1) {
            $("#delivery-menu .holding-list-btn").addClass("active");
        }
    }
    // ================== End Delivery NavBar =====================

    if (
        window.location.href.indexOf("home") > -1 ||
        window.location.href.indexOf("Show_Table") > -1
    ) {
        $(".dienIn a").addClass("active");
    }

    // ================== Start TOGO NavBar =====================
    if (operation === "TO_GO" || window.location.href.indexOf("TO_GO") > -1) {
        takeAway.click();
        $(".nav-togo a").addClass("active");
        $("#takeaway-menu .newOrder").addClass("active");
    }
    if (window.location.href.indexOf("TOGO_holding_list") > -1) {
        takeAway.click();
        $(".nav-togo a").addClass("active");
        $("#takeaway-menu .holdingList").addClass("active");
    }
    if (window.location.href.indexOf("TOGO_Order") > -1) {
        takeAway.click();
        $(".nav-togo a").addClass("active");
        $("#takeaway-menu .togoOrder").addClass("active");
    }
    // ================== End TOGO NavBar =====================
});
