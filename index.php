<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <link rel="stylesheet" type="text/css" href="css/bootstrap-clearmin.min.css">
    <link rel="stylesheet" type="text/css" href="css/style-multiresolution.css">
    <link rel="stylesheet" type="text/css" href="css/style-tree.css">
    <link rel="stylesheet" type="text/css" href="css/style-tooltip.css">
    <link rel="stylesheet" type="text/css" href="css/loader.css">
	<link type="text/css" rel="stylesheet" href="kmeans.css">
	<link rel="stylesheet" type="text/css" href="css/radial-tree.css">

	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">
	<!-- SCRIPTS tweets_2016_us_second_presidencial_debate_sp-->
	<script type="text/javascript" src="js/d3.min.js"></script>
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<!--<script src="js/barchart.js" type="text/javascript"></script>-->
	<script src="js/stackedArea.js" type="text/javascript"></script>
	<script src="js/cloud.js" type="text/javascript"></script>
	<script src="js/wordcloud.js" type="text/javascript"></script>
	<script src="js/kmeans.js"></script>
    <title>Vye Sentiment Visualization</title>
    <script>
        <?php 
            $name = "Analisis de Sentimientos de 2016 US Segundo Debate Presidencial";
			$fileName = "tweets_2016_us_second_presidencial_debate_sp_plus";
			$fileName = "data-100";
            $filePath = "source/" . $fileName . ".json";
            $timePolarity = 0; // 0 minutes, 1 hours, 2 days, 3 week, 4 month, 5 years
            $nTimeGranularity = 1; // interval by nGranularity minimum 1 max 5
            $getText = TRUE; // to get the text of the values
            $description = "<strong>About: </strong>Tweets collected<br><strong>Period: </strong><br><strong>Tweets: </strong>  <br><strong>Hashtag: </strong>";
            
            $str = file_get_contents ( $filePath );    
            // $json = json_decode ( json_encode ( utf8_encode ( $str ) ) ); // WORKS
            $json = json_decode ( json_encode ( $str ) ); // PRUEBA WORKS AUSSI
            $error = json_last_error ();
        ?>
        var jsonArray =  <?php echo ($json); ?>;
        var timePolarity = <?php echo $timePolarity; ?>;
        var nTimeGranularity = <?php echo $nTimeGranularity; ?>;
        var fileName = <?php echo json_encode($fileName); ?>; //json_encode for the String
        var getText = <?php echo json_encode($getText); ?>;
	</script>
	<style>
		.info{
		margin-top: 8rem;
		background-color: white;
		}
		.valor{
			font-size: 40px;
		}
</style>
</head>

