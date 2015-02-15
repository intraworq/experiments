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

<script type='text/javascript'>
    $(document).ready(function() {
    // process the form
    $('form').submit(function(event) {
        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = {
            'name'              : $('#name').val()
        };
        // alert debugging
        //alert(JSON.stringify(formData, undefined, 2));
               // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : '/user', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
            encode      : true
        }).done(function(data) {
        		alert(data['user']);
            });
        $('#name').val("");
        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });
});
</script>


{/block}
