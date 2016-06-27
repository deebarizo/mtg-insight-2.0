/****************************************************************************************
CREATE MANA CURVE CHART
****************************************************************************************/

var manaCurveChart;

manaCurveChart = new Highcharts.Chart({

    chart: {
    	renderTo: 'mana-curve',
        type: 'column',
        spacingLeft: 0,
        spacingBottom: 0
    },
    xAxis: {
        categories: [
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7+',
            'var'
        ],
        title: {
            text: 'Cost'
        }
    },
    title: {
    	text: null
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Number of Cards'
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
        showInLegend: false,
        data: manaCurve
    }],
    credits: false
});