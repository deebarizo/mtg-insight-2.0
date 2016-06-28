var colorBreakdownChart;

colorBreakdownChart = new Highcharts.Chart({

    chart: {
    	renderTo: 'color-breakdown',
        type: 'column',
        spacingLeft: 0,
        spacingBottom: 0
    },
    xAxis: {
        categories: [
            'W',
            'U',
            'B',
            'R',
            'G',
            'C'
        ],
        title: {
            text: 'Cost'
        },
		labels: {
			x: 6,
	        useHTML: true,
	        formatter: function () {
	            return {
	                'W': '<i class="mi mi-mana mi-shadow mi-2x mi-w"></i>',
	                'U': '<i class="mi mi-mana mi-shadow mi-2x mi-u"></i>',
	                'B': '<i class="mi mi-mana mi-shadow mi-2x mi-b"></i>',
	                'R': '<i class="mi mi-mana mi-shadow mi-2x mi-r"></i>',
	                'G': '<i class="mi mi-mana mi-shadow mi-2x mi-g"></i>',
	                'C': '<i class="mi mi-mana mi-shadow mi-2x mi-c"></i>'
	            }[this.value];
	        }
	    }
    },
    title: {
    	text: null
    },
    yAxis: {
        min: 0,
        title: {
            text: null
        }
    },
    tooltip: {
        headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
        pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
            '<td style="padding:0"><b>{point.y:.1f} mm</b></td></tr>',
        footerFormat: '</table>',
        shared: true,
        useHTML: true
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'Mana Symbols',
        data: colorStats.symbols
    }, {
        name: 'Mana Sources',
        data: colorStats.sources
    }],
    credits: false
});