function populate_chartPlanSummaryPerMachine(id, period, mach_number, shift, url){
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data:{
            flag: 'get_ctPlanSummaryPerMachine',
            period: period,
            mach_number: mach_number,
            shift: shift
        },
        destroy:'true',
        success: function(response){
            var chart = {
                    zoomType: 'xy'
            };

            var title = {
                    text: null,
                    align:'left'
            };

            var subtitle = {
                text: null,
                align:'left'
            };

            var xAxis = [{
                categories: [],
                crosshair: true,
                gridLineWidth: 1,
                title: {
                    text: null
                }
            }];

            var yAxis = [{ // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                title: {
                    text: 'Loading ( minutes )',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                opposite: true,
                min:0,
                max:1000
            }, { // Secondary yAxis
                gridLineWidth: 1,
                title: {
                    text: 'Loading',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                min:0,
                max:100
            }, { // Tertiary yAxis
                gridLineWidth: 1,
                title: {
                    text: 'Capacity ( minutes )',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                opposite: true,
                min:0,
                max:1000
            }];

            var tooltip = {
                shared: true
             };

            var plotOptions = {
                xy: {
                    dataLabels: {
                        enabled: true
                    }
                }
            };

            var legend = {
                layout: 'horizontal',
                align: 'left',
                x: 80,
                verticalAlign: 'top',
                y: 0,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
            };

            var credits = {
                enabled: false
            };

            var series = [
                {
                    name: 'Loading ( % )',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' %'
                    }
                },
                {
                    name: 'Capacity ( min )',
                    type: 'spline',
                    yAxis: 2,
                    data: [],
                    marker:
                    {
                        enabled: false
                    },
                    dashStyle: 'shortdot',
                    tooltip:
                    {
                        valueSuffix: ' min'
                    }
                },
                {
                    name: 'Loading ( min )',
                    type: 'spline',
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' min'
                    }
            }];

            var responsive = {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                floating: false,
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                x: 0,
                                y: 0
                            },
                            yAxis: [{
                                labels: {
                                    align: 'right',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                labels: {
                                    align: 'left',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                visible: false
                            }]
                        }
                    }]
            };

            var len = 0;
            if(response != null){
                len = response.length;
            }
            if(len > 0){
                // Read data and create <option >
                let load_pctg = new Array(len);
                let load_min = new Array(len);
                let capt_min = new Array(len);
                let nDate = new Array(len);

                for(var i=0; i<len; i++){
                    load_pctg[i] = response[i].load_pctg;
                    load_min[i] = response[i].load_min;
                    capt_min[i] = parseFloat(response[i].capacity_min);
                    nDate[i] = response[i].ndate+" "+response[i].nday;
                }
                //alert("Message: " + load_min);
                series[0].data = load_pctg;
                series[1].data = capt_min;
                series[2].data = load_min;
                xAxis[0].categories = nDate;

            } else {
                series[0].data = [0];
                series[1].data = [0];
                series[2].data = [0];
                xAxis[0].categories = [period];
            }

            var json = {};
                json.chart = chart;
                json.title = title;
                json.subtitle = subtitle;
                json.tooltip = tooltip;
                json.xAxis = xAxis;
                json.yAxis = yAxis;
                json.series = series;
                json.plotOptions = plotOptions;
                json.legend = legend;
                json.credits = credits;
                json.responsive = responsive;

                $(id).highcharts(json);
        }
    });

}

