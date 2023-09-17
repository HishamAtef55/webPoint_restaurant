@include('includes.stock.Stock_Ajax.public_function')
<script>
    const expensesName = $('#expenses_name');
    const saveExpensesBtn = $('#save_expenses');
    const date = $('#date');
    const expensesSelect = $('#expenses');
    const expensesPrice = $('#expenses_price');
    const addExpensesBtn = $('#add_expenses');
    const expensesNameTable = $('.expenses_name_table tbody');
    const expensesTable = $('.expenses_table tbody');

    $(document).ready(function() {
        $('select').select2({
            selectOnClose: true,
            dir: "rtl"
        });


        /*  ======================== Start Save New Expenses ============================== */
        saveExpensesBtn.on('click',function() {
            $.ajax({
                url: "{{route('inDirectCost.save')}}",
                method: 'post',
                data: {
                    _token,
                    name: expensesName.val(),
                },
                success: function(data) {
                    let tableContent = `<tr rowId="${data.id}">
                            <td>
                                <input type="text" class="form-control" value="${expensesName.val()}"/>
                                <span>${expensesName.val()}</span>
                            </td>
                            <td>
                                <div class="del-edit">
                                    <button class="btn btn-danger delete_expenses_name"><i class="fa-regular fa-trash-can"></i></button>
                                    <button class="btn btn-warning edit_expenses"><i class="fa-regular fa-pen-to-square"></i></button>
                                </div>
                                <button class="btn btn-primary update_expenses update">Update</button>
                            </td>
                        </tr>`;
                        let selectContent = `<option value="${data.id}">${expensesName.val()}</option>`;
                        expensesSelect.append(selectContent).select2("destroy").select2({
                            dir: 'rtl'
                        });
                    expensesNameTable.append(tableContent);
                    expensesName.val('');
                    expensesName.parents('.custom-form').removeClass('invalid');
                    $('.expenses-name-responsive').stop().animate({
                        scrollTop: $('.expenses-name-responsive').prop("scrollHeight")
                    }, 500);
                    Toast.fire({
                        icon: 'success',
                        title: "تم الاضافة بنجاح"
                    });
                },
            });
        });
        /*  ======================== End Save New Expenses ============================== */
        /*  ======================== Start Edit Row In Table ============================== */
        $(document).on('click', '.edit_expenses', function() {
            let rowParent = $(this).parents('tr');
            rowParent.addClass('edit');
            rowParent.find('input').eq(0).focus().select()
        });
        /*  ======================== End Edit Row In Table ============================== */
        /*  ======================== Start Delete Row In Table ============================== */
        $(document).on('click', '.delete_expenses_name', function() {
            let rowParent = $(this).parents('tr');
            let rowId = rowParent.attr('rowId');
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
                    $.ajax({
                        url: "{{route('inDirectCost.destroy')}}",
                        method: 'post',
                        data: {
                            _token,
                            id: rowId
                        },
                        success: function(data) {
                            if (data.status == true) {
                                Toast.fire({
                                    icon: 'success',
                                    title: data.msg
                                });
                                rowParent.remove();
                                expensesSelect.find(`option[value="${rowId}"]`).remove()
                                expensesSelect.select2("destroy").select2({
                                    dir: 'rtl'
                                });
                            }
                        },
                    });
                }
            })
        });
        /*  ======================== End Delete Row In Table ============================== */
        /*  ======================== Start Update Row In Table ============================== */
        $(document).on('click', '.update_expenses', function() {
            let rowParent = $(this).parents('tr');
            let rowId = rowParent.attr('rowId');
            let newExpensesName = rowParent.find('input').val();
            $.ajax({
                url: "{{route('inDirectCost.update')}}",
                method: 'post',
                data: {
                    _token,
                    id: rowId,
                    name: newExpensesName
                },
                success: function(data) {
                    Toast.fire({
                        icon: 'success',
                        title: data.msg
                    });
                    rowParent.removeClass('edit');
                    rowParent.find('span').text(newExpensesName);
                    expensesSelect.find(`option[value="${rowId}"]`).text(newExpensesName)
                    expensesSelect.select2("destroy").select2({
                        dir: 'rtl'
                    });
                },
            });
        });
        /*  ======================== End Update Row In Table ============================== */
        /*  ======================== Start Save New Expenses ============================== */
        addExpensesBtn.on('click',function() {
            $.ajax({
                url: "{{route('inDirectCost.saveInDirectValue')}}",
                method: 'post',
                data: {
                    _token,
                    inDirectCost: expensesSelect.val(),
                    value: expensesPrice.val(),
                    date: date.val(),
                },
                success: function(data) {
                    let tableContent = `<tr rowId="${data.id}">
                        <td> ${expensesSelect.find('option:selected').text()} </td>
                        <td> ${date.val()} </td>
                        <td> ${expensesPrice.val()} </td>
                        <td>
                            <div class="del-edit">
                                <button class="btn btn-danger delete_expenses"><i class="fa-regular fa-trash-can"></i></button>
                            </div>
                        </td>
                    </tr>`;
                    expensesTable.append(tableContent);
                    expensesSelect.val('')
                    expensesPrice.val('').parents('.custom-form').removeClass('invalid');
                    $('.expenses-responsive').stop().animate({
                        scrollTop: $('.expenses-responsive').prop("scrollHeight")
                    }, 500);
                    Toast.fire({
                        icon: 'success',
                        title: "تم الاضافة بنجاح"
                    });
                },
            });
        });
        /*  ======================== End Save New Expenses ============================== */
        /*  ======================== Start Delete Row In Table ============================== */
        $(document).on('click', '.delete_expenses', function() {
            let rowParent = $(this).parents('tr');
            let rowId = rowParent.attr('rowId');
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
                    $.ajax({
                        url: "{{route('inDirectCost.deleteInDirectValue')}}",
                        method: 'post',
                        data: {
                            _token,
                            id : rowId
                        },
                        success: function(data) {
                            if (data.status == true) {
                                Toast.fire({
                                    icon: 'success',
                                    title: data.msg
                                });
                                rowParent.remove();
                            }
                        },
                    });
                }
            })
        });
        /*  ======================== End Delete Row In Table ============================== */

    })
</script>
