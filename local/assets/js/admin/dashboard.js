$(".view-user-by-plan").click(function(){
    if($("#view_user_by_plan_modal").length == 0)
    {
        var browserHtml = '<div  class="modal fade" id="view_user_by_plan_modal" role="dialog"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Number of Active users per Plan</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default danger" data-dismiss="modal">Close</button></div></div></div></div>';
        $('body').append(browserHtml);
    }

    $("#view_user_by_plan_modal").modal('show');

    var _token =  token;
    var _url =  baseUrl;
    var form_data = { _token:_token};
    var url = _url+'/admin/dashboard/plan-count';
    $("#view_user_by_plan_modal .modal-body").html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>');

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType:"json",
        success: function(result)
        {   
            if(result.msg == 'success')
            {
                $("#view_user_by_plan_modal .modal-body").html(result.html);
            }
            else
            {
                console.log('No Records Found.');
            }
        }
    });

});