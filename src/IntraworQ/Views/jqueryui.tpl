{extends file="master.tpl"}

{block name="head"} 
	<script type='text/javascript' src='/assets/jquery/jquery.js'></script>
	<script type='text/javascript' src='/assets/jquery-ui/jquery-ui.js'></script>
	<script type='text/javascript' src='/assets/jquery-ui/ui/i18n/datepicker-pl.js'></script>
	<script type='text/javascript' src='/assets/jquery-ui/ui/i18n/datepicker-ru.js'></script>
	<link rel="stylesheet" href="/assets/jquery-ui/themes/smoothness/jquery-ui.css">
	<style>
		.ui-progressbar {
			position: relative;
			margin: 0px 5px 10px 5px;
		}
		.progress-label {
			position: absolute;
			left: 50%;
			top: 4px;
			font-weight: bold;
			text-shadow: 1px 1px 0 #fff;
		}
		.ui-progressbar .ui-widget-content {
			border: 1px solid #aaaaaa;
		}	
	</style>
{/block}

{block name='body'} 
	<script type="text/javascript">

		$(function () {

			$.datepicker.setDefaults($.datepicker.regional['pl']);

			$("#accordion").accordion();
			$("#age").tooltip();
			$("#datepicker").datepicker();

			var progressbar = $("#progressbar"), progressLabel = $(".progress-label");

			progressbar.progressbar({
				value: false,
				change: function () {
					progressLabel.text(progressbar.progressbar("value") + "%");
				},
				complete: function () {
					progressLabel.text("Misja zakończona sukcesem!");
				}
			});

			function progress() {
				var val = progressbar.progressbar("value") || 0;
				progressbar.progressbar("value", val + 2);
				if (val < 99) {
					setTimeout(progress, 80);
				}
			}

			setTimeout(progress, 2000);


			$("#dialog").dialog({
				autoOpen: false,
				show: {
					effect: "fold",
					duration: 1000
				},
				hide: {
					effect: "explode",
					duration: 1000
				}
			});

			$("#opener").button().click(function () {
				$("#dialog").dialog("open");
			});

			$(".ui-dialog-titlebar-close").attr("title", "Zamknij");
		});
		$(function () {
			$("#slider-range-min").slider({
				range: "min",
				value: 37,
				min: 1,
				max: 700,
				slide: function (event, ui) {
					$("#amount").val("$" + ui.value);
				}
			});
			$("#amount").val("$" + $("#slider-range-min").slider("value"));
		});
	</script>

	<h1>Testy jQueryUI, wybierz komponent:</h1>
	<div id="accordion">
		<h3>ProgressBar:</h3>
		<div> 
			<div id="progressbar">
				<div class="progress-label">Wczytywanie...</div>

			</div>
		</div>
		<h3>DatePicker:</h3>
		<p>
			Data: <input type="text" id="datepicker">
		</p>
		<h3>Tooltip:</h3>
		<p>
			<label for="age">Pole z podpowiedzią:</label>
			<input id="age" title="To jest podpowiedź do pola z podpowiedzią.">
		</p>
		<h3>Button + Dialog:</h3>
		<div>
			<button id="opener">Pokaż okienko</button>
		</div>
		<h3><label for="amount">Maximum price:</label> <input type="text" id="amount" readonly style="width: 60px; border:0; color:#f6931f; font-weight:bold;"></h3>
		<div>
			<input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
			<div id="slider-range-min"></div>
		</div>
	</div>

	<div id="dialog" title="Uważaj:">
		<p>To jest okienko do wyświetlania informacji, możesz je przesunąć, powiększyć lub zmniejszyć, albo wybuchowo zamknąć klikając na 'x'.</p>
	</div>


{/block}