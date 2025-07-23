/*
Template Name: Lexa - Admin & Dashboard Template
Author: Themesbrand
Website: https://themesbrand.com/
Contact: themesbrand@gmail.com
File: Dashboard
*/

function getChartColorsArray(chartId) {
    if (document.getElementById(chartId) !== null) {
        var colors = document.getElementById(chartId).getAttribute("data-colors");
        if (colors) {
            colors = JSON.parse(colors);
            return colors.map(function (value) {
                var newValue = value.replace(" ", "");
                if (newValue.indexOf(",") === -1) {
                    var color = getComputedStyle(document.documentElement).getPropertyValue(
                        newValue
                    );
                    if (color) return color;
                    else return newValue;
                } else {
                    var val = value.split(",");
                    if (val.length == 2) {
                        var rgbaColor = getComputedStyle(
                            document.documentElement
                        ).getPropertyValue(val[0]);
                        rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                        return rgbaColor;
                    } else {
                        return newValue;
                    }
                }
            });
        } else {
            console.warn('data-colors Attribute not found on:', chartId);
        }
    }
}

// Morris Code
function ChartColorChange(chartupdate, chartId) {
    document.querySelectorAll(".theme-color").forEach(function (item) {
        item.addEventListener("click", function (event) {
            setTimeout(function() {
                var updatechartColors = getChartColorsArray(chartId);
                if(chartupdate.options){
                    if(chartupdate.options["colors"]){
                        chartupdate.options["colors"] = updatechartColors;
                    }else if(chartupdate.options["lineColors"]){
                        chartupdate.options["lineColors"] = updatechartColors;
                    }else if(chartupdate.options["barColors"]){
                        chartupdate.options["barColors"] = updatechartColors;
                    }
                    chartupdate.redraw();
                }
            }, 0);
        });
    });
}

// Sparkline Code
function ChartColorChangeSparkLine(series, chartupdate, chartId) {
    document.querySelectorAll(".theme-color").forEach(function (item) {
        item.addEventListener("click", function (event) {
            setTimeout(function() {
                var updatechartColors = getChartColorsArray(chartId);
                chartupdate.barColor = updatechartColors;
                $('#'+ chartId).sparkline(series, chartupdate);
            }, 0);
        });
    });
}

!function($) {
    "use strict";

    var Dashboard = function() {};
    
    //creates area chart
    Dashboard.prototype.createAreaChart = function (element, pointSize, lineWidth, data, xkey, ykeys, labels, lineColors) {
        var areaChart = Morris.Area({
            element: element,
            pointSize: 0,
            lineWidth: 1,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            labels: labels,
            resize: true,
             gridLineColor: 'rgba(108, 120, 151, 0.1)',
            hideHover: 'auto',
            lineColors: lineColors,
            fillOpacity: .9,
            behaveLikeLine: true
        });
        ChartColorChange(areaChart,'morris-area-example');
    },

    //creates Donut chart
    Dashboard.prototype.createDonutChart = function (element, data, colors) {
        var donutChart = Morris.Donut({
            element: element,
            data: data,
            resize: true,
            colors: colors
        });
        ChartColorChange(donutChart,'morris-donut-example');
    },

    //creates Stacked chart
    Dashboard.prototype.createStackedChart  = function(element, data, xkey, ykeys, labels, lineColors) {
        var barChart = Morris.Bar({
            element: element,
            data: data,
            xkey: xkey,
            ykeys: ykeys,
            stacked: true,
            labels: labels,
            hideHover: 'auto',
            resize: true, //defaulted to true
             gridLineColor: 'rgba(108, 120, 151, 0.1)',
            barColors: lineColors
        });
        ChartColorChange(barChart,'morris-bar-stacked');
    },
    
    Dashboard.prototype.init = function() {
        
        //creating area chart
        var areaEXChartColors = getChartColorsArray("morris-area-example");
        if (areaEXChartColors) {
        var $areaData = [
            {y: '2011', a: 0, b: 0, c:0},
            {y: '2012', a: 150, b: 45, c:15},
            {y: '2013', a: 60, b: 150, c:195},
            {y: '2014', a: 180, b: 36, c:21},
            {y: '2015', a: 90, b: 60, c:360},
            {y: '2016', a: 75, b: 240, c:120},
            {y: '2017', a: 30, b: 30, c:30}
        ];
        this.createAreaChart('morris-area-example', 0, 0, $areaData, 'y', ['a', 'b', 'c'], ['Series A', 'Series B', 'Series C'], areaEXChartColors);
    }
        //creating donut chart
        var donutEXChartColors = getChartColorsArray("morris-donut-example");
        if (donutEXChartColors) {
        var $donutData = [
            {label: "Download Sales", value: 12},
            {label: "In-Store Sales", value: 30},
            {label: "Mail-Order Sales", value: 20}
        ];
        this.createDonutChart('morris-donut-example', $donutData, donutEXChartColors);
    }

        var barStackedChartColors = getChartColorsArray("morris-bar-stacked");
        if (barStackedChartColors) {
        var $stckedData  = [
            { y: '2005', a: 45, b: 180},
            { y: '2006', a: 75,  b: 65},
            { y: '2007', a: 100, b: 90},
            { y: '2008', a: 75,  b: 65},
            { y: '2009', a: 100, b: 90},
            { y: '2010', a: 75,  b: 65},
            { y: '2011', a: 50,  b: 40},
            { y: '2012', a: 75,  b: 65},
            { y: '2013', a: 50,  b: 40},
            { y: '2014', a: 75,  b: 65},
            { y: '2015', a: 100, b: 90},
            { y: '2016', a: 80, b: 65}
        ];
        this.createStackedChart('morris-bar-stacked', $stckedData, 'y', ['a', 'b'], ['Series A', 'Series B'], barStackedChartColors);
    }
    },
    //init
    $.Dashboard = new Dashboard, $.Dashboard.Constructor = Dashboard
}(window.jQuery),

//initializing 
function($) {
    "use strict";
    $.Dashboard.init();
}(window.jQuery);


var sparklineChart1Colors = getChartColorsArray("sparkline");
if (sparklineChart1Colors) {
    var series = [8, 6, 4, 7, 10, 12, 7, 4, 9, 12, 13, 11, 12];
    var chartoption = {
        type: 'bar',
        height: '130',
        barWidth: '10',
        barSpacing: '7',
        barColor: '#7A6FBE'
    };
    var demo = $('#sparkline').sparkline(series, chartoption);
    ChartColorChangeSparkLine(series,chartoption,'sparkline');
}