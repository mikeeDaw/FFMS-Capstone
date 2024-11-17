var barChartClass;

$(function() {

    const bchart = $('#barChart');
    barChartClass.ChartData(bchart)
});

barChartClass = {
    ChartData:function(ctx){
        new Chart (ctx, {
            type: 'bar',
            data: {
                labels: categKey,
                datasets: [{
                  label: 'Categories',
                  data: categVal,
                  backgroundColor: [
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                    'rgba(255, 205, 86, 0.2)',
                  ],
                  borderColor: [
                    'rgb(255, 205, 86)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 205, 86)',
                    'rgb(255, 205, 86)',
                  ],
                  borderWidth: 1
                }]              
                },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                      ticks: {
                        beginAtZero: true,
                        callback: function(value) {if (value % 1 === 0) {return value;}},
                      },
                      suggestedMin: 0,
                      suggestedMax: 10,
                    }
                  }
            }
        })
    }
}


