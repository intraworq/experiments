{extends file="master.tpl"}
{block name='title'} {t}Users list{/t} {/block}

{block name='body'} 
<h3>Users</h3>
<table>
	<tr>
		<th>First name</th>
		<th>Last name</th>
		<th>Description</th>
	</tr>
{foreach from=$users item=user}
	<tr>
		<td>{$user->getFirstName()}</td>
		<td>{$user->getName()}</td>
		<td>{$user->getDescription()}</td>
	</tr>
{/foreach}
</table>
{/block}