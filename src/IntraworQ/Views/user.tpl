{extends file="master.tpl"}
{block name='title'} {t}User{/t} {/block}

{block name='body'} 
<h3>Create new user</h3>
<form method="post" name='user' id='form' action="/user">
	<input type="text" name='name' id='name' placeholder="Insert name" />
	<input type="submit" value="Submit" />
</form>

{/block}
