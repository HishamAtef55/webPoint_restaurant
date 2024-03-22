<script>
    // ################################# End code Save Tables ###################
    function createTables(pattern,minHole, maxHole, copiesNum, chair = 1, radius, hole) {
        let tablesLength = $('.active.show.hole .new').length;
        let startNum = minHole + tablesLength
        let tablesNumAvailable = startNum + copiesNum;

        for (let i = startNum; i < tablesNumAvailable; i++) {
            if (i > maxHole) {
                window.alert('Bigger Than')
                break;
            }
            let tables = $(".tables .new").last().attr('number_of_tables').split('-')[1] || (startNum - 1);
            let newTable = $(`<div class='new' number_of_tables='${pattern}-${+tables + 1 }'> </div>`);
            let tableMenu = $( `<div class="table-menu" id="trash_table" hole="${hole}" number_of_tables="${pattern}-${+tables + 1}">
                <i class="fa fa-trash fa-fw"></i><span>Delete</span>
            </div>`);

            newTable
            .appendTo(".hole")
            .animate({
                    width: "140px",
                    height: "140px"
                },400, function() {
                    $(`<div class='text'>${pattern}-${+tables + 1}</div>`)
                        .appendTo($(this))
                        .animate(
                            {
                                opacity: 1
                            },
                            100
                        );

                    $(
                        `<div class='chair'><i class="fa fa-users"></i> <span>${chair}</span> </div>`
                    )
                        .appendTo($(this))
                        .animate(
                            {
                                opacity: 1
                            },
                            100,
                            function() {
                                tableMenu.appendTo(newTable);
                            }
                        );
                }
            )
            .each(function() {
                if (radius == true) {
                    $(this).addClass("circle");
                }
            });
        }
    }
    // ################################# End code Save Tables ###################

    // ################################# code Save Tables ###################
    $('#save_table').on('click',function (e) {
            e.preventDefault();
            let linkActive = $('.nav-link.active')
            let hole = linkActive.attr('hole');
            if ($('#circle').prop('checked')) {
                $('#circle').val('1')
            } else {
                $('#circle').val('0')
            }
            let _token = $('input[name="_token"]').val();
            let no_of_tables = $('#copies').val();
            let circle = $('#circle').val();
            let no_of_chair = $('#chair-num').val() || 1;
            let branch_id = $('#select').val();
            let minHole = linkActive.data('min-hole');
            let maxHole = linkActive.data('max-hole');
            let pattern = linkActive.text().toUpperCase()[0];
            let tablesLength = $('.active.show.hole .new').length;
            let startNum = minHole + tablesLength;
            let type = 'hole'

            $.ajax({
                url: "<?php echo e(route('add.new.table')); ?>",
                method: 'post',
                enctype: "multipart/form-data",
                data:{_token,type,branch_id,no_of_tables,circle,no_of_chair,hole,pattern,startNum,maxHole},
                success: function (data) {
                    $('#copies').val('');
                    $('#circle').prop('checked', false);
                    $('#chair-num').val('');
                    createTables(pattern, minHole, maxHole, +no_of_tables, no_of_chair, circle, hole)
                    Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: 'Your tables has been saved ',
                        showConfirmButton: false,
                        timer: 1000
                    });
                },
                error: function (reject) {
                    var response  = $.parseJSON(reject.responseText);
                    $.each(response.errors , function (key, val)
                    {
                        Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: val[0],
                        });
                    });
                }
            });
    });
    // ################################# End code Save Tables ###################

    // ################################# Start Create Other Tables ###################
    function createOtherTable(tableName) {
        let tables = $(".tables .new").length;
        let newTable = $(`<div class='new' number_of_tables='${tables + 1}'> </div>`);
        let tableMenu = $( `<div class="table-menu" id="trash_table" hole="Other" number_of_tables="${tables +1}">
            <i class="fa fa-trash fa-fw"></i><span>Delete</span>
        </div>`);

        newTable
        .appendTo(".hole")
        .animate({
            width: "140px",
            height: "140px"
        },400, function() {
            $(`<div class='text'>${tableName.replace(' ', '-')}</div>`)
                .appendTo($(this))
                .animate({opacity: 1}, 100);
            $(`<div class='chair'><i class="fa fa-users"></i> <span>1</span> </div>`).appendTo($(this))
                .animate({opacity: 1}, 100, function() { tableMenu.appendTo(newTable)});
        });

    }
    // ################################# End code Save Tables ###################

    // ################################# Start code Save Other Tables ###################
    $('#save_other').on('click', function(e) {
        e.preventDefault();
        let _token = $('input[name="_token"]').val();
        let tableName = $('#other_name').val();
        let branch_id = $('#select').val();
        let type = 'other';
        let linkActive = $('.nav-link.active')
        let hole = linkActive.attr('hole');

        $.ajax({
            url: "<?php echo e(route('add.new.table')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:{_token,branch_id,tableName,type,hole},
            success: function (data) {
                $('#other_name').val('');
                createOtherTable(tableName);
                Swal.fire({
                    position: 'center-center',
                    icon: 'success',
                    title: 'Your table has been saved ',
                    showConfirmButton: false,
                    timer: 1000
                });

                if(data.status == 'false'){
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.msg,
                    });
                }
            },
            error: function (reject) {
                var response  = $.parseJSON(reject.responseText);
                $.each(response.errors , function (key, val)
                {
                    Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: val[0],
                    });
                });
            }
        });
    })
    // ################################# End code Save Other Tables ###################

    // ################################# Start search holes ###################
    $(document).on("click", ".tabs-tables a", function(e){
        e.preventDefault();
        let hole_num = $(this).attr('hole');
        let _token = $('input[name="_token"]').val();
        let branch  = $('#select').val();
        let thisTab = $(this);
        if(thisTab.text() === 'Other') {
            $('.create-table').addClass('d-none');
            $('.create-other-table').removeClass('d-none');
        } else {
            $('.create-table').removeClass('d-none');
            $('.create-other-table').addClass('d-none');
        }
        $.ajax({
            url: "<?php echo e(route('search.holes.admin')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:{_token:_token,hole_num:hole_num,branch:branch},
            success: function (data)
            {
                let html = '';
                html +='<div id="trash_hole" hole="'+hole_num+'" class="trash_hole">X</div>';
                for(var count = 0 ; count < data.length ; count ++)
                {
                    if(data[count].circle == 1)
                    {
                        html  +='<div style="width:'+data[count].width+'px;height:'+data[count].height+'px;top:'+data[count].top+'px;left:'+data[count].left+'px" hole="'+data[count].hole+'" number_of_tables = "'+data[count].number_table+'" class="new circle">';
                    }
                    else if(data[count].circle==0)
                    {
                        html +='<div style="width:'+data[count].width+'px;height:'+data[count].height+'px;top:'+data[count].top+'px;left:'+data[count].left+'px" hole="'+data[count].hole+'" number_of_tables = "'+data[count].number_table+'" class="new">';
                    }
                    html +='<div class="text">'+data[count].number_table+'</div>';
                    html +='<div class="chair">';
                    html +='<i class="fa fa-users"></i>';
                    html +='<span>'+data[count].no_of_gest+'</span>';
                    html +='</div>';
                    html += '<div class="table-menu" id="trash_table" hole="'+data[count].hole+'" number_of_tables = "'+data[count].number_table+'">';
                    html += '<i class="fa fa-trash fa-fw"></i>';
                    html += '<span>Delete</span>';
                    html += '</div>';
                    html += '</div>';
                }
                $(`#hole${hole_num}`).html(html);
                $('#hole_name').val(thisTab.text())
                $('#max_hole').val(thisTab.data('max-hole'))
            }
        });
    });
    // ################################# End search holes ###################

    // ###################### Start Create Hole ######################
    function createHole(holeNum, holeName, minHole, maxHole) {
        let item = $('<li class="nav-item"></li>'),
            link = $(`<a class="nav-link" hole="${holeNum}" data-toggle="tab" href="#hole${holeNum}" role="tab" aria-selected="true" data-min-hole="${minHole}" data-max-hole="${maxHole}">${holeName}</a>`);

        let area = $(`<div class="tab-pane fade" id="hole${holeNum}" data-hole='hole${holeNum}'></div>`),
            trashButton = $(`<div id="trash_hole" hole="${holeNum}" class="trash_hole">X</div>`)

        item.appendTo($('.tables .nav-tabs'));
        link.appendTo(item);

        area.appendTo($('.tables .tab-content'));
        trashButton.prependTo(area)

    }
    // ###################### End Create Hole ######################

    // ###################### Start Save Hole #####################
    $('#save_hole').on('click', function(e) {
        e.preventDefault();
        let holeNum = $('.tables .nav-tabs .nav-link').last().attr('hole') || 0;
        let holeName = $("#hole_name").val();
        let branch   = $('#select').val();
        let minHole   = $('#min_hole').val();
        let maxHole   = $('#max_hole').val();
        let pattern = holeName.toUpperCase()[0];
        if (!holeName == '') {
            holeNum++;
            let _token = $('input[name="_token"]').val();
            $.ajax({
                url: "<?php echo e(route('add.new.hole')); ?>",
                method: 'post',
                enctype: "multipart/form-data",
                data:{_token,holeNum,holeName,branch,min:minHole,max:maxHole,pattern},
                success: function (data)
                {
                    $('#hole_name').val('');

                    if (data.msg === 'Saved Hole') {
                        createHole(holeNum, holeName, minHole, maxHole);
                        Swal.fire({
                        position: 'center-center',
                        icon: 'success',
                        title: data.msg,
                        showConfirmButton: false,
                        timer: 1250
                    });
                    } else if (data.msg === 'Updated Hole') {
                        Swal.fire({
                            position: 'center-center',
                            icon: 'success',
                            title: data.msg,
                            showConfirmButton: false,
                            timer: 1250
                        });
                        $('.tabs-tables a.active').data('max-hole', $('#max_hole').val());
                    }
                    if(data.status =='false'){
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: data.msg,
                    });
                }
                }

            });
        }
    });
    // ###################### End Save Hole #####################

    // ############ Add Hole Class To Tabs For Create Tables On It ###########
    $("body").on('click', '.nav-tabs a', function(e){
        e.preventDefault();
        var href = $(this).tab().attr('href') // For Select Id Tabs Content
        $(href).addClass('hole').siblings().removeClass('hole');
    });
    // ############ Add Hole Class To Tabs For Create Tables On It ###########

    // ################################# Start Delete holes ###############################
    $('body').on('click', '#trash_hole', function() {
        let holeattr = $(this).attr('hole');
        let link = $(`.tables .nav-tabs a[href='#hole${holeattr}']`);
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then( (result) => {
            if (result.isConfirmed) {
                let _token = $('input[name="_token"]').val();
                $.ajax({
                    url: "<?php echo e(route('del.hole')); ?>",
                    method: 'post',
                    enctype: "multipart/form-data",
                    data:{_token:_token,holeattr:holeattr},
                    success: function (data)
                    {
                        $(`#hole${holeattr}`).remove();
                        link.parent().remove();
                    }
                });
                Swal.fire ({
                    title: 'Your hole has been deleted.',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1000
                });
            }
        })

    });
    // ################################# End Delete holes ###############################

    // ################################# Start Delete Table ###############################
    $('body').on('click', '#trash_table', function(e) {
        let _token = $('input[name="_token"]').val();
        let hole = $(this).attr('hole'),
            table= $(this).attr('number_of_tables'),
            branch = $('#select').val();
        $.ajax({
            url: "<?php echo e(route('del.table')); ?>",
            method: 'post',
            enctype: "multipart/form-data",
            data:{_token:_token,hole:hole,table:table,branch:branch},
            success: function (data)
            {
                if(data.status == 'true')
                {
                    $('#table_delete').remove();
                }
                if(data.status == 'false'){
                    Swal.fire ({
                        title: 'Table is Open !',
                        icon: 'error',
                        showConfirmButton: false,
                        timer: 1000
                    });
                }
            }
        });
    });
    // ################################# End Delete Table #################################
    $(document).ready(function() {
        let _token           = $('input[name="_token"]').val();
        $('#select').on('change',function () {
            $('#select_menu').empty();
            let branch = $(this).val();
            if(branch) {
                $.ajax({
                    'type':'POST',
                    'url':"<?php echo e(Route('get.holes')); ?>",
                    'data':
                    {
                        'branch'  : branch,
                        '_token': "<?php echo e(csrf_token()); ?>",
                    },
                    success:function(data)
                    {
                        let html = '';
                        let con = '';
                        for (var count = 0 ; count < data.length ; count ++)
                        {
                            html +='<li class="nav-item" id="">';
                                html +=' <a class="nav-link" data-min-hole="'+data[count].min+'" data-max-hole="'+data[count].max+'" hole="'+data[count].number_holes+'" data-toggle="tab" href="#hole'+data[count].number_holes+'" aria-selected="true">'+data[count].name+'</a>';
                            html +='</li>';
                            con +='<div class="tab-pane fade" id="hole'+data[count].number_holes+'" data-hole="hole'+data[count].number_holes+'">';
                            con +='</div>';
                        }
                        $('#holes').html(html);
                        $('#holesContent').html(con);
                        $('#hole_name').val('')
                        $('#max_hole').val('')
                    }
                });
            }
        });
    });
</script>
<?php /**PATH F:\BackEnd\htdocs\webpoint\resources\views/includes/control/tables.blade.php ENDPATH**/ ?>