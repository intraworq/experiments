{extends file="master.tpl"}

{block name="head"} 
<script type='text/javascript' src='/assets/d3/d3.min.js'></script>
<style type="text/css">

	body {
		margin: 40px;
		font: 14px Helvetica Neue;
	}

	div#visual {
		position: relative;
		overflow: hidden;
	}

	span {
		display: inline-block;
		position: absolute;
		height: 16px;
	}

	.progress {
		border: solid 1px #ccc;
		background: #eee;
	}

	.progress .value {
		background: #3182bd;
	}

</style>
{/block}

{block name='body'} 
<script type="text/javascript">

	var id = 15;

	function create() {
		var duration = 5000 + Math.random() * 25000;

		var div = d3.select("body").insert("div", "div");
		div.attr("id", "visual");

		div.append("span")
		.attr("class", "holder")
		.style("width", "74px")
		.style("text-align", "right")
		.text(++id);

		div.append("span")
		.attr("class", "progress")
		.style("width", "240px")
		.style("left", "80px")
		.append("span")
		.attr("class", "value")
		.transition()
		.duration(duration)
		.ease("linear")
		.style("width", "100%");

		div.transition()
		.style("height", "20px")
		.transition()
		.delay(duration)
		.style("height", "0px")
		.remove();

		setTimeout(create, Math.random() * 10000);
	}

	create();
	create();
	create();
	create();
	create();

</script>
{/block}