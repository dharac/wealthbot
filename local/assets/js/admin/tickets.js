$(document).on( "click", ".ticket-close-admin", function() {
    var values = new Array();
        $.each($("input[name='ticket_id[]']:checked"), function() {
          values.push($(this).val());
        });

    if(values.length > 0)
    {
        var r = confirm("Are you sure ticket close this records ?");
        if (r == true)
        {
            var _token =  token;
            var _url =  baseUrl;
            var form_data = { values:values,_token:_token};
            var url = _url+'/admin/ticket/close';
            $('.ticket-close-admin').attr('disabled',true);
            $(".error-msg").html('<div class="padding"><div class="col-md-12 text-danger text-center"><i class="fa fa-spinner fa-spin fa-fw"></i> Wait for tickets close...</div></div>');

            $.ajax({
                    url: url,
                    type: 'POST',
                    data: form_data,
                    dataType:"json",
                    success: function(result)
                    {
                        if(result.msg == 'success')
                        {
                            $(".error-msg").html('<div class="padding"><div class="col-md-12 text-success text-center"><i class="material-icons">&#xE877;</i> Selected ticket closed</div></div>');

                            var id = "";
                            $.each($("input[name='ticket_id[]']:checked"), function() {
                                id = $(this).val();
                                $(".ch-"+id).remove();
                                $(".status-"+id).html('<span title="Closed">Closed</span>');
                            });

                        }
                        else
                        {
                            $(".error-msg").html('<div class="padding"><div class="col-md-12 text-danger text-center"><i class="material-icons">&#xE001;</i> Something wrong !</div></div>');
                        }
                        $('.ticket-close-admin').attr('disabled',false);
                    }
            });
        }
    }
});


$(".btn-go-fast-ticket").click(function(){
    $(".btn-go-fast-ticket").attr("disabled", true);
    $(".btn-go-fast-ticket span").html('<i class="fa-li fa fa-spinner fa-spin"></i> Wait Updating...');
    $("#new_ticket").submit();
});

$("#ticket_status").change(function(){
    var id = $(this).val();
    var goThere = ''+baseUrl+'/admin/ticket';
    if(id != "")
    {
        var goThere = ''+baseUrl+'/admin/ticket?s='+id+'';
    }
    window.location.href = goThere;
});