<body class="cm-no-transition cm-1-navbar">

	<div id="fb-root"></div>

	<div id="loader"></div>
	<div id="cm-menu">
		<nav class="cm-navbar cm-navbar-primary">
			<div class="cm-flex">
				<div class="cm-logos"></div>
			</div>
			<div class="btn btn-primary md-menu-white" data-toggle="cm-menu"><i class="fa fa-reorder"></i></div>
		</nav>
		<div id="cm-menu-content">
			<svg id="tree-vis"></svg>
		</div>
	</div>

	<header id="cm-header" style="display: inline;">
		<nav class="cm-navbar cm-navbar-primary">
			<div class="btn btn-primary md-menu-white hidden-md hidden-lg"
				data-toggle="cm-menu"></div>
			<div class="cm-flex">
				<h1 style="display: inline;"><?php echo($name)?></h1>
				<i id="panelInfo" class="fa fa-info-circle fa-lg"
					data-toggle="modal" data-target="#infoModal"></i>
			</div>
			<div class="pull-right">
				<button id="svg-export"
					class="btn btn-primary md-download-svg-white"></button>
			</div>
		</nav>
	</header>

	<!-- VISUALIZACIONES-->
	<div id="global" style="display: none;">
		<!-- style="overflow-y:hidden; -->
		<div class="container-fluid cm-container-white"
			style="overflow: hidden; margin-bottom: 10px; padding-top: 10px; padding-bottom: 10px;">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="tab" href="#vis">Temporal</a></li>
				<!--<li><a data-toggle="tab" href="#vis1">Linea de tiempo</a></li>-->
				<li><a data-toggle="tab" href="#vis2">Nube de palabras</a></li>
				<li><a data-toggle="tab" href="#vis5">Matrix Similarity</a></li>
				<li><a data-toggle="tab" href="#vis6">BERT embeddings</a></li>
				<!--<li><a data-toggle="tab" href="#vis3">K-mean</a></li>-->
				<!--<li><a data-toggle="tab" href="#vis4">Animación</a></li>-->
			</ul>

			<div class="tab-content">
				<div id="vis" class="tab-pane fade in active">
					<svg id="multiresolution-vis"></svg>
				</div>
				<div id="vis1" class="tab-pane fade">
					<div id="bar-chart"></div>
					<script>
					//barChart("source/Gravity.csv");
					</script>
				</div>
				<div id="vis2" class="tab-pane fade">
					<div id="chart" class="row"></div>
				</div>
				<!--
				<div id="vis3" class="tab-pane fade">
				<script>
						$(document).ready(function()
						{
							kMeans("#kmeans", 650, 550, 1000, 5, 50);
							//kMeans("body", 250, 250, 1000, 5, 10);
						}); 
						
					</script>
						<div id="kmeans" class="kmeans-chart col-md-10" align="center"></div>
						<div class="clearfix"></div>
					
				</div>
				<div id="vis4" class="tab-pane fade">
					<p>timer: <span id="count"></span></p>
					<script>
						var start = new Date().getTime();
						var time = 0;
						var timeout = 5000;
						function instance() {
							if (time == timeout) {
								time = 0;
							} else {
								time += 10;
							}
							$('#count').text(time);
							var diff = (new Date().getTime() - start) - time;
							window.setTimeout(instance, (100 - diff));
						}
						window.setTimeout(instance, 100);
					</script>
				</div>-->
				<div id="vis5" class="tab-pane fade">
					<div class="row">
						<div class="col-md-8">
							<svg id="matrix"></svg>
						</div>
						<div class="col-md-4 info">
							<div class="row">
								<div class="col-md-2" style="background-color: #fffecb;">0</div>
								<div class="col-md-2" style="background-color: #fee288;">0.2</div>
								<div class="col-md-2" style="background-color: #feab49;">0.4</div>
								<div class="col-md-2" style="background-color: #fc5b2e;">0.6</div>
								<div class="col-md-2" style="background-color: #d30f20;">0.8</div>
								<div class="col-md-2" style="background-color: #800026;">1</div>
							</div>
							<br>
							<p>SIMILARIDAD: </p><p id="similclick" class="valor"></p>
							<p><b>Y:</b> <span id="com1click"></span></p>
							<p><b>X:</b> <span id="com2click"></span></p>
							<hr>
							<p>SIMILARIDAD: </p><p id="simil" class="valor"></p>
							<p><b>Y:</b> <span id="com1"></span></p>
							<p><b>X:</b> <span id="com2"></span></p>
						</div>	
					</div>
					
					<script type='text/javascript'>
						var w = 800,
							h = 800;
						
						var margin = {top: 50, right: 20, bottom: 70, left: 20};
						var pad = 80;
						var width = 2 * w + pad;
						var svg = d3.select('svg#matrix')
							.attr({
								'width': width + margin.left + margin.right,
								'height': h + margin.top + margin.bottom
							})
							.append('g')
							.attr({
								'transform': 'translate(' + margin.left + ',' + margin.top + ')',
								'width': width,
								'height': h
							});
						var corrplot = svg.append('g')
							.attr({
								'id': 'corrplot'
							});
						/*var scatterplot = svg.append('g')
							.attr({
								'id': 'scatterplot',
								'transform': 'translate(' + (w + pad) + ',0)'
							});*/
						corrplot.append('text')
							.text('Correlation matrix')
							.attr({
								'class': 'plottitle',
								'x': w/2,
								'y': -margin.top/2,
								'dominant-baseline': 'middle',
								'text-anchor': 'middle'
							});
						/*scatterplot.append('text')
							.text('Scatter plot')
							.attr({
								'class': 'plottitle',
								'x': w/2,
								'y': -margin.top/2,
								'dominant-baseline': 'middle',
								'text-anchor': 'middle'
							});*/
						var corXscale = d3.scale.ordinal().rangeRoundBands([0,w]),
							corYscale = d3.scale.ordinal().rangeRoundBands([h,0]),
							corColScale = d3.scale.linear().domain([0,0.2,0.4,0.6,0.8,1]).range(['#fffecb','#fee288','#feab49','#fc5b2e','#d30f20','#800026']);
						var corRscale = d3.scale.sqrt().domain([0,1]);
						d3.json('source/vis-100.json', function(err, data) {
							var nind = data.ind.length,
								nvar = data.vars.length;
							corXscale.domain(d3.range(nvar));
							corYscale.domain(d3.range(nvar));
							corRscale.range([0,0.5*corXscale.rangeBand()]);
							var corr = [];
							for (var i = 0; i < data.corr.length; ++i) {
								for (var j = 0; j < data.corr[i].length; ++j) {
									corr.push({row: i, col: j, value:data.corr[i][j]});
								}
							}
							var cells = corrplot.append('g')
								.attr('id', 'cells')
								.selectAll('empty')
								.data(corr)
								.enter().append('g')
								.attr({
									'class': 'cell'
								})
								.style('pointer-events', 'all');
							var rects = cells.append('rect')
								.attr({
									'x': function(d) { return corXscale(d.col); },
									'y': function(d) { return corXscale(d.row); },
									'width': corXscale.rangeBand(),
									'height': corYscale.rangeBand(),
									'fill': 'none',
									'stroke': 'none',
									'stroke-width': '1'
								});
							var circles = cells.append('circle')
								.attr('cx', function(d) {return corXscale(d.col) + 0.5*corXscale.rangeBand(); })
								.attr('cy', function(d) {return corXscale(d.row) + 0.5*corYscale.rangeBand(); })
								.attr('r', function(d) {return corRscale(Math.abs(d.value)); })
								.style('fill', function(d) { return corColScale(d.value); });
							corrplot.selectAll('g.cell')
								.on('mouseover', function(d) {
									d3.select(this)
										.select('rect')
										.attr('stroke', 'black');
									var xPos = parseFloat(d3.select(this).select('rect').attr('x'));
									var yPos = parseFloat(d3.select(this).select('rect').attr('y'));
									corrplot.append('text')
										.attr({
											'class': 'corrlabel',
											'x': corXscale(d.col),
											'y': h + margin.bottom*0.2
										})
										.text(data.vars[d.col][0])
										.attr({
											'dominant-baseline': 'middle',
											'text-anchor': 'middle'
										});
									corrplot.append('text')
										.attr({
											'class': 'corrlabel'
											// 'x': -margin.left*0.1,
											// 'y': corXscale(d.row)
										})
										.text(data.vars[d.row][0])
										.attr({
											'dominant-baseline': 'middle',
											'text-anchor': 'middle',
											'transform': 'translate(' + (-margin.left*0.1) + ',' + corXscale(d.row) + ')rotate(270)'
										});
									corrplot.append('rect')
										.attr({
											'class': 'tooltip',
											'x': xPos + 10,
											'y': yPos - 30,
											'width': 40,
											'height': 20,
											'fill': 'rgba(200, 200, 200, 0.5)',
											'stroke': 'black'
										});
									corrplot.append('text')
										.attr({
											'class': 'tooltip',
											'x': xPos + 30,
											'y': yPos - 15,
											'text-anchor': 'middle',
											'font-family': 'sans-serif',
											'font-size': '14px',
											'font-weight': 'bold',
											'fill': 'black'
										})
										.text(d3.format('.2f')(d.value));
								})
								.on('mouseout', function(d) {
									d3.select('#corrtext').remove();
									d3.selectAll('.corrlabel').remove();
									d3.select(this)
										.select('rect')
										.attr('stroke', 'none');
									//Hide the tooltip
									d3.selectAll('.tooltip').remove();
									var x = document.getElementById("simil");
									x.innerHTML = d.value;
									x.style.color = corColScale(d.value);
									document.getElementById("com1").innerHTML = '<i class="fas fa-circle" style="color: '+data.vars[d.row][2]+'" ></i> '+data.vars[d.row][1]+' - '+data.vars[d.row][0];
									document.getElementById("com2").innerHTML = '<i class="fas fa-circle" style="color: '+data.vars[d.col][2]+'" ></i> '+data.vars[d.col][1]+' - '+data.vars[d.col][0];
								})
								.on('click', function(d) {
									var x = document.getElementById("simil");
									x.innerHTML = d.value;
									x.style.color = corColScale(d.value);
									document.getElementById("com1").innerHTML = '<i class="fas fa-circle" style="color: '+data.vars[d.row][2]+'" ></i> '+data.vars[d.row][1]+' - '+data.vars[d.row][0];
									document.getElementById("com2").innerHTML = '<i class="fas fa-circle" style="color: '+data.vars[d.col][2]+'" ></i> '+data.vars[d.col][1]+' - '+data.vars[d.col][0];

									var x = document.getElementById("similclick");
									x.innerHTML = d.value;
									x.style.color = corColScale(d.value);
									document.getElementById("com1click").innerHTML = '<i class="fas fa-circle" style="color: '+data.vars[d.row][2]+'" ></i> '+data.vars[d.row][1]+' - '+data.vars[d.row][0];
									document.getElementById("com2click").innerHTML = '<i class="fas fa-circle" style="color: '+data.vars[d.col][2]+'" ></i> '+data.vars[d.col][1]+' - '+data.vars[d.col][0];
									/*drawScatter(d.col, d.row);*/

								});
							/*var drawScatter = function(col, row) {
								console.log('column ' + col + ', row ' + row);
								d3.selectAll('.points').remove();
								d3.selectAll('.axis').remove();
								d3.selectAll('.scatterlabel').remove();
								var xScale = d3.scale.linear()
									.domain(d3.extent(data.dat[col]))
									.range([0, w]);
								var yScale = d3.scale.linear()
									.domain(d3.extent(data.dat[row]))
									.range([h, 0]);
								var xAxis = d3.svg.axis()
									.scale(xScale)
									.orient('bottom')
									.ticks(5);
								var yAxis = d3.svg.axis()
									.scale(yScale)
									.orient('left');
								scatterplot.append('g')
									.attr('class', 'points')
									.selectAll('empty')
									.data(d3.range(nind))
									.enter().append('circle')
									.attr({
										'class': 'point',
										'cx': function(d) {
											return xScale(data.dat[col][d]);
										},
										'cy': function(d) {
											return yScale(data.dat[row][d]);
										},
										'r': 2,
										'stroke': 'none',
										'fill': 'black'
									});
								scatterplot.append('g')
									.attr('class', 'x axis')
									.attr('transform', 'translate(0,' + h + ')')
									.call(xAxis);
								scatterplot.append('g')
									.attr('class', 'y axis')
									.call(yAxis);
								scatterplot.append('text')
									.text(data.vars[col])
									.attr({
										'class': 'scatterlabel',
										'x': w/2,
										'y': h + margin.bottom/2,
										'text-anchor': 'middle',
										'dominant-baseline': 'middle'
									});
								scatterplot.append('text')
									.text(data.vars[row])
									.attr({
										'class': 'scatterlabel',
										'transform': 'translate(' + (-pad/1.25) + ',' + (h/2) + ')rotate(270)',
										'dominant-baseline': 'middle',
										'text-anchor': 'middle'
									});
							}*/
						});
					</script>
					
				</div>
				<div id="vis6" class="tab-pane fade">
					<div id="tree-container"></div>
					<div id="toolbar">
						<div class="tool">
							<div id="help" title="click for help"><a href="help.html" target="_blank">HELP</a></div>
						</div>
						<div class="tool">
							<div class="tlabel">zoom</div>
							<div class="tbuttons">
							<div class="button" data-key="187" title="Zoom In (+ OR scrollwheel)">+</div>
							<div class="button" data-key="189" title="Zoom Out (&minus; OR scrollwheel)">&minus;</div>
							</div>
						</div>
						<div class="tool">
							<div class="tlabel">rotate</div>
							<div class="tbuttons">
							<div class="button" data-key="33" title="Rotate CCW (Page Up OR &#8679;scrollwheel)" style="font-size:0.9em">&#8634;</div>
							<div class="button" data-key="34" title="Rotate CW (Page Down OR &#8679;scrollwheel)" style="font-size:0.9em">&#8635;</div>
							</div>
						</div>
						<div class="tool">
							<div class="tlabel">select</div>
							<div class="tbuttons">
							<div class="button" data-key="-38" title="Select Previous (&#8679;&uarr;)" style="font-size:0.9em">&#8613;</div>
							<div class="button" data-key="-40" title="Select Next (&#8679;&darr;)" style="font-size:0.9em">&#8615;</div>
							</div>
						</div>
						<div class="tool">
							<div class="tlabel">view</div>
							<div class="tbuttons">
							<div class="button" data-key="36" title="Center Root (Home)">&#8962;</div>
							<div class="button" data-key="35" title="Center Selected (End)" style="font-size:0.8em">&#9673;</div>
							</div>
						</div>
						<div class="tool">
							<div class="tlabel">toggle</div>
							<div class="tbuttons">
							<div class="button" data-key="32" title="Toggle Node (Space OR double-click)">1</div>
							<div class="button" data-key="13" title="Toggle Level (Return OR &#8679;double-click)">&oplus;</div>
							<div class="button" data-key="191" title="Toggle Root (/)">/</div>
							</div>
						</div>
						<div class="tool">
							<div class="tlabel" style="text-align:left" title="Change Root">&nbsp;selection</div>
							<div id="selection" class="tlabel"></div>
						</div>
					</div>

					<div id="contextmenu">
						<div data-key="32"><span class="expcol">Expand</span> Node</div>
						<div data-key="13">Expand 1 Level</div>
						<div data-key="-13">Expand Full Tree</div>
						<div data-key="35">Center This Node</div>
						<div data-key="36">Center Root</div>
						<div data-key="191">Set Root</div>
					</div>
					<script type="text/javascript" src="radial-tree.js"></script>
