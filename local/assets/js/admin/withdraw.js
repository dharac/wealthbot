$(".withdraw-report-popup").click(function(){

    if($("#withdraw_report_modal").length == 0) 
    {
        var browserHtml = '<div id="withdraw_report_modal" class="modal" data-backdrop="true"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><h5 class="modal-title">[[ firstname ]]</h5></div><div class="modal-body text-center p-lg"><div class="table-responsive"></div></div><div class="modal-footer"><button type="button" class="btn danger p-x-md" data-dismiss="modal">Close</button></div></div></div></div>';
        $('body').append(browserHtml);
    }
    var id = $(this).attr("data-id");
    $("#withdraw_report_modal").modal('show');

    var _token =  token;
    var _url =  baseUrl;
    var form_data = { id:id,_token:_token};
    var url = _url+'/admin/level-commision/report';
    $("#withdraw_report_modal .table-responsive").html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType:"json",
        success: function(result)
        {   
            if(result.msg == 'success')
            {
                $("#withdraw_report_modal .modal-title").text(result.name+' Information');
                var html = getCommissionDetails(result.data.available_commission,result.data.pending_commission,result.data.withdraw_commission,result.data.wallet_commission);
                $("#withdraw_report_modal .table-responsive").html(html);
            }
            else
            {
                console.log('No Records Found.');
            }
        }
    });
});


$(document).on( "click", ".withdraw-approve-admin", function() {

        var values = new Array();
            $.each($("input[name='withdraw_id[]']:checked"), function() {
              values.push($(this).val());
            });

        if(values.length > 0)
        {
            var r = confirm("Are you sure you want to approve these records?");
            if (r == true) 
            {
                var _token =  token;
                var _url =  baseUrl;
                var form_data = { values:values,_token:_token};
                var url = _url+'/admin/withdraw/approve/checked';
                $('.withdraw-approve-admin').attr('disabled',true);
                $(".error-msg").html('<div class="padding"><div class="col-md-12 text-danger text-center"><i class="fa fa-spinner fa-spin fa-fw"></i> Wait for withdrawal request approval...</div></div>');

                $.ajax({
                        url: url,
                        type: 'POST',
                        data: form_data,
                        dataType:"json",
                        success: function(result)
                        {
                            if(result.msg == 'success')
                            {
                                $(".error-msg").html('<div class="padding"><div class="col-md-12 text-success text-center"><i class="material-icons">&#xE877;</i> Withdrawal request approved</div></div>');

                                var id = "";
                                $.each($("input[name='withdraw_id[]']:checked"), function() {
                                    id = $(this).val();
                                    $(".ch-"+id).remove();
                                    $(".status-"+id).html('<span class="text-success" title="Approved"><i class="material-icons">&#xE877;</i>Approved</span>');
                                });

                            }
                            else
                            {
                                $(".error-msg").html('<div class="padding"><div class="col-md-12 text-danger text-center"><i class="material-icons">&#xE001;</i> Something wrong !</div></div>');
                            }
                            $('.withdraw-approve-admin').attr('disabled',false);
                        }
                });
            }
        }
});