function populate_chartPlanSummaryPerMachineByOp(id, period, machine, process, url){
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data:{
            flag: 'get_ctPlanSummaryPerMachineByOp',
            period: period,
            machine: machine,
            process: process,
            plan_date: null,
            switch: 1
        },
        destroy:'true',
        success: function(response){
            var chart = {
                    zoomType: 'xy'
            };

            var title = {
                    text: null,
                    align:'left'
            };

            var subtitle = {
                text: null,
                align:'left'
            };

            var xAxis = [{
                categories: [],
                crosshair: true,
                gridLineWidth: 1,
                title: {
                    text: null
                }
            }];

            var yAxis = [{ // Primary yAxis
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                title: {
                    text: 'Loading ( minutes )',
                    style: {
                        color: Highcharts.getOptions().colors[2]
                    }
                },
                opposite: true,
                min:0,
                max:1000
            }, { // Secondary yAxis
                gridLineWidth: 1,
                title: {
                    text: 'Loading',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                labels: {
                    format: '{value} %',
                    style: {
                        color: Highcharts.getOptions().colors[0]
                    }
                },
                min:0,
                max:100
            }, { // Tertiary yAxis
                gridLineWidth: 1,
                title: {
                    text: 'Capacity ( minutes )',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                labels: {
                    format: '{value}',
                    style: {
                        color: Highcharts.getOptions().colors[1]
                    }
                },
                opposite: true,
                min:0,
                max:1000
            }];

            var tooltip = {
                shared: true
             };

            var plotOptions = {
                xy: {
                    dataLabels: {
                        enabled: true
                    }
                }
            };

            var legend = {
                layout: 'horizontal',
                align: 'left',
                x: 80,
                verticalAlign: 'top',
                y: 0,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
            };

            var credits = {
                enabled: false
            };

            var series = [
                {
                    name: 'Loading ( % )',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' %'
                    }
                },
                {
                    name: 'Capacity ( min )',
                    type: 'spline',
                    yAxis: 2,
                    data: [],
                    marker:
                    {
                        enabled: false
                    },
                    dashStyle: 'shortdot',
                    tooltip:
                    {
                        valueSuffix: ' min'
                    }
                },
                {
                    name: 'Loading ( min )',
                    type: 'spline',
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' min'
                    }
            }];

            var responsive = {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                floating: false,
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                x: 0,
                                y: 0
                            },
                            yAxis: [{
                                labels: {
                                    align: 'right',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                labels: {
                                    align: 'left',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                visible: false
                            }]
                        }
                    }]
            };

            var len = 0;
            if(response != null){
                len = response.length;
            }
            if(len > 0){
                // Read data and create <option >
                let load_pctg = new Array(len);
                let load_min = new Array(len);
                let capt_min = new Array(len);
                let nDate = new Array(len);

                for(var i=0; i<len; i++){
                    load_pctg[i] = response[i].load_pctg;
                    load_min[i] = response[i].load_min;
                    capt_min[i] = parseFloat(response[i].capacity_min);
                    nDate[i] = response[i].plan_date;
                }
                //alert("Message: " + load_min);
                //alert("Message: " + load_pctg);
                series[0].data = load_pctg;
                series[1].data = capt_min;
                series[2].data = load_min;
                xAxis[0].categories = nDate;

            } else {
                series[0].data = [0];
                series[1].data = [0];
                series[2].data = [0];
                xAxis[0].categories = [period];
            }

            var json = {};
                json.chart = chart;
                json.title = title;
                json.subtitle = subtitle;
                json.tooltip = tooltip;
                json.xAxis = xAxis;
                json.yAxis = yAxis;
                json.series = series;
                json.plotOptions = plotOptions;
                json.legend = legend;
                json.credits = credits;
                json.responsive = responsive;

                $(id).highcharts(json);
        }
    });
}

