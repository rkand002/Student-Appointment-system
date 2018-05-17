<?php
include ('auth.php');
include ('connect.php');
include ('header.php');
?>
<?php
session_start();
include ('services/connect.php');
$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD,DB_NAME)
OR die ('Could not connect to MySQL: '.mysqli_connect_error());

$yropts="";
$sql_year = "SELECT distinct(year) FROM semester";
$result = mysqli_query($conn, $sql_year);

if (mysqli_num_rows($result) > 0) {
	// output data of each row
	while($row = mysqli_fetch_assoc($result)) {
		$yropts=$yropts."<option value='".$row["year"]."'>".$row["year"]."</option>";
	}
} else {
	$yropts="0 results";
}
$semopts="";
$sql_sem = "SELECT distinct(name) FROM semester";
$result = mysqli_query($conn, $sql_sem);

if (mysqli_num_rows($result) > 0) {
	// output data of each row
	while($row = mysqli_fetch_assoc($result)) {
		$semopts=$semopts."<option value='".$row["name"]."'>".$row["name"]."</option>";
	}
} else {
	$semopts="0 results";
}



mysqli_close($conn);
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Reports</title>
<link rel="stylesheet"
	href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<script
	src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script
	src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script
	src="http://cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
<script src="http://d3js.org/d3.v3.min.js"></script>
<script src="http://labratrevenge.com/d3-tip/javascripts/d3.tip.v0.6.3.js"></script>
</head>

<style>
.bar {
	fill: #0086b3;
}

.bar:hover {
  fill:  #ff4d4d ;
}

.axis {
	font: 10px sans-serif;
}

.axis path,.axis line {
	fill: none;
	stroke: #000;
	shape-rendering: crispEdges;
}

.x.axis path {
	display: block;
}
.x.axis1 path {
	display: none;
}
.line {
  fill: none;
  stroke: red;
  stroke-width: 1.5px;
}
.d3-tip {
  line-height: 1;
  font-weight: bold;
  padding: 12px;
  background: rgba(0, 0, 0, 0.8);
  color: #fff;
  border-radius: 2px;
}

/* Creates a small triangle extender for the tooltip */
.d3-tip:after {
  box-sizing: border-box;
  display: inline;
  font-size: 10px;
  width: 100%;
  line-height: 1;
  color: rgba(0, 0, 0, 0.8);
  content: "\25BC";
  position: absolute;
  text-align: center;
}

/* Style northward tooltips differently */
.d3-tip.n:after {
  margin: -1px 0 0 0;
  top: 100%;
  left: 0;
}

