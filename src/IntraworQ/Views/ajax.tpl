{extends file="master.tpl"}
{block name="head"} 

	<script type='text/javascript' src='/assets/jquery/ajax.js'></script>
{/block}
{block name='title'} {t}AJAX{/t} {/block}

{block name='body'} 
	<h3>Request AJAX</h3>
	<a href="#" class='goodButton'>good</a><br/>
	<a href="#" class="errorButton">error</a><br/>
	<div class="content"/>

{/block}