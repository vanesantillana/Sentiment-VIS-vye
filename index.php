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
	<!-- SCRIPTS tweets_2016_us_second_presidencial_debate_sp-->
	<script type="text/javascript" src="js/d3.min.js"></script>
    <script type="text/javascript" src="js/jquery-2.2.3.min.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
	<!--<script src="js/barchart.js" type="text/javascript"></script>-->
	<script src="js/stackedArea.js" type="text/javascript"></script>
	<script src="js/cloud.js" type="text/javascript"></script>
	<script src="js/wordcloud.js" type="text/javascript"></script>
    <title>Vye Sentiment Visualization</title>
    <script>
        <?php 
            $name = "Analisis de Sentimientos de 2016 US Segundo Debate Presidencial";
            $fileName = "tweets_2016_us_second_presidencial_debate_sp_plus";
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