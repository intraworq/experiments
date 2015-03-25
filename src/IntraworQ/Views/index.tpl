{extends file="master.tpl"}
{block name='title'} {t}IntraworQ - no translation{/t} {/block}

{block name='body'} 
<h1>{t}Welcome! {/t}{t}To my site{/t}</h1>
<a href="/login">Logowanie</a><br/>
<a href="/logout">Wylogowanie</a><br/>
<a href="/param/Test">Parametr</a><br/>
<a href="/guest">Gość</a><br/>
{/block}
