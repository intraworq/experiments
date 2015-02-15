{extends file="master.tpl"}

{block name="head"} 
<script type='text/javascript' src='/assets/jquery/jquery.js'></script>
{/block}

{block name='body'} 
<button type="" value="validate" onClick="validateData()">validate</button>
<div id="result">
<ul id="results">
	<li>result</li>
</ul>
</div>
{literal}
<script type="text/javascript">
function validateData() {
	var t0 = performance.now();
	var globalResult = {};
	$.when(
		$.ajax({
			url: "/long1",
			data: {name: 'George'},
			type:'POST'
		}),
		$.ajax({
			url: "/long2",
			type:'POST'
		}),
		$.ajax({
			url: "/long3",
			type:'POST'
		})
	).then(function() {
		for (var i = arguments.length - 1; i >= 0; i--) {
			$("#results").append('<li>' + arguments[i]+ '</li>');
		};
		alert("Call took " + (performance.now() - t0) + " milliseconds.");
	});
};
</script>
{/literal}

{/block}