function populate_chartPlanSummaryPerDate(id, period, process, shift, url){
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data:{
            flag: 'get_ctPlanSummaryPerDate',
            period: period,
            process: process,
            shift: shift
        },
        destroy:'true',
        success: function(response){
            var len = 0;
            if(response != null){
                len = response.length;
            }
            if(len > 0){
                // Read data and create <option >
                    var chart = {
                            zoomType: 'xy'
                    };

                    var title = {
                            text: null,
                            align:'left'
                        };

                    var subtitle = {
                            text: null,
                            align:'left'
                        };

                    var xAxis = [{
                            categories: [],
                            crosshair: true,
                            gridLineWidth: 1,
                            title: {
                                text: null
                            }
                        }];

                    var yAxis = [{ // Primary yAxis
                            labels: {
                                format: '{value}',
                                style: {
                                    color: Highcharts.getOptions().colors[2]
                                }
                            },
                            title: {
                                text: 'Loading ( minutes )',
                                style: {
                                    color: Highcharts.getOptions().colors[2]
                                }
                            },
                            opposite: true,
                            min:0,
                            max:1000
                        }, { // Secondary yAxis
                            gridLineWidth: 1,
                            title: {
                                text: 'Loading',
                                style: {
                                    color: Highcharts.getOptions().colors[0]
                                }
                            },
                            labels: {
                                format: '{value} %',
                                style: {
                                    color: Highcharts.getOptions().colors[0]
                                }
                            },
                            min:0,
                            max:100

                        }, { // Tertiary yAxis
                            gridLineWidth: 1,
                            title: {
                                text: 'Capacity ( minutes )',
                                style: {
                                    color: Highcharts.getOptions().colors[1]
                                }
                            },
                            labels: {
                                format: '{value}',
                                style: {
                                    color: Highcharts.getOptions().colors[1]
                                }
                            },
                            opposite: true,
                            min:0,
                            max:1000
                        }];

                    var tooltip = {
                            shared: true
                        };

                    var plotOptions = {
                            xy: {
                                dataLabels: {
                                    enabled: true
                                }
                            }
                        };

                    var legend = {
                        layout: 'horizontal',
                        align: 'left',
                        x: 80,
                        verticalAlign: 'top',
                        y: 0,
                        floating: true,
                        backgroundColor:
                            Highcharts.defaultOptions.legend.backgroundColor || // theme
                            'rgba(255,255,255,0.25)'
                        };

                    var credits = {
                        enabled: false
                        };

                    var series = [
                        {
                            name: 'Loading ( % )',
                            type: 'column',
                            yAxis: 1,
                            data: [],
                            tooltip:
                            {
                                valueSuffix: ' %'
                            }
                        },
                        {
                            name: 'Capacity ( min )',
                            type: 'spline',
                            yAxis: 2,
                            data: [],
                            marker:
                            {
                                enabled: false
                            },
                            dashStyle: 'shortdot',
                            tooltip:
                            {
                                valueSuffix: ' min'
                            }
                        },
                        {
                            name: 'Loading ( min )',
                            type: 'spline',
                            data: [],
                            tooltip:
                            {
                                valueSuffix: ' min'
                            }
                        }];

                    var responsive = {
                            rules: [{
                                condition: {
                                    maxWidth: 500
                                },
                                chartOptions: {
                                    legend: {
                                        floating: false,
                                        layout: 'horizontal',
                                        align: 'center',
                                        verticalAlign: 'bottom',
                                        x: 0,
                                        y: 0
                                    },
                                    yAxis: [{
                                        labels: {
                                            align: 'right',
                                            x: 0,
                                            y: -6
                                        },
                                        showLastLabel: false
                                    }, {
                                        labels: {
                                            align: 'left',
                                            x: 0,
                                            y: -6
                                        },
                                        showLastLabel: false
                                    }, {
                                        visible: false
                                    }]
                                }
                            }]
                        };

                    let load_pctg = new Array(len);
                    let load_min = new Array(len);
                    let capt_min = new Array(len);
                    let machine = new Array(len);

                    for(var i=0; i<len; i++){
                        load_pctg[i] = response[i].load_pctg;
                        load_min[i] = response[i].load_min;
                        capt_min[i] = parseFloat(response[i].capacity_min);
                        machine[i] = response[i].machine;
                    }
                    //alert("Message: " + capt_min);
                    //alert("Message: " + load_min);
                    series[0].data = load_pctg;
                    series[1].data = capt_min;
                    series[2].data = load_min;
                    xAxis[0].categories = machine;

                    var json = {};
                    json.chart = chart;
                    json.title = title;
                    json.subtitle = subtitle;
                    json.tooltip = tooltip;
                    json.xAxis = xAxis;
                    json.yAxis = yAxis;
                    json.series = series;
                    json.plotOptions = plotOptions;
                    json.legend = legend;
                    json.credits = credits;
                    json.responsive = responsive;

                    $(id).highcharts(json);

            } else {
                //alert("Tidak ada data Planning untuk mesin " + def_var2);
            }
        }
    });
}

