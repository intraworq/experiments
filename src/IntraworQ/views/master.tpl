<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>{block name='title'}My site name{/block}</title>
{$debugbarRenderer->renderHead()}
<style type="text/css">
  div.phpdebugbar-widgets-messages li.phpdebugbar-widgets-list-item span.phpdebugbar-widgets-value.phpdebugbar-widgets-warn:before {
    font-family: FontAwesome;
    content: "\f071";
    margin-right: 8px;
    font-size: 11px;
    color: #ecb03d;
  }		
</style>
</head>

<body>
{block name='body'}{/block}
{$debugbarRenderer->render()}
</body>

</html>