function getOptions(select) {

    let options = Array.from(select.children('option'));

    select.children().each(function () {

        let text = $(this).text().split(' ').join('-');
        $(this).attr('data-value', text);

    });

    select.next('.search-select').find('ul').empty();

    for (let i = 1; i < options.length; i++) {

        let item = $(`<li class="search-item" data-value='${options[i].value.split(' ').join('-')}'></li>`);
        item.text(options[i].text);
        item.appendTo(select.next('.search-select').find('ul'));

    }

    select.addClass('d-none');

}

$(document).on('click', '.search-item', function (e) {

    e.stopPropagation();

    let data = $(this).data('value');
    let SelectParent = $(this).parents('.search-select');

    let myOption = SelectParent.prev('select').find(`option[value="${data}"]`);

    myOption.prop('selected', true).siblings().prop('selected', false);

    SelectParent.find('.search-input').val($(this).text());
    SelectParent.find('label').addClass('focused');
    SelectParent.find('.line').addClass('fill');
    SelectParent.prev('select').val(myOption.val()).change();
    $(this).addClass('active').siblings().removeClass('active');
    $(this).siblings().css('display', 'block');
    // $(this).parents('.input-options').toggle();
});

function filterOptions() {

    const text = $(this).val().toLowerCase();

    let myArr = Array.from($(this).parents('.search-select').find('.search-item'));

    myArr.forEach(task => {

        const item = task.textContent;

        if (item.toLowerCase().indexOf(text) != -1) {
            task.style.display = 'block';
        } else {
            task.style.display = 'none';
        }

    });

}

$(document).on('input', '.search-input', filterOptions);

$(document).on('focus', '.search-input', function () {

    let SelectParent = $(this).parents('.search-select');

    SelectParent.find('label').addClass('focused');
    SelectParent.find('.line').addClass('fill');
    SelectParent.find('.input-options').toggle();

});

$(document).on('blur', '.search-input', function () {

    let SelectParent = $(this).parents('.search-select');

    setTimeout( () => {
        if ($(this).val().trim() === "") {

            $(this).val("");
            SelectParent.find('label').removeClass('focused');
            SelectParent.find('.line').removeClass('fill');
        }
        SelectParent.find('.input-options').toggle();
    }, 300);



});

getOptions($('.select_Branch'));
getOptions($('.select_menu'));
getOptions($('.select_group'));
getOptions($('#select_items_sub'));
getOptions($('.select_printers'));
// getOptions($('.select_printers'));
getOptions($('.select_subgroup'));
getOptions($('.drawer-printer'));
getOptions($('.def-transaction'));
getOptions($('.select_printers_Invoice'));
getOptions($('.select_and_Search_units'));
getOptions($('.select_and_Search_chose_itemorsub'));
getOptions($('.select_and_Search_items_details'));
getOptions($('.printer_shift'));
// getOptions($('.main_table'));
// getOptions($('.new_table'));

//###################################################
// getOptions($('.select_and_Search_test1'));
// getOptions($('.select_and_Search_test2'));
// getOptions($('.select_and_Search_test3'));
// getOptions($('.select_and_Search_menu'));
// getOptions($('.select_and_Search_subgroup'));
// getOptions($('.select_and_Search_group'));
// getOptions($('.select_and_Search_printers'));
// getOptions($('.select_and_Search_units'));
// getOptions($('.select_and_Search_chose_itemorsub'));
// getOptions($('.select_and_Search_chose_Details'));
// getOptions($('.select_and_Search_items'));
// getOptions($('.select_and_Search_itemsitems'));
// getOptions($('.chose_branch'));
// getOptions($('.select_items'));
