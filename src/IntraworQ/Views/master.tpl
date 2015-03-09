<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<link rel="stylesheet" href="/vendor/twitter/bootstrap/dist/css/bootstrap.min.css">
		<link rel="stylesheet" href="/vendor/twitter/bootstrap/dist/css/bootstrap-theme.min.css">
		{$debugbarRenderer->renderHead()}
		<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
		<script src="/vendor/twitter/bootstrap/dist/js/bootstrap.min.js"></script>
		<title>{block name='title'}My site name{/block}</title>
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

	<body style="margin: 0px 50px;">
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="navbar-collapse collapse">
					<ul class="nav navbar-nav">
						<li><a href="/">MainPage</a>
						<li><a href="/notes">Notes</a>
						<li><a href="/userList">Users list</a>
						<li><a href="/user">User Form</a>
						<li><a href="/user_ajax">User Ajax</a>
					</ul>
				</div>
			</div>
		</nav>
		{block name='body'}{/block}
		{$debugbarRenderer->render()}
	</body>
</html>
