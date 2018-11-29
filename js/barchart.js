function barChart(filePath){
  $("#bar-chart").empty();
  // $("#chart1").empty();
  var format = d3.time.format("%H:%M:%S,%L");
      var colors = d3.scale.category20();
      var nest = d3.nest().key(function(d) { return d.key; });
      var stack = d3.layout.stack()
          .values(function(d) { return d.values; })
          .x(function(d) { return d.value; })
          .y(function(d) { return d.number });
  
      d3.csv(filePath, function(data) {
          data.forEach(function(d) {
              d.number = data.indexOf(d);
              d.date = format.parse(d.timestamp.split(" --> ")[0]);
              d.value = +d.value;
          });
    
    var m = data.length / 5,
    n = 5, // number of layers
    layers = stack(nest.entries(data));
    newLayers = [];
    for(var i = 0; i < 5; i++) newLayers[i] = [];
      var c = 0;
    for(var i = 0; i < m; i++){
      var max = 0, maxI;
      for(var j = 0; j < n; j++){
      keys = layers[j].values;
        if (keys[i].value > max){
          max = keys[i].value;
          maxI = j;
        }
      }
      newLayers[maxI].push({"x": c, "y" : layers[maxI].values[c].value, "y0": 0});//layers[maxI].values[c].y0});
      c++;
      // newList = d3.range(n).map(function(){return newList;});
      // layers[i]=newList;
    }
  
       // layers = ));
      yGroupMax = d3.max(newLayers, function(layer) { return d3.max(layer, function(d) { return d.y; }); }),
      yStackMax = d3.max(newLayers, function(layer) { return d3.max(layer, function(d) { return d.y0 + d.y; }); });
  
  var margin = {top: 40, right: 10, bottom: 50, left: 10},
      width = document.body.clientWidth - margin.left - margin.right,
      height = 150 - margin.top - margin.bottom;
  
  var x = d3.scale.ordinal()
      .domain(d3.range(m))
      .rangeBands([0, width]);
  
  var y = d3.scale.linear()
      .domain([0, yStackMax])
      .range([height, 0]);
  
  var colorArray =["#d53f3f", "#cf6a6a", "#e9ede6", "#8def2f", "#5a9d1a"];
  // var colorArray =["#d53f3f", "#cf6a6a", "#ffffff", "#c1f68e", "#77cc25"])
  // var colorArray =["#67cb57", "#57a1cb", "#FFF0E9", "#67AE87", "#67A281"])
              
  var color =  d3.scale.ordinal()
        .range(colorArray);
     /*  .domain([0, n - 1])
      .range(["#B30000", "#E34A33", "#ffe7db", ""]);*/
  console.log(m)
  
  var xAxis = d3.svg.axis()
      .scale(d3.scale.ordinal().rangeBands([0,width]).domain(d3.range(50,m,100)))
      .tickSize(1)
      .tickFormat(function(d) { return d3.time.format('%X')(new Date(data[d*5].date)) })
      .tickPadding(6)
      .orient("bottom");
  
  var svg = d3.select("#bar-chart").append("svg")
      .attr("width", width + margin.left + margin.right)
      .attr("height", height + margin.top + margin.bottom)
      .append("g")
      .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
  
  var layer = svg.selectAll(".layer")
      .data(newLayers)
      .enter().append("g")
      .attr("class", "layer")
      .style("fill", function(d, i) { return color(i); })
      .on("click", function(d){
        var xPos = d3.mouse(this)[0];
        var leftEdges = x.range();
        var width = x.rangeBand();
        var j;
        for(j=0; xPos > (leftEdges[j] + width); j++) {} //do nothing, just increment j until case fail
        var clicked = x.domain()[j];
        stackedArea(filePath, clicked);
      //barClicked(clicked);
      });
  
  
  var rect = layer.selectAll("rect")
      .data(function(d) { return d; })
    .enter().append("rect")
      .attr("x", function(d) { return x(d.x); })
      .attr("y", height)
      .attr("width", x.rangeBand())
      .attr("height", 0);
  
  var brush = d3.svg.brush()
      .x(x)
      .on("brushend", function(){
        var extent = brush.extent();
        if(extent[0] == extent[1]) {
          var newExtent = Math.round(extent[0] / x.rangeBand());
          stackedArea(filePath, newExtent);
          //barClicked(newExtent);
        } else {
          if(extent[1] - extent[0] > 100){
            d3.event.target.extent([extent[0],extent[0]+100]); d3.event.target(d3.select(this));
          }
          var newExtent = extent.map(function(e){ return Math.round(e / x.rangeBand());})
          stackedArea(filePath, newExtent);
          //barClicked(Math.floor((newExtent[0] + newExtent[1]) / 2));
        }
      });
  
  rect.transition()
      .delay(function(d, i) { return i * 10; })
      .attr("y", function(d) { return y(d.y0 + d.y); })
      .attr("height", function(d) { return y(d.y0) - y(d.y0 + d.y); });
  
  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis);
  
  d3.selectAll("input").on("change", change);
  
  var gBrush = svg.append("g")
      .attr("class", "brush")
      .call(brush);
  
  gBrush.selectAll("rect")
      .attr("height", height);
  
  function change() {
    var timeout = setTimeout(function() {
    d3.select("input[value=\"grouped\"]").property("checked", true).each(change);
    }, 2000);
    clearTimeout(timeout);
    if (this.value === "grouped") transitionGrouped();
    else transitionStacked();
  }
  
  function transitionGrouped() {
    y.domain([0, yGroupMax]);
  
    rect.transition()
        .duration(1)
        .delay(function(d, i) { return i; })
        .attr("x", function(d, i, j) { return x(d.x) + (x.rangeBand() + 1) / n * j; })
        .attr("width", x.rangeBand() / n)
      .transition()
        .attr("y", function(d) { return y(d.y); })
        .attr("height", function(d) { return height - y(d.y); });
  }
  
  function transitionStacked() {
    y.domain([0, yStackMax]);
  
    rect.transition()
        .duration(1)
        .delay(function(d, i) { return i; })
        .attr("y", function(d) { return y(d.y0 + d.y); })
        .attr("height", function(d) { return y(d.y0) - y(d.y0 + d.y); })
      .transition()
        .attr("x", function(d) { return x(d.x); })
        .attr("width", x.rangeBand());
  }
  
  // Inspired by Lee Byron's test data generator.
  function bumpLayer(n, o) {
  
    function bump(a) {
      var x = 1 / (.1 + Math.random()),
          y = 2 * Math.random() - .5,
          z = 10 / (.1 + Math.random());
      for (var i = 0; i < n; i++) {
        var w = (i / n - y) * z;
        a[i] += x * Math.exp(-w * w);
      }
    }
  
    var a = [], i;
    for (i = 0; i < n; ++i) a[i] = o + o * Math.random();
    for (i = 0; i < 5; ++i) bump(a);
    return a.map(function(d, i) { return {x: i, y: Math.max(0, d)}; });
  }
  });
  
  
  }