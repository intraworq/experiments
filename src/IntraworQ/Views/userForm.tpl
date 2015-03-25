{extends file="master.tpl"}
{block name='title'} {t}User{/t} {/block}

{block name='body'} 
login: guest, admin, workflow
<br/>
pass: rasmuslerdorf
<form method="post" name='user' id='form' action="/login">
	<input type="text" name='username' id='name' placeholder="Insert name" />
	<input type="text" name='password' value="rasmuslerdorf" id='name' placeholder="Insert name" />
	<input type="submit" value="Submit" />
</form>

{/block}
