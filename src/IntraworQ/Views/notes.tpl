{extends file="master.tpl"}
{block name='title'}Notes{/block}

{block name='body'}
	<h1>Notes</h1>
	<table class="table table-striped" style="width: auto">
		<thead>
			<tr>
				<th style="width: 10px">Id</th>
				<th>Content</th>
			</tr>
		</thead>
		<tbody>
			{foreach from=$notes item=note}
				<tr>
					<td>{$note.id}</td>
					<td>{$note.content}</td>
				</tr>
			{/foreach}
		</tbody>
	</table>
{/block}
