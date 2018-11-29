function stackedArea(filePath, zoomRange){
    
    var format = d3.time.format("%H:%M:%S,%L");
    var colors = d3.scale.category20();
    var nest = d3.nest().key(function(d) { return d.key; });
    var stack = d3.layout.stack()
        .offset("silhouette")
        .values(function(d) { return d.values; })
        .x(function(d) { return d.date; })
        .y(function(d) { return d.value; });
   // var keyColor = function(d, i) {return colors(d.key)};

    var chart;
    nv.addGraph(function() {
        d3.csv(filePath, function(data) {
        data.forEach(function(d) {
            d.date = format.parse(d.timestamp.split(" --> ")[0]);
            d.value = +d.value;
        });

        if(Array.isArray(zoomRange)){
            var firstLine = zoomRange[0] * 5,
            lastLine = zoomRange[1] * 5;
        } else {
            var firstLine = (zoomRange*5) - 50;
            if (firstLine < 0) firstLine = 0;
            if (firstLine + 100 > data.length) firstLine -= 50;
            var lastLine = firstLine + 100;
        }

        var zoomedData=[];
        for(var i = firstLine; i < lastLine; i++){
            zoomedData.push(data[i])
        }

        var lastTimeStamp;
        var lastValues = new Array(5);
        for(var i = 0; i < zoomedData.length-5; i+=5){
            if(lastTimeStamp == zoomedData[i].timestamp){
                for(var j = i; j < i + 5; j ++){
                    zoomedData[j].value = lastValues[j-i];
                }
            } else {
                for(var j = i; j < i + 5; j ++){
                    lastValues[j-i] = zoomedData[j].value;
                }
            }
            lastTimeStamp = zoomedData[i].timestamp;
        }

        chart = nv.models.stackedAreaChart()
            .useInteractiveGuideline(true)
            .x(function(d) { return d.date })
            .y(function(d) { return d.y *100})
            .controlLabels({stacked: "Stacked"})
            .color(["#d53f3f", "#cf6a6a", "#e9ede6", "#c1f68e", "#77cc25"])

            .duration(300);
        var layers = stack(nest.entries(zoomedData));
        chart.xAxis.tickFormat(function(d) { return d3.time.format('%X')(new Date(d)) });
        chart.yAxis.tickFormat(d3.format(',.2f'));

        d3.select('#chart1')
            .datum(layers)
            .transition().duration(1000)
            .call(chart)
            .each('start', function() {
                setTimeout(function() {
                    d3.selectAll('#chart1 *').each(function() {
                        if(this.__transition__)
                            this.__transition__.duration = 1;
                    })
                }, 0)
            });

        nv.utils.windowResize(chart.update);
        return chart;
    });

    });

};