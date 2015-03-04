{extends file="master.tpl"}
{block name='title'} {t}User{/t} {/block}

{block name='body'} 
<h3>Create new user</h3>
<form method="POST" name='user' id='form' action="/user">
	<input type="text" name='name' id='name' placeholder="Insert name" value="{$user->getName()}" autocomplete="off"/><br/>
	<input type="text" name='firstName' id='firstName' placeholder="Insert firstName" value="{$user->getFirstName()}" autocomplete="off"/><br/>
	<input type="submit" value="Submit" />
</form>

{/block}
