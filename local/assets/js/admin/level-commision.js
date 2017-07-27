function getBalance()
{
    var _token =  token;
    var _url =  baseUrl;
    var form_data = { _token:_token };
    var url = _url+'/admin/level-commision/pending/json';
    $(".commission-desc").html('<div class="col-md-12 padding text-center text-primary"><i class="fa fa-spinner fa-spin fa-fw"></i>Loading...</div>');

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType:"json",
        success: function(result)
        {
            if(result.msg == 'success')
            {
                $("#pending_commissions .table-responsive").html(result.data);
                var html = getCommissionDetails(result.available_commission,result.pending_commission,result.withdraw_commission,result.wallet_commission);
                $(".commission-desc").html('<div class="col-md-12 padding"><div class="box">'+html+'</div></div>');
            }
            else
            {
                console.log('No Records Found.');
            }
        }
    });
}

$(".btn-go-fast-withdrawal").click(function(){
    $(".btn-go-fast-withdrawal").attr("disabled", true);
    $(".btn-go-fast-withdrawal span").html('<i class="fa-li fa fa-spinner fa-spin"></i> Wait Updating...');
    $("#new_withdrawal").submit();
});