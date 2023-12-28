var morrisCharts = function() {

    Morris.Area({
        element: 'morris-area-example',
        data: [
            { y: '2006', a: 100, b: 90 },
            { y: '2007', a: 75,  b: 65 },
            { y: '2008', a: 50,  b: 40 },
            { y: '2009', a: 75,  b: 65 },
            { y: '2010', a: 50,  b: 40 },
            { y: '2011', a: 75,  b: 65 },
            { y: '2012', a: 100, b: 90 }
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Series A', 'Series B'],   
        resize: true,
        lineColors: ['#1caf9a', '#FEA223']
    });

    Morris.Donut({
        element: 'morris-donut-example',
        data: [
            {label: "Hadir", value: 12},
            {label: "Tidak Hadir", value: 30},
            {label: "Kesiangan", value: 20}
        ],
        colors: ['#95B75D', '#1caf9a', '#FEA223']
    });

}();