/**
 * WofhTools
 *
 * delphinpro <delphinpro@gmail.com>
 * copyright © 2014—2019 delphinpro
 * licensed under the MIT license
 */


const familyBase = '"Open Sans", sans-serif';
const familyHead = '"Roboto Slab", serif';

const backgroundColor = '#26292d';

export const defaultChartSettings = {};

export const darkTheme = {
    credits: {
        enabled: false,
        href: 'http://wofh-tools.ru/stat',
        text: 'wofh-tools.ru/stat',
    },
    colors: [
        '#dddf0d',
        '#7798bf',
        '#55bf3b',
        '#df5353',
        '#aaeeee',
        '#ff0066',
        '#eeaaee',
        '#55bf3b',
        '#df5353',
        '#7798bf',
        '#aaeeee',
        '#2f3337',
    ],
    chart: {
        backgroundColor,
        borderWidth: 1,
        borderRadius: 0,
        borderColor: '#3c4246',
        plotBackgroundColor: null,
        plotShadow: false,
        plotBorderWidth: 0,

        defaultSeriesType: 'spline',
        style: {
            fontSize: '12px',
            fontFamily: familyBase,
        },
    },
    title: {
        style: {
            color: '#ccc',
            fontSize: '16px',
            fontFamily: familyHead,
        },
    },
    subtitle: {
        style: {
            color: '#DDD',
            fontSize: '12px',
            fontFamily: familyHead,
        },
    },
    xAxis: {
        gridLineWidth: 0,
        lineColor: '#404245',
        tickColor: '#404245',
        labels: {
            style: {
                color: '#999',
            },
        },
        title: {
            style: {
                color: '#AAA',
                font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif',
            },
        },
    },
    yAxis: {
        alternateGridColor: null,
        minorTickInterval: null,
        gridLineColor: 'rgba(255, 255, 255, .05)',
        minorGridLineColor: 'rgba(255,255,255,0.025)',
        lineWidth: 0,
        tickWidth: 0,
        labels: {
            style: {
                color: '#999',
            },
        },
        title: {
            style: {
                color: '#AAA',
                font: 'bold 12px Lucida Grande, Lucida Sans Unicode, Verdana, Arial, Helvetica, sans-serif',
            },
        },
    },
    legend: {
        itemStyle: { color: '#999' },
        itemHoverStyle: { color: '#ccc' },
        itemHiddenStyle: { color: '#333' },
        backgroundColor: '#1d1f22',
    },
    labels: {
        style: { color: '#CCC' },
    },
    tooltip: {
        backgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
                [0, 'RGBA(59,61,68,0.8)'],
                [1, 'RGBA(59,61,68,0.8)'],
            ],
        },
        borderWidth: 0,
        shadow: false,
        style: { color: '#eee' },
    },

    plotOptions: {
        series: { shadow: false },
        line: {
            dataLabels: { color: '#CCC' },
            marker: { lineColor: '#333' },
        },
        spline: {
            marker: { lineColor: '#333' },
        },
        scatter: {
            marker: { lineColor: '#333' },
        },
        candlestick: { lineColor: 'white' },
    },

    toolbar: {
        itemStyle: { color: '#CCC' },
    },

    navigation: {
        buttonOptions: {
            symbolStroke: '#DDDDDD',
            hoverSymbolStroke: '#FFFFFF',
            theme: {
                fill: {
                    linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                    stops: [
                        [0.4, '#606060'],
                        [0.6, '#333333'],
                    ],
                },
                stroke: '#000000',
            },
        },
    },

    // scroll charts
    rangeSelector: {
        buttonTheme: {
            fill: {
                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                stops: [
                    [0.4, '#888'],
                    [0.6, '#555'],
                ],
            },
            stroke: '#000000',
            style: {
                color: '#CCC',
                fontWeight: 'bold',
            },
            states: {
                hover: {
                    fill: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0.4, '#BBB'],
                            [0.6, '#888'],
                        ],
                    },
                    stroke: '#000000',
                    style: { color: 'white' },
                },
                select: {
                    fill: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
                        stops: [
                            [0.1, '#000'],
                            [0.3, '#333'],
                        ],
                    },
                    stroke: '#000000',
                    style: { color: 'yellow' },
                },
            },
        },
        inputStyle: {
            backgroundColor: '#333',
            color: 'silver',
        },
        labelStyle: { color: 'silver' },
    },

    navigator: {
        handles: {
            backgroundColor: '#666',
            borderColor: '#AAA',
        },
        outlineColor: '#CCC',
        maskFill: 'rgba(16, 16, 16, 0.5)',
        series: {
            color: '#7798BF',
            lineColor: '#A6C7ED',
        },
    },

    scrollbar: {
        barBackgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
                [0.4, '#888'],
                [0.6, '#555'],
            ],
        },
        barBorderColor: '#CCC',
        buttonArrowColor: '#CCC',
        buttonBackgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
                [0.4, '#888'],
                [0.6, '#555'],
            ],
        },
        buttonBorderColor: '#CCC',
        rifleColor: '#FFF',
        trackBackgroundColor: {
            linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
            stops: [
                [0, '#000'],
                [1, '#333'],
            ],
        },
        trackBorderColor: '#666',
    },

    // special colors for some of the demo examples
    legendBackgroundColor: 'rgba(48, 48, 48, 0.8)',
    legendBackgroundColorSolid: 'rgb(70, 70, 70)',
    dataLabelsColor: '#444',
    textColor: '#E0E0E0',
    maskColor: 'rgba(255,255,255,0.3)',
};
