<script>
    let _token = $('input[name="_token"]').val();

    $(document).ready(function() {
        $('select').select2({
            selectOnClose: true,
            dir: "rtl"
        });
    });

    function searchDb(url, query, inputSearch, branch) {
        let searchList = inputSearch.siblings('.search-result');
        if (query != '') {
            $.ajax({
                url: url,
                method: 'post',
                data: {
                    _token,
                    query,
                    branch,
                },
                success: function(request) {
                    if (request.status == 'true') {
                        let searchItems = ''
                        searchList.html('')
                        request.data.forEach(item => {
                            searchItems += `<li data-id=${item.id}>${item.name}</li>`;
                        });
                        searchList.html(searchItems)
                    }
                }
            });
        } else {
            searchList.html('')
        }
    }

    function getData(url, id, getFun) {
        $.ajax({
            url: url,
            method: 'post',
            data: {
                _token,
                id
            },
            success: function(request) {
                if (request.status == 'true') {
                    getFun(request.data)
                    checkForm();
                }
            }
        });
    }

    function checkForm() {
        $('.custom-form').each(function() {
            $(this).find('input, textarea').on('change', function() {
                if ($(this).val() != '') {
                    $(this).parents('.custom-form').addClass('invalid')
                } else {
                    $(this).parents('.custom-form').removeClass('invalid')
                }
            });

            if ($(this).find('input').val() != '' && $(this).find('textarea').val() != '') {
                $(this).addClass('invalid')
            } else {
                $(this).removeClass('invalid')
            }
        })
    }


    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });


    function successMsg(msg) {
        Toast.fire({
            icon: 'success',
            title: msg,
        });
    }

    function errorMsg(msg) {
        Swal.fire({
            icon: 'error',
            title: 'خطأ.....',
            text: msg,
        });
    }


    checkForm();

    // Function to reset modal content
    function resetModal(modalId) {
        // Clear all input fields
        $(`${modalId} #id, ${modalId} #name, ${modalId} #phone, ${modalId} #address`).val('');

        $(` ${modalId} input[type="checkbox"]`).each(function() {
            // Uncheck the checkbox
            $(this).prop('checked', false);

            // Find the associated select element and reset it
            $(this).closest('tr').find('.unit').val($(this).closest('tr').find(
                '.unit option:first').val()).change();

            // Find the associated input field and clear its value
            $(this).closest('tr').find('input[name="capacity"]').val('');
        });
    }

    function resetSectionsModel(modalId) {
        // Clear all input fields
        $(`${modalId} #id, ${modalId} #name`).val('');
        $(`${modalId} select[name="store_id"], ${modalId} select[name="branch_id"]`).empty().append(
            '<option selected disabled>اختر</option>');
        $(`${modalId} .groups`).html('');
    }
</script>
