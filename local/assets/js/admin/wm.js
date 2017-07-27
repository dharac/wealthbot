var fa_spin     = '<i class="fa fa-spinner fa-spin  fa-fw text-danger"></i>';
var fa_trash    = '<i class="fa fa-trash-o"></i>';

$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip(); 
    $(".dateValue").mask("99/99/9999",{placeholder:"mm/dd/yyyy"});
    $('#upload-profile-image').hide();
});

function twoDecimalPoint(value)
{
    value    =  parseFloat(value).toFixed(2);
    return value;
}
function commaSeparateNumber(val)
{
    while (/(\d+)(\d{3})/.test(val.toString()))
    {
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
}

if($("#topNavSearch").length > 0)
{
      var options = {

      url: function(referral) 
      {
        return baseUrl+'/admin/search/live';
      },

      getValue: function(element)
      {
        return element.name;
      },

        list: {
            onClickEvent: function() 
            {
                var id = $("#topNavSearch").getSelectedItemData().id;
                var name = $("#topNavSearch").getSelectedItemData().name;
                var goThere = ''+baseUrl+'/admin/user/view/'+id+'';
                window.location.href = goThere;
            },
            onChooseEvent: function() 
            {
                var id = $("#topNavSearch").getSelectedItemData().id;
                var name = $("#topNavSearch").getSelectedItemData().name;
                var goThere = ''+baseUrl+'/admin/user/view/'+id+'';
                window.location.href = goThere;
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
        //$(".welathbot-search-user").html('<i class="fa-li fa fa-spinner fa-spin"></i>');
        data.referral = $("#topNavSearch").val();
        return data;
      },

      requestDelay: 0
    };
    $("#topNavSearch").easyAutocomplete(options);
}


$(document).on( "click", ".table-col-del", function() {
        var r = confirm("Are you sure delete this record ?");
        if (r == true) 
        {
            var id = $(this).attr('data-id');
            var p_id = $(this).attr('data-pid');
            var _token =  token;
            var _url =  baseUrl;
            var form_data = { id:id,_token:_token};
            var url = _url+'/admin/'+p_id+'/delete';
            $(this).html(fa_spin);

            $.ajax({
                    url: url,
                    type: 'POST',
                    data: form_data,
                    dataType:"json",
                    success: function(result)
                    {
                        var temp_string = '.'+p_id+'_table_row'+id;
                        if(result.msg == 'sucess')
                        {
                            updateIntimation(temp_string,'del');
                        }
                        else
                        {
                            updateIntimation(temp_string,'err');
                            $(this).html(fa_trash);
                        }
                    }
            });
        }
});


$(".table-col-sta").click(function (){
  var r = confirm("Are you sure Change this Status ?");
  if (r == true) 
  {

    var id = $(this).attr('data-id');
    var p_id = $(this).attr('data-pid');
    var _token =  token;
    var _url =  baseUrl;
    var form_data = { id:id,_token:_token};
    var url = _url+'/admin/'+p_id+'/status';
    var update_rec = this;
    $(update_rec).html('<i class="fa fa-spinner fa-spin  fa-fw text-success"></i>');


    $.ajax({
            url: url,
            type: 'POST',
            data: form_data,
            dataType:"json",
            success: function(result)
            {
                if(result.status == 'active')
                {
                    $(update_rec).html('<i class="fa fa-dot-circle-o text-success"></i>');
                    $(".row-col-sta"+id).html('<span class="label label-success" data-placement="top" data-toggle="tooltip" title="Active">Active</span>');
                }
                else
                {
                    $(update_rec).html('<i class="fa fa-dot-circle-o text-danger"></i>');
                    $(".row-col-sta"+id).html('<span class="label label-default" data-placement="top" data-toggle="tooltip" title="Inactive">Inactive</span>');
                }
                updateIntimation(".row-col-sta"+id);
            }
        });
  }
});




function updateIntimation(attribute,status)
{
    if(status == 'del')
    {
        $(attribute).addClass("selected");   
        setTimeout(function() { $(attribute).remove(); }, 500);
    }
    if(status == 'err')
    {
        $(attribute).addClass("selected");   
        setTimeout(function() { $(attribute).remove(); }, 500);
    }
    else
    {
        $(attribute).addClass("selected");
        setTimeout(function() { $(attribute).removeClass("selected"); }, 6000);
    }
}

function dashboardIcon()
{
    if($("span").hasClass("active_total_lenders"))
    {
        var _token =  token;
        var _url =  baseUrl;
        var form_data = { _token:_token};
        var url = _url+'/admin/dashboard/data';
        var spinner = '<i class="fa fa-spinner fa-spin fa-fw"></i>';
        $(".active_total_lenders_percent").html(spinner);
        $(".active_total_lenders_more_than_one_deposit_percent").html(spinner);
        $(".total_withdrawals_pending").html(spinner);
        $(".payout-value").html(spinner);
        $(".loan-value").html(spinner);
        $(".deposit_percent").html(spinner);
        $(".deposit").html(spinner);
        $(".total_user_count").html(spinner);
        $(".total_country").html(spinner);
        $(".remain_total_payment_percent").html(spinner);
        $(".remain_total_payment").html(spinner);
        $(".pending_withdrawl").html(spinner);
        $(".latest_country").html(spinner);
        $(".aLender-value").html(spinner);
        $(".total_tickets_inprogress").html(spinner);
        $(".total_tickets_awaiting_your_reply").html(spinner);
        $(".total_tickets_reopen").html(spinner);
        $(".total_tickets_new").html(spinner);
        $(".total_tickets_pending").html(spinner);
        

        $.ajax({
                url: url,
                type: 'POST',
                data: form_data,
                dataType:"json",
                success: function(result)
                {
                    if(result.msg == 'success')
                    {
                        $(".active_total_lenders_percent").html(result.active_total_lenders_percent);
                        $(".active_total_lenders").html("("+result.active_total_lenders+")");

                        $(".all_lenders").html(result.all_lenders);
                        $(".new_lenders").html(result.new_lenders);

                        $(".total_tickets_reopen").html(result.total_tickets_reopen);
                        $(".total_tickets_new").html(result.total_tickets_new);
                        $(".total_tickets_pending").html(result.total_tickets_pending);
                        $(".total_tickets_inprogress").html(result.total_tickets_inprogress);
                        $(".total_tickets_awaiting_your_reply").html(result.total_tickets_awaiting_your_reply);

                        $(".total_withdrawals_pending").html(result.total_withdrawals_pending);
                        $(".total_withdrawals_approved").html(result.total_withdrawals_approved);
                        $(".total_user_count").html(result.total_user_count);
                        $(".today_user_count").html(result.today_user_count);
                        $(".total_country").html(result.no_of_country);
                        $(".latest_country").html(result.latest_country);
                        
                        $(".remain_total_payment_percent").html(result.percentage_of_payment_not_completed);
                        result.amount_of_payment_not_completed = twoDecimalPoint(result.amount_of_payment_not_completed);
                        result.amount_of_payment_not_completed = commaSeparateNumber(result.amount_of_payment_not_completed);
                        $(".remain_total_payment").html("("+result.amount_of_payment_not_completed+")");
                        
                        result.amount_withdrawals_pending = twoDecimalPoint(result.amount_withdrawals_pending);
                        result.amount_withdrawals_pending = commaSeparateNumber(result.amount_withdrawals_pending);
                        $(".pending_withdrawl").html("$"+result.amount_withdrawals_pending);
                        
                        //Deposite Start
                        $(".dropdown-item-deposite1").attr('data-id',1);
                        $(".dropdown-item-deposite1").attr('data-value',result.deposite['one_percentage']);
                        $(".dropdown-item-deposite1").attr('data-deposite',result.deposite['one_deposite']);

                        $(".dropdown-item-deposite2").attr('data-id',2);
                        $(".dropdown-item-deposite2").attr('data-value',result.deposite['two_percentage']);
                        $(".dropdown-item-deposite2").attr('data-deposite',result.deposite['two_deposite']);

                        $(".dropdown-item-deposite3").attr('data-id',3);
                        $(".dropdown-item-deposite3").attr('data-value',result.deposite['three_percentage']);
                        $(".dropdown-item-deposite3").attr('data-deposite',result.deposite['three_deposite']);


                        var moreThanOne = 0;
                        moreThanOne    =  result.deposite['one_percentage'];
                       
                        $(".deposit_percent").html(moreThanOne);
                        $(".deposit").html("("+result.deposite['one_deposite']+")");
                        $(".deposite_count").html(1);
                        //Deposite End

                        $(".dropdown-item-7").attr('data-id',7);
                        $(".dropdown-item-7").attr('data-value',result.payouttotal[7]);

                        $(".dropdown-item-14").attr('data-id',14);
                        $(".dropdown-item-14").attr('data-value',result.payouttotal[14]);

                        $(".dropdown-item-21").attr('data-id',21);
                        $(".dropdown-item-21").attr('data-value',result.payouttotal[21]);

                        $(".dropdown-item-30").attr('data-id',30);
                        $(".dropdown-item-30").attr('data-value',result.payouttotal[30]);

                        var days7Total = 0;
                        days7Total    =  result.payouttotal[7];
                        days7Total = twoDecimalPoint(days7Total)
                        days7Total = commaSeparateNumber(days7Total);

                        $(".payout-value").html('$ '+days7Total);
                        $(".payout-day").html(7);

                        
                        $(".dropdown-loan-item-24hours").attr('data-id',24);                        
                        $(".dropdown-loan-item-24hours").attr('data-value',result.loantotal[24]);
                        $(".dropdown-loan-item-24hours").attr('data-time',"Hours");                        

                        $(".dropdown-loan-item-48hours").attr('data-id',48);
                        $(".dropdown-loan-item-48hours").attr('data-value',result.loantotal[48]);
                        $(".dropdown-loan-item-48hours").attr('data-time',"Hours");

                        $(".dropdown-loan-item-72hours").attr('data-id',72);
                        $(".dropdown-loan-item-72hours").attr('data-value',result.loantotal[72]);
                        $(".dropdown-loan-item-72hours").attr('data-time',"Hours");

                        $(".dropdown-loan-item-7").attr('data-id',7);
                        $(".dropdown-loan-item-7").attr('data-value',result.loantotal[7]);
                        $(".dropdown-loan-item-7").attr('data-time',"Days");

                        $(".dropdown-loan-item-14").attr('data-id',14);
                        $(".dropdown-loan-item-14").attr('data-value',result.loantotal[14]);
                        $(".dropdown-loan-item-14").attr('data-time',"Days");

                        $(".dropdown-loan-item-21").attr('data-id',21);
                        $(".dropdown-loan-item-21").attr('data-value',result.loantotal[21]);
                        $(".dropdown-loan-item-21").attr('data-time',"Days");

                        $(".dropdown-loan-item-30").attr('data-id',30);
                        $(".dropdown-loan-item-30").attr('data-value',result.loantotal[30]);
                        $(".dropdown-loan-item-30").attr('data-time',"Days");

                        $(".dropdown-loan-item-60").attr('data-id',60);
                        $(".dropdown-loan-item-60").attr('data-value',result.loantotal[60]);
                        $(".dropdown-loan-item-60").attr('data-time',"Days");

                        $(".dropdown-loan-item-90").attr('data-id',90);
                        $(".dropdown-loan-item-90").attr('data-value',result.loantotal[90]);
                        $(".dropdown-loan-item-90").attr('data-time',"Days");

                        $(".dropdown-loan-item-180").attr('data-id',6);
                        $(".dropdown-loan-item-180").attr('data-value',result.loantotal[180]);
                        $(".dropdown-loan-item-180").attr('data-time',"Months");

                        $(".dropdown-loan-item-270").attr('data-id',9);
                        $(".dropdown-loan-item-270").attr('data-value',result.loantotal[270]);
                        $(".dropdown-loan-item-270").attr('data-time',"Months");

                        $(".dropdown-loan-item-360").attr('data-id',12);
                        $(".dropdown-loan-item-360").attr('data-value',result.loantotal[360]);
                        $(".dropdown-loan-item-360").attr('data-time',"Months");

                        $(".dropdown-loan-item-720").attr('data-id',24);
                        $(".dropdown-loan-item-720").attr('data-value',result.loantotal[720]);
                        $(".dropdown-loan-item-720").attr('data-time',"Months");

                        var days7TotalLoan  = 0;
                        hours24TotalLoan    =  twoDecimalPoint(result.loantotal[24]);
                        hours24TotalLoan    = commaSeparateNumber(hours24TotalLoan);

                        $(".loan-value").html('$ '+hours24TotalLoan);
                        $(".loan-day").html(24);
                        $(".loan-time").html("Hours");

                        //Registered User

                        $(".dropdown-user-item-total").attr('data-id',0);                        
                        $(".dropdown-user-item-total").attr('data-value',result.total_user_count);
                        $(".dropdown-user-item-total").attr('data-time',"");


                        $(".dropdown-user-item-24hours").attr('data-id',24);                        
                        $(".dropdown-user-item-24hours").attr('data-value',result.usertotal[24]);
                        $(".dropdown-user-item-24hours").attr('data-time',"Hours");                        

                        $(".dropdown-user-item-48hours").attr('data-id',48);
                        $(".dropdown-user-item-48hours").attr('data-value',result.usertotal[48]);
                        $(".dropdown-user-item-48hours").attr('data-time',"Hours");

                        $(".dropdown-user-item-72hours").attr('data-id',72);
                        $(".dropdown-user-item-72hours").attr('data-value',result.usertotal[72]);
                        $(".dropdown-user-item-72hours").attr('data-time',"Hours");

                        $(".dropdown-user-item-7").attr('data-id',7);
                        $(".dropdown-user-item-7").attr('data-value',result.usertotal[7]);
                        $(".dropdown-user-item-7").attr('data-time',"Days");

                        $(".dropdown-user-item-14").attr('data-id',14);
                        $(".dropdown-user-item-14").attr('data-value',result.usertotal[14]);
                        $(".dropdown-user-item-14").attr('data-time',"Days");

                        $(".dropdown-user-item-21").attr('data-id',21);
                        $(".dropdown-user-item-21").attr('data-value',result.usertotal[21]);
                        $(".dropdown-user-item-21").attr('data-time',"Days");

                        $(".dropdown-user-item-30").attr('data-id',30);
                        $(".dropdown-user-item-30").attr('data-value',result.usertotal[30]);
                        $(".dropdown-user-item-30").attr('data-time',"Days");

                        $(".dropdown-user-item-60").attr('data-id',60);
                        $(".dropdown-user-item-60").attr('data-value',result.usertotal[60]);
                        $(".dropdown-user-item-60").attr('data-time',"Days");

                        $(".dropdown-user-item-90").attr('data-id',90);
                        $(".dropdown-user-item-90").attr('data-value',result.usertotal[90]);
                        $(".dropdown-user-item-90").attr('data-time',"Days");

                        var hours24TotalUser = 0;
                        hours24TotalUser    =  result.usertotal[24];

                        $(".total_user_count").html(result.total_user_count);
                        $(".user_count").html(result.usertotal[24]);



                        $(".dropdown-aLender-item-24hours").attr('data-id',24);                        
                        $(".dropdown-aLender-item-24hours").attr('data-value',result.aLender_total[24]);
                        $(".dropdown-aLender-item-24hours").attr('data-time',"Hours");                        

                        $(".dropdown-aLender-item-48hours").attr('data-id',48);
                        $(".dropdown-aLender-item-48hours").attr('data-value',result.aLender_total[48]);
                        $(".dropdown-aLender-item-48hours").attr('data-time',"Hours");

                        $(".dropdown-aLender-item-72hours").attr('data-id',72);
                        $(".dropdown-aLender-item-72hours").attr('data-value',result.aLender_total[72]);
                        $(".dropdown-aLender-item-72hours").attr('data-time',"Hours");

                        $(".dropdown-aLender-item-7").attr('data-id',7);
                        $(".dropdown-aLender-item-7").attr('data-value',result.aLender_total[7]);
                        $(".dropdown-aLender-item-7").attr('data-time',"Days");

                        $(".dropdown-aLender-item-14").attr('data-id',14);
                        $(".dropdown-aLender-item-14").attr('data-value',result.aLender_total[14]);
                        $(".dropdown-aLender-item-14").attr('data-time',"Days");

                        $(".dropdown-aLender-item-21").attr('data-id',21);
                        $(".dropdown-aLender-item-21").attr('data-value',result.aLender_total[21]);
                        $(".dropdown-aLender-item-21").attr('data-time',"Days");

                        $(".dropdown-aLender-item-30").attr('data-id',30);
                        $(".dropdown-aLender-item-30").attr('data-value',result.aLender_total[30]);
                        $(".dropdown-aLender-item-30").attr('data-time',"Days");

                        $(".dropdown-aLender-item-60").attr('data-id',60);
                        $(".dropdown-aLender-item-60").attr('data-value',result.aLender_total[60]);
                        $(".dropdown-aLender-item-60").attr('data-time',"Days");

                        $(".dropdown-aLender-item-90").attr('data-id',90);
                        $(".dropdown-aLender-item-90").attr('data-value',result.aLender_total[90]);
                        $(".dropdown-aLender-item-90").attr('data-time',"Days");

                        $(".dropdown-aLender-item-6").attr('data-id',6);
                        $(".dropdown-aLender-item-6").attr('data-value',result.aLender_total[6]);
                        $(".dropdown-aLender-item-6").attr('data-time',"Month");

                        var days7TotalLoan = 0;
                        hours24TotalLoan    =   twoDecimalPoint(result.aLender_total[24]);
                        hours24TotalLoan = commaSeparateNumber(hours24TotalLoan);

                        $(".aLender-value").html('$ '+hours24TotalLoan);
                        $(".aLender-day").html(24);
                        $(".aLender-time").html("Hours");

                        duplicatorsIcon();
                        getBalance();
                    }
                }
            });
    }
}


function duplicatorsIcon()
{
    var _token =  token;
    var _url =  baseUrl;
    var form_data = { _token:_token};
    var url = _url+'/admin/dashboard/duplicators';
    var spinner = '<i class="fa fa-spinner fa-spin fa-fw"></i>';
    
    $(".referal-percentage").html(spinner);
    $(".referal-value").html(spinner);
    $(".count_referral").html(spinner);
   
    $(".referal3-percentage").html(spinner);
    $(".referal3-value").html(spinner);
    $(".count3_referral").html(spinner);
    
    $(".referal5-percentage").html(spinner);
    $(".referal5-value").html(spinner);
    $(".count5_referral").html(spinner);

    $.ajax({
        url: url,
        type: 'POST',
        data: form_data,
        dataType:"json",
        success: function(result)
        {

            if(result.msg == 'success')
            {
                //Second Level Duplicator Start
                $(".dropdown-item-referal1").attr('data-id',1);
                $(".dropdown-item-referal1").attr('data-value',result.aDuplicators['one_percentage']);
                $(".dropdown-item-referal1").attr('data-referal',result.aDuplicators['one']);
                $(".dropdown-item-referal1").attr('data-username',result.aDuplicators['one_username']);
                $(".dropdown-item-referal1").attr('data-userID',result.aDuplicators['one_userId']);
               
                $(".dropdown-item-referal2").attr('data-id',2);
                $(".dropdown-item-referal2").attr('data-value',result.aDuplicators['two_percentage']);
                $(".dropdown-item-referal2").attr('data-referal',result.aDuplicators['two']);
                $(".dropdown-item-referal2").attr('data-username',result.aDuplicators['two_username']);
                $(".dropdown-item-referal2").attr('data-userID',result.aDuplicators['two_userId']);

                $(".dropdown-item-referal3").attr('data-id',3);
                $(".dropdown-item-referal3").attr('data-value',result.aDuplicators['three_percentage']);
                $(".dropdown-item-referal3").attr('data-referal',result.aDuplicators['three']);
                $(".dropdown-item-referal3").attr('data-username',result.aDuplicators['three_username']);
                $(".dropdown-item-referal3").attr('data-userID',result.aDuplicators['three_userId']);
                
                $(".dropdown-item-referal10").attr('data-id',10);
                $(".dropdown-item-referal10").attr('data-value',result.aDuplicators['ten_percentage']);
                $(".dropdown-item-referal10").attr('data-referal',result.aDuplicators['ten']);
                $(".dropdown-item-referal10").attr('data-username',result.aDuplicators['ten_username']);
                $(".dropdown-item-referal10").attr('data-userID',result.aDuplicators['ten_userId']);

                var moreThanOne = 0;
                moreThanOne    =  result.aDuplicators['one_percentage'];
               
                $(".referal-percentage").html(moreThanOne+" %");
                $(".referal-value").html(result.aDuplicators['one']);
                $(".count_referral").html(1);

                var user_listing    = result.aDuplicators['one_username'];
                var user_id         = result.aDuplicators['one_userId'];
                var list_html       = '<div class="row row-sm">';
                var res             = user_listing.split(",");
                var userId          = user_id.split(",");

                for(var i = 0; i < res.length; i++) {
                    list_html      += '<div class="col-xs-12 col-lg-3"><div class="list-item box r m-b"><a herf="" class="list-left"><span class="w-40 circle blue">'+res[i][0]+'<i class="busy b-white bottom"></i></span></a><div class="list-body"><div class="text-ellipsis"><a href="'+baseUrl+'/admin/user/referrals/'+userId[i]+'" target="_blank">'+res[i]+'</a></div></div></div></div>';
                }
                list_html           += '</div>';

                $(".duplicators_count").html(1);
                $(".user_listing").html(list_html);

                //Second Level Duplicator End

                //Tihrd Level Duplicator Start
                $(".dropdown-item-referal31").attr('data-id',1);
                $(".dropdown-item-referal31").attr('data-value',result.aThirdLevelDuplicators['one_percentage']);
                $(".dropdown-item-referal31").attr('data-referal',result.aThirdLevelDuplicators['one']);
                $(".dropdown-item-referal31").attr('data-username',result.aThirdLevelDuplicators['one_username']);
                $(".dropdown-item-referal31").attr('data-userID',result.aThirdLevelDuplicators['one_userId']);
               
                $(".dropdown-item-referal32").attr('data-id',2);
                $(".dropdown-item-referal32").attr('data-value',result.aThirdLevelDuplicators['two_percentage']);
                $(".dropdown-item-referal32").attr('data-referal',result.aThirdLevelDuplicators['two']);
                $(".dropdown-item-referal32").attr('data-username',result.aThirdLevelDuplicators['two_username']);
                $(".dropdown-item-referal32").attr('data-userID',result.aThirdLevelDuplicators['two_userId']);

                $(".dropdown-item-referal33").attr('data-id',3);
                $(".dropdown-item-referal33").attr('data-value',result.aThirdLevelDuplicators['three_percentage']);
                $(".dropdown-item-referal33").attr('data-referal',result.aThirdLevelDuplicators['three']);
                $(".dropdown-item-referal33").attr('data-username',result.aThirdLevelDuplicators['three_username']);
                $(".dropdown-item-referal33").attr('data-userID',result.aThirdLevelDuplicators['three_userId']);
                
                $(".dropdown-item-referal310").attr('data-id',10);
                $(".dropdown-item-referal310").attr('data-value',result.aThirdLevelDuplicators['ten_percentage']);
                $(".dropdown-item-referal310").attr('data-referal',result.aThirdLevelDuplicators['ten']);
                $(".dropdown-item-referal310").attr('data-username',result.aThirdLevelDuplicators['ten_username']);
                $(".dropdown-item-referal310").attr('data-userID',result.aThirdLevelDuplicators['ten_userId']);

                var moreThanOne = 0;
                moreThanOne    =  result.aThirdLevelDuplicators['one_percentage'];
               
                $(".referal3-percentage").html(moreThanOne+" %");
                $(".referal3-value").html(result.aThirdLevelDuplicators['one']);
                $(".count3_referral").html(1);

                var user_listing    = result.aThirdLevelDuplicators['one_username'];
                var user_id         = result.aThirdLevelDuplicators['one_userId'];
                var list_html       = '<div class="row row-sm">';
                var res             = user_listing.split(",");
                var userId          = user_id.split(",");

                for(var i = 0; i < res.length; i++) {
                    list_html      += '<div class="col-xs-12 col-lg-3"><div class="list-item box r m-b"><a herf="" class="list-left"><span class="w-40 circle blue">'+res[i][0]+'<i class="busy b-white bottom"></i></span></a><div class="list-body"><div class="text-ellipsis"><a href="'+baseUrl+'/admin/user/referrals/'+userId[i]+'" target="_blank">'+res[i]+'</a></div></div></div></div>';
                }
                list_html           += '</div>';

                $(".duplicators_count3").html(1);
                $(".user_listing3").html(list_html);

                //Third Level Duplicator End


                //Fifth Level Duplicator Start
                $(".dropdown-item-referal51").attr('data-id',1);
                $(".dropdown-item-referal51").attr('data-value',result.aFifthLevelDuplicators['one_percentage']);
                $(".dropdown-item-referal51").attr('data-referal',result.aFifthLevelDuplicators['one']);
                $(".dropdown-item-referal51").attr('data-username',result.aFifthLevelDuplicators['one_username']);
                $(".dropdown-item-referal51").attr('data-userID',result.aFifthLevelDuplicators['one_userId']);
               
                $(".dropdown-item-referal52").attr('data-id',2);
                $(".dropdown-item-referal52").attr('data-value',result.aFifthLevelDuplicators['two_percentage']);
                $(".dropdown-item-referal52").attr('data-referal',result.aFifthLevelDuplicators['two']);
                $(".dropdown-item-referal52").attr('data-username',result.aFifthLevelDuplicators['two_username']);
                $(".dropdown-item-referal52").attr('data-userID',result.aFifthLevelDuplicators['two_userId']);

                $(".dropdown-item-referal53").attr('data-id',3);
                $(".dropdown-item-referal53").attr('data-value',result.aFifthLevelDuplicators['three_percentage']);
                $(".dropdown-item-referal53").attr('data-referal',result.aFifthLevelDuplicators['three']);
                $(".dropdown-item-referal53").attr('data-username',result.aFifthLevelDuplicators['three_username']);
                $(".dropdown-item-referal53").attr('data-userID',result.aFifthLevelDuplicators['three_userId']);
                
                $(".dropdown-item-referal510").attr('data-id',10);
                $(".dropdown-item-referal510").attr('data-value',result.aFifthLevelDuplicators['ten_percentage']);
                $(".dropdown-item-referal510").attr('data-referal',result.aFifthLevelDuplicators['ten']);
                $(".dropdown-item-referal510").attr('data-username',result.aFifthLevelDuplicators['ten_username']);
                $(".dropdown-item-referal510").attr('data-userID',result.aFifthLevelDuplicators['ten_userId']);

                var moreThanOne = 0;
                moreThanOne    =  result.aFifthLevelDuplicators['one_percentage'];
               
                $(".referal5-percentage").html(moreThanOne+" %");
                $(".referal5-value").html(result.aFifthLevelDuplicators['one']);
                $(".count5_referral").html(1);

                var user_listing    = result.aFifthLevelDuplicators['one_username'];
                var user_id         = result.aFifthLevelDuplicators['one_userId'];
                var list_html       = '<div class="row row-sm">';
                var res             = user_listing.split(",");
                var userId          = user_id.split(",");

                for(var i = 0; i < res.length; i++) {
                    list_html      += '<div class="col-xs-12 col-lg-3"><div class="list-item box r m-b"><a herf="" class="list-left"><span class="w-40 circle blue">'+res[i][0]+'<i class="busy b-white bottom"></i></span></a><div class="list-body"><div class="text-ellipsis"><a href="'+baseUrl+'/admin/user/referrals/'+userId[i]+'" target="_blank">'+res[i]+'</a></div></div></div></div>';
                }
                list_html           += '</div>';

                $(".duplicators_count5").html(1);
                $(".user_listing5").html(list_html);

            }
        }
    });
}

$(document).on( "click", ".dropdown-menu-scale-payout .dropdown-item", function() {

    var id      = $(this).attr('data-id');
    var value   = $(this).attr('data-value');
    value = twoDecimalPoint(value)
    value = commaSeparateNumber(value);
    $(".payout-value").html('$ '+value);
    $(".payout-day").html(id);

});


$(document).on( "click", ".dropdown-menu-scale-loan .dropdown-item", function() {

    var id      = $(this).attr('data-id');
    var value   = $(this).attr('data-value');
    var time    = $(this).attr('data-time');
    value       = twoDecimalPoint(value);
    value = commaSeparateNumber(value);
    $(".loan-value").html('$ '+value);
    $(".loan-day").html(id);
    $(".loan-time").html(time);

});

$(document).on( "click", ".dropdown-menu-scale-user .dropdown-item", function() {


    var id      = $(this).attr('data-id');
    var value   = $(this).attr('data-value');
    var time   = $(this).attr('data-time');

    $(".user_count").html(value);
    if(id!=0){
        $(".user-day").html(id);
        $(".user-time").html(time);        
    } 
});

$(document).on( "click", ".dropdown-menu-scale-deposite .dropdown-item", function() {

    var id      = $(this).attr('data-id');
    var percentage   = $(this).attr('data-value');
    var deposite   = $(this).attr('data-deposite');

    $(".deposit_percent").html(percentage);
    $(".deposite_count").html(id);
    $(".deposit").html("("+deposite+")");

});

$(document).on( "click", ".dropdown-menu-scale-aLender .dropdown-item", function() {

    var id      = $(this).attr('data-id');
    var value   = $(this).attr('data-value');
    var time   = $(this).attr('data-time');
    value = twoDecimalPoint(value);
    value = commaSeparateNumber(value);
    $(".aLender-value").html('$ '+value);
    $(".aLender-day").html(id);
    $(".aLender-time").html(time);

});

$(document).on( "click", ".dropdown-menu-scale-referal .dropdown-item", function() {

    var id              = $(this).attr('data-id');
    if(id == 10) {
        id = "10+";
    }
    var value           = $(this).attr('data-value');
    var referal         = $(this).attr('data-referal');
    var user_listing    = $(this).attr('data-username');
    var userID          = $(this).attr('data-userID');

    value               = twoDecimalPoint(value);
    value               = commaSeparateNumber(value);

    var list_html       = '<div class="row row-sm">';
    var res             = user_listing.split(",");
    var userId          = userID.split(",");

    for(var i = 0; i < res.length; i++) {
        list_html      += '<div class="col-xs-12 col-lg-3"><div class="list-item box r m-b"><a herf="" class="list-left"><span class="w-40 circle blue">'+res[i][0]+'<i class="busy b-white bottom"></i></span></a><div class="list-body"><div class="text-ellipsis"><a target="_blank" href="'+baseUrl+'/admin/user/referrals/'+userId[i]+'">'+res[i]+'</a></div></div></div></div>';
    }
    list_html           += '</div>';

    $(".referal-value").html(referal);
    $(".referal-percentage").html(value +" %");
    $(".count_referral").html(id);
    $(".duplicators_count").html(id);
    $(".user_listing").html(list_html);
});



$(document).on( "click", ".dropdown-menu-scale-referal3 .dropdown-item", function() {

    var id              = $(this).attr('data-id');
    if(id == 10) {
        id = "10+";
    }
    var value           = $(this).attr('data-value');
    var referal         = $(this).attr('data-referal');
    var user_listing    = $(this).attr('data-username');
    var userID          = $(this).attr('data-userID');

    value               = twoDecimalPoint(value);
    value = commaSeparateNumber(value);

    var list_html       = '<div class="row row-sm">';
    var res             = user_listing.split(",");
    var userId          = userID.split(",");

    for(var i = 0; i < res.length; i++) {
        list_html      += '<div class="col-xs-12 col-lg-3"><div class="list-item box r m-b"><a herf="" class="list-left"><span class="w-40 circle blue">'+res[i][0]+'<i class="busy b-white bottom"></i></span></a><div class="list-body"><div class="text-ellipsis"><a target="_blank" href="'+baseUrl+'/admin/user/referrals/'+userId[i]+'">'+res[i]+'</a></div></div></div></div>';
    }
    list_html           += '</div>';

    $(".referal3-value").html(referal);
    $(".referal3-percentage").html(value +" %");
    $(".count3_referral").html(id);
    $(".duplicators_count3").html(id);
    $(".user_listing3").html(list_html);
});

$(document).on( "click", ".dropdown-menu-scale-referal5 .dropdown-item", function() {

    var id              = $(this).attr('data-id');
    if(id == 10) {
        id = "10+";
    }
    var value           = $(this).attr('data-value');
    var referal         = $(this).attr('data-referal');
    var user_listing    = $(this).attr('data-username');
    var userID          = $(this).attr('data-userID');

    value               = twoDecimalPoint(value);
    value = commaSeparateNumber(value);

    var list_html       = '<div class="row row-sm">';
    var res             = user_listing.split(",");
    var userId          = userID.split(",");

    for(var i = 0; i < res.length; i++) {
        list_html      += '<div class="col-xs-12 col-lg-3"><div class="list-item box r m-b"><a herf="" class="list-left"><span class="w-40 circle blue">'+res[i][0]+'<i class="busy b-white bottom"></i></span></a><div class="list-body"><div class="text-ellipsis"><a target="_blank" href="'+baseUrl+'/admin/user/referrals/'+userId[i]+'">'+res[i]+'</a></div></div></div></div>';
    }
    list_html           += '</div>';

    $(".referal5-value").html(referal);
    $(".referal5-percentage").html(value +" %");
    $(".count5_referral").html(id);
    $(".duplicators_count5").html(id);
    $(".user_listing5").html(list_html);
});

function notificationCount()
{
    $.ajax({
            url: baseUrl+'/countNotify',
            type: 'get',
            data: { },
            dataType:"json",
            success: function(result)
            {
                $(".info-number-notification").text("");
                $(".info-number-notification").addClass('hide');
                $(".ticket-response-count").html("");
                if(result.msg == 'success')
                {
                    $(".info-number-notification").removeClass('hide');
                    $(".info-number-notification").text(result.messageCount);
                    if(result.ticketCount > 0)
                    {
                        $(".ticket-response-count").html('<b class="label label-sm red">'+result.ticketCount+'</b>');
                    }
                }
            }
    });
}

$( document ).ready(function() {
    notificationCount();
    dashboardIcon();
    setInterval(function(){ notificationCount(); }, 100000);
});

$(document).on( "click", "#info-message-notification", function() {
    var loading = '<li class="list-group-item info lt box-shadow-z0 b"> <span class="clear block"><i class="material-icons">&#xE8B5;</i> Your notifications live here.<br><small class="text-muted">Wait..</small></span></li>';
    $("#info-message-notification-area").html(loading);
    $.ajax({
        url: baseUrl+'/getNotify',
        type: 'get',
        data: { },
        dataType:"json",
        success: function(result)
        {
            if(result.msg == 'success')
            {
                $("#info-message-notification-area").html(result.message);
            }
            else
            {
                $("#info-message-notification-area").html(result.message);
            }
            notificationCount();
        }
    });
});

$(document).ready(function() {
      var currentUrl = window.location.href;
      $('#aside_admin_panel li').removeClass('active');
      $('#aside_admin_panel li ul li a[href="' + currentUrl + '"]').parent('li').parent('ul').parent('li').addClass('active');
      $('#aside_admin_panel li ul li a[href="' + currentUrl + '"]').parent('li').addClass('active');
      $('#aside_admin_panel li a[href="' + currentUrl + '"]').parent('li').addClass('active');

    $(document).on( "click", "#linkSearchMaster", function() {
        $( "#masterSearch" ).slideToggle( "slow", function() {
            $("#txtMasterSearch").focus();
        });
    });

    $(document).on( "click", "#chkMasterCheckbox", function() {
        if($(this).is(':checked'))
        {
            $('.chkSubCheckbox').prop('checked', true);
        } 
        else 
        {
            $('.chkSubCheckbox').prop('checked', false);
        }
    });
});

$(document).on( "click", ".flat", function() {
    if ($('#plan_statusY').is(':checked'))
    {
        $(".plan_end_area").removeClass('hide');
    }
    else
    {
        $(".plan_end_area").addClass('hide');
    }

    if ($('#type_a').is(':checked'))
    {
        $(".user_area").addClass('hide');
    }
    else
    {
        $(".user_area").removeClass('hide');
    }

    if ($('#type_html').is(':checked'))
    {
        $(".url_area").addClass('hide');
        $(".html_area").removeClass('hide');
    }
    else
    {
        $(".url_area").removeClass('hide');
        $(".html_area").addClass('hide');
    }
});

jQuery('.replys').click(function(e) {
$('html, body').animate({
    scrollTop: $("#replys").offset().top
}, 1000);
});


$(function() {

    if($("#reportrange").length > 0) 
    {
        function cb(start, end)
        {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $("#startdt").val(start.format('M/D/YYYY'));
            $("#enddt").val(end.format('M/D/YYYY'));
        }

        var start   = $(".box-header-data").attr('data-stdt');
        var end     = $(".box-header-data").attr('data-endt');

        if(start != "" && end != "")
        {
            start       =  moment(start, "MM-DD-YYYY");
            end         =  moment(end, "MM-DD-YYYY");
            cb(start, end);
        }
        else
        {
            start = moment();
            end = moment();
            cb(start, end);
        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
             showButtonPanel: false,
            ranges: {
               'Today': [moment(), moment()],
               'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
               'Last 7 Days': [moment().subtract(6, 'days'), moment()],
               'Last 30 Days': [moment().subtract(29, 'days'), moment()],
               'This Month': [moment().startOf('month'), moment().endOf('month')],
               'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
    }
});


function userAutoFillup(id)
{
    var _token =  token;
    var _url =  baseUrl;
    var form_data = { id:id,_token:_token};
    var url = _url+'/admin/user/autofill';
    $( ".userAutoFillup" ).after('<p class="userFetch"><i class="fa-li fa fa-spinner fa-spin"></i>User Loading...</p>');

    $.ajax({
            url: url,
            type: 'POST',
            data: form_data,
            dataType:"json",
            success: function(result)
            {
                if(result.msg == 'sucess')
                {
                    var data    = result.data.length;
                    var sid     = "";
                    var snm     = "";
                    var selv    = "";
                    for(i=0; i < data; i++)
                    {
                        selv = "";
                        sid  = result.data[i].id;
                        snm  = result.data[i].first_name+' '+result.data[i].last_name+' | '+result.data[i].username;

                        if(id === sid) 
                        { 
                            selv = "selected='selected'";
                            $(".select2-selection__rendered").attr('title',snm);
                            $(".select2-selection__rendered").text(snm);
                        }
                        $(".userAutoFillup").append('<option value="'+sid+'" '+selv+'>'+snm+'</option>');
                    }
                }
                $(".userFetch").remove();
            }
    });
}

function getCommissionDetails(available_commission = null ,pending_commission = null ,withdraw_commission = null,wallet_commission = null)
{
        var available_commission    =  parseFloat(available_commission).toFixed(2);
        var pending_commission      =  parseFloat(pending_commission).toFixed(2);
        var withdraw_commission     =  parseFloat(withdraw_commission).toFixed(2);
        var wallet_commission       =  parseFloat(wallet_commission).toFixed(2);

        var afterwith = 0;
        afterwith = parseFloat(available_commission) - parseFloat(withdraw_commission) - parseFloat(wallet_commission);
        afterwith = parseFloat(afterwith).toFixed(2);

        var withdraw_commission1    = withdraw_commission;
        var wallet_commission1      = wallet_commission;
        available_commission        = commaSeparateNumber(available_commission);
        withdraw_commission         = commaSeparateNumber(withdraw_commission);
        wallet_commission           = commaSeparateNumber(wallet_commission);

        afterwith               = commaSeparateNumber(afterwith);
        pending_commission      = commaSeparateNumber(pending_commission);


        var html = '<table class="table">'
        +'<thead>'
          +'<tr>'
            +'<th style="width: 60px">#</th>'
            +'<th>DESCRIPTION</th>'
            +'<th style="width: 140px">AMOUNT ($)</th>'
          +'</tr>'
        +'</thead>'
        +'<tbody>'
          +'<tr class="text-primary">'
            +'<td>1</td>'
            +'<td class="text-left">Available Commission</td>'
            +'<td class="text-right"> $ '+available_commission+'</td>'
          +'</tr>';
           if(withdraw_commission1 > 0)
          {
            html += '<tr class="text-danger">'
            +'<td colspan="2" class="text-right"><strong>Withdrawn Commissions</strong></td>'
            +'<td class="text-right"> - $ '+withdraw_commission+'</td>'
            +'</tr>';
          }
          if(wallet_commission1 > 0)
          {
            html += '<tr class="text-danger">'
            +'<td colspan="2" class="text-right"><strong>Transfer in Wallet</strong></td>'
            +'<td class="text-right"> - $ '+wallet_commission+'</td>'
            +'</tr>';
          }
          html += '<tr>'
            +'<td colspan="2" class="text-right"><strong>Available Commission Total</strong></td>'
            +'<td class="text-right"> $ '+afterwith+'</td>'
            +'</tr>';
        html += '<tr class="text-info">'
            +'<td>2</td>'
            +'<td class="text-left">Pending Commission ( Next 30 Days )</td>'
            +'<td class="text-right"> $ '+pending_commission+'</td>'
          +'</tr>';
        html += '</tbody></table>';

      return html;
}

$(document).on( "click", ".upload-result", function() {

    if(document.getElementById("upload").files.length == 0)
    {
        $('.response_messsage').html('<span class="text-danger"><i class="material-icons">&#xE192;</i> Please select profile picture.</span>');
        return false;
    }

    var _token =  token;
    var _url =  baseUrl;
    var url = _url+'/admin/user/images';
    $(".upload-result").attr("disabled", true);
    $(".upload-result span").html('<i class="material-icons">&#xE192;</i> Wait Updating...');
    $('.response_messsage').html('<span class="text-danger"><i class="material-icons">&#xE192;</i> Wait... Uploading image.</span>');
  
    $uploadCrop.croppie('result', {
        type: 'canvas',
        size: 'viewport'
    }).then(function (resp)
        {
            $.ajax({
                url: url,
                type: "POST",
                data: {"image":resp,_token:_token},
                success: function (data)
                {
                     console.log(data);
                   if(data.msg == 'success')
                   {
                        $('.response_messsage').html('<i class="material-icons">&#xE003;</i> Profile Picture Successfully Uploaded.');
                        $('#profile_picture_modal').modal('hide');
                        $('#upload-profile-image').hide();
                        $('.user-profile-picture').attr("src",data.profile_name);
                    }
                    else
                    {
                        $('.response_messsage').html("Somthing Want Wrong.");
                    }
                    $(".upload-result").attr("disabled", false);
                    $(".upload-result span").html('<i class="material-icons">&#xE2C3;</i> Update Image');
                }
            });
    });
});

$(document).on( "click", ".change_profile_image", function() {

    if($("#profile_picture_modal").length == 0)
    {
        var browserHtml = '<div id="profile_picture_modal" class="modal fade " role="dialog"><div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-header"><button type="button" class="close" data-dismiss="modal">&times;</button><h4 class="modal-title">Profile Picture </h4><span class="response_messsage"></span></div><div class="modal-body"><div class="box-body"><div class="container"><div class="panel panel-default"><div class="panel-body"><div class="row"><div class="upload-btn-wrapper col-md-12 text-center"><button class="btn btn-outline btn-sm b-primary text-primary btn"><i class="material-icons">&#xE3AF;</i> Select Image</button><input type="file" id="upload"></div><div class="col-md-12 text-center"><div id="upload-profile-image"></div></div></div></div></div></div></div></div><div class="modal-footer"><button type="button" class="btn danger p-x-md" data-dismiss="modal">Close</button>&nbsp;&nbsp;&nbsp;<button type="button" class="btn btn-fw primary upload-result"><span><i class="material-icons">&#xE2C3;</i> Update Image</span></button></div></div></div></div>';
        $('body').append(browserHtml);
    }
    $("#profile_picture_modal").modal('show');
});

$(document).on( "change", "#upload", function() {
        $('#upload-profile-image').show();
        $('#upload-profile-image').html("");

        $uploadCrop = $('#upload-profile-image').croppie({
            enableExif: true,
            viewport: {
                width: 200,
                height: 200,
                type: 'rectangle'
            },
            boundary: {
                width: 300,
                height: 300
            }
        });

        var reader = new FileReader();
        reader.onload = function (e) {
            $uploadCrop.croppie('bind', {
                url: e.target.result
            }).then(function()
            { });
        }

        reader.readAsDataURL(this.files[0]);
});