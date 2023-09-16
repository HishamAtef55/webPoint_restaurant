$(function () {
    'use strict';

    function moveOne(active, anotherSide) {

        var checkItem = active.data('item'),
            sideItem = anotherSide.find(`li[data-item='${checkItem}']`);

        if (sideItem.length == 0) {

            active.clone(true, true).appendTo(anotherSide).find('input').val(1);
            active.find('input').val(active.find('input').val() - 1)

        } else {

            if (active.find('input').val() > 1) {

                sideItem.find('input').val(Number(sideItem.find('input').val()) + 1)
                active.find('input').val(active.find('input').val() - 1)

            } else {

                sideItem.find('input').val(Number(sideItem.find('input').val()) + 1)
                active.remove()

            }

        }
    }

    $('.trans-all').on('click', function() {

        var left = $('.left .table-list li'),
            right = $('.right .table-list li');

        if (right.length == 0) {
            $('.left li').clone(true, true).appendTo('.right .table-list');
            $('.left li').remove();
        }

        if (left.length == 0) {
            $('.right li').clone(true, true).appendTo('.left .table-list');
            $('.right li').remove();
        }

    });

    $('body').on('click', '.left li', function() {

        moveOne($(this), $('.right .table-list'));

    });

    $('body').on('click', '.right li', function() {

        moveOne($(this), $('.left .table-list'));

    });

});
