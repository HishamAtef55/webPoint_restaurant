@include('includes.Stock_Ajax.public_function')
<script>
    $(document).ready(function() {
        let branch = $('#branch');
        let items = $('#items');
        let save = $('#saveHalk');
        let qty = $('#quantity');
        let sections = $('#section');
        let note = $('#notes');
        let date = $('#date');
        let serial = $('#permission');
        let deleteBtn = $('.delete');
        let table = $('#tableView');



        // function of get all items in branch
        branch.on('change', function() {
            $.ajax({
                url: "{{route('components_items_get_items')}}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                },
                success: function (data) {
                    if (data.status == true) {
                        let html = '<option value="" disabled selected></option>';
                        data.items.forEach((item) => {
                            html += `<option value="${item.id}" data-price="${item.price}" >${item.name}</option>`
                        });
                        items.html(html)
                        items.select2({
                            dir: "rtl"
                        });
                        items.select2('open');
                    }
                },
            });
            $.ajax({
                url: "{{route('changePurchasesBranch')}}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                },
                success: function(data) {
                    let htmlsec = '<option value="" disabled selected></option>';
                    data.sections.forEach((section) => {
                        htmlsec += `<option value="${section.id}">${section.name}</option>`
                    });
                    sections.html(htmlsec)
                    sections.select2({
                        dir: "rtl"
                    });
                },
            });
            $.ajax({
                url: "{{route('getHalkOld')}}",
                method: 'post',
                data: {
                    _token,
                    branch: branch.val(),
                },
                success: function(data) {
                    let htmlhalk = '';
                    table.html('')
                    data.halks.forEach((halk) => {
                        htmlhalk += `<tr value="${halk.id}">
                                <td>${halk.id}</td>
                                <td>${halk.getbranch.name}</td>
                                <td>${halk.getsection.name}</td>
                                <td>${halk.item}</td>
                                <td>${halk.qty}</td>
                                <td>${halk.date}</td>
                                <td>
                                    <div class="del-edit">
                                        <button class="btn btn-danger delete" halkId="${halk.id}"><i class="fa-regular fa-trash-can"></i></button>
                                    </div>
                                </td>
                                      </tr>`;
                    });
                    table.html(htmlhalk)
                },
            });
        });

        // save Halkر
        save.on('click', function() {
            $.ajax({
                url: "{{route('save_halk_item')}}",
                method: 'post',
                data: {
                    _token,
                    item: items.val(),
                    branch: branch.val(),
                    qty: qty.val(),
                    section:sections.val(),
                    date:date.val(),
                    note:note.val()
                },
                success: function (data) {
                    if (data.status == true) {
                        Toast.fire({
                            icon: 'success',
                            title: data.data
                        });
                        $('#serial').val(data.id)
                    }
                },
            });
        });

        deleteBtn.on('click', function() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: 'rgb(21, 157, 113)',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم',
                cancelButtonText: 'لا'
            }).then((result) => {
                if (result.isConfirmed) {
                    let id = $(this).attr('halkId');
                    let rowParent = $(this).parents('tr');
                    $.ajax({
                        url: "{{route('deleteHalkItem')}}",
                        method: 'post',
                        data: {
                            _token,
                            id
                        },
                        success: function(data) {
                            if (data.status == true) {
                                Toast.fire({
                                    icon: 'success',
                                    title: data.data
                                });
                                rowParent.remove();
                            }
                        },
                    });
                }
            })
        });
    });

</script>
