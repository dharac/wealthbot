$(".btn-go-fast-deposit").click(function(){
    $(".btn-go-fast-deposit").attr("disabled", true);
    $(".btn-go-fast-deposit span").html('<i class="fa-li fa fa-spinner fa-spin"></i> Wait Updating...');
    $("#new_deposit").submit();
});