</style>
<script type="text/javascript">
	$(document).ready(function()
			{
			
			$("#yearSelect").append("<?php echo $yropts?>");
			$("#semSelect").append("<?php echo $semopts?>");
			
			});
			
			
		function generateChart()
		{
			var quizid1=document.getElementById("quizSelect");
			var quizid=quizid1.options[quizid1.selectedIndex].value;
			//console.log(quizid);
			$.ajax({
	          type: "GET",
	          url: "/quizapp_web_dev/services/GetQuizData.php?qid="+quizid,
	          data: "",
	          dataType: "text",
	          success: function( data, textStatus, jqXHR) {
	        	//alert(data);
				//console.log(data);
				//dataset=data;
				createChart(data);
	          },
		 	 error: function( data, textStatus, jqXHR) {
	      	alert("error"+data);
	        }
		  });
		$.ajax({
	          type: "GET",
	          url: "/quizapp_web_dev/services/ResultAnalysis.php?qid="+quizid,
	          data: "",
	          dataType: "text",
	          success: function( data, textStatus, jqXHR) {
	        	//alert(data);
				//console.log(data);
				//dataset=data;
				document.getElementById("generateAnalysis").innerHTML = "";
				$("#generateAnalysis").append(data);
				//createChart(data);
	          },
		 	 error: function( data, textStatus, jqXHR) {
	      	alert("error"+data);
	        }
		  });
	
}
function getopts()
	{
		var y=document.getElementById("yearSelect");
		var s=document.getElementById("semSelect");
		var yr=y.options[y.selectedIndex].value;
		var sem=s.options[s.selectedIndex].value;
		$('#crnSelect').find('option').remove();
		$('#secSelect').find('option').remove();
		$('#quizSelect').find('option').remove();
		$.ajax({
	          type: "GET",
	          url: "/quizapp_web_dev/services/GetReportOptions.php?yr="+yr+"&sem="+sem,
	          data: "",
	          dataType: "json",
	          success: function( data, textStatus, jqXHR) {
		          
	        	  $("#crnSelect").append(data[0]);
	        	  $("#secSelect").append(data[1]);
	  			  //$("#quizSelect").append(data[2]);
	          },
		 	 error: function( data, textStatus, jqXHR) {
	      	alert("error"+data);
	        }
		  });
	}
	function getquizopts()
	{
		var c=document.getElementById("crnSelect");
		var s=document.getElementById("secSelect");
		var crn=c.options[c.selectedIndex].value;
		var sec=s.options[s.selectedIndex].value;
		$('#quizSelect').find('option').remove();
		console.log("entered"+crn+sec);
		$.ajax({
	          type: "GET",
	          url: "/quizapp_web_dev/services/GetQuizOptions.php?crn="+crn+"&sec="+sec,
	          data: "",
	          dataType: "text",
	          success: function( data, textStatus, jqXHR) {
		         console.log(data);
	  			  $("#quizSelect").append(data);
	          },
		 	 error: function( data, textStatus, jqXHR) {
	      	alert("error"+data);
	        }
		  });

		
	}
	function createLineChart(userid)
	{
		
		$.ajax({
	          type: "GET",
	          url: "/quizapp_web_dev/services/GetStudentDetails.php?uid="+userid,
	          data: "",
	          dataType: "text",
	          success: function( data, textStatus, jqXHR) {
		      
			         drawLineChart(data);
	          },
		 	 error: function( data, textStatus, jqXHR) {
	      	alert("error"+data);
	        }
		  });
	}
