(function(){
    function getMax(items) {
        var max = items[0];
        for (var i = 0; i < items.length; i++) {
            if (items[i] > max) {
                max = items[i];
            }
        }

        return max;
    }

    function drawChart(options) {
        var hours = options.hours;
        var max = getMax(hours);
        var step = max /(hours.length - 1);
        var centers = [max];
        for (var i = 1; i < hours.length; i++) {
            centers.push(max - i * step);
        }
        g = new RGraph.Line('g', centers, hours);
        g.Set('chart.labels', options.labels);
        g.Set('chart.title', options.title);
        g.Set('chart.title.vpos', 0.2);
        g.Set('chart.background.barcolor1', 'white');
        g.Set('chart.background.barcolor2', 'white');
        g.Set('chart.linewidth', 3);
        g.Set('chart.colors', options.colors || ['green', 'red']);
        g.Set('chart.hmargin', 5);
        g.Set('chart.tickmarks', 'circle');
        g.Set('chart.ymax', max);
        g.Set('chart.gutter.left', 30);
        g.Set('chart.gutter.top', 30);
        return g;
    }

    window.drawChart = drawChart;
})();
