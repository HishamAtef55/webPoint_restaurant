<script>
let _token = $('input[name="_token"]').val();

$(document).ready(function(){
    $('#save').on('click',function (e) {
        e.preventDefault();
        let category  = $('#search_main_table').val();
        let amount    = $('#amount').val();
        let note      = $('#note').val();
        $.ajax({
            url:"{{route('DailyExpenses.save')}}",
            method:'post',
            data:
            {
                _token,
                category,
                amount,
                note,
            },
            success:function(data)
            {
                if(data.status == 'true') {
                    $('#alert_show').show();
                    setTimeout(function () {
                        $('#alert_show').hide();
                    }, 2500);
                    $(`.left ul li`).remove();
                        location.href = '/menu/Show_Table'
                }

            }
        });
    });
});
</script>