function createChart(data){
	
	document.getElementById("generateChart").innerHTML = "";
	var dataset=$.parseJSON(data);
	
	var margin = {top: 30, right: 20, bottom: 80, left: 40},
    width = (document.documentElement.clientWidth/2) - margin.left - margin.right-30,
    height = 500 - margin.top - margin.bottom;

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .ticks(10);  //some change here

var tip = d3.tip()
.attr('class', 'd3-tip')
.offset([-10, 0])
.html(function(d) {
  createLineChart(d.user_id);
  return "<strong>Score:</strong> <span style='color:red'>" + d.score + "</span>";
});

var svg = d3.select("#generateChart").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
  .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

  x.domain(dataset.map(function(d) { return d.first_name; }));
  y.domain([0, d3.max(dataset, function(d) { return parseInt(d.score); })]);

  var maxy=d3.max(dataset, function(d) { return parseInt(d.score); });
  var color = d3.scale.category20c();
  
  svg.call(tip);
 
  svg.append("g")
      .attr("class", "x axis")
      .attr("transform", "translate(0," + height + ")")
      .call(xAxis)
    .selectAll("text")
      .style("text-anchor", "end")
      .attr("dx", "-.8em")
      .attr("dy", "-.55em")
      .attr("transform", "rotate(-90)" );

  svg.append("g")
      .attr("class", "y axis")
      .call(yAxis)
    .append("text")
      .attr("transform", "rotate(0)")
      .attr("y", 6)
      .attr("dy", ".71em")
      .style("text-anchor", "end")
      .text("Scores");

  svg.selectAll(".bar")
      .data(dataset)
    .enter().append("rect")
      .attr("class", "bar")
      .attr("x", function(d) { return x(d.first_name); })
      .attr("width", x.rangeBand())
      .attr("y", function(d) { return y(d.score); })
      .attr("height", function(d) { return height - y(d.score); })  
 	  .on("mouseover",tip.show)
	  .on('mouseout', tip.hide)
	  	    ;
}
function drawLineChart(data)
		{
			document.getElementById("generateLineChart").innerHTML = "";
			var dataset=$.parseJSON(data);
			//console.log(dataset[0]['first_name']);
			var margin = {top: 30, right: 20, bottom: 80, left: 20},
		    width = (document.documentElement.clientWidth/2) - margin.left - margin.right-30,
		    height = 500 - margin.top - margin.bottom;

			var xScale =d3.scale.ordinal().rangeRoundBands([0, width]); 
			xScale.domain(dataset.map(function(d) { return d.quiz_id; }));
		var yScale = d3.scale.linear()
		    .domain([0, d3.max(dataset, function(d){ return parseInt(d.score); })])
		    .range([height, 0]);

		var tip = d3.tip()
		.attr('class', 'd3-tip')
		.offset([-10, 0])
		.html(function(d) {
		 		  return "<strong>Score:</strong> <span style='color:red'>" + d.score + "</span><br><strong>Quiz Id: </strong><span style='color:red'>"+d.quiz_id+"</span>";
		});
	    
		var xAxis = d3.svg.axis()
		    .scale(xScale)
		    .orient("bottom");
		   
		var yAxis = d3.svg.axis()
		    .scale(yScale)
		    .orient("left");
		 
		var line = d3.svg.line()
		    .x(function(d) { return xScale(d.quiz_id); })
		    .y(function(d) { return yScale(d.score); });

		
		
		
		var svg = d3.select("#generateLineChart").append("svg")
		    .attr("width", width + margin.left + margin.right)
		    .attr("height", height + margin.top + margin.bottom)
		  .append("g")
		    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");
		svg.call(tip);
			svg.append("text")
	        .attr("x", (width / 2))             
	        .attr("y", 0 - (margin.top / 2))
	        .attr("text-anchor", "middle")  
	        .style("font-size", "16px") 
	        .text(dataset[0]['first_name']+" "+dataset[0]['last_name']);
	        //console.log(dataset.first_name);

			svg.selectAll(".dot")
	        .data(dataset)
	      	.enter().append("circle")
	        .attr("r", 3.5)
	        .attr("cx", function(d) { return xScale(d.quiz_id); })
	        .attr("cy", function(d) { return yScale(d.score); })
	        .on("mouseover",tip.show)
	 		.on('mouseout', tip.hide)
	        ;
	        
		  svg.append("g")
		      .attr("class", "x axis")
		      .attr("transform", "translate(0," + height + ")")
		      .call(xAxis)
		      .selectAll("text").remove()
		      .style("text-anchor", "start");
	      
		  svg.append("g")
		      .attr("class", "y axis")
		      .call(yAxis)
		      ;

		  svg.append("path")
		      .data([dataset])
		      .attr("class", "line")
		      .attr("d", line);

		
		
		}
	


  </script>


<body>

<div class="panel panel-info">
	<div class="panel panel-success">
		<div class="panel-heading">Generate Reports</div>
		<div class="panel-body">


			<label>Select Year:</label> <select id="yearSelect" name="yearSelect"
				style="width: 100px;" onChange="getopts()">
			</select> <label>Select Semester:</label> <select id="semSelect"
				name="semSelect" style="width: 100px;" onChange="getopts()">
			</select> <label>Select CRN:</label> <select id="crnSelect"
				name="crnSelect" style="width: 200px;" onChange="getquizopts()">
			</select><br> <label>Select Section:</label> <select id="secSelect"
				name="secSelect" style="width: 200px;" onChange="getquizopts()">
			</select> <label>Select Quiz:</label> <select id="quizSelect"
				name="quizSelect" style="width: 200px;">
			</select><br> <input id="generate" name="generate" type="button"
				value="Generate Charts" onclick="generateChart()" />

		</div>
	</div>
<div class="row">
<div class="col-sm-6">
	<div class="panel panel-info" >
	<div class="panel-heading">Score vs Student Graph</div>
		<div class="panel-body" id="generateChart"></div>
	</div>
	</div>
	<div class="col-sm-6" >
	<div class="panel panel-info" >
	<div class="panel-heading">Score vs Quiz Graph</div>
		<div class="panel-body" id="generateLineChart"></div>
	</div>
	</div>
	<br><br><br>
</div>
	<div id="generateAnalysis"></div>
	</div>
	
	
</body>
</html>
