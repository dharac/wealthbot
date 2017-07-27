$("#nature_of_plan").change(function(){
    var id = $(this).val();
    var goThere = ''+baseUrl+'/admin/interest-payment/pending';
    if(id != "")
    {
        var goThere = ''+baseUrl+'/admin/interest-payment/pending/'+id+'';
    }
    window.location.href = goThere;
});