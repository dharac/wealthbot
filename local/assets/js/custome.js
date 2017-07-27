var token = $('meta[name="_token"]').attr('content');
var baseUrl = $('meta[name="_url"]').attr('content');

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
});

function googleTranslateElementInit1() {
  new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'pfr,es,hi,ja,zh-CN,zh-TW,ko,pt,bn,de,it,pa,ru,vi,ar,id,ms,tl', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}

$("#country").change(function() {
	var id = $(this).val();
    var _token =  token;
    var _url =  baseUrl;
    var form_data = { id:id,_token:_token};
    var url = _url+'/country/code';

    $.ajax({
            url: url,
            type: 'POST',
            data: form_data,
            dataType:"json",
            success: function(result)
            {
            	if(result.msg == 'success')
            	{
            		$("#countrycode").val(result.code);
            	}
            	else
            	{
            		$("#countrycode").val("");	
            	}
            }
    });
});

$(".btn-go-fast-user-register").click(function(){
    $(".btn-go-fast-user-register").attr("disabled", true);
    $(".btn-go-fast-user-register span").html('<i class="fa fa-hourglass-start" aria-hidden="true"></i> Wait Registering User...');
    $("#new_user_register").submit();
});


$(".btn-go-fast-user-reset").click(function(){
    $(".btn-go-fast-user-reset").attr("disabled", true);
    $(".btn-go-fast-user-reset span").html('<i class="fa fa-hourglass-start" aria-hidden="true"></i> Wait Sending Email...');
    $("#user_reset_password").submit();
});