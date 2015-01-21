<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>{block name='title'}My site name{/block}</title>
{$debugbarRenderer->renderHead()}
</head>

<body>
{block name='body'}{/block}
{$debugbarRenderer->render()}
</body>

</html>