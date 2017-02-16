<div class="layer"></div>
<!-- <div id="logo"></div> -->
<?=form_open('register', 
	array(
		'class' => 'form',
		'id'	=> 'register_form'
	)
); ?>
<h3>
	<?=$title;?>
</h3>
<section id="error_messages">
	<?=validation_errors(); ?> 
</section>
    <input type="text" min="3" max="20" name="username" placeholder="Username">
    <input type="email" min="5" max="20" name="email" placeholder="Email">
    <input type="password" min="5" max="100" name="password" placeholder="Password">
    <input type="password" min="5" max="100" name="cpassword" placeholder="Confirm password">
    <input type="submit" name="submit_register" value="Register">

	<span id="register_form_redirect">
		Allready a member...? <a href="login">Login</a>
	</span>
    
</form>