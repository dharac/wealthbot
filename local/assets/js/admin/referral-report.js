$(".referral-report-popup").click(function(){

    if($("#referral_report_modal").length == 0)
    {
        var browserHtml = '<div id="referral_report_modal" class="modal" data-backdrop="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">[[ firstname ]]</h5></div><div class="modal-body text-center p-lg"><div class="table-responsive"></div></div><div class="modal-footer"><button type="button" class="btn danger p-x-md" data-dismiss="modal">Close</button></div></div></div></div>';   
        $('body').append(browserHtml);
    }
    var id = $(this).attr("data-id");
    var selId = $("#cmb_user").val();
    $("#referral_report_modal").modal('show');

    var _token =  token;
    var _url =  baseUrl;
    var form_data = { selId:selId,id:id,_token:_token};
    var url = _url+'/admin/referral-report/referral-detail';
    $("#referral_report_modal .table-responsive").html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType:"json",
        success: function(result)
        {   
            if(result.msg == 'success')
            {
                $("#referral_report_modal .modal-title").text(result.name+' Information');
                $("#referral_report_modal .table-responsive").html(result.html);
                $(document).tooltip({
                    selector: '.cls_tooltip',
                    placement: 'top'
                });
            }
            else
            {
                console.log('No Records Found.');
            }
        }
    });

});