$(".chart-report-popup").click(function(){
var id = $(this).attr("data-id");
$("#chart_report_modal").modal('show');
var _token =  token;
var _url =  baseUrl;
var form_data = { id:id,_token:_token};
var url = _url+'/user/chart';

$.ajax({
    url: url,
    type: 'POST',
    data: form_data,
    dataType:"json",
    success: function(result)
    {   
        if(result.msg == 'success')
        {
            if(result.diff_in_days > 0)
            {
                var processed_json = new Array();   
                $.each( result.data, function( key, value ) {
                    processed_json.push([value[0], parseFloat(value[1]) ]);
                });
                chartCreate(processed_json,result.startDate,result.endDate,result.maxInterest);
            }
            else
            {
                $("#chartLine").html('<h4>No Records Found.</h4>');
            }
        }
        else
        {
            console.log('No Records Found.');
        }
    }
});
});

function chartCreate(chartdata,startdt,enddt,maxInterest)
{
    var chart = new Highcharts.Chart({
          chart: {
             renderTo: 'chartLine',
              type: 'column'
          },
            title: {
                text: ''
            },
            subtitle: {
                text: 'Interest Earnings for the period <b>'+startdt+'</b> to <b>'+enddt+'</b>'
            },
            xAxis: {
                type: 'category',
                labels: {
                    rotation: -45,
                    style: {
                        fontSize: '12px',
                    }
                }
            },
            yAxis: {
                min: 0,
                max: maxInterest,
                title: {
                    text: 'Interest Earned'
                },

                labels: {
                formatter: function () {
                        return '$ '+ this.axis.defaultLabelFormatter.call(this);
                    }            
                }

            },
            legend: {
                enabled: false
            },
            
            tooltip:{
                formatter:function(){
                    var num = this.y;
                    var n = num.toFixed(2)
                    return 'Total Interest earned $'+n+' as on '+this.key+'';
                }
            },

            exporting: { enabled: false },
            series: [{
                name: 'Interest',
                data: chartdata,
                dataLabels: {
                    enabled: true,
                    rotation: -90,
                    color: '#FFFFFF',
                    align: 'right',
                    format: '{point.y:.1f}',
                    y: 10,
                    style: {
                        fontSize: '12px',
                    }
                }
            }]
        });
}