function populate_chartPlanSummaryPerMonth(id, period, process, offset, limit, url){
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data:{
            flag:'get_ctPlanSummaryPerMonth',
            period: period,
            process: process,
            offset: offset,
            limit: limit
        },
        destroy:'true',
        success: function(response){
            var chart = {
                zoomType: 'xy'
                };
            var title = {
                    text: null,
                    align:'left'
                };
            var subtitle = {
                    text: null,
                    align:'left'
                };
            var xAxis = [{
                    categories: [],
                    crosshair: true,
                    gridLineWidth:2,
                    title: {
                        text: null
                    }
                }];
            var yAxis = [
                { // Secondary yAxis
                    gridLineWidth: 1,
                    title: {
                        text: 'Loading (hours)',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} hrs',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    min:0, max:16
                }, { // Tertiary yAxis
                    gridLineWidth: 0,
                    title: {
                        text: 'Loading ( hours )',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    labels: {
                        format: '{value} hrs',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true,
                    min:0, max:16
                }];
            var tooltip = {
                    shared: true
                };
            var plotOptions = {
                    xy: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                };
            var legend = {
                layout: 'horizontal',
                align: 'left',
                x: 80,
                verticalAlign: 'top',
                y: 0,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
                };
            var credits = {
                enabled: false
                };
            var series = [
                {
                    name: 'Period (N-3) month',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    }
                },
                {
                    name: 'Period (N-2) month',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    }
                },
                {
                    name: 'Period (N-1) month',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    }
                },
                {
                    name: 'Period (N) month',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    }
                },
                {
                    name: '1 shift',
                    type: 'spline',
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    },
                    marker:
                    {
                        enabled: true,
                        radius: 3
                    },
                    dashStyle: 'shortdot',
                },
                {
                    name: '2 shift',
                    type: 'spline',
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    },
                    marker:
                        {
                            enabled: true,
                            radius: 3
                        },
                    dashStyle: 'shortdot'
                },
                {
                    name: '2 shift + 3 hrs',
                    type: 'spline',
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    },
                    marker:
                        {
                            enabled: true,
                            radius: 3
                        },
                    dashStyle: 'shortdot'
                }
                ];
            var responsive = {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                floating: false,
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                x: 0,
                                y: 0
                            },
                            yAxis: [{
                                labels: {
                                    align: 'right',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                labels: {
                                    align: 'left',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                visible: false
                            }]
                        }
                    }]
                };

            var len = 0;
            if(response != null){
                len = response.length;
            }
            if(len > 0){
                // Read data and create <option >
                let np_ld = new Array(len);
                let n1p_ld = new Array(len);
                let n2p_ld = new Array(len);
                let n3p_ld = new Array(len);
                let s1 = new Array(len);
                let s2 = new Array(len);
                let s3 = new Array(len);
                let machine = new Array(len);

                for(var i=0; i<len; i++){
                    np_ld[i] = parseFloat(response[i].np_ld);
                    n1p_ld[i] = parseFloat(response[i].n1p_ld);
                    n2p_ld[i] = parseFloat(response[i].n2p_ld);
                    n3p_ld[i] = parseFloat(response[i].n3p_ld);
                    s1[i] = parseFloat(response[i].s1);
                    s2[i] = parseFloat(response[i].s2);
                    s3[i] = parseFloat(response[i].s3);
                    machine[i] = response[i].machine;
                }

                series[0].data = n3p_ld;
                series[1].data = n2p_ld;
                series[2].data = n1p_ld;
                series[3].data = np_ld;
                series[4].data = s1;
                series[5].data = s2;
                series[6].data = s3;
                xAxis[0].categories = machine;
                //alert(machine);
                //series[0].data=[3, 2, 1, 3, 4]; //(N-3)
                //series[1].data=[4, 3, 3, 5, 3]; //(N-2)
                //series[2].data=[5, 6, 7, 6, 5]; //(N-1)
                //series[3].data=[2, 5, 3, 4, 9]; //(N)
                //series[4].data=[5, 5, 5, 5, 5];
                //series[5].data=[8, 8, 8, 8, 8];
                //series[6].data=[10, 10, 10, 10, 10];
                //xAxis[0].categories=['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums'];

            } else {
                //alert("Tidak ada data Planning untuk mesin " + def_var2);
                series[0].data=[0]; //(N-3)
                series[1].data=[0]; //(N-2)
                series[2].data=[0]; //(N-1)
                series[3].data=[0]; //(N)
                series[4].data=[0];
                series[5].data=[0];
                series[6].data=[0];
                xAxis[0].categories=['N/A'];
            }

            var json = {};
                json.chart = chart;
                json.title = title;
                json.subtitle = subtitle;
                json.tooltip = tooltip;
                json.xAxis = xAxis;
                json.yAxis = yAxis;
                json.series = series;
                json.plotOptions = plotOptions;
                json.legend = legend;
                json.credits = credits;
                json.responsive = responsive;
            $(id).highcharts(json);
        }
    });
}

