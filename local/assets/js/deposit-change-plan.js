$(document).on( "click", ".plan-change-popup", function() {
var id = $(this).attr("data-id");
$("#change_plan_modal").modal('show');
var _token =  token;
var _url =  baseUrl;
var form_data = { id:id,_token:_token};
var url = _url+'/user/deposit/plan/data';
$(".message-update").html("");
$("#change_plan_modal .table-responsive table tbody").html('<tr><td colspan="4" class="text-center"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span></td><tr>');

$.ajax({
    url: url,
    type: 'POST',
    data: form_data,
    dataType:"json",
    success: function(result)
    {   
        if(result.msg == 'success')
        {
            $("#change_plan_modal .table-responsive table tbody").html(result.data);
        }
        else
        {
            console.log('No Records Found.');
        }
    }
});
});


$(document).on( "click", ".change-plan-cls", function() {
    var r = confirm("Are you sure you want to change this plan ?");
    if (r == true)
    {
        var pl = $(this).attr("data-pl");
        var dp = $(this).attr("data-dp");
        var _token =  token;
        var _url =  baseUrl;
        var form_data = { pl:pl,dp:dp,_token:_token};
        var url = _url+'/user/deposit/plan/update';
        $("#change_plan_modal").modal('hide');
        $(".message-update").html('<span class="text-danger"><i class="material-icons">&#xE003;</i> Wait updating the plan...</span>');

        $.ajax({
            url: url,
            type: 'POST',
            data: form_data,
            dataType:"json",
            success: function(result)
            {   
                if(result.msg == 'success')
                {
                    $(result.dpMessage).insertAfter(".dp-main-"+dp);
                    $(".plan_ch_btn_"+dp).html('<button class="md-btn md-fab m-b-sm red plan-request-cancel" data-id="'+dp+'" data-pl="'+pl+'" title="Cancel Request"><i class="material-icons md-24">&#xE888;</i></button>');
                    $(".message-update").html('<span class="text-success"><i class="material-icons">&#xE003;</i> Your plan change request is successful. The change plan would be effective from '+result.cycleEnd+'</span>');
                }
                else
                {
                    $(".message-update").html('<span class="text-danger"><i class="material-icons">&#xE888;</i> Something wrong.</span>');   
                }
            }
        });
    }
});


$(document).on( "click", ".plan-request-cancel", function() {
    var r = confirm("Are you sure you want to cancel this request ?");
    if (r == true) 
    {
        var id = $(this).attr("data-id");
        var pl = $(this).attr("data-pl");
        var _token =  token;
        var _url =  baseUrl;
        var form_data = { id:id,pl:pl,_token:_token};
        var url = _url+'/user/deposit/plan/cancel';
        $(".message-update").html('<span class="text-danger"><i class="material-icons">&#xE88B;</i> Wait updating the plan...</span>');

        $.ajax({
            url: url,
            type: 'POST',
            data: form_data,
            dataType:"json",
            success: function(result)
            {   
                if(result.msg == 'success')
                {
                    $(".dp-message-"+id).remove();
                    $(".plan_ch_btn_"+id).html('<button class="md-btn md-fab m-b-sm blue plan-change-popup" title="Click to Change Plan" data-id="'+id+'"><i class="material-icons">&#xE163;</i></button>');
                    $(".message-update").html('<span class="text-success"><i class="material-icons">&#xE877;</i> Request changed successfully.</span>');
                }
                else
                {
                    $(".message-update").html('<span class="text-danger"><i class="material-icons">&#xE888;</i> Something wrong.</span>');   
                }
            }
        });
    }
});