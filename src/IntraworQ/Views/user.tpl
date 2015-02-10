{extends file="master.tpl"}
{block name='title'} {t}User{/t} {/block}

{block name='body'} 
<h3>Create new user</h3>
<form method="post" name='user' action="/user">
	<input type="text" name='name' value="{$user->getName()}" />
	<input type="submit" value="Submit" />
</form>

{/block}
