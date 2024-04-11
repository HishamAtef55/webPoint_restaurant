$("document").ready(function() {
    /*  ===========================
        ===== Start Menu Page =====
        ===========================
    */
    $(".menu .sub-menu .nav-sub")
        .find(".nav-item")
        .first()
        .find(".nav-link")
        .click();
    $(".menu")
        .find(".sub-tab .tab-pane")
        .first()
        .addClass("hole show active");

    $("#report-filter").modal("show");
    let minChrageMenu = $("#min_charge_menu").attr("value");

    if (minChrageMenu > 0 && $(".item-parent").length == 0) {
        $("#min_charge_modal_menu").modal("show");
        $("#min_charge_modal_menu #minchrage-input")
            .attr("disabled", "disabled")
            .val(minChrageMenu);
    }

    let number_div = 0;

    /* Variabels For Calc Check Height */
    let windowHeight = $(window),
        navbarHight = $("nav.navbar"),
        customerHeight = $(".table-customer"),
        infoHeight = $(".table-info"),
        checkFooterHeight = $(".footer-check"),
        calculatorHeight = $(".calculator"),
        footerHeight = $("footer");

    let clicking = false, // For MouseDown On Item
        clickCalculator = false, // For MouseDown On Calculator
        left = null, // For Item Position
        discountLeft = null, // For Item Position
        startPositionItem = null, // For Position Item
        startPositionCheck = null; // For Position Check

    let quantInput = $(".calculator form .calc-header input"); // For Input Quant From Calculator

    // Get Device Number From LocalStorage
    $("#device_id").val(localStorage.getItem("device_number"));

    // Function To Get Title And Price From Card
    function getInfoCard(card) {
        let title = card.find("h5").text(),
            price = parseFloat(card.find(".price").text());

        return { title, price };
    }

    function createMenuItem(item) {
        let menu = $(`<div class='item-menu'> </div>`),
            comment = $(
                `<button class='btn' id="comment" id_order="${number_div}" data-toggle="modal" data-target="#item-menu-model" data-model="comment"> <i class='fas fa-comment-dots'></i> <input type="hidden" value="" class="comment_content"> Comment </button>`
            ),
            extra = $(
                `<button class='btn'  id_order="${number_div}" data-toggle="modal" data-target="#item-menu-model" data-model="extra"> <i class='fas fa-plus-square'></i> Extra </button>`
            ),
            without = $(
                `<button class='btn' id="without" id_order="${number_div}" data-toggle="modal" data-target="#item-menu-model" data-model="without"> <i class='fas fa-minus-square'></i> Without </button>`
            );
        discount = $(
            `<button class='btn' id_order="${number_div}" data-toggle="modal" data-target="#item-menu-model" data-model="discount"> <i class='fas fa-tags'></i> Discount </button>`
        );

        menu.prependTo(item);
        comment.appendTo(menu);
        extra.appendTo(menu);
        without.appendTo(menu);
        let checkPerDiscount = $("#checkPerDiscount").attr("value");
        if (checkPerDiscount == "discount") {
            discount.appendTo(menu);
        }
    }

    // Funtion To Get Quant From Number Keyboard
    function getQuantNumber() {
        if (quantInput.val() == "") {
            return 1;
        } else {
            return quantInput.val();
        }
    }

    // Function To Create New Item In My check
    function createItem(element) {
        if ($(".item-parent").length) {
            number_div = $(".item-parent")
                .last()
                .attr("item_id");
            number_div++;
        } else {
            number_div = 1;
        }

        let parent = $(
                `<div class='item-parent' item_id='${number_div}' item='${element.attr(
                    "value"
                )}'> </div>`
            ),
            item = $(`<div class='table-item'> </div>`),
            title = $(
                `<div class='product-name'> ${
                    getInfoCard(element).title
                } </div>`
            ),
            quant = $(
                `<div> <input type='number' value='${getQuantNumber()}' min='1' step="0.1" class='num' disabled /> </div>`
            ),
            cach = $(`<div class='price'>${getInfoCard(element).price}</div>`),
            total = quant.children("input").val() * getInfoCard(element).price,
            colTotal = $(
                `<div class='total'> <input type='number' value='${total}' min='1' disabled class='total-input' /> </div>`
            ),
            trashButton = $(
                `<button class='btn btn-danger trash' id_order='${number_div}'> <i class='fas fa-trash-alt text-white'></i> </button>`
            );

        parent.attr("value", total);
        parent.appendTo(".table-body");
        item.appendTo(parent);
        title.appendTo(item);
        quant.appendTo(item);
        cach.appendTo(item);
        colTotal.appendTo(item);
        trashButton.appendTo(item);

        createMenuItem(item);

        quantInput.val("");
    }

    // Function To Create Modal Detials
    function createModal(card) {
        let sectionNo = Array.from(card.find(".details_info"));
        let sectionArr = [];
        let maxArr = [];
        let idDetailsArr = [];
        let nameDetailsArr = [];
        let priceArr = [];
        let tabs = $("#details-model .nav-tabs");
        let section = $("#details-model .tab-content");

        tabs.empty();
        section.empty();

        sectionNo.forEach(secNo => {
            let attrSec = secNo.getAttribute("section");
            let max = secNo.getAttribute("max");
            let idDetails = secNo.getAttribute("id_detail");
            let namDetails = secNo.getAttribute("name");
            let priceDetails = secNo.getAttribute("price");
            if (sectionArr.indexOf(attrSec) == -1) {
                sectionArr.push(attrSec);
            }
            maxArr.push(max);
            idDetailsArr.push(idDetails);
            nameDetailsArr.push(namDetails);
            priceArr.push(priceDetails);
        });

        for (let i = 0; i < sectionArr.length; i++) {
            let area;
            let link;

            link = $(
                `<a class="nav-item nav-link" data-toggle="tab" href="#sec${sectionArr[i]}">${sectionArr[i]}</a>`
            );
            area = $(
                `<div class="tab-pane fade" id="sec${sectionArr[i]}"></div>`
            );
            let parentArea = $(
                `<section class='d-flex align-items-center flex-wrap'></section>`
            );

            for (let x = 0; x < sectionNo.length; x++) {
                if (sectionNo[x].getAttribute("section") == sectionArr[i]) {
                    let formGroup = $(`<div class='form-group'></div>`);
                    let checkInput = $(
                        `<input type="checkbox" name="section${sectionArr[i]}" id_detail="${idDetailsArr[x]}" id="details_${sectionArr[i]}_${idDetailsArr[x]}" max_="${maxArr[x]}"/>`
                    );
                    let checkLabel = $(
                        `<label for="details_${sectionArr[i]}_${idDetailsArr[x]}"><span>${nameDetailsArr[x]}</span> &nbsp; | &nbsp; <span>${priceArr[x]}</span></label>`
                    );

                    checkInput.appendTo(formGroup);
                    checkLabel.appendTo(formGroup);
                    formGroup.appendTo(parentArea);
                }
            }
            link.appendTo(tabs);
            area.appendTo(section);
            parentArea.appendTo(area);

            area.attr("max", area.find('input[type="checkbox"]').attr("max_"));
        }
    }

    // When Click on Crad Handel Create Item Function
    // $("body").on("dblclick", ".card", function() {
    //     clicking = false; // For MouseDown On Item
    //     if ($(this).attr("details_no") > 0) {
    //         createItem($(this));
    //         $(this).attr("id", "card_chose");
    //         $("#details-model").modal("show");
    //     } else {
    //         createItem($(this));
    //     }
    //     createModal($(this));
    // });

    function handelCardClick(card) {
        clicking = false; // For MouseDown On Item
        if (card.attr("details_no") > 0) {
            createItem(card);
            card.attr("id", "card_chose");
            $("#details-model").modal("show");
        } else {
            createItem(card);
        }
        createModal(card);
    }

    window.handelCardClick = handelCardClick;


    $("body").on("click", "#details-model .nav-link", function() {
        let href = $(this).attr("href");
        $(`${href}`)
            .siblings()
            .find('input[type="checkbox"]:checked')
            .prop("checked", false);
    });

    // Function To Sum All Extra Price In Item
    function CalcExtraPrice(priceExtra) {
        let extraSumPrice = 0;
        priceExtra.forEach(element => {
            extraSumPrice += parseFloat(element.value);
        });

        return extraSumPrice;
    }

    // When Remove Item From Check
    $("body").on("click", ".table-item .trash", function(e) {
        e.stopPropagation();

        $(this)
            .parents(".item-parent")
            .addClass("fall")
            .fadeOut(400, function() {
                $(this).remove();
            });
    });

    // When Remove Item From Check
    $("body").on("click", ".discount-item .trash", function(e) {
        e.stopPropagation();

        $(this)
            .parents(".discount-item")
            .addClass("fall")
            .fadeOut(400, function() {
                $(this).remove();
            });
    });

    /* ===== Start Discount Item In Discount List ===== */

    // When MouseDown On Item In Discount List
    $("body").on("mousedown touchstart", ".discount-item", function(e) {
        e.stopPropagation();
        clicking = true;
        let position = e.clientX ? e.clientX : e.touches[0].clientX;
        startPositionItem = position;

        let itemLeft = parseInt($(this).position().left);

        if (itemLeft == 39) {
            discountLeft = 0;
        } else if (itemLeft == 0) {
            discountLeft = -40;
        } else if (itemLeft == 84) {
            discountLeft = 45;
        }
    });

    // When Move On Item From Discount List
    $("body").on("mousemove touchmove", ".discount-item", function(e) {
        e.stopPropagation();
        if (clicking === false) return;

        let position = e.clientX ? e.clientX : e.touches[0].clientX;
        let endPosition = startPositionItem - position;

        if (discountLeft == 0 && endPosition > 60) {
            $(this)
                .addClass("right active")
                .siblings()
                .removeClass("left right active");
        }

        if (discountLeft == 0 && endPosition < -60) {
            $(this)
                .addClass("left active")
                .siblings()
                .removeClass("left right active");
        }

        if (discountLeft == 45 && endPosition > 10) {
            $(this).removeClass("left right active");
        }

        if (discountLeft == -40 && endPosition < 0) {
            $(this).removeClass("left right active");
        }
    });
    /* ===== End Discount Item In Discount List ===== */

    /* ===== Start Item Parent ===== */
    $("body").on("mousedown touchstart", function(e) {
        e.stopPropagation();
        $(".item-parent").removeClass("left right active");
    });
    // When MouseDown On Item In Check
    $("body").on("mousedown touchstart", ".item-parent", function(e) {
        e.stopPropagation();
        clicking = true;
        let position = e.clientX ? e.clientX : e.touches[0].clientX;
        startPositionItem = position;

        $(this).css("cursor", "grabbing");

        let itemLeft = parseInt($(this).position().left);

        if (itemLeft == 0) {
            left = 0;
        } else if (itemLeft > 250) {
            left = 260;
        } else if (itemLeft < -50) {
            left = -60;
        }
    });

    // When Move On Item From Check
    $("body").on("mousemove touchmove", ".item-parent", function(e) {
        e.stopPropagation();
        if (clicking === false) return;

        let position = e.clientX ? e.clientX : e.touches[0].clientX;
        let endPosition = startPositionItem - position;

        if (left == 0 && endPosition > 60) {
            $(this)
                .addClass("right active")
                .siblings()
                .removeClass("left right active");
        }

        if (left == 0 && endPosition < -60) {
            $(this)
                .addClass("left active")
                .siblings()
                .removeClass("left right active");
        }

        if (left == 260 && endPosition > 10) {
            $(this).removeClass("left right active");
        }

        if (left == -60 && endPosition < 0) {
            $(this).removeClass("left right active");
        }
    });
    /* ===== End Item Parent ===== */

    /* ===== Start Small Calculator ===== */
    // When MouseDown On Small Calculator (Small Screen)
    $("body").on("mousedown touchstart", ".calc-header", function(e) {
        e.stopPropagation();
        clickCalculator = true;

        $(this).css("cursor", "grabbing");
    });

    // When Move Calculator In Screen
    $("body").on("mousemove touchmove", ".calc-header", function(e) {
        e.stopPropagation();
        if (clickCalculator === false) return;

        if (clickCalculator === true) {
            let positionLeft = e.clientX ? e.clientX : e.touches[0].clientX,
                positionTop = e.clientY ? e.clientY : e.touches[0].clientY;

            $(".calculator-small").css({
                left: positionLeft - 50,
                top: positionTop - 20,
                bottom: "auto"
            });
        }
    });
    /* ===== End Small Calculator ===== */
    /* ===== Start Check ===== */
    $(".btn-barcode").on("click", function() {
        $(".barcode").slideToggle();
    });
    // When Mouse Down On Check
    $("body").on("mousedown touchstart", ".check", function(e) {
        clicking = true;
        let position = e.clientX ? e.clientX : e.touches[0].clientX;
        startPositionCheck = position;

        $(this).css("cursor", "grabbing");
    });

    // When Move On Check
    $("body").on("mousemove touchmove", ".check", function(e) {
        if (clicking === false) return;

        let position = e.clientX ? e.clientX : e.touches[0].clientX;
        let endPosition = startPositionCheck - position;

        if (endPosition < -90) {
            $(this).toggleClass("open");
            $("body").toggleClass("check-open");
            $(".item-parent").removeClass("left right active");
        }
    });
    /* ===== End Check ===== */

    // When MouseUp From Body
    $("body").on("mouseup touchend", function() {
        clicking = false;
        clickCalculator = false; // For Calculator Click

        $(".check").css("cursor", "grab");
        $(".item-parent").css("cursor", "grab");
        $(".calc-header").css("cursor", "grab");
    });

    // When Click On Check Button
    $(".check-btn").on("click", function() {
        $(".check").toggleClass("open");
        $("body").toggleClass("check-open");
    });

    /* Start Model Box */
    // Function To Get Title And Price From Card

    $("#item-menu-model").on("show.bs.modal", function(event) {
        var button = $(event.relatedTarget); // Button that triggered the modal
        var recipient = button.data("model"); // Extract info from data-Model

        let send = button.parents(".item-parent").attr("item_id");

        let quantity = button
            .parents(".table-item")
            .find(".num")
            .val();

        var modal = $(this);

        let comentContent = button
            .parents(".item-menu")
            .find(`input.comment_content`);

        modal.find(`#nav-comment textarea`).val(comentContent.val());

        modal.attr("id_order", send);
        modal.attr("quantity", quantity);
        modal.find(`#nav-${recipient}-tab`).addClass("active");
        modal.find(`#nav-${recipient}`).addClass("show active");
        button.parents(".item-parent").removeClass("left active");

        createSelect(quantity, modal.find("#num_quant"));
    });
    $("#pilot").on("show.bs.modal", function(event) {
        let button = $(event.relatedTarget); // Button that triggered the modal
        let order_id = button
            .parents(".box")
            .find(".order_id")
            .text();
        $(this).attr("order_id", order_id);
    });
    // create SelectBox In Box Item Modal
    function createSelect(quant, parent) {
        $("select#num_quant").empty();
        for (i = 1; i <= quant; i++) {
            let option = $(`<option value='${i}'>${i}</option>`);

            if (i == quant) {
                option.attr("selected", "selected");
            }

            option.appendTo(parent);
        }
    }

    $("#item-menu-model").on("hidden.bs.modal", function() {
        var modal = $(this);

        modal.attr("id_order", "");
        modal.find(".nav-link").removeClass("active");
        modal.find(".tab-pane").removeClass("show active");
    });
    /* End Model Box */

    // When Click On Arrow In Footer Check
    $(".Arrow-check").on("click", function() {
        $(this)
            .children(".fas")
            .toggleClass("fa-angle-up fa-angle-down ");
        $(".footer-check").toggleClass("up");
        $(this).toggleClass("up");
    });

    // When Click On Left Arrow From Tabs In Menu
    $(".angle.left").on("click", function() {
        $(".nav-sub").animate(
            {
                scrollLeft: `${$("#pills-tab").scrollLeft() - 100}`
            },
            100
        );
    });

    // When Click On Right Arrow From Tabs In Menu
    $(".angle.right").on("click", function() {
        // $(".nav-sub").animate(
        //     {
        //         scrollLeft: `${$("#pills-tab").scrollLeft() + 100}`
        //     },
        //     50
        // );
        document.getElementById("pills-tab").scrollBy({
            left: 100,
            behavior: "smooth"
        });
    });

    // When Click On Calculator Button
    $(".calc-btn").on("click", function() {
        $(".calculator").toggleClass("up");
        calcCheckHeight();
    });

    // When Click On Calculator Button in Small Screen
    $(".claculator-btn").on("click", function() {
        $(".calculator").toggleClass("up");
        calcCheckHeight();
    });

    $(".exit span").on("click", function(e) {
        e.stopPropagation();
        $(".calculator").removeClass("up");
        calcCheckHeight();
    });

    // Funtion To Make Same Item In Page = The Same Height
    function sameHeightCard(ele) {
        var maxheight = 0;
        ele.each(function() {
            if ($(this).height() > maxheight) {
                maxheight = $(this).height();
            }
        });
        ele.height(maxheight);
    }

    sameHeightCard($(".card .card-image"));
    sameHeightCard($(".card .card-title"));
    sameHeightCard($(".card .card-text"));
    sameHeightCard($(".card"));

    // Funtion To Claculate Height Claculator Section
    function calcCheckHeight() {
        if ($(window).width() <= 991) {
            $(".calculator").addClass("calculator-small"); // Add Class ON Calculator Section In Small Screen

            $(".check").height(
                windowHeight.outerHeight() -
                    (navbarHight.outerHeight() + footerHeight.outerHeight())
            );
            $(".table-body").height(
                windowHeight.outerHeight() -
                    (navbarHight.outerHeight() +
                        customerHeight.outerHeight() +
                        infoHeight.outerHeight() +
                        checkFooterHeight.outerHeight() +
                        footerHeight.outerHeight())
            );
        } else {
            $(".calculator").removeClass("calculator-small"); // Remove Class ON Calculator Section In Small Screen

            $(".check").height(
                windowHeight.outerHeight() -
                    (navbarHight.outerHeight() +
                        calculatorHeight.outerHeight() +
                        footerHeight.outerHeight())
            );
            $(".table-body").height(
                windowHeight.outerHeight() -
                    (navbarHight.outerHeight() +
                        customerHeight.outerHeight() +
                        infoHeight.outerHeight() +
                        checkFooterHeight.outerHeight() +
                        calculatorHeight.outerHeight() +
                        footerHeight.outerHeight())
            );
        }
    }

    calcCheckHeight();

    // When Resize Window Handel Some Functions
    $(window).resize(function() {
        sameHeightCard($(".card .card-image"));
        sameHeightCard($(".card .card-title"));
        sameHeightCard($(".card .card-text"));
        sameHeightCard($(".card"));

        calcCheckHeight();
    });

    // Function to Type Number From Calculator
    function typeNumber(element, num) {
        let numInput = $(".calculator form .calc-header input");

        if (element.hasClass("decimal") && numInput.val().indexOf(".") != -1) {
            // When CLick On '.' Button
            return false;
        }

        if (element.hasClass("float") && numInput.val().indexOf(".") != -1) {
            // When Click On Float Numbers

            numInput.val(num);
        } else {
            numInput.val(numInput.val() + num);
        }

        if (element.hasClass("clear")) {
            // When CLick On 'C' Button
            numInput.val("");
        }
    }

    // handel Calculator Function
    $(".calculator__keys button").on("click", function(e) {
        e.preventDefault();
        typeNumber($(this), $(this).data("num"));
    });

    // Start CheckOut
    $(".price-value").on("input", function() {
        if ($(this).val() < 0) {
            $(this).val(0);
        }
        let summaryPrice = $(this)
            .parents(".tab-pane")
            .find(".summary-price")
            .text();
        let RestPrice = $(this)
            .parents(".tab-pane")
            .find(".price-rest");
        RestPrice.text((parseFloat(summaryPrice) - $(this).val()).toFixed(2));
    });
    $(".input-ser").on("input", function() {
        if ($(this).val() < 0) {
            $(this).val(0);
        }
    });
    /*  =========================
        ===== End Menu Page =====
        =========================
    */

    /*  =============================
        ===== Start Tables Page =====
        =============================
    */
    $("section.tables .table-tabs")
        .find(".nav-link")
        .eq(1)
        .click();
    $("section.tables .table-tabs")
        .find(".tab-pane")
        .eq(1)
        .addClass("hole show active");

    // Add Hole Class To Tabs For Create Tables On It
    $(document).on("click", ".tables #holes a.nav-link", function(e) {
        e.preventDefault();

        var href = $(this)
            .tab()
            .attr("href"); // For Select Id Tabs Content

        $(href)
            .addClass("hole")
            .siblings()
            .removeClass("hole");
    });

    // When Click On Table Body Show All Menus
    $(document).on("click", ".table-body", function(e) {
        e.stopPropagation();

        $(this)
            .parents(".table")
            .addClass("focused");

        if (
            $(this)
                .parents(".table")
                .hasClass("busy")
        ) {
            $(this)
                .parents(".table")
                .find(".occupy span")
                .text("Empty");

            $(this)
                .parents(".table")
                .find(".occupy i")
                .addClass("fa-unlock-alt")
                .removeClass("fa-lock");
        } else {
            $(this)
                .parents(".table")
                .find(".occupy span")
                .text("Occupy");

            $(this)
                .parents(".table")
                .find(".occupy i")
                .addClass("fa-lock")
                .removeClass("fa-unlock-alt");
        }

        $("body").addClass("blur");
    });

    //  When Click On Reservation Icon Show List Reservation
    $(document).on("click", ".reservation-time", function(e) {
        e.stopPropagation();

        let resevationPop = $(this)
            .parents(".table")
            .find(".resrvation-menu");

        new Popper($(this), resevationPop, {
            placement: "auto",
            modifiers: [
                {
                    name: "offset", //offsets popper from the reference/button
                    options: {
                        offset: [0, 8]
                    }
                },
                {
                    name: "flip", //flips popper with allowed placements
                    options: {
                        allowedAutoPlacements: [
                            "right",
                            "left",
                            "top",
                            "bottom"
                        ],
                        rootBoundary: "viewport"
                    }
                }
            ]
        });

        $(this)
            .parents(".table")
            .addClass("focused");

        resevationPop.fadeIn();

        $("body").addClass("blur");
    });

    // When CLick On Body Remove All Classes Effecting
    $(document).on("click", function() {
        $(".focused").removeClass("focused");

        $(".table-info").fadeOut();

        $(".table-menu")
            .slideUp()
            .removeClass("open");

        $("body").removeClass("blur");

        $(".resrvation-menu").fadeOut();
    });

    // When Click On Occupy Item
    $(document).on("click", ".occupy", function(e) {
        e.stopPropagation();

        $(this)
            .parents(".table")
            .removeClass("focused");

        $(this)
            .parents(".table-menu")
            .slideUp()
            .removeClass("open");

        $("body").removeClass("blur");

        $(this)
            .parents(".table")
            .toggleClass("busy");

        if (
            $(this)
                .parents(".table")
                .hasClass("busy")
        ) {
            $(this)
                .find("span")
                .text("Empty");

            $(this)
                .find("i")
                .addClass("fa-unlock-alt")
                .removeClass("fa-lock");
        } else {
            $(this)
                .find("span")
                .text("Occupy");

            $(this)
                .find("i")
                .addClass("fa-lock")
                .removeClass("fa-unlock-alt");
        }
    });

    // Function To Greate Modal Tables Info
    function CreateTableOnModal(
        nameTable,
        isChecked,
        isHidden,
        isDisabled,
        isMaster
    ) {
        let table = $(`<div class='form-group'></div>`);

        let tableCheck;

        if (isHidden) {
            table = $(`<div class='form-group' hidden='true'></div>`);
            tableCheck = $(
                `<input type="checkbox" name="merge" id="${nameTable}" />`
            );
        } else if (isMaster) {
            table = $(
                `<div class='form-group' hidden='true' master="true"></div>`
            );
            tableCheck = $(
                `<input type="checkbox" name="merge" id="${nameTable}" disabled/>`
            );
        } else if (isDisabled) {
            table = $(`<div class='form-group' hidden='true'></div>`);
            tableCheck = $(
                `<input type="checkbox" name="merge" id="${nameTable}" disabled/>`
            );
        } else if (isChecked) {
            tableCheck = $(
                `<input type="checkbox" name="merge" id="${nameTable}" checked />`
            );
        } else {
            tableCheck = $(
                `<input type="checkbox" name="merge" id="${nameTable}" />`
            );
        }

        let tableLabel = $(`<label for="${nameTable}"> ${nameTable} </label>`);

        table.append(tableCheck);
        table.append(tableLabel);

        return table;
    }

    // When Merge Modal Show Get All Tables Info
    $("#table-merge-modal").on("show.bs.modal", function(e) {
        let modal = $(this);

        let mergeButton = $(e.relatedTarget);

        let masterTable = mergeButton.parents(".table");

        masterTable.addClass("checked");

        let tablesInHole = $(".hole").find(".table");

        tablesInHole.each(function() {
            let tableName = $(this)
                .find(".table-name")
                .text();

            let followTbale = $(this).attr("follow");

            let masterTableNumber = masterTable.find(".table-name").text();

            if (followTbale == masterTableNumber) {
                modal
                    .find(".modal-body")
                    .append(CreateTableOnModal(tableName, true));
            } else if ($(this).hasClass("checked")) {
                modal
                    .find(".modal-body")
                    .append(
                        CreateTableOnModal(tableName, false, false, true, true)
                    );
            } else if (
                $(this).attr("state") == 1 ||
                $(this).attr("booked") == 1
            ) {
                modal
                    .find(".modal-body")
                    .append(CreateTableOnModal(tableName, false, false, true));
            } else if ($(this).hasClass("master")) {
                modal
                    .find(".modal-body")
                    .append(CreateTableOnModal(tableName, true, true));
            } else if (followTbale == 0) {
                modal
                    .find(".modal-body")
                    .append(CreateTableOnModal(tableName, false));
            } else {
                modal
                    .find(".modal-body")
                    .append(CreateTableOnModal(tableName, true, true));
            }
        });

        let tableChecked = modal.find(`input[type="checkbox"]:checked`).length;

        let AllCheckBoxes = modal.find(`.form-group:not([hidden="true"])`)
            .length;

        // if table Is Mater And This Is Only Master                    // If All CheckBoxes Equal Tables In Hole
        if (
            ($(".hole .master").length <= 1 &&
                masterTable.hasClass("master")) ||
            AllCheckBoxes == tablesInHole.length - 1
        ) {
            // If CheckBoxes Checked Equal Tables In Hole
            if (tableChecked == tablesInHole.length - 1) {
                // Hole CheckBox Is Checked (Marked)
                modal
                    .find(".modal-body")
                    .prepend(CreateTableOnModal("hole", true));
            } else {
                // Hole CheckBox Is UnCheck (not Marked)
                modal.find(".modal-body").prepend(CreateTableOnModal("hole"));
            }
        }
    });

    // When Minimum Charge Modal Is Show
    $("#min_charge_modal").on("show.bs.modal", function(e) {
        let minChrageButton = $(e.relatedTarget);

        let minChargeValue = minChrageButton
            .parents(".table")
            .find(".table-body")
            .attr("min-charge");

        let tableNumber = minChrageButton
            .parents(".table")
            .find(".table-name")
            .text();

        $(this)
            .find("#minchrage-input")
            .val(minChargeValue);

        $(this).attr("table", tableNumber);
    });

    // All CheckBox Tables And Hole
    $(document).on(
        "change",
        '#table-merge-modal input[type="checkbox"]',
        function() {
            let inputsCheck = $(this)
                .parents("#table-merge-modal")
                .find('input[type="checkbox"]');

            let inputsChecked = $(this)
                .parents("#table-merge-modal")
                .find('input[type="checkbox"]:checked');

            if ($(this).attr("id") == "hole" && $(this).is(":checked")) {
                inputsCheck.prop("checked", true);
            } else if (
                $(this).attr("id") !== "hole" &&
                $(this).is(":checked") == false
            ) {
                inputsCheck.filter("#hole").prop("checked", false);
            } else if (
                $(this).attr("id") !== "hole" &&
                $(this).is(":checked") &&
                inputsChecked.length == inputsCheck.length - 1
            ) {
                inputsCheck.filter("#hole").prop("checked", true);
            } else if (
                $(this).attr("id") == "hole" &&
                $(this).is(":checked") == false
            ) {
                inputsCheck.not(":disabled").prop("checked", false);
            }
        }
    );

    // When Modla Hidden Empty Modal From All Info
    $("#table-merge-modal").on("hidden.bs.modal", function() {
        let modal = $(this);

        modal.find(".modal-body").empty();

        $(".table.checked").removeClass("checked");
    });
    $("#Reservation_modal").on("hidden.bs.modal", function() {
        $(".table.checked").removeClass("checked");
    });

    /*  ===========================
        ===== End Tables Page =====
        ===========================
    */

    /*  ==============================
        ===== Start Move TO Page =====
        ==============================
    */
    function moveOne(active, anotherSide) {
        var checkItem = active.data("item"),
            sideItem = anotherSide.find(`li[data-item='${checkItem}']`);

        if (sideItem.length == 0) {
            active
                .clone(true, true)
                .appendTo(anotherSide)
                .find("input")
                .val(1)
                .end()
                .attr("quantity", 1)
                .end()
                .addClass("added");

            if (active.find("input").val() > 1) {
                active.find("input").val(active.find("input").val() - 1);
                active.attr("quantity", active.find("input").val());
            } else {
                active.remove();
            }
        } else {
            if (active.find("input").val() > 1) {
                sideItem
                    .find("input")
                    .val(Number(sideItem.find("input").val()) + 1);
                sideItem.attr("quantity", Number(sideItem.find("input").val()));
                active.find("input").val(active.find("input").val() - 1);
                active.attr("quantity", active.find("input").val());
            } else {
                sideItem
                    .find("input")
                    .val(Number(sideItem.find("input").val()) + 1);
                sideItem.attr("quantity", Number(sideItem.find("input").val()));
                active.remove();
            }
        }
    }

    let newTable = $(".new_table");

    $(".trans-all").on("click", function() {
        let left = $(".left .table-list li");
        let right = $(".right .table-list li");

        if (left.length == 0 && newTable.val()) {
            $(".right li")
                .clone(true, true)
                .appendTo(".left .table-list")
                .end()
                .addClass("added");
            $(".right li").remove();
        }
    });

    $("body").on("click", ".right li", function() {
        if (newTable.val()) {
            moveOne($(this), $(".left .table-list"));
        }
    });

    /*  ============================
        ===== End Move TO Page =====
        ============================
    */

    /*  ===============================
        ===== Start Customer Page =====
        ===============================
    */
    let check_customer = $("#Edit_customer").attr("value");
    let check_operation = $("#operation").attr("value");
    if (check_customer == "New_customer" && check_operation == "Delivery") {
        $("#Customer-model").modal("show");
    }

    function getRowData(rowDataInput, inputs) {
        inputs.forEach((input, index) => {
            let customAttribute = rowDataInput[index].dataset.value;

            if (input.dataset.value == customAttribute) {
                input.value = rowDataInput[index].textContent;
            }
        });
    }

    $(".phone .plus").on("click", function() {
        let GrandFatherRow = $(this).parents(".form-row"),
            phoneClone = $(this)
                .parent(".phone")
                .clone();

        if (GrandFatherRow.children().length < 6) {
            phoneClone
                .appendTo(GrandFatherRow)
                .addClass("col-6 mb-3")
                .children(".plus")
                .remove();

            phoneClone.children("label").html("Another Phone");

            phoneClone.children("input").val("");
        }
    });

    $(document).on("click", "#Customer-model tr", function() {
        let rowData = Array.from($(this).children("td[data-value]"));
        let cusLocation = $(this)
            .find('td[data-value="cus_location_id"]')
            .text();

        rowData.forEach(dataTd => {
            $(this)
                .parents("#Customer-model")
                .find(`input#${dataTd.dataset.value}`)
                .first()
                .val(dataTd.textContent);
        });

        $(this)
            .parents("#Customer-model")
            .find(`select#cus_location`)
            .val(cusLocation)
            .change();

        $(this)
            .parents("#Customer-model")
            .find("#save_customer")
            .addClass("closed");
        $(this)
            .parents("#Customer-model")
            .find(".buttons .closed")
            .removeClass("closed");
        $(this)
            .addClass("active")
            .siblings()
            .removeClass("active");
        $("#row_id").val($(this).attr("id_customer"));
        $(this)
            .parents("#Customer-model")
            .find("button:disabled")
            .prop("disabled", false);
    });

    $("#Customer-model").on("hidden.bs.modal", function() {
        $(this)
            .find(".form-row input.form-control")
            .each(function() {
                $(this).val("");
            });
        $(this)
            .find("table tbody")
            .empty();
        $("#order_customer, #update_customer").addClass("closed");
    });

    /*  =============================
        ===== End Customer Page =====
        =============================
    */
    /*  ==========================================
        ===== Start Delivery And TO GO Pages =====
        ==========================================
    */
    // When Click On Delivery Box And TO GO Box
    $(document).on("click", ".delivery .box", function(e) {
        e.stopPropagation();

        $("body").addClass("blur");

        let boxMenu = $(this).find(".box-menu");
        $(this)
            .addClass("focused")
            .attr("id", "box-removeing");

        $("#summary_hold").attr("order", $(this).attr("box_id"));

        new Popper($(this), boxMenu, {
            placement: "auto",
            modifiers: [
                {
                    name: "offset", //offsets popper from the reference/button
                    options: {
                        offset: [0, 8]
                    }
                },
                {
                    name: "flip", //flips popper with allowed placements
                    options: {
                        allowedAutoPlacements: [
                            "right",
                            "left",
                            "top",
                            "bottom"
                        ],
                        rootBoundary: "viewport"
                    }
                }
            ]
        });

        boxMenu.slideDown();
    });

    $(document).on("click", function(e) {
        $(".box").removeClass("focused");
        $("body").removeClass("blur");
        $(".box-menu").slideUp();
    });

    /*  ========================================
        ===== End Delivery And TO GO Pages =====
        ========================================
    */
    //    $(document).ready(function() {});
    window.history.pushState(null, "", window.location.href);
    window.onpopstate = function() {
        window.history.pushState(null, "", window.location.href);
    };
    $("#pay-model").on("hidden.bs.modal", function() {
        $(this)
            .find(".summary ul li")
            .not(".last-item")
            .remove();
        // console.log($(this).find('.summary ul li'))
    });

    // ============= Hospitality ======================
    $("a#hospitality-tab").on("click", function() {
        $(".summary")
            .find(
                ".all-total, .summary-total, .summary-service, .summary-tax, .summary-bank, .summary-mincharge, .summary-discount"
            )
            .text("0");
    });
});
