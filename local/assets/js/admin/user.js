if($("#autocomplete_referral").length > 0)
{
      var options = {

      url: function(referral) 
      {
        return baseUrl+'/admin/autosuggest/referral';
      },

      getValue: function(element) 
      {
        return element.name;
      },

        list: {
            onSelectItemEvent: function() {
                var value = $("#autocomplete_referral").getSelectedItemData().id;
                $("#autocomplete_referral_id").val(value).trigger("change");
            }
        },

      ajaxSettings: {
        dataType: "json",
        method: "POST",
        data: {
          dataType: "json",
            _token:token,
        }
      },

      preparePostData: function(data) {
        data.referral = $("#autocomplete_referral").val();
        return data;
      },

      requestDelay: 0
    };
    $("#autocomplete_referral").easyAutocomplete(options);

    $( ".removeReferrer" ).click(function() {
        $("#autocomplete_referral").val("");
        $("#autocomplete_referral_id").val("");
    });
}

$(".user-export-popup").click(function(){

    $("#user_export_modal").modal('show');

    var _token =  token;
    var _url =  baseUrl;
    var form_data = { _token:_token};
    var url = _url+'/admin/user/column';
    $("#user_export_modal .table-responsive").html('<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span>');

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType:"json",
        success: function(result)
        {   
            if(result.msg == 'success')
            {
                var html = "";
                jQuery.each( result.usersArray, function( key, val ) {

                    html += '<div class="col-md-4 col-xs-6 text-left"><p><label class="md-check"><input type="checkbox" name="column[]" value="'+key+'" class="has-value"><i class="indigo"></i>'+val+'</label></p></div>';
                });

                $("#user_export_modal .table-responsive").html(html);
            }
            else
            {
                console.log('No Records Found.');
            }
        }
    });
});

$(".btn-go-fast-user").click(function(){
    $(".btn-go-fast-user").attr("disabled", true);
    $(".btn-go-fast-user span").html('<i class="fa-li fa fa-spinner fa-spin"></i> Wait Updating...');
    $("#new_user").submit();
});

$(".view-upper-level").click(function(){
    if($("#view_upper_level_modal").length == 0)
    {
        var browserHtml = '<div  class="modal fade" id="view_upper_level_modal" role="dialog"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Level Referrer</h4></div><div class="modal-body"></div><div class="modal-footer"><button type="button" class="btn btn-default danger" data-dismiss="modal">Close</button></div></div></div></div>';
        $('body').append(browserHtml);
    }

    $("#view_upper_level_modal").modal('show');
    var id = $(this).attr("data-id");

    var _token =  token;
    var _url =  baseUrl;
    var form_data = { id:id,_token:_token};
    var url = _url+'/admin/user/level-referrer';
    $("#view_upper_level_modal .modal-body").html('<div class="text-center"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span></div>');

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType:"json",
        success: function(result)
        {   
            if(result.msg == 'success')
            {
                $("#view_upper_level_modal .modal-body").html(result.html);
            }
            else
            {
                console.log('No Records Found.');
            }
        }
    });

});