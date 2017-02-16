<div class="layer"></div>
<!-- <div id="logo"></div> -->
<?=form_open('login', 
	array(
		'class' => 'form',
		'id'	=> 'login_form'
	)
); ?>
<h3>
	<?=$title;?>
</h3>
<section id="error_messages">
	<?=validation_errors(); ?> 
</section>
	<input type="text" size="20" id="username" name="username" placeholder="Username">
    <input type="password" size="20" id="password" name="password" placeholder="Password">
    <input type="submit" name="submit_login" value="Login">

    <a id="login_form_redirect" href="register">Register new player</a>
</form>