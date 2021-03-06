{extends file="master.tpl"}
{block name="head"} 
<script type='text/javascript' src='/assets/jquery/jquery.js'></script>
{/block}
{block name='title'} {t}User{/t} {/block}

{block name='body'} 
<h3>Create new user AJAX</h3>
<form method="post" name='user' id='form' action="/user">
	<input type="text" name='name' id='name' placeholder="Insert name" />
	<input type="submit" value="Submit" />
</form>
<div id="result"></div>
<script type='text/javascript'>
    $(document).ready(function() {
    // process the form
    $('form').submit(function(event) {
        var formData = {
            'name'              : $('#name').val()
        };

        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '/user', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        }).done(function(data) {
        		$("#result").append(data['user']);
            });
        $('#name').val("");
        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });
});
</script>


{/block}
