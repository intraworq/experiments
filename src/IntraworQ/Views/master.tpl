<!DOCTYPE html>
<html>
	<head>
		{block name='head'}
		{/block}
		<meta charset="UTF-8">
		<title>{block name='title'} {t}My site name{/t} {/block}</title>
	{if isset($debugbarRenderer)}{$debugbarRenderer->renderHead()}{/if}
</head>

<body>
	<div id="login_info">{if isset($login_info)}{$login_info}{/if}</div>
	{block name='body'}{/block}
	{if isset($debugbarRenderer)}{$debugbarRenderer->render()}{/if}
</body>

</html>