function populate_chartPlanSummaryPerMonthByOp(id, period, process, offset, limit, url){
    $.ajax({
        url: url,
        type: 'get',
        dataType: 'json',
        data:{
            flag:'get_ctPlanSummaryPerMonthByOp',
            period: period,
            process: process,
            offset: offset,
            limit: limit
        },
        destroy:'true',
        success: function(response){
            var chart = {
                zoomType: 'xy'
                };
            var title = {
                    text: null,
                    align:'left'
                };
            var subtitle = {
                    text: null,
                    align:'left'
                };
            var xAxis = [{
                    categories: [],
                    crosshair: true,
                    gridLineWidth:2,
                    title: {
                        text: null
                    }
                }];
            var yAxis = [
                { // Secondary yAxis
                    gridLineWidth: 1,
                    title: {
                        text: 'Loading (hours)',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    labels: {
                        format: '{value} hrs',
                        style: {
                            color: Highcharts.getOptions().colors[0]
                        }
                    },
                    min:0, max:16
                }, { // Tertiary yAxis
                    gridLineWidth: 0,
                    title: {
                        text: 'Loading ( hours )',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    labels: {
                        format: '{value} hrs',
                        style: {
                            color: Highcharts.getOptions().colors[1]
                        }
                    },
                    opposite: true,
                    min:0, max:16
                }];
            var tooltip = {
                    shared: true
                };
            var plotOptions = {
                    xy: {
                        dataLabels: {
                            enabled: true
                        }
                    }
                };
            var legend = {
                layout: 'horizontal',
                align: 'left',
                x: 80,
                verticalAlign: 'top',
                y: 0,
                floating: true,
                backgroundColor:
                    Highcharts.defaultOptions.legend.backgroundColor || // theme
                    'rgba(255,255,255,0.25)'
                };
            var credits = {
                enabled: false
                };
            var series = [
                {
                    name: 'Period (N-3) month',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    }
                },
                {
                    name: 'Period (N-2) month',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    }
                },
                {
                    name: 'Period (N-1) month',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    }
                },
                {
                    name: 'Period (N) month',
                    type: 'column',
                    yAxis: 1,
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    }
                },
                {
                    name: '1 shift',
                    type: 'spline',
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    },
                    marker:
                    {
                        enabled: true,
                        radius: 3
                    },
                    dashStyle: 'shortdot',
                },
                {
                    name: '2 shift',
                    type: 'spline',
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    },
                    marker:
                        {
                            enabled: true,
                            radius: 3
                        },
                    dashStyle: 'shortdot'
                },
                {
                    name: '2 shift + 3 hrs',
                    type: 'spline',
                    data: [],
                    tooltip:
                    {
                        valueSuffix: ' hours'
                    },
                    marker:
                        {
                            enabled: true,
                            radius: 3
                        },
                    dashStyle: 'shortdot'
                }
                ];
            var responsive = {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                        chartOptions: {
                            legend: {
                                floating: false,
                                layout: 'horizontal',
                                align: 'center',
                                verticalAlign: 'bottom',
                                x: 0,
                                y: 0
                            },
                            yAxis: [{
                                labels: {
                                    align: 'right',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                labels: {
                                    align: 'left',
                                    x: 0,
                                    y: -6
                                },
                                showLastLabel: false
                            }, {
                                visible: false
                            }]
                        }
                    }]
                };

            var len = 0;
            if(response != null){
                len = response.length;
            }
            if(len > 0){
                // Read data and create <option >
                let np_ld = new Array(len);
                let n1p_ld = new Array(len);
                let n2p_ld = new Array(len);
                let n3p_ld = new Array(len);
                let s1 = new Array(len);
                let s2 = new Array(len);
                let s3 = new Array(len);
                let machine = new Array(len);

                for(var i=0; i<len; i++){
                    np_ld[i] = parseFloat(response[i].np_ld);
                    n1p_ld[i] = parseFloat(response[i].n1p_ld);
                    n2p_ld[i] = parseFloat(response[i].n2p_ld);
                    n3p_ld[i] = parseFloat(response[i].n3p_ld);
                    s1[i] = parseFloat(response[i].s1);
                    s2[i] = parseFloat(response[i].s2);
                    s3[i] = parseFloat(response[i].s3);
                    machine[i] = response[i].machine;
                }

                series[0].data = n3p_ld;
                series[1].data = n2p_ld;
                series[2].data = n1p_ld;
                series[3].data = np_ld;
                series[4].data = s1;
                series[5].data = s2;
                series[6].data = s3;
                xAxis[0].categories = machine;
                //alert(machine);
                //series[0].data=[3, 2, 1, 3, 4]; //(N-3)
                //series[1].data=[4, 3, 3, 5, 3]; //(N-2)
                //series[2].data=[5, 6, 7, 6, 5]; //(N-1)
                //series[3].data=[2, 5, 3, 4, 9]; //(N)
                //series[4].data=[5, 5, 5, 5, 5];
                //series[5].data=[8, 8, 8, 8, 8];
                //series[6].data=[10, 10, 10, 10, 10];
                //xAxis[0].categories=['Apples', 'Oranges', 'Pears', 'Bananas', 'Plums'];

            } else {
                //alert("Tidak ada data Planning untuk mesin " + def_var2);
                series[0].data=[0]; //(N-3)
                series[1].data=[0]; //(N-2)
                series[2].data=[0]; //(N-1)
                series[3].data=[0]; //(N)
                series[4].data=[0];
                series[5].data=[0];
                series[6].data=[0];
                xAxis[0].categories=['N/A'];
            }

            var json = {};
                json.chart = chart;
                json.title = title;
                json.subtitle = subtitle;
                json.tooltip = tooltip;
                json.xAxis = xAxis;
                json.yAxis = yAxis;
                json.series = series;
                json.plotOptions = plotOptions;
                json.legend = legend;
                json.credits = credits;
                json.responsive = responsive;
            $(id).highcharts(json);
        }
    });
}
