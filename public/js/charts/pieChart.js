var pieChartClass;

$(function() {

    const pchart = $('#pieChart');
    pieChartClass.ChartData(pchart)
});

pieChartClass = {
    ChartData:function(ctx){
        new Chart (ctx, {
            type: 'doughnut',
            data: {
                labels: servKey,
                datasets: [{
                    label: 'Service Type',
                    data: servVal,
                    backgroundColor: [
                        'rgb(255, 99, 132)',
                        'rgb(54, 162, 235)',
                        'rgb(255, 205, 86)',
                        'rgb(211, 205, 86)',
                        'rgb(158, 25, 86)',
                        'rgb(58, 77, 227)',
                        'rgb(8, 209, 16)'
                    ],
                    hoverOffset: 4,
                    }],
                },
            
        })
    }
}


