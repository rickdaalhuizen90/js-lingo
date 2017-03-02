<div class="layer"></div>
<!-- <div id="logo"></div> -->
<main class="auth_wrapper">
	<?=form_open('login', 
		[
			'class' => 'form',
			'id' => 'login_form'
		]
	);?>
		<h3>
			<?=$title;?>
		</h3>
		<section id="error_messages">
			<?=validation_errors(); ?> 
		</section>
		
		<input type="text" size="20" id="username" name="username" placeholder="Username">
	    <input type="password" size="20" id="password" name="password" placeholder="Password">
	    <input type="submit" name="submit_login" value="Login">
	</form>

	<?=form_open('login', 
		[
			'class' => 'form',
			'id' => 'login_guest'
		]
	);?>
		<input type="hidden" name="guest">
	    <input id="submit_login_guest" type="submit" name="submit_login_guest" value="Login as guest">

	    <a id="login_form_redirect" href="register">Register new player</a>
	</form>
</main>