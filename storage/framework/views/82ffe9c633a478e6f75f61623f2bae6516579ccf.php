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
</script><?php /**PATH C:\xampp\htdocs\web_point\resources\views/includes/stock/Stock_Ajax/public_function.blade.php ENDPATH**/ ?>