<!--
					<div id="w2v"></div>
					<script>
					var margin = {top: 20, right: 20, bottom: 30, left: 40},
						width = 960 - margin.left - margin.right,
						height = 500 - margin.top - margin.bottom;

					/* 
					* value accessor - returns the value to encode for a given data object.
					* scale - maps value to a visual display encoding, such as a pixel position.
					* map function - maps from data value to display value
					* axis - sets up axis
					*/ 

					// setup x 
					var xValue = function(d) { return d.Calories;}, // data -> value
						xScale = d3.scale.linear().range([0, width]), // value -> display
						xMap = function(d) { return xScale(xValue(d));}, // data -> display
						xAxis = d3.svg.axis().scale(xScale).orient("bottom");

					// setup y
					var yValue = function(d) { return d["Protein (g)"];}, // data -> value
						yScale = d3.scale.linear().range([height, 0]), // value -> display
						yMap = function(d) { return yScale(yValue(d));}, // data -> display
						yAxis = d3.svg.axis().scale(yScale).orient("left");

					// setup fill color
					var cValue = function(d) { return d.Manufacturer;},
						color = d3.scale.category10();

					// add the graph canvas to the body of the webpage
					var svg = d3.select("#w2v").append("svg")
						.attr("width", width + margin.left + margin.right)
						.attr("height", height + margin.top + margin.bottom)
					.append("g")
						.attr("transform", "translate(" + margin.left + "," + margin.top + ")");

					// add the tooltip area to the webpage
					var tooltip = d3.select("w2v").append("div")
						.attr("class", "tooltip")
						.style("opacity", 0);

					// load data
					d3.csv("cereal.csv", function(error, data) {

					// change string (from CSV) into number format
					data.forEach(function(d) {
						d.Calories = +d.Calories;
						d["Protein (g)"] = +d["Protein (g)"];
					//    console.log(d);
					});

					// don't want dots overlapping axis, so add in buffer to data domain
					xScale.domain([d3.min(data, xValue)-1, d3.max(data, xValue)+1]);
					yScale.domain([d3.min(data, yValue)-1, d3.max(data, yValue)+1]);

					// x-axis
					svg.append("g")
						.attr("class", "x axis")
						.attr("transform", "translate(0," + height + ")")
						.call(xAxis)
						.append("text")
						.attr("class", "label")
						.attr("x", width)
						.attr("y", -6)
						.style("text-anchor", "end")
						.text("Calories");

					// y-axis
					svg.append("g")
						.attr("class", "y axis")
						.call(yAxis)
						.append("text")
						.attr("class", "label")
						.attr("transform", "rotate(-90)")
						.attr("y", 6)
						.attr("dy", ".71em")
						.style("text-anchor", "end")
						.text("Protein (g)");

					// draw dots
					svg.selectAll(".dot")
						.data(data)
						.enter().append("circle")
						.attr("class", "dot")
						.attr("r", 3.5)
						.attr("cx", xMap)
						.attr("cy", yMap)
						.style("fill", function(d) { return color(cValue(d));}) 
						.on("mouseover", function(d) {
							tooltip.transition()
								.duration(200)
								.style("opacity", .9);
							tooltip.html(d["Cereal Name"] + "<br/> (" + xValue(d) 
								+ ", " + yValue(d) + ")")
								.style("left", (d3.event.pageX + 5) + "px")
								.style("top", (d3.event.pageY - 28) + "px");
						})
						.on("mouseout", function(d) {
							tooltip.transition()
								.duration(500)
								.style("opacity", 0);
						});

					// draw legend
					var legend = svg.selectAll(".legend")
						.data(color.domain())
						.enter().append("g")
						.attr("class", "legend")
						.attr("transform", function(d, i) { return "translate(0," + i * 20 + ")"; });

					// draw legend colored rectangles
					legend.append("rect")
						.attr("x", width - 18)
						.attr("width", 18)
						.attr("height", 18)
						.style("fill", color);

					// draw legend text
					legend.append("text")
						.attr("x", width - 24)
						.attr("y", 9)
						.attr("dy", ".35em")
						.style("text-anchor", "end")
						.text(function(d) { return d;})
					});

					</script>-->
				</div>
			</div>
		</div>
		<footer class="cm-footer" style="display: none;"> </footer>
		<!--<nav id="menu-bottom" class="navbar navbar-default">
			
		</nav>-->

	</div>

	<div id="alert-msg"
		class="alert alert-danger alert-dismissable hidden fade in">
		<span id="close-alert" class="close">&times;</span> <strong>Warning!</strong>
		not allowed due to constraints.
	</div>

	<!-- Modal information -->
	<div class="modal fade" id="infoModal" role="dialog">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">&times;</button>
					<h4 class="modal-title" style="text-transform: capitalize;">
						<?php echo($name)?>
					</h4>
				</div>
				<div class="modal-body">
					<p>
						<?php echo($description)?>
					</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal data -->
	<div class="modal fade" id="data-modal" role="dialog">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div id="data-modal-title" class="modal-header"></div>
				<div id="data-modal-msg" class="modal-body">
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
	<script type="text/javascript" src="js/bootstrap.min.js"></script>
	<script type="text/javascript" src="js/jquery.mousewheel.min.js"></script>
	<script type="text/javascript" src="js/jquery.cookie.min.js"></script>
	<script type="text/javascript" src="js/fastclick.min.js"></script>
	<script type="text/javascript" src="js/clearmin.min.js"></script>
	<script type="text/javascript" src="js/compVecOut.js"></script>
	<script type="text/javascript" src="js/rpoly.js"></script>
	<script type="text/javascript" src="js/PolyReCoeffInT.js"></script>
	<script type="text/javascript" src="js/svg-crowbar-2.js"></script>
	<script type="text/javascript" src="js/chroma.js"></script>
	<script type="text/javascript" src="js/generalizes.js"></script>
	<script type="text/javascript" src="js/multistream-hierarchy-util.js"></script>
	<script type="text/javascript" src="js/multistream-var-global.js"></script>
	<script type="text/javascript" src="js/multiresolution-vis.js"></script>
	<script type="text/javascript" src="js/tree-vis.js"></script>
	
</body>
</html>