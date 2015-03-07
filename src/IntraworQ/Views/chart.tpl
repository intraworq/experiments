{extends file="master.tpl"}

{block name="head"} 
<script type='text/javascript' src='/assets/jquery/jquery.js'></script>
<script type='text/javascript' src='/assets/chartjs/Chart.min.js'></script>
{/block}

{block name='body'} 
	<canvas id="myChart" width="400" height="400" style="border: solid black 1px; position: absolute; top: 50px; left: 50px; padding: 10px"></canvas>
	<script type="text/javascript">
	// Get context with jQuery - using jQuery's .get() method.
	var ctx = $("#myChart").get(0).getContext("2d");
	
	var data = [
	    {
	        value: 300,
	        color:"#F7464A",
	        highlight: "#FF5A5E",
	        label: "Red"
	    },
	    {
	        value: 50,
	        color: "#46BFBD",
	        highlight: "#5AD3D1",
	        label: "Green"
	    },
	    {
	        value: 100,
	        color: "#FDB45C",
	        highlight: "#FFC870",
	        label: "Yellow"
	    },
	    {
	        value: 40,
	        color: "#949FB1",
	        highlight: "#A8B3C5",
	        label: "Grey"
	    }
	];

	var options = {
		animateScale : false,
		scaleShowLine : true,
		animateRotate : false,
		percentageInnerCutout : 30
	}
	var myDoughnutChart = new Chart(ctx).Doughnut(data,options);

	</script>
{/block}