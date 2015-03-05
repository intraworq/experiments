<!DOCTYPE html>
<html>
<head>
{block name='head'}
{/block}
<meta charset="UTF-8">
<title>{block name='title'} {t}My site name{/t} {/block}</title>
{$debugbarRenderer->renderHead()}
</head>

<body>
{block name='body'}{/block}
{$debugbarRenderer->render()}
</body>

</html>