@include('includes.Stock_Ajax.public_function')
<script>
let id = $('#section_id')
let store = $('#store')
let branch = $('#branch')
let name = $('#section_name')
let groupsDiv = $('.groups');

$('#branch').on('change', function() {
    let branch = $('#branch').val()
    $.ajax({
        url: "{{route('get_group')}}",
        method: 'post',
        data: {
            _token,
            branch,
        },
        success: function(data) {
            if (data.status == 'true') {
                let groupsContent = '';
                groupsDiv.html('');
                for (const group in data.groups) {
                    groupsContent += ` <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" value="${data.groups[group].name}" id="group-${data.groups[group].id}" name="groups">
                        <label class="form-check-label" for="group-${data.groups[group].id}">
                            ${data.groups[group].name}
                        </label>
                    </div>`
                }
                groupsDiv.html(groupsContent)
            }
        },
    });
});

$('#save_section').on('click', function() {
    let groupsChecked = $('input[name="groups"]:checked');
    let groups = [];
    groupsChecked.each(function() {
        let groupName = $(this).val()
        let groupId = $(this).attr('id').replace('group-', '')
        groups.push({
            name: groupName,
            id: groupId
        });
    });
    $.ajax({
        url: "{{route('save_section')}}",
        method: 'post',
        data: {
            _token,
            store: store.val(),
            branch: branch.val(),
            name: name.val(),
            groups
        },
        success: function(data) {
            if (data.status == 'true') {
                // let html = $(`<tr><th>${id.val()}</th><td>${branch.find('option:selected').text()}</td><td>${name.val()}</td></tr>`)
                id.val(data.new_section)
                name.val('');
                groupsDiv.find('input[type="checkbox"]').each(function() {
                    $(this).prop('checked', false)
                });
                // $('tbody').append(html)
                Swal.fire({
                    icon: 'success',
                    title: data.msg,
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        },
        error: function (reject) {
            let response  = $.parseJSON(reject.responseText);
            $.each(response.errors , function (key, val) {
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ.....',
                    text: val[0],
                });
            });
        }
    });
});


$('#section_name').on('keyup', function () {
    let query = $(this).val()
    searchDb('search_section',query,$(this),branch.val());
});

$(document).on('click', '.search-result li', function(e) {
    e.stopPropagation();
    getData('get_section', $(this).attr('data-id'), function(data) {
        id.val(data.id);
        name.val(data.name);
        $('input[type="checkbox"]').each(function (){$(this).prop('checked', false)})
        $('#store').find(`option[value='${data.sectionstore.store_id}']`).prop('selected', true)
        data.sectiongroup.forEach(group => {
            $(`input#group-${group.group_id}`).prop('checked', true);
        });
        $('#save_section').addClass('d-none');
        $('#update_section').removeClass('d-none');
        $('.search-result').html('');
    });

});

$('#update_section').on('click', function() {
    let groupsChecked = $('input[name="groups"]:checked');
    let groups = [];
    groupsChecked.each(function() {
        let groupName = $(this).val()
        let groupId = $(this).attr('id').replace('group-', '')
        groups.push({
            name: groupName,
            id: groupId
        });
    });

    $.ajax({
        url:"{{route('update.section')}}",
        method:'post',
        data:{
            _token,
            id: id.val(),
            store: store.val(),
            branch: branch.val(),
            name: name.val(),
            groups
        },
        success:function(data)
        {
            if(data.status == 'true') {
                id.val(data.new_section)
                name.val('');
                groupsDiv.find('input[type="checkbox"]').each(function() {
                    $(this).prop('checked', false)
                });
                Swal.fire({
                    icon: 'success',
                    title: data.msg,
                    showConfirmButton: false,
                    timer: 1500
                });
                $('#update_section').addClass('d-none')
                $('#save_section').removeClass('d-none')
            }
        }
    });
});

</script>
