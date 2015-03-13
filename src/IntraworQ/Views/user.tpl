{extends file="master.tpl"}
{block name='title'} {t}User{/t} {/block}

{block name='body'} 
	<h3>Create new user</h3>
	{if count($messages) > 0}
		<div style="list-style: none" class=" alert alert-danger alert-dismissible">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			{foreach from=$messages item=message}
				<p>{$message}</p>
			{/foreach}
		</div>
	{/if}
	<form method="POST" name='user' id='form' action="/user/save">
		<div class="input-group">
			<div><input class="form-control" type="text" name='name' id='name' placeholder="Insert name" value="{$user->getName()}" autocomplete="off"/></div>
			<div><input class="form-control" type="text" name='firstName' id='firstName' placeholder="Insert firstName" value="{$user->getFirstName()}" autocomplete="off"/></div>
			<div><input class="btn btn-default" type="submit" value="Submit" /></div>
		</div>
	</form>
{/block}
