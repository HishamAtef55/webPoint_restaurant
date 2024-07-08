<script>
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
                    _token: "{{ csrf_token() }}",
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
                _token: "{{ csrf_token() }}",
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

    checkForm();

    function validateForm(meta) {
        let isValid = true;

        Object.keys(meta).forEach(function(key) {
            if (meta[key].val() === '') {
                isValid = false;
                errorMsg("قم بإدخال اسم المخزن")
            }
        });
        return isValid;
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

    function collectJsonMeta(action, meta) {

        let endPoint = '';
        let method = '';
        let body = {};

        switch (action) {
            case 'create':
                endPoint = "{{ route('save.store') }}";
                method = 'post';
                body = {
                    _token: "{{ csrf_token() }}",
                    name: meta.name.val(),
                    phone: meta.phone.val(),
                    address: meta.address.val(),
                };
                break;

            default:
                console.error('Unknown action:', action);
                return;
        }

        return {
            "endPoint": endPoint,
            "method": method,
            "body": body
        };
    }

    function callApi(JsonMeta) {
        $.ajax({

            url: JsonMeta.endPoint,
            method: JsonMeta.method,
            DataType: 'json',
            data: JsonMeta.body,
            success: function(data) {
                switch (action) {
                    case 'create':
                        if (data.status == 'true') {
                            let html = '';
                            tbody.empty();
                            data.stores.forEach(store => {
                                html += `<tr>
                                <th>${store.id}</th>
                                <td>${store.name || '-'}</td>
                                <td>${store.phone || '-'}</td>
                                <td>${store.address || '-'}</td>

                                 <td>
                                <button title="تعديل" class="btn btn-success stores_btns"
                                    data-id="${ store.id }"data-action="edit">

                                    <i class="far fa-edit"></i>
                                </button>

                                <button title="عرض" data-id="${ store.id }" data-action="view"
                                    class="btn btn-primary stores_btns">

                                    <i class="fa fa-eye" aria-hidden="true"></i>

                                </button>
                                <button class="btn btn-danger stores_btns" data-action="delete"
                                    data-id="${ store.id }">
                                    <i class="fa fa-trash"></i>
                                </button>
                                           </td>
                                </tr>`;
                            })
                            tbody.html(html);
                            id.val(data.new_store);
                            name.val('');
                            phone.val('');
                            address.val('');
                            $('input[type="checkbox"]').each(function() {
                                $(this).prop('checked', false)
                            })
                            $('.unit').each(function() {
                                $(this).find(' option:first-child')
                                    .prop('selected', true)
                            })
                            $('input[name="capacity"]').each(function() {
                                $(this).val('')
                            })

                            successMsg(data.msg);
                        }
                    default:
                        console.error('Unknown action:', action);
                        return;
                }
            },
            error: function(reject) {
                let response = $.parseJSON(reject.responseText);
                $.each(response.errors, function(key, val) {
                    errorMsg(val[0])
                });
            }
        });
    }
</script>
