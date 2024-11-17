var lineChartClass;

$(function() {

    const lchart = $('#lineChart');
    lineChartClass.ChartData(lchart)
});

lineChartClass = {
    ChartData:function(ctx){
        new Chart (ctx, {
            type: 'line',
            data: {
                labels: datesLbl,
                datasets: [{
                    label: 'Sales Trend',
                    data: dataVals,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                    }]
                },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                    }
                }
            }
        })
    }
}


