$('.founder_sustainablity').change(function(){
    if(this.checked)
    {
        $(this).attr('value',1);
        $('.founder_sustainablity_textbox').removeClass('hide');

    }
    else
    {
         $(this).attr('value',0);
         $('.founder_sustainablity_textbox').addClass('hide');
    }
});

$('.non_founder_sustainablity').change(function(){
    if(this.checked)
    {
        $(this).attr('value',1);
        $('.non_founder_sustainablity_textbox').removeClass('hide');
    }
    else
    {
        $(this).attr('value',0);
        $('.non_founder_sustainablity_textbox').addClass('hide');
    }
});