$(function() {

    if($("#payout_report_range").length > 0) 
    {
        function cb(start, end)
        {
            $('#payout_report_range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
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
            end = moment().add(30, 'days');
            cb(start, end);
        }

        $('#payout_report_range').daterangepicker({
            startDate: start,
            endDate: end,
            showButtonPanel: false,
            showCustomRangeLabel : true,
            ranges: {
                'Tomorrow': [moment(), moment().add(1, 'days')],
                'Next Week': [moment(), moment().add(7, 'days')],
                'Next 2 Weeks': [moment(), moment().add(14, 'days')],
                'Next 3 Weeks': [moment(), moment().add(21, 'days')],
                'Next 30 Days': [moment(), moment().add(30, 'days')],
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last Week': [moment().subtract(6, 'days'), moment()],
                'Last 2 Week': [moment().subtract(13, 'days'), moment()],
                'Last 3 Week': [moment().subtract(21, 'days'), moment()],
                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            }
        }, cb);
    }
});