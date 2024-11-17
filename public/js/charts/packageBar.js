var packChartClass;

$(function() {

    const packB = $('#packBar');
    packChartClass.ChartData(packB)
});

packChartClass = {
    ChartData:function(ctx){
        new Chart (ctx, {
            type: 'bar',
            data: {
                labels: packKey,
                datasets: [{
                  label: 'Package Type',
                  data: packVal,
                  backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                  ],
                  borderColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 99, 132)',
                    'rgb(255, 99, 132)',
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


