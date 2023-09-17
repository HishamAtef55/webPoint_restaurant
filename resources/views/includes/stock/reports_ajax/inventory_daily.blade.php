
@include('includes.stock.Stock_Ajax.public_function')
<script>
    let date = $('#date');
    let branch = $('#branch');
    let sections = $('#sections');
    let stores = $('#stores');
    let resetValuesBtn = $('#reset_values');
    let saveInventoryBtn = $('#save_inventory');
    let actualQtyBtn = $('#actual_qty');
    let print = $('#print');
    let tableBody = $('.table-inventory tbody');

$(document).ready(function() {
    $('select').select2({
        selectOnClose: true,
        dir: "rtl"
    });
    /*  ======================== Start Change Purchases Method ============================== */
    $('input[name="inventory_method"]').on('change', function() {
        let type = $(this).val()
        if (type === 'section') {
            $('.branch-sec').removeClass('d-none')
            $('.stores').addClass('d-none')
        } else if(type === 'store') {
            $('.branch-sec').addClass('d-none')
            $('.stores').removeClass('d-none')
        }
    });
    /*  ======================== End Change Purchases Method ============================== */
    /*  ======================== Start Change Branch ============================== */
    branch.on('change',function() {
        $.ajax({
            url: "{{route('changePurchasesBranch')}}",
            method: 'post',
            data: {
                _token,
                branch: branch.val(),
            },
            success: function(data) {
                let html = '<option value="" disabled selected></option>';
                data.sections.forEach((section) => {
                    html += `<option value="${section.id}">${section.name}</option>`
                });
                sections.html(html)
                sections.select2('open')
            },
        });
    });
    /*  ======================== End Change Branch ============================== */
    /*  ======================== Start limit Min Report ============================== */
        sections.on('change', function() {
            let type = $('input[name="inventory_method"]:checked').val();
            $.ajax({
                url: "{{route('inventoryDaily.getMaterials')}}",
                method: 'post',
                data: {
                    _token,
                    section: sections.val(),
                    type,
                },
                success: function(data) {
                    createTable(data.materials)
                },
            });
        });

        stores.on('change', function() {
            let type = $('input[name="inventory_method"]:checked').val();
            $.ajax({
                url: "{{route('inventoryDaily.getMaterials')}}",
                method: 'post',
                data: {
                    _token,
                    store: stores.val(),
                    type,
                },
                success: function(data) {
                    createTable(data.materials)
                },
            });
        });

        function createTable(data) {
            console.log(data)
            let html = ``;
                data.forEach(item => {
                html += `<tr>
                        <td>${item.code}</td>
                        <td>${item.material}</td>
                        <td>${item.l_price}</td>
                        <td>${item.qty}</td>
                        <td><input type="number" min="0" step="0.1" class="table-input form-control text-center mx-auto" style="width: 100px"/></td>
                    </tr>`
            });
            tableBody.html(html);
            resetValuesBtn.prop('disabled', false)
        }

    /*  ======================== End limit Min Report ============================== */
    /*  ======================== Start Reset Values ============================== */
    resetValuesBtn.on('click', function() {
        $('.table-input').each(function() {
            $(this).val('');
        });
    })
    /*  ======================== End Reset Values ============================== */
    /*  ======================== Start actual Qty  Button ============================== */
    actualQtyBtn.on('click', function() {
        $('.table-input').each(function() {
            if($(this).val() === '') {
                let cell = $(this).parents('td').prev('td').text();
                $(this).val(cell);
            }
        });
    })
    /*  ======================== End actual Qty  Button ============================== */
    /*  ======================== Start Save Inventory ============================== */
    function setData() {
        let materialArray = [];
        let type = $('input[name="inventory_method"]:checked').val();

        tableBody.find('tr').each(function() {
            materialArray.push({
                code: $(this).find('td').eq(0).text(),
                itemName: $(this).find('td').eq(1).text(),
                unitName: $(this).find('td').eq(2).text(),
                balance: $(this).find('td').eq(3).text(),
                actualBalance: $(this).find('td').eq(4).find('input').val(),
            });
        })

        let formData = new FormData();
        formData.set("_token", _token);
        formData.set("type", type);
        formData.set("date", date.val());
        formData.set("branch", branch.val());
        formData.set("sections", sections.val());
        formData.set("stores", stores.val());
        formData.set("materialArray", JSON.stringify(materialArray));

        return formData;
    }
    saveInventoryBtn.on('click', function() {
        $.ajax({
            url: "{{route('inventoryDaily.storeInventory')}}",
            method:'post',
            enctype:"multipart/form-data",
            processData:false,
            cache : false,
            contentType:false,
            'data' : setData(),
            success: function(data) {
                if (data.status == true) {
                    Toast.fire({
                        icon: 'success',
                        title: data.data
                    });
                }
            },
        });
    });
    /*  ======================== End Save Inventory ============================== */
    print.on('click', function() {
        printContent('printableArea')
    })

    function printContent(el) {
        let type = $('input[name="inventory_method"]:checked').val();
        let section = sections.find('option:selected').text();
        let store = stores.find('option:selected').text();
        let title = $(`<h3 class="text-center">جرد ${type === 'section' ? section : store}</h3>`)

		let restorePage = $('body').html();
		let printContent = $('#' + el).clone();
        printContent.prepend(title)
		$('body').empty().html(printContent);
		window.print();
		$('body').html(restorePage);
		location.reload();
	}


})


</script>
